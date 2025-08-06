<?php


function hashtag_sort()
{
    return array('chainhandletype = \'34513\' DESC' => null, 'chainkey' => 'ASC', 'chaintime' => 'DESC');
}

function handle_sort()
{
    return array('chainkey' => 'ASC', 'chaintime' => 'DESC'); //'chainhandletype = \'41011\' DESC' => null,
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

function discover_chainhandletype()
{
    return (isset($_POST['js_request_uri']) && substr($_POST['js_request_uri'], 0, 1) == '/' && substr_count($_POST['js_request_uri'], '/') == 2 ? '/' . strtok(substr($_POST['js_request_uri'], 1), '/') : null);
}

function handle_pinned($handleid, $return_itself = false)
{

    $CI =& get_instance();
    $return_val = '';
    $pinned_down = $CI->config->item('pinned_down');
    if (isset($pinned_down[$handleid])) {
        $return_val = reset($pinned_down[$handleid]);
    }

    $pinned_up = $CI->config->item('pinned_up');
    if (isset($pinned_up[$handleid])) {
        $return_val = reset($pinned_up[$handleid]);
    }

    $return_val = ($return_itself ? $handleid : 0);

    return ($return_val > 0 ? $return_val : 4559);

}

function hashtag_type_discovery($i, $trying_to_skip = false)
{

    if ($trying_to_skip) {
        return 31022;
    }

    $CI =& get_instance();
    if ($i['hashtagtype'] == 26560) {
        $currency_types = $CI->Chains->read(array(
            'chainhandletype IN (' . join(',', $CI->config->item('handleids___42991')) . ')' => null, //Active Writes
            'chainhashtagoutput' => $i['hashtagid'],
            'chainhandleinput IN (' . join(',', $CI->config->item('handleids___26661')) . ')' => null, //Currency
        ));
        $total_dues = $CI->Chains->read(array(
            'chainhandletype IN (' . join(',', $CI->config->item('handleids___42991')) . ')' => null, //Active Writes
            'chainhashtagoutput' => $i['hashtagid'],
            'chainhandleinput' => 26562, //Total Due
        ));
        return (count($total_dues) && doubleval($total_dues[0]['chainvalue']) && count($currency_types) ? 26595 : 42332);
    } else {
        return handle_pinned($i['hashtagtype']);
    }

}


function string_is_icon($string)
{
    return substr_count($string, 'fa-');
}


function hashtag_number_calculator($i)
{

    //TODO Improve later (This is a very basic logic)
    $CI =& get_instance();
    $count_x = $CI->Chains->read(array(
        '(chainhashtaginput=' . $i['hashtagid'] . ' OR chainhashtagoutput=' . $i['hashtagid'] . ')' => null,
    ), array(), 0, 0, array(), 'COUNT(chainid) as totals');

    //Should we update?
    if ($count_x[0]['totals'] != $i['hashtagweight']) {
        return $CI->Hashtags->update($i['hashtagid'], array(
            'hashtagweight' => $count_x[0]['totals'],
        ));
    } else {
        return 0;
    }

}

function handle_number_calculator($e)
{

    //TODO Improve later (This is a very basic logic)
    $CI =& get_instance();
    $count_x = $CI->Chains->read(array(
        '(chainhandleoutput=' . $e['handleid'] . ' OR chainhandleinput=' . $e['handleid'] . ' OR chainhandlecreator=' . $e['handleid'] . ')' => null,
    ), array(), 0, 0, array(), 'COUNT(chainid) as totals');

    //Should we update?
    if ($count_x[0]['totals'] != $e['handleweight']) {
        return $CI->Handles->update($e['handleid'], array(
            'handleweight' => $count_x[0]['totals'],
        ));
    } else {
        return 0;
    }

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


function phone_href($chainhandletype, $number)
{

    $number = preg_replace("/[^0-9]/", "", $number);

    if ($chainhandletype == 13815) {
        //WhatsApp
        return 'https://wa.me/' . $number;
    } elseif ($chainhandletype == 20337) {
        //Telegram
        return 'https://t.me/' . $number;
    } else {
        //general number:
        return 'tel:' . $number;
    }
}

function handlecover_generator($handleid)
{
    $CI =& get_instance();
    $fetch = $CI->config->item('handles___' . $handleid);
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


function reset_cache($chainhandlecreator)
{
    $CI =& get_instance();
    $count = 0;
    foreach ($CI->Chains->read(array(
        'chainhandletype' => 44179, //Triggered
        'chainhandleinput' => 14599, //Cache App
        'chainhandleoutput >' => 0,
    )) as $delete_cahce) {
        //Void:
        $count += $CI->Chains->delete($delete_cahce['chainid'], $chainhandlecreator);
    }
    return $count;
}

function hashtag_spots_remaining($hashtagid)
{

    $CI =& get_instance();
    $handle_session = handle_session();

    //Any Limits on Selection?
    $spots_remaining = -1; //No limits
    $max_available = $CI->Chains->read(array(
        'chainhandletype IN (' . join(',', $CI->config->item('handleids___42991')) . ')' => null, //Active Writes
        'chainhashtagoutput' => $hashtagid,
        'chainhandleinput' => 26189,
    ), array(), 1);
    if (count($max_available) && is_numeric($max_available[0]['chainvalue'])) {

        //We have a limit! See if we've met it already:
        $query_filters = array(
            'chainhandletype IN (' . join(',', $CI->config->item('handleids___40986')) . ')' => null, //DISCOVERIES
            'chainhashtaginput' => $hashtagid,
        );
        if ($handle_session) {
            //Do not count current user to give them option to edit & resubmit:
            $query_filters['chainhandlecreator !='] = $handle_session['handleid'];
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

function hashtag_redirect_url($i)
{
    $CI =& get_instance();
    if (strlen($i['hashtagtext']) && count($CI->Chains->read(array(
            'chainhandletype IN (' . join(',', $CI->config->item('handleids___42991')) . ')' => null, //Active Writes
            'chainhashtagoutput' => $i['hashtagid'],
            'chainhandleinput' => 43871, //Redirect URL
        )))) {
        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $i['hashtagtext'], $match);
        foreach ($match[0] as $url) {
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                return $url;
            }
        }
    }

    return false;
}

function hashtag_popup_url($i)
{
    if (!handle_session()) {
        return false;
    }
    $CI =& get_instance();
    foreach ($CI->Chains->read(array(
        'chainhandletype IN (' . join(',', $CI->config->item('handleids___42991')) . ')' => null, //Active Writes
        'chainhashtagoutput' => $i['hashtagid'],
        'chainhandleinput' => 44266, //Popup URL
    )) as $popup_url) {
        if (filter_var($popup_url['chainvalue'], FILTER_VALIDATE_URL)) {
            return $popup_url['chainvalue'];
        }
    }
    return false;
}

function hashtag_required($i)
{
    $CI =& get_instance();
    return count($CI->Chains->read(array(
        'chainhandletype IN (' . join(',', $CI->config->item('handleids___42991')) . ')' => null, //Active Writes
        'chainhashtagoutput' => $i['hashtagid'],
        'chainhandleinput' => 28239, //Required
    )));
}

function get_redirected($url, $message = null, $log_error = false)
{
    //An error handling function that would redirect member to $url with optional $message
    //Do we have a Message?
    $CI =& get_instance();
    $handle_session = handle_session();
    $handle_id = ($handle_session ? $handle_session['handleid'] : 14068);

    if ($message) {
        $CI->session->set_flashdata('flash_message', $message);
    }

    if ($log_error) {
        //Log thie error:
        log_error($url . ' ' . stripslashes($message), array(
            'chainhandleoutput' => $handle_id,
            'chainhandlecreator' => $handle_id,
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

    $es = $CI->Handles->read(array(
        'handleid' => $cookie_parts[0],
    ));

    if (count($es) && $cookie_parts[2] == view_hash($cookie_parts[0] . $cookie_parts[1])) {

        //Assign session & log Chain:
        $CI->Handles->activate($es[0], false, true);
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
    $has_children = count($i['next_hashtags']);
    $handles___11035 = $CI->config->item('handles___11035'); //Encyclopedia

    echo '<div class="slim_title">';

    echo '<div class="hideIfEmpty">';

    echo '<a href="javascript:void(0);" onclick="$(\'.frame_id_' . $i['hashtagid'] . '\').toggleClass(\'hidden\')">';
    echo '<span class="icon-block-sm ' . ($open_by_default ? 'hidden' : '') . ' frame_id_' . $i['hashtagid'] . '"><i class="far fa-circle-plus"></i></span>';
    echo '<span class="icon-block-sm ' . ($open_by_default ? '' : 'hidden') . ' frame_id_' . $i['hashtagid'] . '"><i class="far fa-circle-minus"></i></span>';
    echo '<span class="' . (!isset($i['user_hashtag_discovered']) || count($i['user_hashtag_discovered']) ? ' main__title ' : '') . '">' . view_hashtag_title($i, true) . '</span>';
    echo '</a>';

    echo(isset($i['user_hashtag_discovered']['chainkey']) && intval($i['user_hashtag_discovered']['chainkey']) > 1 ? $i['user_hashtag_discovered']['chainkey'] . 'x ' : '');

    echo(isset($i['user_written_response']['hashtagtext']) && strlen($i['user_written_response']['hashtagtext']) ? ' ' . $i['user_written_response']['hashtagtext'] : '');


    echo '<span class="float_right inner_items ' . ($open_by_default ? '' : 'hidden') . ' frame_id_' . $i['hashtagid'] . '">';
    //Chain Highlights
    foreach ($CI->config->item('handles___1592660') as $handleid => $m) {

        $opener = '<span ';
        $closer = '</span>';

        if (isset($i['stats']) && $handleid == 12273 && $i['stats']['all_steps'] > 0) {

            if ($CI->uri->segment(1) == 'doc') {
                $opener = '<a href="/' . $i['hashtagterm'] . '" ';
                $closer = '</a>';
            }
            echo $opener . 'data-toggle="tooltip" data-placement="top" title="' . $m['m__title'] . (strlen($m['m__message']) ? ': ' . $m['m__message'] : '') . '"><span class="icon-block-sm">' . $m['m__cover'] . '</span><span>' . $i['stats']['all_steps'] . '</span>' . $closer;

        } elseif (isset($i['stats']) && $handleid == 1592672 && ($i['current_level'] > 0 || $i['stats']['max_level'] > 0)) {

            if ($CI->uri->segment(1) == 'doc') {
                $opener = '<a href="/doc/' . $i['hashtagterm'] . '" ';
                $closer = '</a>';
            }
            echo $opener . ' data-toggle="tooltip" data-placement="top" title="' . $m['m__title'] . (strlen($m['m__message']) ? ': ' . $m['m__message'] : '') . '"><span class="icon-block-sm">' . $m['m__cover'] . '</span><span>' . $i['current_level'] . '/' . $i['stats']['max_level'] . '</span>' . $closer;

        } elseif (isset($i['stats']) && $handleid == 1592682 && ($i['stats']['min_choices'] > 0 || $i['stats']['max_choices'] > 0)) {

            echo $opener . ' data-toggle="tooltip" data-placement="top" title="' . $m['m__title'] . (strlen($m['m__message']) ? ': ' . $m['m__message'] : '') . '"><span class="icon-block-sm">' . $m['m__cover'] . '</span><span>' . ($i['stats']['min_choices'] > 0 && $i['stats']['min_choices'] != $i['stats']['max_choices'] ? $i['stats']['min_choices'] . '-' : '') . $i['stats']['max_choices'] . '</span>' . $closer;

        } elseif (isset($i['stats']) && $handleid == 1592686 && ($i['stats']['min_steps'] > 0 || $i['stats']['max_steps'] > 0)) {

            echo $opener . ' data-toggle="tooltip" data-placement="top" title="' . $m['m__title'] . (strlen($m['m__message']) ? ': ' . $m['m__message'] : '') . '"><span class="icon-block-sm">' . $m['m__cover'] . '</span><span>' . ($i['stats']['min_steps'] != $i['stats']['max_steps'] ? $i['stats']['min_steps'] . '-' : '') . $i['stats']['max_steps'] . '</span>' . $closer;

        } elseif ($handleid == 31777 && isset($i['hashtag_count_discovery']) && intval($i['hashtag_count_discovery']) > 0) {

            if (hashtag_is_startable($i)) {
                $opener = '<a href="/' . $i['hashtagterm'] . '/start" ';
                $closer = '</a>';
            }

            $max_available = $CI->Chains->read(array(
                'chainhandletype IN (' . join(',', $CI->config->item('handleids___42991')) . ')' => null, //Active Writes
                'chainhashtagoutput' => $i['hashtagid'],
                'chainhandleinput' => 26189,
            ), array(), 1);

            echo $opener . ' data-toggle="tooltip" data-placement="top" title="' . $m['m__title'] . (strlen($m['m__message']) ? ': ' . $m['m__message'] : '') . '"><span class="icon-block-sm">' . $m['m__cover'] . '</span><span>' . $i['hashtag_count_discovery'] . (count($max_available) && is_numeric($max_available[0]['chainvalue']) ? '<span title="' . $handles___11035[26189]['m__title'] . '" style="border-bottom: 1px dotted #000000;">/' . intval($max_available[0]['chainvalue']) . '</span>' : '') . '</span>' . $closer;

        } else {
            //block
            echo $opener . '>&nbsp;' . $closer;
        }
    }
    echo '</span>';
    echo '<div class="doclear">&nbsp;</div>';

    echo(isset($i['hashtag_count_discovery']) ? '<div class="grey hide-subline maxwidth hideIfEmpty remove_first_line extra_message ' . ($open_by_default || !$has_children ? '' : 'hidden') . ' frame_id_' . $i['hashtagid'] . '">' . view_hashtag_value($i) . '</div>' : '');
    echo '</div>';


    //Hashtag Discovery Expanded List
    if (isset($_GET['expand'])) {
        $already_shown = array();
        foreach ($CI->Chains->read(array(
            'chainhandletype IN (' . join(',', $CI->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
            'chainhashtaginput' => $i['hashtagid'],
        ), array('chainhandlecreator'), 0, 0, array('chainid' => 'DESC')) as $creator) {
            if (in_array($creator['chainhandlecreator'], $already_shown)) {
                continue;
            }
            array_push($already_shown, $creator['chainhandlecreator']);
            echo '<div class="maxwidth cover_x_' . $creator['chainid'] . '" style="padding:3px 0;">' . (strlen($_GET['expand']) > 1 ? '<a href="' . view_app_chain(44328) . '/' . $_GET['expand'] . '@' . $creator['handleterm'] . '" target="_blank" title="' . $handles___11035[44328]['m__title'] . '">' : '') . '<span class="icon-block-sm grey">' . $handles___11035[44328]['m__cover'] . '</span></a> <a href="' . view_memory(42903, 42902) . $creator['handleterm'] . '"><span class="icon-block">' . view_cover($creator['handlecover']) . '</span><span class="grey">@' . $creator['handleterm'] . '</span></a> <span class="grey"><a href="javascript:void(0);" onclick="chain_delete(' . $creator['chainid'] . ', ' . $creator['chainid'] . ',\'' . $i['hashtagterm'] . '\')" title="' . $handles___11035[10673]['m__title'] . '" class="grey">' . $handles___11035[10673]['m__cover'] . '</a> ' . view_time_difference($creator['chaintime'], false) . '</span></div>';
            if (count($already_shown) >= view_memory(6404, 11064)) {
                break;
            }
        }
    } elseif (isset($focus_e['handleid']) && !count($CI->Chains->read(array(
            'chainhandletype IN (' . join(',', $CI->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
            'chainhashtaginput' => $i['hashtagid'],
            'chainhandlecreator' => $focus_e['handleid'],
        )))) {
        //Not discovered by this user:
        echo '<span class="grey inline-block"><span class="icon-block-sm"><i class="far fa-eye-slash"></i></span>Not Yet Discovered</span>';
    }


    //Hashtag Filters:
    $filters_ui = '';
    if (isset($i['hashtag_list_config'])) {
        //Hashtag<>Handle Settings:
        $current_handleid = 0;
        foreach ($CI->config->item('handles___43006') as $handleid => $m) {
            foreach ($i['hashtag_list_config']['full_config_' . $handleid] as $filtered_handle) {
                if (!$current_handleid) {
                    $current_handleid = $handleid;
                }
                if (strlen($filters_ui) && $current_handleid != $handleid) {
                    $current_handleid = $handleid;
                    $filters_ui .= '<div class="and_filter">-AND-</div>';
                }
                $filters_ui .= '<div><span class="icon-block-sm">' . $m['m__cover'] . '</span>' . $m['m__title'] . ': <a href="/@' . $filtered_handle['handleterm'] . '"><span class="icon-block-sm">' . view_cover($filtered_handle['handlecover']) . '</span>' . $filtered_handle['handlename'] . '</a></div>';
            }
        }
        //Hashtag<>Hashtag Settings:
        foreach ($CI->config->item('handles___40792') as $handleid => $m) {
            foreach ($i['hashtag_list_config']['full_config_' . $handleid] as $filtered_hashtag) {
                if (!$current_handleid) {
                    $current_handleid = $handleid;
                }
                if (strlen($filters_ui) && $current_handleid != $handleid) {
                    $current_handleid = $handleid;
                    $filters_ui .= '<div class="and_filter">-AND-</div>';
                }
                $filters_ui .= '<div><span class="icon-block-sm">' . $m['m__cover'] . '</span>' . $m['m__title'] . ': <a href="/' . $filtered_hashtag['hashtagterm'] . '">' . view_hashtag_title($filtered_hashtag) . '</a></div>';
            }
        }
    }
    if ($filters_ui) {
        $handles___11035 = $CI->config->item('handles___11035'); //Encyclopedia
        echo '<div class="hideIfEmpty filter_data ' . ($open_by_default || !$has_children ? '' : 'hidden') . ' frame_id_' . $i['hashtagid'] . '">';
        echo '<h3>' . $handles___11035[40946]['m__cover'] . ' ' . $handles___11035[40946]['m__title'] . ':</h3>';
        echo $filters_ui;
        echo '</div>';
    }

    foreach ($i['next_hashtags'] as $next_i) {
        echo '<div class="sub_frame ' . ($open_by_default ? '' : 'hidden') . ' frame_id_' . $i['hashtagid'] . '">';
        view_tree($next_i, (isset($_GET['view_all']) ? true : false));
        echo '</div>';
    }

    echo '</div>';
}


function hashtag_list_config($hashtagid, $access_limit = true)
{

    $CI =& get_instance();

    $hashtag_list_config = array(); //To compile the settings of this sheet:

    foreach ($CI->config->item('handles___40792') as $chainhandletype => $m) {
        $hashtag_list_config[intval($chainhandletype)] = array(); //Assume no chains for this type
        $hashtag_list_config['full_config_' . $chainhandletype] = array(); //Assume no chains for this type
    }
    foreach ($CI->config->item('handles___43006') as $chainhandletype => $m) {
        $hashtag_list_config[intval($chainhandletype)] = array(); //Assume no chains for this type
        $hashtag_list_config['full_config_' . $chainhandletype] = array(); //Assume no chains for this type
    }

    //Now search for these settings across Handles:
    foreach ($CI->Chains->read(array(
        'chainhandleinput >' => 0,
        'chainhashtagoutput' => $hashtagid,
        'chainhandletype IN (' . join(',', $CI->config->item('handleids___43006')) . ')' => null,
    ), array('chainhandleinput'), 0, 0, array(), '*', null, $access_limit) as $setting_chain) {
        array_push($hashtag_list_config[intval($setting_chain['chainhandletype'])], intval($setting_chain['chainhandleinput']));
        array_push($hashtag_list_config['full_config_' . $setting_chain['chainhandletype']], $setting_chain);
    }

    //Now search for these settings across hashtags:
    foreach ($CI->Chains->read(array(
        'chainhashtagoutput >' => 0,
        'chainhashtaginput' => $hashtagid,
        'chainhandletype IN (' . join(',', $CI->config->item('handleids___40792')) . ')' => null,
    ), array('chainhashtagoutput'), 0, 0, array(), '*', null, $access_limit) as $setting_chain) {
        array_push($hashtag_list_config[intval($setting_chain['chainhandletype'])], intval($setting_chain['chainhashtagoutput']));
        array_push($hashtag_list_config['full_config_' . $setting_chain['chainhandletype']], $setting_chain);
    }

    return $hashtag_list_config;
}


function handle_list_config($handleid, $access_limit = true)
{

    $CI =& get_instance();

    $handle_list_config = array(); //To compile the settings of this sheet:
    $memory_detected = is_array($CI->config->item('handleids___6287')) && count($CI->config->item('handleids___6287'));
    if (!$memory_detected) {
        return false;
    }

    foreach ($CI->config->item('handles___1645191') as $chainhandletype => $m) {
        $handle_list_config[intval($chainhandletype)] = array(); //Assume no chains for this type
        $handle_list_config['full_config_' . $chainhandletype] = array(); //Assume no chains for this type
    }

    //Now search for these settings across Handles:
    foreach ($CI->Chains->read(array(
        'chainhandleinput >' => 0,
        'chainhandleoutput' => $handleid,
        'chainhandletype IN (' . join(',', $CI->config->item('handleids___1645191')) . ')' => null,
    ), array('chainhandleinput'), 0, 0, array(), '*', null, $access_limit) as $setting_chain) {
        array_push($handle_list_config[intval($setting_chain['chainhandletype'])], intval($setting_chain['chainhandleinput']));
        array_push($handle_list_config['full_config_' . $setting_chain['chainhandletype']], $setting_chain);
    }

    return $handle_list_config;
}


function hashtag_settings($hashtagterm, $fetch_contact = false)
{

    $CI =& get_instance();
    $handle_column = array();
    $hashtag_column = array();
    $contact_details = array(
        'full_list' => '',
        'email_list' => '',
        'email_count' => 0,
        'phone_count' => 0,
    );

    foreach ($CI->Hashtags->read(array(
        'LOWER(hashtagterm)' => strtolower($hashtagterm),
    )) as $i) {

        $hashtag_list_config = hashtag_list_config($i['hashtagid']);

        //Generate filter:
        $query_string_all = array();
        if (count($hashtag_list_config[40791])) {

            //If hashtag_discovered Any
            $query_string_all = $CI->Chains->read(array(
                'chainhashtaginput IN (' . join(',', $hashtag_list_config[40791]) . ')' => null,
                'chainhandletype IN (' . join(',', $CI->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
            ), array('chainhandlecreator'), 0, 0, array('chainid' => 'DESC'));

        } elseif (count($hashtag_list_config[44161])) {

            //If hashtag_discovered All
            $query_string_all = $CI->Chains->read(array(
                'chainhashtaginput IN (' . join(',', $hashtag_list_config[44161]) . ')' => null,
                'chainhandletype IN (' . join(',', $CI->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
            ), array('chainhandlecreator'), 0, 0, array('chainid' => 'DESC'));

        } elseif (count($hashtag_list_config[27984])) {

            //IF Follows Any
            $query_string_all = $CI->Chains->read(array(
                'chainhandleinput IN (' . join(',', $hashtag_list_config[27984]) . ')' => null,
                'chainhandletype IN (' . join(',', $CI->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
            ), array('chainhandleoutput'), 0, 0, array('chainkey' => 'ASC', 'chainid' => 'DESC'));

        } elseif (count($hashtag_list_config[43513])) {

            //IF Follows All
            $query_string_all = $CI->Chains->read(array(
                'chainhandleinput IN (' . join(',', $hashtag_list_config[43513]) . ')' => null,
                'chainhandletype IN (' . join(',', $CI->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
            ), array('chainhandleoutput'), 0, 0, array('chainkey' => 'ASC', 'chainid' => 'DESC'));

        } else {

            //All Discoveries:
            $query_string_all = $CI->Chains->read(array(
                'chainhashtaginput' => $i['hashtagid'],
                'chainhandletype IN (' . join(',', $CI->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
            ), array('chainhandlecreator'), 0, 0, array('chainkey' => 'ASC', 'chainid' => 'DESC'));

        }

        //Filter list:
        $query_string_filtered = array();
        $unique_users_count = array();
        foreach ($query_string_all as $key => $x) {
            if (in_array(intval($x['handleid']), $unique_users_count)) {
                //Already added:
                continue;
            } elseif (!hashtag_access(null, $i['hashtagid'], $i, $x['handleid'], $hashtag_list_config)) {
                //Does not have access:
                continue;
            } else {
                //Passed all filters:
                array_push($query_string_filtered, $x);
                array_push($unique_users_count, intval($x['handleid']));
            }
        }


        //Determine columns if any:
        $pinned_columns = array();
        foreach ($CI->Chains->read(array(
            'chainhashtagoutput' => $i['hashtagid'],
            'chainhandletype' => 34513, //Pinned
        ), array('chainhandleinput'), 0) as $setting_chain) {
            array_push($pinned_columns, intval($setting_chain['handleid']));
        }
        if (count($pinned_columns)) {

            //Add to results:
            $hashtag_list_config[34513] = $pinned_columns;

            $handle_column = $CI->Chains->read(array(
                'chainhandleinput IN (' . join(',', $pinned_columns) . ')' => null,
                'chainhandletype IN (' . join(',', $CI->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
            ), array('chainhandleoutput'), 0, 0, handle_sort());

            foreach ($CI->Chains->read(array(
                'chainhandleinput IN (' . join(',', $pinned_columns) . ')' => null,
                'chainhandletype IN (' . join(',', $CI->config->item('handleids___33602')) . ')' => null, //Hashtag/Handle Chains Active
                'chainhashtagoutput !=' => $i['hashtagid'],
            ), array('chainhashtagoutput'), 0, 0, array('hashtagtext' => 'ASC')) as $chain_i) {
                array_push($hashtag_column, $chain_i);
            }
        }


        if ($fetch_contact) {
            foreach ($query_string_filtered as $count => $x) {

                //Fetch email & phone:
                $fetch_names = $CI->Chains->read(array(
                    'chainhandleinput' => 42584, //First Name
                    'chainhandleoutput' => $x['handleid'],
                    'chainhandletype IN (' . join(',', $CI->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                ));
                $fetch_emails = $CI->Chains->read(array(
                    'chainhandleinput' => 3288, //Email
                    'chainhandleoutput' => $x['handleid'],
                    'chainhandletype IN (' . join(',', $CI->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                ));
                $fetch_phones = $CI->Chains->read(array(
                    'chainhandleinput' => 4783, //Phone
                    'chainhandleoutput' => $x['handleid'],
                    'chainhandletype IN (' . join(',', $CI->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                ));

                $query_string_filtered[$count]['extension_name'] = (count($fetch_names) && strlen($fetch_names[0]['chainvalue']) ? $fetch_names[0]['chainvalue'] : $x['handlename']);
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
            'list_config' => $hashtag_list_config,
            'handle_column' => $handle_column,
            'hashtag_column' => $hashtag_column,
            'query_string_filtered' => $query_string_filtered,
            'contact_details' => $contact_details, //Optional addon
        );
    }
}


function count_chain_groups($chainhandletype, $chaintime_start = null, $chaintime_end = null)
{

    $CI =& get_instance();

    $query_filters = array(
        'chainhandletype IN (' . join(',', (is_array($CI->config->item('handleids___' . $chainhandletype)) ? $CI->config->item('handleids___' . $chainhandletype) : array($chainhandletype))) . ')' => null,
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
    $handle_session = handle_session();
    return ($handle_session ? view_memory(42903, 42902) . $handle_session['handleterm'] : view_memory(42903, 14565));
}

function hashtag_is_startable($i)
{
    $CI =& get_instance();
    return count($CI->Chains->read(array(
        'chainhandletype IN (' . join(',', $CI->config->item('handleids___42991')) . ')' => null, //Active Writes
        'chainhashtagoutput' => $i['hashtagid'],
        'chainhandleinput' => 4235,
    )));
}


function remove_none_utf8($string)
{
    return preg_replace('/[\x00-\x1F\x7F-\xFF]/', ' ', $string);
}


function handle_session($superpower_handleid = null, $force_redirect = 0, $session_handle_session = false)
{

    if (isset($session_handle_session['handleid'])) {
        //We have the handle!
        return $session_handle_session;
    }
    //Authenticates logged-in members with their session information
    $CI =& get_instance();
    $handle_session = $CI->session->userdata('session_handle');

    //Let's start checking various ways we can give member access:
    if ($handle_session && !$superpower_handleid) {

        //No minimum level required, grant access IF member is logged in:
        return $handle_session;

    } elseif ($handle_session && in_array($superpower_handleid, $CI->session->userdata('session_superpowers_unlocked'))) {

        //They are part of one of the levels assigned to them:
        return $handle_session;

    }

    //Still here?!
    //We could not find a reason to give member access, so block them:
    if (!$force_redirect) {

        return false;

    } else {

        //Block access:
        if ($handle_session) {
            $goto_url = view_memory(42903, 42902) . $handle_session['handleterm'];
        } else {
            $goto_url = view_app_chain(4269) . (isset($_SERVER['REQUEST_URI']) ? '?url=' . urlencode($_SERVER['REQUEST_URI']) : '');
        }

        //Now redirect:
        return get_redirected($goto_url, '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>' . blocked_reasoning($superpower_handleid) . '</div>');
    }

}


function get_server($var_name)
{
    return (isset($_SERVER[$var_name]) ? $_SERVER[$var_name] : null);
}

function html_input_type($data_type)
{
    $CI =& get_instance();
    $handles___42291 = $CI->config->item('handles___42291'); //HTML Input Types
    if (isset($handles___42291[$data_type]['m__message']) && strlen($handles___42291[$data_type]['m__message'])) {
        return $handles___42291[$data_type]['m__message'];
    } else {
        //Default option:
        return 'text';
    }
}

function js_php_redirect($url, $timer = 0)
{
    echo '<script> $(document).ready(function () { js_redirect(\'' . $url . '\', ' . $timer . '); }); </script>';
}


function generate_handle($focus__node, $str, $suggestion = null, $increment = 1)
{

    //Generates a Suitable Handle from the title:
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
        $suggestion = ($focus__node == 12273 ? 'Hashtag' : 'Handle') . $suggestion;
    }


    //Make sure no duplicates:
    if ($focus__node == 12273 && count($CI->Hashtags->read(array(
            'LOWER(hashtagterm)' => strtolower($suggestion),
        )))) {
        return generate_handle(12273, $str, $suggestion, $increment);
    } elseif ($focus__node == 12274 && count($CI->Handles->read(array(
            'LOWER(handleterm)' => strtolower($suggestion),
        )))) {
        return generate_handle(12274, $str, $suggestion, $increment);
    } else {
        //All good:
        return $suggestion;
    }

}


function add_media($uploaded_media)
{

    $CI =& get_instance();
    $handle_session = handle_session();
    if (!$handle_session || !count($uploaded_media)) {
        return false;
    }

    //We have media to process:
    foreach ($uploaded_media as $upload_media) {

        //Adding new media...
        //Search eTag to see if we already have it:
        $etag_detected = false;
        if (isset($upload_media['media_cache']['etag']) && strlen($upload_media['media_cache']['etag'])) {
            //We already have this asset, return handle:
            foreach ($CI->Chains->read(array(
                'chainhandletype IN (' . join(',', $CI->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                'chainhandleinput' => 42662, //etag
                'chainvalue' => $upload_media['media_cache']['etag'],
            ), array('chainhandleoutput'), 1) as $existing_media) {
                $upload_media['handleid'] = $existing_media['handleid'];
                $etag_detected = true;
            }
        }

        //Create Handle for this new media:
        $added_e = $CI->Handles->create(array(
            'handlename' => $upload_media['handlename'],
            'handlecover' => ($upload_media['media_typeid'] == 4259 /* Audio has no thumbnail! */ ? 'far fa-volume-up' : $upload_media['handlecover']),
        ), $handle_session['handleid']);
        if (!$added_e['status']) {
            log_error('Failed to create a new Handle for [' . $upload_media['handlename'] . '] with cover [' . $upload_media['handlecover'] . ']', array(
                'chainhandleoutput' => $upload_media['handleid'],
            ));
            continue;
        }

        //Create new media and assign ID:
        $upload_media['handleid'] = $added_e['handle_create']['handleid'];

        //new asset, create new Handle and insert tags...
        $handles___32088 = $CI->config->item('handles___32088'); //Platform Variables
        foreach ($CI->config->item('handles___42679') as $chainhandletype => $m) {

            //Ensure variable name exists so we can check the API call:
            $target_variable = false;
            if (isset($handles___32088[$chainhandletype]['m__message'])) {
                //Determine if variable exists...
                if (in_array($chainhandletype, $CI->config->item('handleids___42763')) && isset($upload_media['media_cache']['video'][$handles___32088[$chainhandletype]['m__message']])) {
                    //Video info:
                    $target_variable = $upload_media['media_cache']['video'][$handles___32088[$chainhandletype]['m__message']];
                } elseif (in_array($chainhandletype, $CI->config->item('handleids___42675')) && isset($upload_media['media_cache']['audio'][$handles___32088[$chainhandletype]['m__message']])) {
                    //Audio info:
                    $target_variable = $upload_media['media_cache']['audio'][$handles___32088[$chainhandletype]['m__message']];
                } elseif (isset($upload_media['media_cache'][$handles___32088[$chainhandletype]['m__message']])) {
                    //Media info:
                    $target_variable = $upload_media['media_cache'][$handles___32088[$chainhandletype]['m__message']];
                }
            }
            if (!strlen($target_variable) || $target_variable == '0') {
                //This variable does not have a value, move on...
                continue;
            }

            //We have a variable, see what it is...
            if (in_array($chainhandletype, $CI->config->item('handleids___33331'))) {

                //Single select that needs auto creation of Handles if missing:
                $child_id = 0;
                foreach ($CI->Chains->read(array(
                    'chainhandletype IN (' . join(',', $CI->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                    'chainhandleinput' => $chainhandletype,
                    'handlename' => $target_variable,
                ), array('chainhandleoutput'), 1, 0, array('chainid' => 'ASC')) as $child_handle) {
                    $child_id = $child_handle['handleid'];
                }

                //If not found create the child:
                if (!$child_id) {
                    $added_child = $CI->Handles->create(array(
                        'handlename' => $target_variable,
                    ));
                    if (!$added_child['status']) {
                        log_error('Failed to create a new Handle for [' . $target_variable . ']', array(
                            'chainhandleoutput' => $chainhandletype,
                        ));
                        continue;
                    }

                    //Add chains for this new Handle:
                    $CI->Chains->create(array(
                        'chainhandlecreator' => $handle_session['handleid'],
                        'chainhandleinput' => $chainhandletype,
                        'chainhandleoutput' => $added_child['handle_create']['handleid'],
                        'chainhandletype' => 4230,
                    ));

                    //Assign child Handle:
                    $child_id = $added_child['handle_create']['handleid'];

                }

                if ($child_id) {
                    //Child Handle found, simply chain:
                    $CI->Chains->create(array(
                        'chainhandlecreator' => $handle_session['handleid'],
                        'chainhandleinput' => $child_id,
                        'chainhandleoutput' => $upload_media['handleid'],
                        'chainhandletype' => 4230,
                    ));
                }

            } else {

                //Save variable as is:
                $CI->Chains->create(array(
                    'chainhandlecreator' => $handle_session['handleid'],
                    'chainhandleinput' => $chainhandletype,
                    'chainhandleoutput' => $upload_media['handleid'],
                    'chainvalue' => $target_variable,
                    'chainhandletype' => 4230,
                ));

            }
        }

        //By now have the media Handle, create necessary chains:
        if ($upload_media['handleid'] && $upload_media['media_typeid']) {

            //Chain to Handle as Uploader:
            if (!count($CI->Chains->read(array(
                'chainhandleinput' => $handle_session['handleid'],
                'chainhandleoutput' => $upload_media['handleid'],
                'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
            )))) {
                $CI->Chains->create(array(
                    'chainhandlecreator' => $handle_session['handleid'],
                    'chainhandleinput' => $handle_session['handleid'],
                    'chainhandleoutput' => $upload_media['handleid'],
                    'chainhandletype' => 4230,
                    'chainvalue' => $upload_media['playback_code'],
                ));
            }

            //Chain to Media Type:
            if (!count($CI->Chains->read(array(
                'chainhandleinput' => $upload_media['media_typeid'],
                'chainhandleoutput' => $upload_media['handleid'],
                'chainhandletype' => 4230,
            )))) {
                $CI->Chains->create(array(
                    'chainhandlecreator' => $handle_session['handleid'],
                    'chainhandleinput' => $upload_media['media_typeid'],
                    'chainhandleoutput' => $upload_media['handleid'],
                    'chainhandletype' => 4230,
                    'chainvalue' => $upload_media,
                ));
            }

        }
    }

    return true;

}


function append_handle($chainhandleinput, $chainhandlecreator, $chainvalue, $hashtagid, $update_if_existing = true)
{

    $CI =& get_instance();

    //First validate data type to ensure it matches:
    foreach ($CI->Chains->read(array(
        'chainhandletype IN (' . join(',', $CI->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
        'chainhandleinput IN (' . join(',', $CI->config->item('handleids___4592')) . ')' => null, //Data Types
        'chainhandleoutput' => $chainhandleinput,
    )) as $data_type) {
        $data_type_validate = data_type_validate($data_type['chainhandleinput'], $chainvalue);
        if (!$data_type_validate['status']) {
            //It's not the data type needed:
            return false;
        }
    }

    //Now check existing chains:
    $existing_x = $CI->Chains->read(array(
        'chainvoid >=' => 0, //Any Chain
        'chainhandletype IN (' . join(',', $CI->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
        'chainhandleinput' => $chainhandleinput,
        'chainhandleoutput' => $chainhandlecreator,
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
                'chainhandlecreator' => $chainhandlecreator,
            ));
        }

    } else {

        //Create Chain:
        $CI->Chains->create(array(
            'chainhandletype' => 4230, //Follow Handle
            'chainvalue' => $chainvalue,
            'chainhandlecreator' => $chainhandlecreator,
            'chainhandleinput' => $chainhandleinput,
            'chainhandleoutput' => $chainhandlecreator,
        ));

    }

    return true;

}


function data_type_validate($data_type, $data_value, $data_title = null)
{

    $CI =& get_instance();
    $handles___4592 = $CI->config->item('handles___4592'); //Data types

    if ($data_type == 4319 && !is_numeric($data_value)) {
        //Number:
        return array(
            'status' => 0,
            'message' => $data_title . ' must be set to a valid ' . $handles___4592[$data_type]['m__title'],
        );
    } elseif ($data_type == 42181 && (strlen(preg_replace('/[^0-9]/', '', $data_value)) < 10 || strlen(preg_replace('/[^0-9]/', '', $data_value)) > 14)) {
        //Phone Number:
        return array(
            'status' => 0,
            'message' => $data_title . ' must be set to a valid ' . $handles___4592[$data_type]['m__title'] . ' with 10-14 numbers including country code.',
        );
    } elseif ($data_type == 4318 && !strtotime($data_value)) {
        return array(
            'status' => 0,
            'message' => $data_title . ' must be set to a valid ' . $handles___4592[$data_type]['m__title'],
        );
    } elseif ($data_type == 4255 && !strlen($data_value)) {
        //Text:
        return array(
            'status' => 0,
            'message' => $data_title . ' must be set to a valid ' . $handles___4592[$data_type]['m__title'],
        );
    } elseif ($data_type == 32097 && !filter_var($data_value, FILTER_VALIDATE_EMAIL)) {
        //Email:
        return array(
            'status' => 0,
            'message' => $data_title . ' must be set to a valid ' . $handles___4592[$data_type]['m__title'],
        );
    } elseif ($data_type == 42947 && (!is_numeric($data_value) || $data_value < 0 || $data_value > 1)) {
        //Percentage:
        return array(
            'status' => 0,
            'message' => $data_title . ' must be set to a number between 0.00 & 1.00.',
        );
    } elseif (in_array($data_type, $CI->config->item('handleids___42189')) && !filter_var($data_value, FILTER_VALIDATE_URL)) {
        //URL:
        return array(
            'status' => 0,
            'message' => $data_title . ' must be set to a valid ' . $handles___4592[$data_type]['m__title'],
        );
    } elseif (in_array($data_type, $CI->config->item('handleids___42188'))) {
        //Single Choice of Multi Choice Handle types should not be validated here
        log_error('data_type_validate() was asked to validate choice options for @' . $data_type . ' [' . $data_value . '] [' . $data_title . ']', array(
            'chainhandleoutput' => $data_type,
        ));
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

    if (in_array($data_type, $CI->config->item('handleids___4318')) && strtotime($data_value) > 0) {
        //Format Time:
        return date(view_memory(6404, 4318), strtotime($data_value));
    }

    //No special formatting needed:
    return $data_value;

}

function change_handle($old_handle)
{
    $max_length = view_memory(6404, 41985);
    if (strlen($old_handle) < $max_length) {
        //We have some room to change:
        return substr($old_handle . rand(100000, 999999), 0, $max_length);
    } else {
        //No room to change, remove some words from the end:
        return substr($old_handle, 0, ($max_length - 6)) . rand(100000, 999999);
    }
}

function sort_by($handleid, $custom_sort = array())
{

    $CI =& get_instance();
    $order_by = array();
    foreach ($CI->config->item('handles___' . $handleid) as $sort_id => $sort) {
        $order_by['chainhandleinput = \'' . $sort_id . '\' DESC'] = null;
    }

    if (is_array($custom_sort)) {
        return array_merge($order_by, $custom_sort);
    } else {
        return $order_by;
    }
}


function validate_update_handle($str, $hashtagid = null, $handleid = null)
{

    $CI =& get_instance();
    $handle_session = handle_session();

    //Validate:
    if (($hashtagid && $handleid) || (!$hashtagid && !$handleid)) {

        return array(
            'status' => 0,
            'db_duplicate' => 0,
            'message' => 'Must set either Hashtag or Handle ID! Pick one',
        );

    } elseif (!strlen($str)) {

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

    } elseif (strlen($str) > view_memory(6404, 41985)) {

        return array(
            'status' => 0,
            'db_duplicate' => 0,
            'message' => 'Hashtag Must be ' . view_memory(6404, 41985) . ' characters or less',
        );

    } elseif ($hashtagid && array_key_exists(strtolower($str), $CI->config->item('handlhandles___6287'))) {

        return array(
            'status' => 0,
            'db_duplicate' => 1,
            'message' => 'Hashtag "' . $str . '" already in use.',
        );

    }

    //Syntax good! Now let's check the DB for duplicates
    if ($hashtagid > 0) {

        foreach ($CI->Hashtags->read(array(
            'hashtagid !=' => $hashtagid,
            'LOWER(hashtagterm)' => strtolower($str),
        ), 0) as $matched) {
            return array(
                'status' => 0,
                'db_duplicate' => 1,
                'message' => 'Hashtag "' . $str . '" already in use.',
            );
        }

        //Since not found we can replace this:
        $CI->Hashtags->update($hashtagid, array(
            'hashtagterm' => change_handle($str),
        ), $handle_session['handleid']);

    } elseif ($handleid > 0) {

        foreach ($CI->Handles->read(array(
            'handleid !=' => $handleid,
            'LOWER(handleterm)' => strtolower($str),
        ), 0) as $matched) {
            //Is it active?
            return array(
                'status' => 0,
                'db_duplicate' => 1,
                'message' => 'Hashtag "' . $str . '" already in use.',
            );
        }

        //Since not active we can replace this:
        $CI->Handles->update($handleid, array(
            'handleterm' => change_handle($str),
        ), $handle_session['handleid']);

    }


    //All good, return success:
    return array(
        'status' => 1,
        'db_duplicate' => 0,
        'message' => 'Success',
    );

}


function validate_handlename($str)
{

    //Validate:
    $title_clean = trim($str);
    while (substr_count($title_clean, '  ') > 0) {
        $title_clean = str_replace('  ', ' ', $title_clean);
    }

    if (!strlen(trim($str))) {

        return array(
            'status' => 0,
            'message' => 'Handle title missing',
        );

    } elseif (strlen(trim($str)) < 1) {

        return array(
            'status' => 0,
            'message' => 'Enter Handle title to continue.',
        );

    } elseif (strlen($str) > view_memory(6404, 6197)) {

        return array(
            'status' => 0,
            'message' => 'Handle title must be ' . view_memory(6404, 6197) . ' characters or less',
        );

    }

    //All good, return success:
    return array(
        'status' => 1,
        'handlename_clean' => trim($title_clean),
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

function user_website($chainhandlecreator)
{
    $CI =& get_instance();
    foreach ($CI->Chains->read(array(
        'chainhandleoutput' => $chainhandlecreator,
        'chainhandletype' => 4230, //New Handle Created
    ), array(), 1) as $handle_created) {
        return $handle_created['chainhandledomain'];
    }
    foreach ($CI->Chains->read(array(
        'chainhandlecreator' => $chainhandlecreator,
    ), array(), 1) as $handle_created) {
        return $handle_created['chainhandledomain'];
    }
    return 0;
}


function random_adjective()
{

    $adjectives = array('Amazing', 'Awesome', 'Adventurous', 'Ambitious', 'Adorable', 'Artistic', 'Agile', 'Acrobatic', 'Attractive', 'Alluring', 'Astonishing', 'Authentic', 'Awkward', 'Ancient', 'American', 'Australian', 'Austrian', 'African', 'Asian', 'Brave', 'Beautiful', 'Bright', 'Busy', 'Big', 'Bold', 'Basic', 'Blissful', 'Bouncy', 'Beneficial', 'Bashful', 'Black', 'Brown', 'Burgundy', 'Broad', 'British', 'Belgian', 'Brazilian', 'Creative', 'Confident', 'Cheerful', 'Calm', 'Cute', 'Clever', 'Curious', 'Charming', 'Courageous', 'Clean', 'Cool', 'Considerate', 'Caring', 'Crazy', 'Classic', 'Chic', 'Cloudy', 'Colombian', 'Chinese', 'Delightful', 'Dreamy', 'Daring', 'Dynamic', 'Dark', 'Decent', 'Drastic', 'Defiant', 'Dedicated', 'Deep', 'Desirable', 'Dirty', 'Dramatic', 'Dizzy', 'Demanding', 'Diligent', 'Dutch', 'Danish', 'Delicious', 'Dazzling', 'Easy', 'Elegant', 'Enthusiastic', 'Eager', 'Efficient', 'Empathetic', 'Excellent', 'Exciting', 'Effective', 'Extravagant', 'Entertaining', 'Exotic', 'Expressive', 'Expensive', 'Elaborate', 'European', 'Egyptian', 'Eastern', 'Elderly', 'Educational', 'Fantastic', 'Fabulous', 'Friendly', 'Funny', 'Fearless', 'Fresh', 'Fascinating', 'Fluffy', 'Fierce', 'Fine', 'Free', 'Frugal', 'French', 'Futuristic', 'Fast', 'Flat', 'Famous', 'Flawless', 'Formal', 'Frizzy', 'Gorgeous', 'Great', 'Gentle', 'Generous', 'Gracious', 'Genuine', 'Glorious', 'Graceful', 'Golden', 'Grand', 'Green', 'Growing', 'Groovy', 'Greek', 'Grumpy', 'Gothic', 'Gargantuan', 'Gigantic', 'German', 'Georgian', 'Happy', 'Hot', 'Humble', 'Honest', 'Healthy', 'Heavy', 'Handsome', 'High', 'Helpful', 'Hilarious', 'Heavenly', 'Harmonious', 'Hardworking', 'Historical', 'Heartfelt', 'Homey', 'Hungry', 'Huge', 'Hispanic', 'Hindu', 'Interesting', 'Intelligent', 'Incredible', 'Inspiring', 'Impressive', 'Imaginative', 'Inquisitive', 'Iconic', 'Indigo', 'Industrious', 'Inevitable', 'Inexpensive', 'Incomparable', 'Hashtaglistic', 'Illustrious', 'Indian', 'Italian', 'Irresistible', 'Irrelevant', 'Icy', 'Joyful', 'Jolly', 'Jovial', 'Jaunty', 'Jaded', 'Jazzy', 'Jumpy', 'Juicy', 'Judgmental', 'Jumbled', 'Japanese', 'Javanese', 'Jewish', 'Jittery', 'Junior', 'Justified', 'Jubilant', 'Jade', 'Jumbo', 'Joint', 'Kind', 'Knowledgeable', 'Keen', 'Kooky', 'Knotty', 'Kinetic', 'Known', 'Keen-eyed', 'Knightly', 'Keen-witted', 'Kempt', 'Knockout', 'Knackered', 'Kindhearted', 'Kenyan', 'Kiddy', 'Knotted', 'Kyrgyzstani', 'Kindred', 'Kentuckian', 'Loud', 'Lively', 'Lazy', 'Loyal', 'Long', 'Lonely', 'Lovely', 'Large', 'Light', 'Low', 'Luxurious', 'Lasting', 'Literal', 'Learned', 'Lucky', 'Magnificent', 'Mysterious', 'Modern', 'Moody', 'Musical', 'Mighty', 'Masculine', 'Mesmerizing', 'Mindful', 'Memorable', 'Multicultural', 'Moral', 'Majestic', 'Mischievous', 'Mouthwatering', 'Mellow', 'Modest', 'Magical', 'Melodic', 'Mature', 'Nervous', 'Natural', 'New', 'Nice', 'Noble', 'Naughty', 'Neat', 'Nonchalant', 'Noisy', 'Narrow', 'Nostalgic', 'Needy', 'Negative', 'Nutritious', 'Nonstop', 'Noteworthy', 'Numerous', 'Notable', 'Nurturing', 'Nifty', 'Obvious', 'Original', 'Optimistic', 'Ordinary', 'Official', 'Outstanding', 'Open', 'Organic', 'Odd', 'Observant', 'Obedient', 'Opaque', 'Obsolete', 'Offensive', 'Oily', 'Old-fashioned', 'Ornate', 'Onyx', 'Overwhelming', 'Oceanic', 'Perfect', 'Patient', 'Positive', 'Powerful', 'Popular', 'Polite', 'Peaceful', 'Playful', 'Pleasant', 'Precious', 'Practical', 'Private', 'Proud', 'Profound', 'Pretty', 'Painful', 'Priceless', 'Puzzled', 'Persistent', 'Passionate', 'Quaint', 'Quick', 'Quiet', 'Quirky', 'Quizzical', 'Queenly', 'Quivering', 'Quotable', 'Qualified', 'Quantifiable', 'Questionable', 'Quarrelsome', 'Queasy', 'Quenched', 'Quack', 'Quilted', 'Quizzing', 'Reliable', 'Responsible', 'Romantic', 'Rich', 'Rude', 'Real', 'Radiant', 'Royal', 'Rough', 'Respectful', 'Red', 'Rational', 'Rustic', 'Radiant', 'Robust', 'Rare', 'Resilient', 'Reckless', 'Ready', 'Rambunctious', 'Strong', 'Smart', 'Serious', 'Sad', 'Special', 'Simple', 'Super', 'Sincere', 'Safe', 'Stunning', 'Sweet', 'Shy', 'Successful', 'Satisfied', 'Shiny', 'Silent', 'Sparkling', 'Strong-willed', 'Scary', 'Surprised', 'Tall', 'Talkative', 'Tasty', 'Tender', 'Terrific', 'Terrible', 'Thoughtful', 'Thrifty', 'Timely', 'Tough', 'Traditional', 'Trustworthy', 'Tremendous', 'Tricky', 'Tolerant', 'Tenacious', 'Tiny', 'Tired', 'Top', 'Trembling', 'Ugly', 'Ultimate', 'Unbelievable', 'Uncertain', 'Uncommon', 'Unconditional', 'Unconscious', 'Understanding', 'Unforgettable', 'Unhappy', 'Unique', 'United', 'Universal', 'Unusual', 'Upbeat', 'Uplifting', 'Urbane', 'Urgent', 'Useful', 'Useless', 'Valuable', 'Vague', 'Valid', 'Vast', 'Various', 'Vengeful', 'Vibrant', 'Victorious', 'Vigorous', 'Villainous', 'Vital', 'Vivacious', 'Vocal', 'Volatile', 'Volcanic', 'Voracious', 'Vulnerable', 'Vicious', 'Velvet', 'Verbal', 'Warm', 'Wild', 'Witty', 'Wise', 'Wonderful', 'Worried', 'Wondrous', 'Wealthy', 'Whimsical', 'Wicked', 'Wide', 'Wavy', 'Watery', 'Weighty', 'Wooden', 'Weak', 'Wary', 'Winning', 'Well-groomed', 'Wholesome', 'Xeric', 'Xerophytic', 'Xerotic', 'Xyloid', 'Xylonic', 'Xylophagous', 'Xanthic', 'Xanthous', 'Xerarch', 'Xylotomous', 'Xerographic', 'Xenial', 'Xenogenetic', 'Xenolithic', 'Xylophilous', 'Yellow', 'Young', 'Yielding', 'Yearly', 'Yummy', 'Yawning', 'Yucky', 'Yearning', 'Yeasty', 'Yielding', 'Youthful', 'Yare', 'Yclept', 'Yellowish', 'Yearlong', 'Youth', 'Zealous', 'Zesty', 'Zigzag', 'Zillionth', 'Zinciferous', 'Zingy', 'Zippered', 'Zippy', 'Zoological', 'Zonal', 'Ambitious', 'Amiable', 'Analytical', 'Assertive', 'Authentic', 'Bold', 'Calm', 'Charismatic', 'Charming', 'Cheerful', 'Compassionate', 'Confident', 'Conscientious', 'Considerate', 'Creative', 'Curious', 'Dependable', 'Diligent', 'Disciplined', 'Easygoing', 'Empathetic', 'Enthusiastic', 'Extraverted', 'Flexible', 'Friendly', 'Generous', 'Genuine', 'Gracious', 'Hardworking', 'Honest', 'Humble', 'Independent', 'Innovative', 'Insightful', 'Intelligent', 'Kind', 'Logical', 'Loyal', 'Open-minded', 'Optimistic', 'Outgoing', 'Passionate', 'Patient', 'Persistent', 'Practical', 'Rational', 'Reliable', 'Rehandleful', 'Responsible', 'Self-confident', 'Happy', 'Sad', 'Angry', 'Fearful', 'Anxious', 'Excited', 'Frustrated', 'Nostalgic', 'Hopeful', 'Envious', 'Jealous', 'Empathetic', 'Curious', 'Surprised', 'Disappointed', 'Grateful', 'Confused', 'Content', 'Lonely', 'Loved', 'Joyful', 'Melancholic', 'Irritated', 'Apprehensive', 'Restless', 'Ecstatic', 'Distraught', 'Panicked', 'Annoyed', 'Numb', 'Scared', 'Enraged', 'Heartbroken', 'Amused', 'Overwhelmed', 'Grateful', 'Conflicted', 'Peaceful', 'Devastated', 'Empowered');

    return $adjectives[array_rand($adjectives)];
}


function dispatch_sms($to_phone, $single_message, $handleid = 0, $x_data = array(), $template_hashtagid = 0, $chainhandledomain = 0, $log_tr = true, $demo_only = false)
{

    $CI =& get_instance();
    $twilio_account_sid = website_setting(30859);
    $twilio_auth_token = website_setting(30860);
    $twilio_from_number = website_setting(27673);
    if (!$twilio_from_number || !$twilio_auth_token || !$twilio_account_sid) {

        //No way to send an SMS:
        if ($log_tr) {
            log_error('dispatch_sms() missing either: ' . $twilio_account_sid . ' / ' . $twilio_auth_token . ' / ' . $twilio_from_number, array(
                'chainhandleoutput' => $handleid,
                'chainhandlecreator' => $handleid,
                'chainhandledomain' => $chainhandledomain,
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
    if ($log_tr) {

        $target_handle = ($sms_success ? 27676 : 27678);
        $handle_session = handle_session();
        $handleid = ($handleid > 0 ? $handleid : ($handle_session ? $handle_session['handleid'] : 14068));
        if ($template_hashtagid && count($CI->Hashtags->read(array(
                'hashtagid' => $template_hashtagid,
            )))) {
            foreach ($CI->Hashtags->read(array(
                'hashtagid' => $template_hashtagid,
            )) as $hashtag_template) {
                $CI->Chains->hashtag_discovered($target_handle, $handleid, 0, $hashtag_template, array(), array(
                    'chainvalue' => $single_message,
                ));
            }
        } elseif ($handleid > 0) {

            $CI->Chains->create(array_merge($x_data, array(
                'chainhandletype' => 44179, //Triggered
                'chainhandleinput' => $target_handle,
                'chainhandleoutput' => $handleid,
                'chainhandlecreator' => $handleid,
                'chainvalue' => $single_message,
                'chainhashtagoutput' => $template_hashtagid,
            )));
        }


    }

    return true;

}

function dispatch_email($to_emails, $subject, $email_body, $handleid = 0, $x_data = array(), $template_hashtagid = 0, $chainhandledomain = 0, $log_tr = true, $demo_only = false)
{

    $CI =& get_instance();
    $domain_name = get_domain('m__title', $handleid, $chainhandledomain);
    $domain_email = website_setting(28614, $handleid, $chainhandledomain);

    if (!strlen($domain_email)) {
        $domain_name = 'MENCH';
        $domain_name = 'support@mench.com';
        log_error('Domain email is missing! (' . $domain_name . ') (' . $domain_email . ') (' . join(' & ', $to_emails) . ')', array(
            'chainhandleoutput' => $handleid,
        ));
    }

    $email_domain = '"' . $domain_name . '" <' . $domain_email . '>';
    $name = 'New User';
    $ReplyToAddresses = array($email_domain);

    if ($handleid > 0) {

        $es = $CI->Handles->read(array(
            'handleid' => $handleid,
        ));
        if (count($es)) {

            $name = $es[0]['handlename'];

            //Also fetch email for this user to populate the reply to:
            $fetch_emails = $CI->Chains->read(array(
                'chainhandleinput' => 3288, //Email
                'chainhandleoutput' => $handleid,
                'chainhandletype IN (' . join(',', $CI->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
            ));
            if (count($fetch_emails) && filter_var($fetch_emails[0]['chainvalue'], FILTER_VALIDATE_EMAIL)) {
                array_push($ReplyToAddresses, trim($fetch_emails[0]['chainvalue']));
            }
        }
    }

    //Email has no word limit to add header & footer:
    $handles___6287 = $CI->config->item('handles___6287'); //APP
    $base_domain = 'https://' . get_domain('m__message', $handleid, $chainhandledomain);

    $email_message = '<div class="line">' . randomize_text(29749) . ' ' . $name . ' ' . randomize_text(29750) . '</div>';
    $email_message .= $email_body . "\n";
    $email_message .= '<div class="line">' . randomize_text(12691) . '</div>';
    $email_message .= '<div class="line">' . get_domain('m__title', $handleid, $chainhandledomain) . '</div>';


    if ($handleid > 0 && count($es) && (!$template_hashtagid || !count($CI->Chains->read(array(
                'chainhandletype IN (' . join(',', $CI->config->item('handleids___42991')) . ')' => null, //Writes
                'chainhandleinput' => 31779, //Mandatory Emails
                'chainhashtagoutput' => $template_hashtagid,
            ))))) {
        //User specific notifications:
        $email_message .= '<div class="line"><a href="' . $base_domain . view_app_chain(28904) . '?handleterm=' . $es[0]['handleterm'] . '&time=' . time() . '&hash=' . view_hash(time() . $es[0]['handleterm']) . '" style="font-size:13px;">' . $handles___6287[28904]['m__title'] . '</a></div>';
    }


    $general_style = 'width:100%; max-width:610px; font-size:16px; margin-bottom:8px; line-height:134%;';

    //Email HTML Transformations:
    $email_message = str_replace('>Show more<', '><', $email_message); //Hide the show more content if any
    $email_message = str_replace('<img ', '<img style="' . $general_style . '" ', $email_message);
    $email_message = str_replace('<div class="line', '<div style="' . $general_style . '" class="line', $email_message);
    $email_message = str_replace("\n", '<div style="padding:3px 0 0; line-height:100%;">&nbsp;</div>', $email_message);
    $email_message = str_replace('href="/', 'style="display:inline-block;" href="' . $base_domain . '/', $email_message);

    $email_data = array(
        // Handle is required
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

        $handle_session = handle_session();
        $handleid = ($handleid > 0 ? $handleid : ($handle_session ? $handle_session['handleid'] : 14068));
        if ($template_hashtagid && count($CI->Hashtags->read(array(
                'hashtagid' => $template_hashtagid,
            )))) {
            foreach ($CI->Hashtags->read(array(
                'hashtagid' => $template_hashtagid,
            )) as $hashtag_template) {
                $CI->Chains->hashtag_discovered(29399, $handleid, 0, $hashtag_template, array(), array(
                    'chainvalue' => $subject . "\n" . $email_message,
                ));
            }
        } elseif ($handleid > 0) {

            $CI->Chains->create(array_merge($x_data, array(
                'chainhandletype' => 44179, //Triggered
                'chainhandleinput' => 29399,
                'chainhandleoutput' => $handleid,
                'chainhandlecreator' => $handleid,
                'chainvalue' => $subject . "\n" . $email_message,
                'chainhashtagoutput' => $template_hashtagid,
            )));
        }

        //Can we also mark the discovery as complete?
        if ($handleid && isset($x_data['chainhashtaginput']) && $x_data['chainhashtaginput'] > 0 && isset($x_data['chainhashtagoutput'])) {
            foreach ($CI->Hashtags->read(array(
                'hashtagid' => $x_data['chainhashtaginput'],
            )) as $email_i) {
                $CI->Chains->hashtag_discovered(hashtag_type_discovery($email_i), $handleid, $x_data['chainhashtagoutput'], $email_i, $x_data);
            }
        }

    }


    return $response;

}


function website_setting($setting_id = 0, $initiator_handleid = 0, $chainhandledomain = 0, $force_website = true)
{

    $CI =& get_instance();
    $handle_id = 0; //Assume no domain unless found below

    if (!$initiator_handleid) {
        $handle_session = handle_session();
        if ($handle_session && isset($handle_session['handleid']) && $handle_session['handleid'] > 0) {
            $initiator_handleid = $handle_session['handleid'];
        }
    }

    if ($chainhandledomain && $force_website) {

        $handle_id = $chainhandledomain;

    } else {

        $server_name = get_server('SERVER_NAME');
        if (strlen($server_name)) {
            foreach ($CI->config->item('handles___14870') as $chainhandletype => $m) {
                if (substr_count($m['m__message'], $server_name) == 1) {
                    $handle_id = $chainhandletype;
                    break;
                }
            }
        }

        $handle_id = ($handle_id ? $handle_id : ($chainhandledomain > 0 ? $chainhandledomain : 2738 /* Mench */));

    }


    if (!$setting_id) {
        return $handle_id;
    }


    $handles___domain_sett = $CI->config->item('handles___' . $setting_id); //DOMAINS

    if (!isset($handles___domain_sett[$handle_id]) || !strlen($handles___domain_sett[$handle_id]['m__message'])) {
        $target_return = (in_array($setting_id, $CI->config->item('handleids___6404')) ? view_memory(6404, $setting_id) : false);
    } else {
        $target_return = $handles___domain_sett[$handle_id]['m__message'];
    }

    return $target_return;

}


function get_domain($var_field, $initiator_handleid = 0, $chainhandledomain = 0, $force_website = true)
{
    $CI =& get_instance();
    $domain_e = website_setting(0, $initiator_handleid, $chainhandledomain, $force_website);
    $handles___14870 = $CI->config->item('handles___14870'); //DOMAINS
    return $handles___14870[$domain_e][$var_field];
}


function handle_access($handleterm = null, $handleid = 0, $e = false, $replacement_handleid = false, $handle_list_config = array())
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
    $handle_session = handle_session();
    if (!$replacement_handleid && handle_session(10939)) {
        return 3;
    } elseif (!$replacement_handleid && $handle_session && ($handleterm == $handle_session['handleterm'] || $handleid == $handle_session['handleid'])) {
        return 3;
    }

    if (strlen($handleterm)) {
        $filters['LOWER(handleterm)'] = strtolower($handleterm);
    } elseif (intval($handleid)) {
        $filters['handleid'] = $handleid;
    } elseif (!$e || (!$handle_session && !$replacement_handleid)) {
        return 0;
    }

    if (!$e) {
        //Check privacy first:
        foreach ($CI->Handles->read($filters) as $match_e) {
            $e = $match_e;
            break;
        }
    }


    //IF Follows Any
    $chainhandlecreator = ($replacement_handleid > 0 ? $replacement_handleid : ($handle_session ? $handle_session['handleid'] : 0));
    if (!count($handle_list_config)) {
        $handle_list_config = handle_list_config($e['handleid']);
    }
    if (is_array($handle_list_config[1645062]) && count($handle_list_config[1645062])) {
        $the_counter = 0;
        if ($chainhandlecreator) {
            foreach ($handle_list_config[1645062] as $focushandleid) {
                if ((($chainhandlecreator == $focushandleid) || count($CI->Chains->read(array(
                        'chainhandletype IN (' . join(',', $CI->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                        'chainhandleinput' => $focushandleid,
                        'chainhandleoutput' => $chainhandlecreator,
                    ))))) {
                    $the_counter++;
                    break;
                }
            }
        }
        if (!$chainhandlecreator || !$the_counter) {
            return 0;
        }
    }


    //IF Follows All
    if (is_array($handle_list_config[1645146]) && count($handle_list_config[1645146])) {
        $the_counter = 0;
        if ($chainhandlecreator) {
            foreach ($handle_list_config[1645146] as $focushandleid) {
                if ((($chainhandlecreator == $focushandleid) || count($CI->Chains->read(array(
                        'chainhandletype IN (' . join(',', $CI->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                        'chainhandleinput' => $focushandleid,
                        'chainhandleoutput' => $chainhandlecreator,
                    ))))) {
                    $the_counter++;
                }
            }
        }
        if (!$chainhandlecreator || $the_counter < count($handle_list_config[1645146])) {
            return 0;
        }
    }


    //IF Not Follows Any
    if (is_array($handle_list_config[1645161]) && count($handle_list_config[1645161])) {
        $the_counter = 0;
        if ($chainhandlecreator) {
            foreach ($handle_list_config[1645161] as $focushandleid) {
                if (($chainhandlecreator == $focushandleid) || count($CI->Chains->read(array(
                        'chainhandletype IN (' . join(',', $CI->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                        'chainhandleinput' => $focushandleid,
                        'chainhandleoutput' => $chainhandlecreator,
                    )))) {
                    //Found an exclusion, so skip this:
                    $the_counter++;
                    break;
                }
            }
        }
        if (!$chainhandlecreator || $the_counter > 0) {
            return 0;
        }
    }

    //IF Not Follows All
    if (is_array($handle_list_config[1645176]) && count($handle_list_config[1645176])) {
        $the_counter = 0;
        if ($chainhandlecreator) {
            foreach ($handle_list_config[1645176] as $focushandleid) {
                if (($chainhandlecreator == $focushandleid) || count($CI->Chains->read(array(
                        'chainhandletype IN (' . join(',', $CI->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                        'chainhandleinput' => $focushandleid,
                        'chainhandleoutput' => $chainhandlecreator,
                    )))) {
                    //Found an exclusion, so skip this:
                    $the_counter++;
                }
            }
        }
        if (!$chainhandlecreator || $the_counter == count($handle_list_config[1645176])) {
            return 0;
        }
    }


    $is_public = true;
    $is_author = false;
    if ($handle_session) {
        $is_author = count($CI->Chains->read(array(
            'chainhandletype' => 12274,
            'chainhandlecreator' => $chainhandlecreator,
            'chainid' => $e['handleid'],
        )));
    }

    return ($is_author ? 3 : ($is_public ? 2 : 1));

}

function handle_up($handleid, $return_ids = array())
{

    if (!count($return_ids)) {
        $return_ids = array(intval($handleid));
    }
    $CI =& get_instance();
    foreach ($CI->Chains->read(array(
        'chainhandleinput > 0' => null,
        'chainhandleoutput' => $handleid,
        'chainhandletype IN (' . join(',', $CI->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
    ), array(), 0) as $up_handle) {
        if (in_array(intval($up_handle['chainhandleinput']), $return_ids)) {
            continue;
        }
        array_push($return_ids, intval($up_handle['chainhandleinput']));
        $return_ids_up = handle_up($up_handle['chainhandleinput'], $return_ids);
        foreach ($return_ids_up as $return_id_up) {
            if (!in_array($return_id_up, $return_ids)) {
                array_push($return_ids, $return_id_up);
            }
        }
    }

    return $return_ids;
}

function hashtag_access($hashtagterm = null, $hashtagid = 0, $i = false, $replacement_handleid = false, $hashtag_list_config = array(), $is_cahce = false)
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
    $handle_session = handle_session();
    $discovery_mode = ($replacement_handleid > 0 ? true : ((isset($_POST['js_request_uri']) && substr_count($_POST['js_request_uri'], '/') == 2) || (!isset($_POST['js_request_uri']) && strlen($CI->uri->segment(2)) && !array_key_exists(strtolower($CI->uri->segment(1)), $CI->config->item('handlhandles___6287'))) ? true : false));

    if ($is_cahce) {
        return 1;
    }

    if (!$discovery_mode && handle_session(12700)) {
        return 3;
    }


    if (!$i) {
        if (strlen($hashtagterm)) {
            $filters['LOWER(hashtagterm)'] = strtolower($hashtagterm);
        } elseif (intval($hashtagid)) {
            $filters['hashtagid'] = $hashtagid;
        } elseif (!$i) {
            return 0;
        }
        //Check privacy first:
        foreach ($CI->Hashtags->read($filters) as $match_i) {
            $i = $match_i;
            break;
        }
    }

    $chainhandlecreator = ($replacement_handleid > 0 ? $replacement_handleid : ($handle_session ? $handle_session['handleid'] : 0));
    $is_author = false;
    if ($chainhandlecreator) {
        $is_author = count($CI->Chains->read(array(
            'chainhandletype' => 12273,
            'chainhandlecreator' => $chainhandlecreator,
            'chainid' => $i['hashtagid'],
        )));
    }

    if ($is_author) {

        //Authors can always edit:
        return (!$discovery_mode ? 3 : 2);

    } elseif (!$discovery_mode && count($CI->Chains->read(array(
            'chainhandletype IN (' . join(',', $CI->config->item('handleids___42953')) . ')' => null, //Mentioned Handles
            'chainhandleinput' => $chainhandlecreator,
            'chainhashtagoutput' => $i['hashtagid'],
        )))) {

        //Mentioned can always reply:
        return 2;

    } else {

        //Inventory Limits:
        if (!count($hashtag_list_config) && hashtag_spots_remaining($hashtagid) == 0) {
            return 0;
        }

        // HASHTAG RELATION CHECK:
        $hashtag_list_config = hashtag_list_config($hashtagid);


        //If hashtag_discovered All
        if (count($hashtag_list_config[44161])) {
            $the_counter = 0;
            if ($chainhandlecreator) {
                foreach ($hashtag_list_config[44161] as $focushashtagid) {
                    if (count($CI->Chains->read(array(
                        'chainhandlecreator' => $chainhandlecreator,
                        'chainhashtaginput' => $focushashtagid,
                        'chainhandletype IN (' . join(',', $CI->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
                    )))) {
                        $the_counter++;
                    }
                }
            }
            if (!$chainhandlecreator || $the_counter < count($hashtag_list_config[44161])) {
                return 0;
            }
        }

        //If hashtag_discovered Any
        if (count($hashtag_list_config[40791])) {
            $the_counter = 0;
            if ($chainhandlecreator) {
                foreach ($hashtag_list_config[40791] as $focushashtagid) {
                    if (count($CI->Chains->read(array(
                        'chainhandlecreator' => $chainhandlecreator,
                        'chainhashtaginput' => $focushashtagid,
                        'chainhandletype IN (' . join(',', $CI->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
                    )))) {
                        $the_counter++;
                        break;
                    }
                }
            }
            if (!$chainhandlecreator || !$the_counter) {
                return 0;
            }
        }


        //If Not hashtag_discovered All
        if (count($hashtag_list_config[44162])) {
            $the_counter = 0;
            if ($chainhandlecreator) {
                foreach ($hashtag_list_config[44162] as $focushashtagid) {
                    if (count($CI->Chains->read(array(
                        'chainhandlecreator' => $chainhandlecreator,
                        'chainhashtaginput' => $focushashtagid,
                        'chainhandletype IN (' . join(',', $CI->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
                    )))) {
                        $the_counter++;
                    }
                }
                if (!$chainhandlecreator || $the_counter >= count($hashtag_list_config[44162])) {
                    return 0;
                }
            } else {
                return 0;
            }
        }


        //If Not hashtag_discovered Any
        if (count($hashtag_list_config[40793])) {
            $the_counter = 0;
            if ($chainhandlecreator) {
                foreach ($hashtag_list_config[40793] as $focushashtagid) {
                    if (count($CI->Chains->read(array(
                        'chainhandlecreator' => $chainhandlecreator,
                        'chainhashtaginput' => $focushashtagid,
                        'chainhandletype IN (' . join(',', $CI->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
                    )))) {
                        $the_counter++;
                        break;
                    }
                }
            }
            if (!$chainhandlecreator || $the_counter > 0) {
                return 0;
            }
        }


        // HANDLE RELATION CHECK:


        //IF Follows Any
        if (count($hashtag_list_config[27984])) {
            $the_counter = 0;
            if ($chainhandlecreator) {
                foreach ($hashtag_list_config[27984] as $focushandleid) {
                    if ((($chainhandlecreator == $focushandleid) || count($CI->Chains->read(array(
                            'chainhandletype IN (' . join(',', $CI->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                            'chainhandleinput' => $focushandleid,
                            'chainhandleoutput' => $chainhandlecreator,
                        ))))) {
                        $the_counter++;
                        break;
                    }
                }
            }
            if (!$chainhandlecreator || !$the_counter) {
                return 0;
            }
        }


        //IF Follows All
        if (count($hashtag_list_config[43513])) {
            $the_counter = 0;
            if ($chainhandlecreator) {
                foreach ($hashtag_list_config[43513] as $focushandleid) {
                    if ((($chainhandlecreator == $focushandleid) || count($CI->Chains->read(array(
                            'chainhandletype IN (' . join(',', $CI->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                            'chainhandleinput' => $focushandleid,
                            'chainhandleoutput' => $chainhandlecreator,
                        ))))) {
                        $the_counter++;
                    }
                }
            }
            if (!$chainhandlecreator || $the_counter < count($hashtag_list_config[43513])) {
                return 0;
            }
        }


        //IF Not Follows Any
        if (count($hashtag_list_config[43514])) {
            $the_counter = 0;
            if ($chainhandlecreator) {
                foreach ($hashtag_list_config[43514] as $focushandleid) {
                    if (($chainhandlecreator == $focushandleid) || count($CI->Chains->read(array(
                            'chainhandletype IN (' . join(',', $CI->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                            'chainhandleinput' => $focushandleid,
                            'chainhandleoutput' => $chainhandlecreator,
                        )))) {
                        //Found an exclusion, so skip this:
                        $the_counter++;
                        break;
                    }
                }
            }
            if (!$chainhandlecreator || $the_counter > 0) {
                return 0;
            }
        }

        //IF Not Follows All
        if (count($hashtag_list_config[26600])) {
            $the_counter = 0;
            if ($chainhandlecreator) {
                foreach ($hashtag_list_config[26600] as $focushandleid) {
                    if (($chainhandlecreator == $focushandleid) || count($CI->Chains->read(array(
                            'chainhandletype IN (' . join(',', $CI->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                            'chainhandleinput' => $focushandleid,
                            'chainhandleoutput' => $chainhandlecreator,
                        )))) {
                        //Found an exclusion, so skip this:
                        $the_counter++;
                    }
                }
            }
            if (!$chainhandlecreator || $the_counter == count($hashtag_list_config[26600])) {
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


function update_algolia($focus__node = null, $s__id = 0)
{

    if (!search_enabled() || isset($_GET['disable_algolia'])) {
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

    if ($focus__node && !in_array($focus__node, $CI->config->item('handleids___12761'))) {
        return array(
            'status' => 0,
            'message' => 'Object type is invalid',
        );
    } elseif (($focus__node && !$s__id) || ($s__id && !$focus__node)) {
        return array(
            'status' => 0,
            'message' => 'Must define both object type and ID',
        );
    }


    $handles___4737 = $CI->config->item('handles___4737'); //Hashtag Status

    //Define the support objects indexed on algolia:
    $s__id = intval($s__id);
    $limits = array();


    if ($focus__node == 12273) {
        $focus_field_id = 'hashtagid';
    } elseif ($focus__node == 12274) {
        $focus_field_id = 'handleid';
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

        //Do both hashtags and Handles:
        $fetch_objects = $CI->config->item('handleids___12761');

        //We need to update the entire index, so let's truncate it first:
        $search_index->clearIndex();

        //Boost processing power:
        boost_power();

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
                $filters['hashtagid'] = $s__id;
            }

            $db_rows[$loop_obj] = $CI->Hashtags->read($filters, 0);

        } elseif ($loop_obj == 12274) {

            //HANDLES
            if ($s__id) {
                $filters['handleid'] = $s__id;
            }

            $db_rows[$loop_obj] = $CI->Handles->read($filters, 0);

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
                    hashtag_number_calculator($s);
                } elseif ($focus__node == 12274) {
                    handle_number_calculator($s);
                }
            }


            //Attempt to fetch Algolia object ID from object Metadata:
            if ($focus__node) {

                $external_name = ($focus__node == 12273 ? 'hashtagexternal' : 'handleexternal');

                if (intval($s[$external_name]) > 0) {
                    //We found it! Let's just update existing algolia record
                    $export_row['objectID'] = intval($s[$external_name]);
                }

            } else {

                //Clear possible metadata algolia ID's that have been cached:
                if ($loop_obj == 12273) {
                    $CI->Hashtags->update($s['hashtagid'], array(
                        'hashtagexternal' => 0,
                    ));
                } elseif ($loop_obj == 12274) {
                    $CI->Handles->update($s['handleid'], array(
                        'handleexternal' => 0,
                    ));
                }

            }

            //To hold followings info
            $export_row['_tags'] = array();
            $export_row['s__keywords'] = '';

            //Now build object-specific index:
            if ($loop_obj == 12273) {

                //HASHTAGS
                //See if this hashtag has a time-range:
                $export_row['s__type'] = $loop_obj;
                $export_row['s__id'] = intval($s['hashtagid']);
                $export_row['s__handle'] = $s['hashtagterm'];
                $export_row['s__url'] = view_memory(42903, 33286) . $s['hashtagterm']; //Default to hashtag, forward to discovery is lacking superpowers
                $export_row['s__cover'] = '';
                $export_row['s__title'] = $s['hashtagtext'];
                $export_row['s__weight'] = intval($s['hashtagweight']);

                if (hashtag_is_startable($s)) {
                    array_push($export_row['_tags'], 'public_index');
                }

            } elseif ($loop_obj == 12274) {

                //HANDLES
                $export_row['s__type'] = $loop_obj;
                $export_row['s__id'] = intval($s['handleid']);
                $export_row['s__handle'] = $s['handleterm'];
                $export_row['s__url'] = view_memory(42903, 42902) . $s['handleterm'];
                $export_row['s__cover'] = $s['handlecover'];
                $export_row['s__title'] = $s['handlename'];
                $export_row['s__weight'] = intval($s['handleweight']);

                //Is this an image?
                if (strlen($s['handlecover'])) {
                    array_push($export_row['_tags'], 'has_image');
                }

                array_push($export_row['_tags'], 'public_index');

                //Fetch Following:
                foreach ($CI->Chains->read(array(
                    'chainhandletype IN (' . join(',', $CI->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                    'chainhandleoutput' => $s['handleid'], //This follower Handle
                ), array('chainhandleinput'), 0, 0, array('handlename' => 'DESC')) as $x) {

                    //Add tags:
                    array_push($export_row['_tags'], 'z_' . $x['handleid']);

                    //Add Keywords:
                    $export_row['s__keywords'] .= $x['handlename'] . (strlen($x['chainvalue']) ? ' ' . $x['chainvalue'] : '') . ' ';

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
            if (isset($algolia_results['objectIDs']) && count($algolia_results['objectIDs']) == 1) {
                foreach ($algolia_results['objectIDs'] as $key => $algolia_id) {
                    if ($focus__node == 12273) {
                        $CI->Hashtags->update($all_db_rows[$key][$focus_field_id], array(
                            'hashtagexternal' => $algolia_id,
                        ));
                    } elseif ($focus__node == 12274) {
                        $CI->Handles->update($all_db_rows[$key][$focus_field_id], array(
                            'handleexternal' => $algolia_id,
                        ));
                    }
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

                if (isset($all_db_rows[$key]['hashtagid'])) {
                    $CI->Hashtags->update($all_db_rows[$key][(isset($all_db_rows[$key]['hashtagid']) ? 'hashtagid' : 'handleid')], array(
                        'hashtagexternal' => intval($algolia_id),
                    ));
                } else {
                    $CI->Handles->update($all_db_rows[$key][(isset($all_db_rows[$key]['hashtagid']) ? 'hashtagid' : 'handleid')], array(
                        'handleexternal' => intval($algolia_id),
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


function hashtag_creation_time($hashtagid)
{
    $CI =& get_instance();
    foreach ($CI->Chains->read(array(
        'chainid' => $hashtagid,
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
        $x['chainhandledomain'] .
        $x['chainhandlecreator'] .
        $x['chainhandletype'] .
        (isset($x['chainhandleinput']) ? $x['chainhandleinput'] : 0) .
        (isset($x['chainhandleoutput']) ? $x['chainhandleoutput'] : 0) .
        (isset($x['chainhashtaginput']) ? $x['chainhashtaginput'] : 0) .
        (isset($x['chainhashtagoutput']) ? $x['chainhashtagoutput'] : 0) .
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
    foreach ($CI->config->item('handles___4341') as $handleid => $m) {

        $column_value = null;

        if (in_array($handleid, array(4593, 14870, 4364, 4366, 4429))) {

            //HANDLE
            $column_value .= '<td style="width:25px !important;"><div style="width:25px !important; overflow:hidden;">';
            if (isset($x[$m['m__handle']]) && intval($x[$m['m__handle']]) > 0) {
                foreach ($CI->Handles->read(array('handleid' => $x[$m['m__handle']])) as $focus_e) {
                    $column_value .= '<a href="' . view_memory(42903, 42902) . $focus_e['handleterm'] . '" target="_blank" data-toggle="tooltip" title="' . $focus_e['handlename'] . '" class="icon-block-sm">' . view_cover($focus_e['handlecover'], '<i class="far fa-at"></i>') . '</a>';
                }
            }
            $column_value .= '</div></td>';

        } elseif (in_array($handleid, array(4368, 4369))) {

            //HASHTAG
            $column_value .= '<td style="width:89px !important;"><div style="width:85px !important; overflow:hidden;">';
            if (isset($x[$m['m__handle']]) && intval($x[$m['m__handle']]) > 0) {
                foreach ($CI->Hashtags->read(array('hashtagid' => $x[$m['m__handle']])) as $focus_i) {
                    $column_value .= '<a href="' . view_memory(42903, 33286) . $focus_i['hashtagterm'] . '" data-toggle="popover">#' . $focus_i['hashtagterm'] . '</a>';
                }
            }
            $column_value .= '</div></td>';

        } elseif ($handleid == 4367) {

            //Chain ID

            //Determine chain group:
            $handleterm_sign = '';
            if (in_array($x['chainhandletype'], array(12273, 12274))) {
                $handles___4593 = $CI->config->item('handles___4593'); //Chain Type
                $handleterm_sign = '<span class="group_sign" title="' . $handles___4593[$x['chainhandletype']]['m__title'] . '">' . $handles___4593[$x['chainhandletype']]['m__cover'] . '</span>';
            } else {
                foreach ($CI->config->item('handles___31770') as $groupid => $groupm) {
                    if (in_array($x['chainhandletype'], $CI->config->item('handleids___' . $groupid))) {
                        $handleterm_sign = '<span class="group_sign" title="' . $groupm['m__title'] . '">' . $groupm['m__cover'] . '</span>';
                        break;
                    }
                }
            }

            $column_value .= '<td style="width:72px !important;"><div style="width:72px !important; overflow:hidden;">';
            $column_value .= ($x[$m['m__handle']] > 0 ? '<a href="' . view_app_chain(4341) . '?chainid=' . $x[$m['m__handle']] . '" target="_blank">' . $handleterm_sign . $x[$m['m__handle']] . '</a>' : '&nbsp;');
            $column_value .= '</div></td>';

        } elseif ($handleid == 44395) {

            //Void:
            $column_value .= '<td style="width:72px !important;"><div style="width:72px !important; overflow:hidden;">';
            $column_value .= ($x[$m['m__handle']] > 0 ? '<a href="' . view_app_chain(4341) . '?chainid=' . $x[$m['m__handle']] . '" target="_blank"><span class="group_sign">' . $m['m__cover'] . '</span>' . $x[$m['m__handle']] . '</a>' : '&nbsp;');
            $column_value .= '</div></td>';

        } elseif ($handleid == 4362) {

            //TIME
            $column_value .= '<td style="width:25px !important;">';
            $column_value .= '<div style="width:25px !important; overflow:hidden; text-align: center;">';
            $column_value .= '<span data-toggle="tooltip" data-placement="top" title="' . $x['chaintime'] . ' PST">' . view_time_difference($x['chaintime'], true) . '</span>';
            $column_value .= '</div>';
            $column_value .= '</td>';

        } elseif (in_array($handleid, array(1579301, 1579321))) {

            //HASH
            $column_value .= '<td style="width:50px !important;">';
            $column_value .= '<div style="width:50px !important; overflow:hidden;">';
            $column_value .= '<span data-toggle="tooltip" data-placement="top" title="0x' . $x[$m['m__handle']] . '">0x' . substr($x[$m['m__handle']], -4) . '</span>';
            $column_value .= '</div>';
            $column_value .= '</td>';

        } elseif ($handleid == 4370) {

            //Number
            $column_value .= '<td>';
            $column_value .= ($x['chainkey'] > 0 ? $x['chainkey'] : '&nbsp;');
            $column_value .= '</td>';

        } elseif ($handleid == 4372) {

            //Text
            $column_value .= '<td>';
            $column_value .= (strip_tags($x['chainvalue']) == $x['chainvalue'] || strlen(strip_tags($x['chainvalue'])) < view_memory(6404, 6197) ? $x['chainvalue'] : '<span class="hidden html_message_' . $x['chainid'] . '">' . $x['chainvalue'] . '</span><a class="html_message_' . $x['chainid'] . '" href="javascript:void(0);" onclick="$(\'.html_message_' . $x['chainid'] . '\').toggleClass(\'hidden\');">View HTML Message</a>');
            $column_value .= '</td>';

        }

        if (in_array($handleid, $CI->config->item('handleids___1579727'))) {
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
    $memory_tree = @$CI->config->item('handles___' . $following);
    if (is_array($memory_tree) && count($memory_tree) && isset($memory_tree[$follower][$filed])) {
        return $memory_tree[$follower][$filed];
    } else {
        return null;
    }
}


function view_cache($following, $handleid, $micro_status = true, $data_placement = 'top', $hashtagid = 0)
{

    /*
     *
     * UI for Platform Cache Handles
     *
     * */

    $CI =& get_instance();
    $config_array = $CI->config->item('handles___' . $following);
    if (!isset($config_array[$handleid])) {
        return false;
    }
    $cache = $config_array[$handleid];
    if (!$cache) {
        //Could not find matching item
        return false;
    }


    //We have two skins for displaying Status:
    if (is_null($data_placement)) {
        if ($micro_status) {
            return $cache['m__cover'];
        } else {
            return $cache['m__cover'] . ' ' . $cache['m__title'];
        }
    } else {
        //data-toggle="tooltip" data-placement="' . $data_placement . '"
        return '<span class="' . ($micro_status ? 'cache_micro_' . $following . '_' . $hashtagid : '') . '" ' . ($micro_status && !is_null($data_placement) ? ' title="' . ($micro_status ? $cache['m__title'] : '') . (strlen($cache['m__message']) > 0 ? ($micro_status ? ': ' : '') . $cache['m__message'] : '') . '"' : 'style="cursor:pointer;"') . '>' . $cache['m__cover'] . ' ' . ($micro_status ? '' : $cache['m__title']) . '</span>';
    }
}


function view_card($href, $is_current, $chainhandletype, $o__type, $o__title, $chainvalue = null)
{
    $CI =& get_instance();
    $handles___4593 = $CI->config->item('handles___4593');
    return '<a href="' . ($is_current ? 'javascript:alert(\'You are here already!\');' : $href) . '" class="dropdown-item ' . ($is_current ? ' active ' : '') . '">' .
        (in_array($chainhandletype, $CI->config->item('handleids___32172')) ? '<span class="icon-block-xs">' . $handles___4593[$chainhandletype]['m__cover'] . '</span>' : '') .
        (strlen($o__type) ? '<span class="icon-block-xs">' . $o__type . '</span>' : '&nbsp;') . //Type or Cover
        $o__title .
        (strlen($chainvalue) && handle_session(12701) ? '<div class="message2">' . strip_tags($chainvalue) . '</div>' : '') .
        '</a>';
}

function view_more($href, $is_current, $chainhandletype, $o__type, $o__title, $chainvalue = null)
{
    return '<a href="' . ($is_current ? 'javascript:alert(\'You are here already!\');' : $href) . '" class="dropdown-item ' . ($is_current ? ' active ' : '') . '">' .
        ($chainhandletype ? '<span class="icon-block-xs">' . $chainhandletype . '</span>' : '') .
        (strlen($o__type) ? '<span class="icon-block-xs">' . $o__type . '</span>' : '&nbsp;') . //Type or Cover
        $o__title .
        (strlen($chainvalue) && handle_session(12701) ? '<div class="message2">' . strip_tags($chainvalue) . '</div>' : '') .
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
    $handle_session = handle_session();

    if ($log_error) {

        $CI =& get_instance();
        log_message('error', 'MENCH ERROR: ' . $error_message
            . ($handle_session ? ' | PLAYER: ' . print_r($handle_session, true) : '')
            . ($handle_session ? ' | ERROR DATA: ' . print_r($error_data, true) : '')
        );

        $CI->Chains->create(array_merge($error_data, array(
            'chainhandleinput' => 4246, //Platform Bug Reports
            'chainhandletype' => 44179, //Triggered
            'chainvalue' => $error_message,
            'chainhandlecreator' => (isset($error_data['chainhandlecreator']) && $error_data['chainhandlecreator'] > 0 ? $error_data['chainhandlecreator'] : ($handle_session ? $handle_session['handleid'] : 0)),
        )));

    }

    return array(
        'status' => 0,
        'message' => $error_message,
        'handle_session' => $handle_session,
        'error_data' => $error_data,
    );

}


function handles_query($chainhandletype, $handleid, $current_page = 0, $append_card_icon = true, $chainhandlesub = 0)
{

    /*
     *
     * Loads Handle
     *
     * */

    $CI =& get_instance();
    $first_segment = $CI->uri->segment(1);

    if ($chainhandletype == 12273) {

        //Hashtags Created
        $order_columns['chainid'] = 'DESC';
        $joins_objects = array('chainhashtagoutput');
        $query_filters = array(
            'chainhandlecreator' => $handleid,
            'chainhandletype' => $chainhandletype,
        );

    } elseif ($chainhandletype == 12274) {

        //Handle Created
        $order_columns['chainid'] = 'DESC';
        $joins_objects = array('chainhandleoutput');
        $query_filters = array(
            'chainhandlecreator' => $handleid,
            'chainhandletype' => $chainhandletype,
        );

    } elseif (!in_array($chainhandletype, $CI->config->item('handleids___4527')) || !is_array($CI->config->item('handleids___' . $chainhandletype)) || !count($CI->config->item('handleids___' . $chainhandletype))) {

        log_error('handles_query() @' . $chainhandletype . ' Empty Array in Cache @4527');
        return false;

    } elseif ($chainhandletype == 32292) {

        //Relationships
        $order_columns['chainid'] = 'DESC';
        $joins_objects = array('chainhandleoutput');
        $query_filters = array(
            'chainhandlecreator' => $handleid,
            'chainhandleoutput !=' => $handleid,
            'chainhandleinput !=' => $handleid,
            'chainhandletype IN (' . join(',', $CI->config->item('handleids___' . $chainhandletype)) . ')' => null, //HANDLE CHAINS
        );

    } elseif ($chainhandletype == 42373) {

        $order_columns = handle_sort();
        $joins_objects = array('chainhandleoutput');

        if (in_array($chainhandlesub, $CI->config->item('handleids___32292'))) {

            //Down/Followers Sub
            $query_filters = array(
                'chainhandleinput' => $handleid,
                'chainhandletype' => $chainhandlesub,
            );

        } else {

            //Down/Followers Handle Chain Groups:
            $query_filters = array(
                'chainhandleinput' => $handleid,
                'chainhandletype IN (' . join(',', $CI->config->item('handleids___' . $chainhandletype)) . ')' => null, //HANDLE CHAINS
            );

        }

    } elseif ($chainhandletype == 42279) {

        $order_columns = handle_sort();
        $joins_objects = array('chainhandleinput');

        if (in_array($chainhandlesub, $CI->config->item('handleids___32292'))) {

            //Up/Following Sub
            $query_filters = array(
                'chainhandleoutput' => $handleid,
                'chainhandletype' => $chainhandlesub,
            );

        } else {

            //Up/Following Handle Chain Groups:
            $query_filters = array(
                'chainhandleoutput' => $handleid,
                'chainhandletype IN (' . join(',', $CI->config->item('handleids___' . $chainhandletype)) . ')' => null, //HANDLE CHAINS
            );

        }

    } elseif ($chainhandletype == 13550) {

        $joins_objects = array('chainhashtagoutput');
        $order_columns = hashtag_sort();

        if (in_array($chainhandlesub, $CI->config->item('handleids___13550'))) {
            //Mentions Sub
            $query_filters = array(
                'chainhandletype' => $chainhandlesub,
                '(chainhandlecreator=' . $handleid . ' OR chainhandleinput=' . $handleid . ' OR chainhandleoutput=' . $handleid . ')' => null,
            );
        } else {
            //Mentions
            $query_filters = array(
                'chainhandletype IN (' . join(',', $CI->config->item('handleids___' . $chainhandletype)) . ')' => null,
                '(chainhandlecreator=' . $handleid . ' OR chainhandleinput=' . $handleid . ' OR chainhandleoutput=' . $handleid . ')' => null,
                //'(chainhandlecreator='.$handleid.' OR chainhandleinput='.$handleid.')' => null,
            );
        }

    } elseif ($chainhandletype == 4486) {

        $order_columns = array();
        $order_columns['chainid'] = 'DESC';
        $joins_objects = array('chainhashtagoutput');


        if (in_array($chainhandlesub, $CI->config->item('handleids___4486'))) {

            //Hashtags Sub
            $query_filters = array(
                'chainhandlecreator' => $handleid,
                'chainhandletype' => $chainhandlesub,
            );

        } else {

            //Hashtags
            $query_filters = array(
                'chainhandlecreator' => $handleid,
                'chainhandletype IN (' . join(',', $CI->config->item('handleids___' . $chainhandletype)) . ')' => null, //DISCOVERY GROUP
            );

        }

    } elseif ($chainhandletype == 31777) {

        $order_columns = array();
        $order_columns['chainid'] = 'DESC';
        $joins_objects = array('chainhashtaginput');

        if (in_array($chainhandlesub, $CI->config->item('handleids___31777'))) {

            //Discoveries SUB
            $query_filters = array(
                '(chainhandlecreator=' . $handleid . ' OR chainhandleinput=' . $handleid . ' OR chainhandleoutput=' . $handleid . ')' => null,
                'chainhandletype' => $chainhandlesub,
            );

        } else {

            //Discoveries
            $query_filters = array(
                '(chainhandlecreator=' . $handleid . ' OR chainhandleinput=' . $handleid . ' OR chainhandleoutput=' . $handleid . ')' => null,
                'chainhandletype IN (' . join(',', $CI->config->item('handleids___' . $chainhandletype)) . ')' => null, //DISCOVERY GROUP
            );

        }

    } else {

        return null;

    }


    //Return Results:
    if ($current_page > 0) {

        $limit = view_memory(6404, 11064);
        $query = $CI->Chains->read($query_filters, $joins_objects, $limit, ($current_page - 1) * $limit, $order_columns);
        return $query;

    } else {

        $handles___11035 = $CI->config->item('handles___11035');
        if (!isset($handles___11035[$chainhandletype]['m__title'])) {
            log_error('@' . $chainhandletype . ' Missing from Nav @11035', array(
                'chainhandleoutput' => $chainhandletype,
            ));
            $handles___11035[$chainhandletype] = array(
                'm__title' => '',
                'm__cover' => '',
            );
        }
        $query = $CI->Chains->read($query_filters, $joins_objects, 1, 0, array(), 'COUNT(chainid) as totals');
        $count_query = $query[0]['totals'];
        $visual_counter = '<span class="mini-hidden adjust-left">' . view_number($count_query) . '<span>';
        $title_desc = number_format($count_query, 0) . ' ' . $handles___11035[$chainhandletype]['m__title'];

        if ($append_card_icon) {

            if (!$count_query) {
                return null;
            }

            $card_icon = '<span class="icon-block-xs">' . $handles___11035[$chainhandletype]['m__cover'] . '</span>';

            $ui = '<div class="dropdown inline-block">';
            $ui .= '<button type="button" class="btn no-left-padding no-right-padding loadhandle_cards button_of_' . $handleid . '_' . $chainhandletype . '" id="cardhandle_group_' . $chainhandletype . '_' . $handleid . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" load_chainhandletype="' . $chainhandletype . '" load_handleid="' . $handleid . '" load_counter="' . $count_query . '" load_first_segment="' . $first_segment . '"><span title="' . $title_desc . '" data-toggle="tooltip" data-placement="top">' . $card_icon . $visual_counter . '</span></button>';
            $ui .= '<div class="dropdown-menu dropdown_' . $chainhandletype . ' coinshandle_' . $handleid . '_' . $chainhandletype . '" aria-labelledby="cardhandle_group_' . $chainhandletype . '_' . $handleid . '">';
            //Menu To be loaded dynamically via AJAX
            $ui .= '</div>';
            $ui .= '</div>';

            return $ui;

        } else {
            return intval($count_query);
        }
    }

}


function hashtags_query($chainhandletype, $hashtagid, $current_page = 0, $append_card_icon = true, $headline_authors = array())
{

    /*
     *
     * Loads Hashtag
     *
     * */

    $CI =& get_instance();
    $first_segment = $CI->uri->segment(1);

    if ($chainhandletype == 13550) {

        //HANDLES
        $joins_objects = array('chainhandleinput');
        $query_filters = array(
            'chainhandletype IN (' . join(',', $CI->config->item('handleids___' . $chainhandletype)) . ')' => null,
            'chainhashtagoutput' => $hashtagid,
        );
        $order_columns = hashtag_sort();

    } elseif ($chainhandletype == 11019) {

        //HASHTAG Chain Groups Previous
        $order_columns = array('chainid' => 'DESC');
        $joins_objects = array('chainhashtaginput');
        $query_filters = array(
            'chainhandletype IN (' . join(',', $CI->config->item('handleids___' . $chainhandletype)) . ')' => null, //HASHTAG CHAINS
            'chainhashtagoutput' => $hashtagid,
        );

    } elseif ($chainhandletype == 12840) {

        //HASHTAG Chain Groups Next
        $order_columns = array('chainkey' => 'ASC');
        $joins_objects = array('chainhashtagoutput');
        $query_filters = array(
            'chainhandletype IN (' . join(',', $CI->config->item('handleids___' . $chainhandletype)) . ')' => null,
            'chainhashtaginput' => $hashtagid,
        );

    } elseif (in_array($chainhandletype, $CI->config->item('handleids___12144'))) {

        //DISCOVERIES
        $order_columns = array('chainid' => 'DESC');
        $joins_objects = array('chainhandlecreator');
        $query_filters = array(
            'chainhandletype IN (' . join(',', $CI->config->item('handleids___' . $chainhandletype)) . ')' => null, //DISCOVERIES
            'chainhashtaginput' => $hashtagid,
        );

    } else {

        return null;

    }


    //Return Results:
    if ($current_page > 0) {

        $limit = view_memory(6404, 11064);
        return $CI->Chains->read($query_filters, $joins_objects, $limit, ($current_page - 1) * $limit, $order_columns);

    } else {

        $handles___11035 = $CI->config->item('handles___11035'); //COINS
        $query = $CI->Chains->read($query_filters, $joins_objects, 1, 0, array(), 'COUNT(chainid) as totals');
        $count_query = $query[0]['totals'];
        $visual_counter = '<span class="mini-hidden adjust-left">' . view_number($count_query) . '<span>';
        $title_desc = number_format($count_query, 0) . (isset($handles___11035[$chainhandletype]['m__title']) ? ' ' . $handles___11035[$chainhandletype]['m__title'] : '');

        if ($append_card_icon) {

            if (!$count_query) {
                return null;
            }

            $card_icon = '<span class="icon-block-sm">' . $handles___11035[$chainhandletype]['m__cover'] . '</span>';

            $ui = '<div class="dropdown inline-block">';
            $ui .= '<button type="button" class="btn no-left-padding no-right-padding load_hashtag_cards button_of_' . $hashtagid . '_' . $chainhandletype . '" id="card_group_hashtag_' . $chainhandletype . '_' . $hashtagid . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" load_chainhandletype="' . $chainhandletype . '" load_hashtagid="' . $hashtagid . '" load_counter="' . $count_query . '" load_first_segment="' . $first_segment . '"><span title="' . $title_desc . '" data-toggle="tooltip" data-placement="top">' . $card_icon . $visual_counter . '</span></button>';

            //Menu To be loaded dynamically via AJAX:
            $ui .= '<div class="dropdown-menu dropdown_' . $chainhandletype . ' coins_hashtag_' . $hashtagid . '_' . $chainhandletype . '" aria-labelledby="card_group_hashtag_' . $chainhandletype . '_' . $hashtagid . '"></div>';

            $ui .= '</div>';

            return $ui;

        } else {
            return intval($count_query);
        }

    }

}

function view_dynamic_headline($dynamic_handleid, $m, $selected_e = null)
{

    $CI =& get_instance();
    $handles___11035 = $CI->config->item('handles___11035'); //Encyclopedia

    $headline = '<span class="icon-block-sm">' . $m['m__cover'] . '</span>' . $m['m__title'] . ': ';

    if (in_array($dynamic_handleid, $CI->config->item('handleids___28239'))) {
        $headline .= '<span class="icon-block-sm" title="' . $handles___11035[28239]['m__message'] . '" data-toggle="tooltip" data-placement="top" style="font-size:0.34em;">' . $handles___11035[28239]['m__cover'] . '</span>';
    }
    if (in_array($dynamic_handleid, $CI->config->item('handleids___32145'))) {
        $headline .= '<span class="icon-block-sm" title="' . $handles___11035[32145]['m__title'] . '" data-toggle="tooltip" data-placement="top">' . $handles___11035[32145]['m__cover'] . '</span>';
    }

    if (isset($handles___11035[$dynamic_handleid]) && strlen($handles___11035[$dynamic_handleid]['m__message'])) {
        $headline .= '<span class="doregular info_blob ' . (strlen($handles___11035[$dynamic_handleid]['m__message']) < 55 ? ' short_blob ' : '') . '"><span>' . $handles___11035[$dynamic_handleid]['m__message'] . '</span></span>';
    }

    return $headline;
}


function view_instant_select($focus__id, $down_handleid = 0, $right_hashtagid = 0)
{

    /*
     * Either single or multi select UI elements...
     * */

    $CI =& get_instance();
    $handles___42179 = $CI->config->item('handles___42179'); //Dynamic Input Fields
    $handles___11035 = $CI->config->item('handles___11035'); //Encyclopedia
    $handles___4527 = $CI->config->item('handles___4527'); //Memory
    $is_compact = in_array($focus__id, $CI->config->item('handleids___42191'));
    $single_select = in_array($focus__id, $CI->config->item('handleids___33331'));
    $multi_select = in_array($focus__id, $CI->config->item('handleids___33332'));
    $access_locked = in_array($focus__id, $CI->config->item('handleids___32145'));
    $focus_select = $CI->config->item($single_select ? 'handles___33331' : 'handles___33332');

    if (!$single_select && !$multi_select) {
        //Must be either:
        log_error('view_instant_select() @' . $focus__id . ' not in single select @33331 or multi select 33332', array(
            'chainhandleoutput' => $focus__id,
            'chainhashtagoutput' => $right_hashtagid,
        ));
        return false;
    }

    $already_selected = array();
    $selection_ids = array();
    $selection_options = $CI->Chains->read(array(
        'chainhandleinput' => $focus__id,
        'chainhandletype IN (' . join(',', $CI->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
    ), array('chainhandleoutput'), 0, 0, array('chainkey' => 'ASC'));
    foreach ($selection_options as $list_item) {
        array_push($selection_ids, $list_item['handleid']);
    }

    //UI for Single select or multi?
    $ui = '<div class="dynamic_selection">';
    if (!$is_compact) {
        $ui .= '<h3 class="mini-font grey">' . view_dynamic_headline($focus__id, $focus_select[$focus__id]) . '</h3>';
    }
    $ui .= '<div class="list-group list-radio-select grey-line radio-' . $focus__id . ($is_compact ? ' is_compact ' : '') . '">';

    if ($down_handleid > 0) {

        //Handle Focus:
        if (count($selection_ids)) {
            foreach ($CI->Chains->read(array(
                'chainhandleinput IN (' . join(',', $selection_ids) . ')' => null, //All possible answers
                'chainhandleoutput' => $down_handleid,
                'chainhandletype IN (' . join(',', $CI->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
            )) as $sel) {
                array_push($already_selected, $sel['chainhandleinput']);
            }
        }

        if (!count($already_selected) && $single_select && handle_session()) {
            //FIND DEFAULT if set in session of this user:
            $var_id = @$CI->session->userdata('session_custom_ui_' . $focus__id);
            foreach ($selection_ids as $handleid2) {
                if ($var_id == $handleid2) {
                    $already_selected = array($handleid2);
                    break;
                }
            }
        }

    } elseif ($right_hashtagid > 0) {

        //Hashtag focus:
        foreach ($CI->Chains->read(array(
            'chainhandleinput IN (' . join(',', $selection_ids) . ')' => null, //All possible answers
            'chainhashtagoutput' => $right_hashtagid,
            'chainhandletype IN (' . join(',', $CI->config->item('handleids___33602')) . ')' => null, //Hashtag/Handle Chains Active
        )) as $sel) {
            array_push($already_selected, $sel['chainhandleinput']);
        }

    }

    $unselected_count = 0;
    $overflow_unselected_limit = 5;
    $has_selected = count($already_selected);
    $has_multiple = count($selection_options) > 1;
    $overflow_reached = false;
    $exclude_fonts = (in_array($focus__id, $CI->config->item('handleids___42417')) ? 'exclude_fonts' : '');
    $handles___42179 = $CI->config->item('handles___42179'); //Dynamic Input Fields

    foreach ($selection_options as $list_item) {

        //Has superpower?
        if (isset($handles___42179[$list_item['handleid']]['m__following']) && count($handles___42179[$list_item['handleid']]['m__following'])) {
            $superpowers_required = array_intersect($CI->config->item('handleids___10957'), $handles___42179[$list_item['handleid']]['m__following']);
            if (count($superpowers_required) && !handle_session(end($superpowers_required))) {
                continue;
            }
        }

        $selected = in_array($list_item['handleid'], $already_selected);
        if (!$overflow_reached && $unselected_count >= $overflow_unselected_limit && !$selected && !$is_compact) {
            $overflow_reached = true;
        }

        $headline = '<span class="inner_headline">' . (strlen($list_item['handlecover']) ? '<span class="icon-block-sm change-results">' . view_cover($list_item['handlecover']) . '</span>' : '') . $list_item['handlename'] . '</span>';
        if (in_array($list_item['handleid'], $CI->config->item('handleids___32145'))) {
            $headline .= '<span class="icon-block-sm" title="' . $handles___11035[32145]['m__title'] . '" data-toggle="tooltip" data-placement="top">' . $handles___11035[32145]['m__cover'] . '</span>';
        }
        if ($selected) {
            $headline .= '<span class="icon-block-sm checked_icon"><i class="far fa-check"></i></span>';
        }
        if (in_array($list_item['handleid'], $CI->config->item('handleids___11035')) && strlen($handles___11035[$list_item['handleid']]['m__message']) > 0) {
            $headline .= '<span class="doregular info_blob ' . (strlen($handles___11035[$list_item['handleid']]['m__message']) < 55 ? ' short_blob ' : '') . '"><span>' . $handles___11035[$list_item['handleid']]['m__message'] . '</span></span>';
        }


        if ($selected) {
            if ($access_locked) {
                $ui .= '<span class="list-group-item custom_ui_' . $focus__id . '_' . $list_item['handleid'] . ' ' . $exclude_fonts . ' itemsetting_' . $focus__id . ' selection_preview selection_preview_' . $focus__id . ' itemsetting active" title="' . stripslashes($list_item['handlename']) . '">' . $headline . '</span>';
            } elseif ($has_multiple) {
                $ui .= '<a href="javascript:void(0);" onclick="$(\'.selection_item_' . $focus__id . '\').removeClass(\'hidden\');$(\'.selection_preview_' . $focus__id . '\').addClass(\'hidden\');" class="list-group-item custom_ui_' . $focus__id . '_' . $list_item['handleid'] . ' ' . $exclude_fonts . ' itemsetting_' . $focus__id . ' selection_preview selection_preview_' . $focus__id . ' itemsetting active" title="' . stripslashes($list_item['handlename']) . '">' . $headline . '<span class="icon-block-sm"><i class="far fa-pen-to-square"></i></span></a>';
            }
        }

        if (!$access_locked) {
            $ui .= '<a href="javascript:void(0);" onclick="handle_select_apply(' . $focus__id . ',' . $list_item['handleid'] . ',' . ($multi_select ? 1 : 0) . ',' . $down_handleid . ',' . $right_hashtagid . ')" class="list-group-item itemsetting custom_ui_' . $focus__id . '_' . $list_item['handleid'] . ' ' . $exclude_fonts . ' item-' . $list_item['handleid'] . ' itemsetting_' . $focus__id . ' selection_item_' . $focus__id . (($has_selected && $has_multiple) || $overflow_reached ? ' hidden' : '') . ($selected ? ' active ' : '') . '" title="' . stripslashes($list_item['handlename']) . '">' . $headline . '</a>';
        }


        if (!$selected) {
            $unselected_count++;
        }
    }

    if ($overflow_reached && !$has_selected && !$access_locked) {
        //We show this only if non are selected and has too many options:
        $ui .= '<a href="javascript:void(0);" onclick="$(\'.selection_item_' . $focus__id . '\').removeClass(\'hidden\');$(\'.selection_preview_' . $focus__id . '\').addClass(\'hidden\');" class="list-group-item itemsetting selection_preview selection_preview_' . $focus__id . '"><span class="icon-block"><i class="far fa-search-plus"></i></span>Show More...</a>';
    }

    $ui .= '</div>';
    $ui .= '</div>';
    return $ui;
}


function searchingle_select_form($cache_handleid, $selected_handleid, $show_dropdown_arrow = false, $show_title = false)
{

    $CI =& get_instance();
    $handles___this = $CI->config->item('handles___' . $cache_handleid);
    $handles___4527 = $CI->config->item('handles___4527'); //Memory
    $handles___11035 = $CI->config->item('handles___11035'); //Encyclopedia

    if (!$selected_handleid || !isset($handles___this[$selected_handleid])) {
        return false;
    }

    //Make sure it's not locked:
    $ui = '<div class="dropdown inline-block dropd_form_' . $cache_handleid . '" selected_value="' . $selected_handleid . '">';

    $ui .= '<button type="button" class="btn no-left-padding dropdown-toggle" id="dropdown_form_' . $cache_handleid . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';

    $ui .= '<span class="current_content"><span class="icon-block-sm">' . $handles___this[$selected_handleid]['m__cover'] . '</span>' . ($show_title ? $handles___this[$selected_handleid]['m__title'] : '') . '</span>' . ($show_dropdown_arrow ? '<span class="icon-block-sm"><i class="far fa-angle-down"></i></span>' : '');

    $ui .= '</button>';

    $ui .= '<div class="dropdown-menu dropmenu_form_' . $cache_handleid . '" aria-labelledby="dropdown_form_' . $cache_handleid . '">';

    if (!$show_title) {
        $ui .= '<div class="dropdown-item main__title intro_header"><span class="icon-block-sm">' . $handles___4527[$cache_handleid]['m__cover'] . '</span>' . $handles___4527[$cache_handleid]['m__title'] . ':' . (isset($handles___11035[$cache_handleid]) && strlen($handles___11035[$cache_handleid]['m__message']) ? '<span class="doregular info_blob ' . (strlen($handles___11035[$cache_handleid]['m__message']) < 55 ? ' short_blob ' : '') . '"><span>' . $handles___11035[$cache_handleid]['m__message'] . '</span></span>' : '') . '</div>';
    }

    foreach ($handles___this as $handleid => $m) {

        if (in_array($handleid, $CI->config->item('handleids___32145'))) {
            continue; //Locked Dropdown
        }
        $superpowers_required = array_intersect($CI->config->item('handleids___10957'), $m['m__following']);
        if (count($superpowers_required) && !handle_session(end($superpowers_required))) {
            continue;
        }

        $ui .= '<a class="dropdown-item main__title optiond_' . $handleid . ' ' . ($handleid == $selected_handleid ? ' active ' : '') . '" href="javascript:void();" this_id="' . $handleid . '" onclick="update_form_select(' . $cache_handleid . ', ' . $handleid . ', 0, ' . intval($show_title) . ')"><span class="content_' . $handleid . '"><span class="icon-block-sm">' . $m['m__cover'] . '</span>' . $m['m__title'] . '</span>' . (isset($handles___11035[$handleid]) && strlen($handles___11035[$handleid]['m__message']) ? '<span class="doregular info_blob ' . (strlen($handles___11035[$handleid]['m__message']) < 55 ? ' short_blob ' : '') . '"><span>' . $handles___11035[$handleid]['m__message'] . '</span></span>' : '') . '</a>';

    }

    $ui .= '</div>';
    $ui .= '</div>';

    return $ui;
}


function searchingle_select_instant($cache_handleid, $selected_handleid, $hashtag_access = 0, $show_title = true, $o__id = 0, $chainid = 0)
{

    $CI =& get_instance();
    $handles___this = $CI->config->item('handles___' . $cache_handleid);
    $handle_session = handle_session();
    $handles___11035 = $CI->config->item('handles___11035'); //Encyclopedia
    $unselected_radio = in_array($cache_handleid, $CI->config->item('handleids___33331')) && !$selected_handleid;
    $handles___4527 = $CI->config->item('handles___4527'); //Memory

    if ($selected_handleid && !isset($handles___this[$selected_handleid])) {

        return false;

        /*
    } elseif(!$selected_handleid && $hashtag_access && $handle_session){

        //See if this user has any of these options:
        foreach($CI->Chains->read(array(
            'chainhandleinput IN (' . join(',', $CI->config->item('handleids___'.$cache_handleid)) . ')' => null, //HANDLE CHAINS
            'chainhandleoutput' => $handle_session['handleid'],
            'chainhandletype IN (' . join(',', $CI->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
        )) as $x) {
            //Supports one for now
            $selected_handleid = $x['chainhandleinput'];
            break;
        }
    */
    }

    //Make sure it's not locked:
    $hashtag_access = (!in_array($cache_handleid, $CI->config->item('handleids___32145')) && !in_array($selected_handleid, $CI->config->item('handleids___32145')) ? $hashtag_access : 0);

    $ui = '<div class="dropdown ' . ($show_title ? 'dropdown_type_' . $cache_handleid : '') . ' inline-block dropd_instant_' . $cache_handleid . '_' . $o__id . '_' . $chainid . '" selected_value="' . $selected_handleid . '">';

    $ui .= '<button type="button" ' . ($hashtag_access >= 3 ? 'class="btn no-left-padding ' . ($show_title ? 'dropdown-toggle' : 'no-right-padding dropdown-lock') . '" id="dropdown_instant_' . $cache_handleid . '_' . $o__id . '_' . $chainid . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : 'class="btn adj-btn ' . (!$show_title ? 'no-padding' : '') . ' edit-locked" ') . '>';

    $ui .= '<span class="current_content">' . (isset($handles___this[$selected_handleid]['m__cover']) ? '<span class="icon-block-sm">' . $handles___this[$selected_handleid]['m__cover'] . '</span>' . ($show_title ? $handles___this[$selected_handleid]['m__title'] : '') : '<span class="icon-block-sm">' . $handles___11035[$cache_handleid]['m__cover'] . '</span>' . ($show_title ? $handles___11035[$cache_handleid]['m__title'] : '')) . '</span>'; //.( $show_title ? '<span class="icon-block-sm"><i class="far fa-angle-down"></i></span>' : '' )

    $ui .= '</button>';

    if ($hashtag_access >= 3) {

        $ui .= '<div class="dropdown-menu dropmenu_instant_' . $cache_handleid . '" o__id="' . $o__id . '" chainid="' . $chainid . '" aria-labelledby="dropdown_instant_' . $cache_handleid . '_' . $o__id . '_' . $chainid . '">';

        if (!$show_title) {
            $ui .= '<div class="dropdown-item main__title intro_header"><span class="icon-block-sm">' . $handles___4527[$cache_handleid]['m__cover'] . '</span>' . $handles___4527[$cache_handleid]['m__title'] . ':' . (isset($handles___11035[$cache_handleid]) && strlen($handles___11035[$cache_handleid]['m__message']) ? '<span class="doregular info_blob ' . (strlen($handles___11035[$cache_handleid]['m__message']) < 55 ? ' short_blob ' : '') . '"><span>' . $handles___11035[$cache_handleid]['m__message'] . '</span></span>' : '') . '</div>';
        }

        foreach ($handles___this as $handleid => $m) {

            if (in_array($handleid, $CI->config->item('handleids___32145'))) {
                continue; //Locked Dropdown
            }
            $superpowers_required = array_intersect($CI->config->item('handleids___10957'), $m['m__following']);
            if (count($superpowers_required) && !handle_session(end($superpowers_required))) {
                continue;
            }

            $superpowers_required = array_intersect($CI->config->item('handleids___10957'), $m['m__following']);
            $removal_option = in_array($handleid, $CI->config->item('handleids___42850'));

            $ui .= '<a class="dropdown-item drop_item_instant_' . $handleid . '_' . $o__id . '_' . $chainid . ' main__title optiond_' . $handleid . '_' . $o__id . '_' . $chainid . ' ' . ($handleid == $selected_handleid ? ' active ' : '') . ($removal_option ? ' removal_option ' . ($unselected_radio ? ' hidden ' : '') : '') . '" href="javascript:void();" this_id="' . $handleid . '" onclick="selector(' . $cache_handleid . ', ' . $handleid . ', ' . $o__id . ', ' . $chainid . ', ' . intval($show_title) . ')"><span class="icon-block-sm">' . $m['m__cover'] . '</span>' . $m['m__title'] . (isset($handles___11035[$handleid]) && strlen($handles___11035[$handleid]['m__message']) ? '<span class="doregular info_blob ' . (strlen($handles___11035[$handleid]['m__message']) < 55 ? ' short_blob ' : '') . '"><span>' . $handles___11035[$handleid]['m__message'] . '</span></span>' : '') . '</a>';


        }

        $ui .= '</div>';
    }


    $ui .= '</div>';

    return $ui;
}


function randomize_text($handleid)
{
    $CI =& get_instance();
    $handles___12687 = $CI->config->item('handles___12687');
    $line_messages = explode("\n", $handles___12687[$handleid]['m__message']);
    return $line_messages[rand(0, (count($line_messages) - 1))];
}

function blocked_reasoning($superpower_handleid = 0)
{

    if (!handle_session()) {

        return 'Sign-in to continue';

    } elseif ($superpower_handleid && !handle_session($superpower_handleid)) {

        $CI =& get_instance();
        $handles___10957 = $CI->config->item('handles___10957');
        return 'Error: You are missing access to ' . $handles___10957[$superpower_handleid]['m__title'];

    } else {

        return null;

    }

}


function view_hash($string)
{
    $CI =& get_instance();
    return substr(md5($string . $CI->config->item('secret_hash')), 0, 10);
}


function view_hashtag_title($i, $string_only = false)
{

    if (!isset($i['hashtagtext'])) {
        return null;
    }

    //Break down by lines:
    foreach (explode("\n", $i['hashtagtext']) as $line) {
        if (strlen($line) && !filter_var($line, FILTER_VALIDATE_URL)) {
            return ($string_only ? $line : '<span class="main__title">' . $line . '</span>');
        }
    }

    //If not yet found we need to use other data to generate title:
    return (isset($i['hashtagterm']) && strlen($i['hashtagterm']) ? $i['hashtagterm'] : (isset($i['hashtagid']) && intval($i['hashtagid']) ? 'Hashtag Number ' . $i['hashtagid'] : 'Hashtag' . rand(100000000000, 999999999999)));

}

function view_valid_handle_handle($string, $check_db = false)
{
    $CI =& get_instance();
    return (substr($string, 0, 1) == '@' && ctype_alnum(substr($string, 1)) && (!$check_db || count($CI->Handles->read(array(
            'LOWER(handleterm)' => strtolower(substr($string, 1)),
        )))) ? substr($string, 1) : false);
}

function view_valid_handle_hashtag($string, $check_db = false)
{
    $CI =& get_instance();
    return (substr($string, 0, 1) == '#' && ctype_alnum(substr($string, 1)) && (!$check_db || count($CI->Hashtags->read(array(
            'LOWER(hashtagterm)' => strtolower(substr($string, 1)),
        )))) ? substr($string, 1) : false);
}

function view_valid_handle_reverse_hashtag($string, $check_db = false)
{
    $CI =& get_instance();
    return (substr($string, 0, 2) == '!#' && ctype_alnum(substr($string, 2)) && (!$check_db || count($CI->Hashtags->read(array(
            'LOWER(hashtagterm)' => strtolower(substr($string, 2)),
        )))) ? substr($string, 2) : false);
}


function view_hashtag_value($i, $handleid = 0, $focus__node = false, $discovery_mode = true)
{

    if (!isset($i['hashtagid'])) {
        return null;
    }

    //Append Custom Reference Chain contents, if any:
    $CI =& get_instance();
    $field = ( $discovery_mode ? 'hashtagdiscover' : 'hashtagedit' );

    if ($handleid > 0) {
        foreach ($CI->Chains->read(array(
            'chainhashtagoutput' => $i['hashtagid'],
            'chainhandletype' => 31835, //References
        ), array('chainhandleinput'), 0) as $message_references) {
            if (!substr_count(strtolower($i[$field]), '>@' . strtolower($message_references['handleterm']))) {
                //Maybe because it was duplicated, etc... REMOVE IT:
                $CI->Chains->delete($message_references['chainid']);
                continue;
            }
            foreach ($CI->Chains->read(array(
                'chainhandleinput' => $message_references['handleid'],
                'chainhandleoutput' => $handleid,
                'chainhandletype IN (' . join(',', $CI->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                'LENGTH(chainvalue) > 0' => null,
            ), array(), 1) as $reference_profile) {
                if (strlen($reference_profile['chainvalue'])) {
                    if (filter_var($reference_profile['chainvalue'], FILTER_VALIDATE_URL)) {
                        $i[$field] = str_ireplace('@' . $message_references['handleterm'] . '</a>', '</a>' . '<a href="' . $reference_profile['chainvalue'] . '" target="_blank">' . $reference_profile['chainvalue'] . '</a>', $i[$field]);

                    } else {
                        $i[$field] = str_ireplace('@' . $message_references['handleterm'], (filter_var($reference_profile['chainvalue'], FILTER_VALIDATE_URL) ? '' : '@' . $message_references['handleterm'] . ' ') . $reference_profile['chainvalue'], $i[$field]);
                    }
                }
            }
        }
    }

    return
        $i[$field]  . view_list_handle($i, !$focus__node); //. view_hashtag_media($i)
}


function hashtag_cache($save_hashtagid, $hashtagtext, $chainhandlecreator, $replace_term = 0, $findterm = null, $replaceterm = null)
{

    //Display Images, Audio, Video & PDF Files:
    //Analyze the message to find referencing URLs and Members in the message text:
    $CI =& get_instance();
    $hashtag_cache = array(
        'hashtagchain' => '',
        'hashtagtext' => '',
        'hashtagdiscover' => '',
        'hashtagedit' => '',
        'actionstats' => array(
            'current' => 0,
            'added' => 0,
            'removed' => 0,
            'udated' => 0,
        ),
    );

    //All the possible reference types that can be found:
    $hashtag_references = array();
    $chainkey = 0;
    $hashtagtext = str_replace('	',' ', $hashtagtext);
    //$hashtagtext = preg_replace('/\s+/', ' ', $hashtagtext);

    //See what we can find:
    foreach (explode("\n", $hashtagtext) as $line_count => $line) {

        $first_line = !$line_count;
        $words = explode(' ', trim($line));
        $only_word_in_line = count($words) == 1;
        $second_word_onwards = null;

        $linehashtagchain = null;
        $linehashtagtext = null;
        $linehashtagdiscover = null;
        $linehashtagedit = null;

        foreach ($words as $word_count => $word_text) {


            $reference_type = 0;
            $first_word = !$word_count;
            if(!$first_word){
                $second_word_onwards .= ( strlen($second_word_onwards) ? ' ' : '' ).$word_text;
            }
            $hashtagchain = null;
            $hashtagtext = null;
            $hashtagdiscover = null;
            $hashtagedit = null;

            if (filter_var($word_text, FILTER_VALIDATE_URL)) {

                //Generic URL, Try to find:
                $newHandleTerm = null;
                foreach ($CI->Chains->read(array(
                    'chainvalue' => $word_text,
                    'chainhandleinput' => 1326, //URL
                    'chainhandletype IN (' . join(',', $CI->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                ), array('chainhandleoutput'), 0) as $x) {
                    $newHandleTerm = $x['handleterm'];
                }

                if (!$newHandleTerm) {
                    //Not found, create it:
                    $added_e = $CI->Handles->create(array(
                        'handlename' => 'URL ' . random_string(8),
                    ));
                    if ($added_e['status']) {

                        //Chain:
                        $CI->Chains->create(array(
                            'chainhandletype' => 4230, //Follow
                            'chainhandleinput' => 1326, //URL
                            'chainhandleoutput' => $added_e['handle_create']['handleid'],
                            'chainvalue' => $word_text,
                        ));

                        $newHandleTerm = $added_e['handle_create']['handleterm'];

                    }
                }

                //Replace Word:
                $word_text = '@' . $newHandleTerm;

            }


            //Could be another reference, check:
            $core_references = array('@', '#');
            if (in_array(substr($word_text, 0, 1), $core_references) || in_array(substr($word_text, 1, 1), $core_references)) {

                foreach ($CI->config->item('handles___1696899') as $chainhandletype => $m) {

                    //Found a reference?
                    $term = substr($word_text, strlen($m['m__cover']));
                    if (!(substr($word_text, 0, strlen($m['m__cover'])) == $m['m__cover'] && ctype_alnum($term))) {
                        //No reference found:
                        continue;
                    }

                    if (!in_array($chainhandletype, $CI->config->item('handleids___4486'))) {

                        if($replace_term==12274 && strtolower($term)==$findterm && ctype_alnum($replace_term)){
                            $term = $replace_term;
                            $word_text = $m['m__cover'].$term;
                        }

                        if(is_numeric(trim($term))){
                            $filter = array(
                                'handleid' => intval(trim($term)),
                            );
                        } else {
                            $filter = array(
                                'LOWER(handleterm)' => strtolower($term),
                            );
                        }

                        //Handle Reference
                        foreach ($CI->Handles->read($filter) as $handle) {

                            if(is_numeric($term)){
                                //Replace Word:
                                $term = $handle['handleterm'];
                                $word_text = $m['m__cover'] . $handle['handleterm'];
                            }

                            $media_append_end = false;

                            if ($m['m__cover'] == '@') {

                                $media_attachments = array();

                                foreach ($CI->Chains->read(array(
                                    'chainhandleinput IN (' . join(',', $CI->config->item('handleids___1735577')) . ')' => null, //HANDLE DISPLAY
                                    'chainhandleoutput' => $handle['handleid'],
                                    'chainhandletype IN (' . join(',', $CI->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                                ), array(), 0) as $x) {
                                    if ($x['chainhandleinput'] == 1326) {

                                        //URL
                                        array_push($media_attachments, '<a href="' . $x['chainvalue'] . '" target="_blank">' . $x['chainvalue'] . '</a>');

                                    } elseif ($x['chainhandleinput'] == 4258) {

                                        //Video
                                        array_push($media_attachments, '<video id="video_handle_' . $x['chainvalue'] . '" controls class="cld-video-handle cld-fluid cld-video-handle-skin-light" poster="' . $handle['handlecover'] . '"></video><script> play_video(\'' . $x['chainvalue'] . '\'); </script>');

                                    } elseif ($x['chainhandleinput'] == 4259) {

                                        //Audio
                                        array_push($media_attachments, '<audio controls src="' . $x['chainvalue'] . '"></audio>');

                                    } elseif ($x['chainhandleinput'] == 4260) {

                                        //Image
                                        array_push($media_attachments, '<img src="' . $x['chainvalue'] . '" />');

                                    } else {

                                        //Invalid value:
                                        log_error('ERROR: [' . $x['chainvalue'] . '] is an invalid chainvalue for media type @' . $x['chainhandleinput'] . ' for handle @' . $handle['handleid'].' - Consider deleting?', array(
                                            'chainvalue' => $x['chainvalue'],
                                            'chainhandleinput' => $x['chainhandleinput'],
                                            'chainhandleoutput' => $handle['handleid'],
                                        ));

                                        //Delete chain:
                                        //$this->Chains->delete($x['chainid']);

                                    }
                                }

                                if (count($media_attachments)) {
                                    //Replace the Entity:
                                    $media_append_end = '<div class="media_append">'.join(' ', $media_attachments).'</div>';
                                }
                            }

                            //Valid Handle
                            $reference_type = $chainhandletype;
                            if($save_hashtagid>0 && $chainhandlecreator>0){
                                $chainkey++;
                                $hashtag_references[($chainkey-1)] = array(
                                    'chainhandletype' => $chainhandletype,
                                    'chainhandleinput' => $handle['handleid'],
                                    'chainhandleoutput' => 0,
                                    'chainhashtaginput' => $save_hashtagid,
                                    'chainhashtagoutput' => $save_hashtagid, //TODO could be removed later must check all references
                                    'chainvalue' => ( $first_word && strlen($second_word_onwards) ? $second_word_onwards : null ),
                                    'chainkey' => $chainkey,
                                );
                            }

                            $hashtagchain = $m['m__cover'] . $handle['handleid'];
                            $hashtagtext = $word_text;
                            if(!($first_word && $only_word_in_line) && !(count($media_attachments)==1 && $x['chainhandleinput'] == 1326)){
                                $hashtagdiscover = '<a href="' . view_memory(42903, 42902) . $handle['handleterm'] . '" data-toggle="popover" class="ref_handle">' . $word_text . '</a>' . $media_append_end;
                            } elseif($media_append_end){
                                $hashtagdiscover = $media_append_end;
                            }
                            $hashtagedit = '<a href="' . view_memory(42903, 42902) . $handle['handleterm'] . '" data-toggle="popover" class="ref_handle">' . $word_text . '</a>';

                        }

                    } else {

                        if($replace_term==12273 && strtolower($term)==$findterm && ctype_alnum($replace_term)){
                            $term = $replace_term;
                            $word_text = $m['m__cover'].$term;
                        }

                        //Hashtag reference:
                        foreach ($CI->Hashtags->read(array(
                            'LOWER(hashtagterm)' => strtolower($term),
                        )) as $hashtag) {

                            //Valid Hashtag
                            $reference_type = $chainhandletype;

                            if($save_hashtagid>0 && $chainhandlecreator>0){
                                $chainkey++;
                                $hashtag_references[($chainkey-1)] = array(
                                    'chainhandletype' => $chainhandletype,
                                    'chainhandleinput' => 0,
                                    'chainhandleoutput' => 0,
                                    'chainhashtaginput' => $save_hashtagid,
                                    'chainhashtagoutput' => $hashtag['hashtagid'],
                                    'chainkey' => $chainkey,
                                    'chainvalue' => null,
                                );
                            }

                            $hashtagchain = $m['m__cover'] . $hashtag['hashtagid'];
                            $hashtagtext = $word_text;
                            if(!($first_word && $only_word_in_line)){
                                $hashtagdiscover = '<a href="' . view_memory(42903, 33286) . $hashtag['hashtagterm'] . '" data-toggle="popover" class="ref_hashtag">' . $word_text . '</a>';
                            }
                            $hashtagedit = '<a href="' . view_memory(42903, 33286) . $hashtag['hashtagterm'] . '">' . $word_text . '</a>';

                        }

                    }

                    //We found a match:
                    break;

                }
            }

            if (!$reference_type) {
                //This word is not referencing anything!
                $hashtagchain = $word_text;
                $hashtagtext = $word_text;
                $hashtagdiscover = $word_text;
                $hashtagedit = $word_text;
            }

            //See what we found to add:
            $linehashtagchain .= (!$first_word && $hashtagchain ? ' ' : '').$hashtagchain;
            $linehashtagtext .= (!$first_word && $hashtagtext ? ' ' : '').$hashtagtext;
            $linehashtagdiscover .= (!$first_word && $hashtagdiscover ? ' ' : '').$hashtagdiscover;
            $linehashtagedit .= (!$first_word && $hashtagedit ? ' ' : '').$hashtagedit;

        }

        $hashtag_cache['hashtagchain'] .= (!$first_line && $linehashtagchain ? "\n" : '').$linehashtagchain;
        $hashtag_cache['hashtagtext'] .= (!$first_line && $linehashtagtext ? "\n" : '').$linehashtagtext;
        $hashtag_cache['hashtagdiscover'] .=  ( $linehashtagdiscover ? '<div class="line' . ($first_line ? ' first_line' : '') . '">'.$linehashtagdiscover.'</div>' : '' );
        $hashtag_cache['hashtagedit'] .=  ( $linehashtagedit ? '<div class="line' . ($first_line ? ' first_line' : '') . '">'.$linehashtagedit.'</div>' : '' );

    }

    //Give HTML their frame:
    if(strlen($hashtag_cache['hashtagdiscover'])){
        $hashtag_cache['hashtagdiscover'] = '<div class="i_cache i_hashtagdiscover cache_frame_' . $save_hashtagid . '">'.$hashtag_cache['hashtagdiscover'].'</div>';
    }
    if(strlen($hashtag_cache['hashtagedit'])){
        $hashtag_cache['hashtagedit'] = '<div class="i_cache i_hashtagedit cache_frame_' . $save_hashtagid . '">'.$hashtag_cache['hashtagedit'].'</div>';
    }

    if (!intval($chainhandlecreator)) {
        //Nothing else we need to do:
        return $hashtag_cache;
    }

    //Save Found references to remove the ones who exist in DB:
    $chainkey = 0;

    foreach ($CI->Chains->read(array(
        'chainhandletype IN (' . join(',', $CI->config->item('handleids___1696899')) . ')' => null, //All possible refereces
        'chainhashtaginput' => intval($save_hashtagid),
    ), array(), 0) as $x) {

        $hashtag_cache['actionstats']['current']++;

        //What should happen here?
        $chainkey++;

        if(!isset($hashtag_references[($chainkey-1)])){
            //Must be removed:
            $CI->Chains->delete($x['chainid']);
            $hashtag_cache['actionstats']['removed']++;
            continue;
        }

        //We have it, see if it matches or needs updating:
        foreach($hashtag_references[($chainkey-1)] as $key => $value){
            if($x[$key]!=$value){
                //Updating needed:
                $hashtag_references[($chainkey-1)]['chainhandlecreator'] = $chainhandlecreator;
                $CI->Chains->update($x['chainid'], $hashtag_references[($chainkey-1)]);
                $hashtag_cache['actionstats']['udated']++;
                break;
            }
        }
    }


    //Any more links left that were not in DB?
    for($i=$chainkey;$i<=count($hashtag_references);$i++){
        if(isset($hashtag_references[$i])){
            $hashtag_references[$i]['chainhandlecreator'] = $chainhandlecreator;
            $CI->Chains->create($hashtag_references[$i]);
            $hashtag_cache['actionstats']['added']++;
        }
    }

    return $hashtag_cache;

}


function view_featured_chains($chainhandletype, $location, $m = null, $focus__node)
{
    $CI =& get_instance();
    $handles___11035 = $CI->config->item('handles___11035'); //Encyclopedia
    return '<div class="creator_headline" ' . (is_array($m) ? ' data-toggle="tooltip" data-placement="top" title="' . $m['m__title'] . (strlen($m['m__message']) ? ': ' . $m['m__message'] : ' @' . $location['handleterm']) . (strlen($location['chainvalue']) ? ': ' . $location['chainvalue'] : '') . '" ' : '') . '>' . ($focus__node ? '<a href="' . view_memory(42903, 42902) . $location['handleterm'] . '">' : '') . '<span class="grey ' . ($chainhandletype == 41949 ? 'icon-block' : 'icon-block-xs') . '">' . $handles___11035[$chainhandletype]['m__cover'] . '</span><span class="grey mini-frame ' . ($chainhandletype == 41949 ? 'mini-font' : '') . '">' . $location['handlename'] . '</span>' . ($focus__node ? '</a>' : '') . '</div>';
}


function view_hashtag_nav($discovery_mode, $focus_i, $x_completes = false)
{

    $CI =& get_instance();
    $coins_count = array();
    $body_content = '';
    $handle_session = handle_session();
    $hashtagtion_pen = handle_session(10939);
    $handles___loading_order = $CI->config->item('handles___' . ($discovery_mode ? 26005 : 26005));

    if ($handle_session && !is_array($x_completes)) {
        $x_completes = $CI->Chains->read(array(
            'chainhandletype IN (' . join(',', $CI->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
            'chainhandlecreator' => $handle_session['handleid'],
            'chainhashtaginput' => $focus_i['hashtagid'],
        ), array('chainhashtagoutput'));
    }

    $discovery_next_hide = $handle_session && $discovery_mode && !count($x_completes) && count($CI->Chains->read(array(
            'chainhandletype IN (' . join(',', $CI->config->item('handleids___42991')) . ')' => null, //Active Writes
            'chainhashtagoutput' => $focus_i['hashtagid'],
            'chainhandleinput' => 44250, //Hide Next Hashtags
        )));

    $ui = '';
    $ui .= '<ul class="nav nav-tabs nav12273 nav__' . $focus_i['hashtagid'] . ' hideIfEmpty">';
    foreach ($CI->config->item('handles___' . ($discovery_mode ? 42877 : 31890)) as $chainhandletype => $m) {

        $superpowers_required = array_intersect($CI->config->item('handleids___10957'), $m['m__following']);
        if (count($superpowers_required) && !handle_session(end($superpowers_required))) {
            continue;
        }


        $coins_count[$chainhandletype] = hashtags_query($chainhandletype, $focus_i['hashtagid'], 0, false);
        if (!$coins_count[$chainhandletype] && $discovery_mode) {
            continue;
        }


        if (($handle_session && in_array($chainhandletype, $CI->config->item('handleids___42945'))) || $coins_count[$chainhandletype] > 0) {
            $body_content .= '<div class="headlinebody pillbody headline_body_' . $chainhandletype . ' hidden" read-counter="' . $coins_count[$chainhandletype] . '"><div class="tab_content"></div></div>';


            if ($chainhandletype != 12840 || !$discovery_next_hide) {
                $ui .= '<li class="nav-item thepill' . $chainhandletype . '"><a class="nav-chain handle_nav_' . $m['m__handle'] . '" chainhandletype="' . $chainhandletype . '" href="#' . $m['m__handle'] . '" title="' . $m['m__title'] . '"><span class="icon-block">' . $m['m__cover'] . '</span><span class="hideIfEmpty xtypecounter' . $chainhandletype . '">' . view_number($coins_count[$chainhandletype]) . '</span><span class="hidden xtypetitle xtypetitle_' . $chainhandletype . '">&nbsp;' . $m['m__title'] . '&nbsp;</span></a></li>';
            }

        }

    }
    $ui .= '</ul>';
    $ui .= $body_content;

    if (!$discovery_next_hide) {
        $ui .= '<script> $(document).ready(function () { load_hashtag_menu(\'Next\'); }); </script>';
    }


    if (in_array($focus_i['hashtagtype'], $CI->config->item('handleids___34826')) && $handle_session && $discovery_mode && !count($x_completes)) {
        foreach ($CI->Chains->read(array(
            'chainhandletype IN (' . join(',', $CI->config->item('handleids___42991')) . ')' => null, //Active Writes
            'chainhashtagoutput' => $focus_i['hashtagid'],
            'chainhandleinput' => 44262, //Skip Next If Unhashtag_discovered
        )) as $skip) {
            //Not yet hashtag_discovered, lets go next automatically:
            $ui .= '<script> $(document).ready(function () { setTimeout(function () { hashtag_discovered(0); }, ' . (is_numeric($skip['chainvalue']) && intval($skip['chainvalue']) > 0 ? intval($skip['chainvalue']) : '2584') . '); }); </script>';
            break;
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


function hashtag_view($chainhandletype, $i, $previous_i = null, $target_hashtagterm = null, $focus_handleid = 0, $x_completes = false)
{

    //Search to see if an hashtag has a thumbnail:
    $CI =& get_instance();

    $chainid = (isset($i['chainid']) && $i['chainid'] > 0 ? $i['chainid'] : 0);
    $handles___11035 = $CI->config->item('handles___11035'); //Encyclopedia
    $is_cache = in_array($chainhandletype, $CI->config->item('handleids___14599'));
    $goto_start = in_array($chainhandletype, $CI->config->item('handleids___42988'));
    $handle_session = handle_session();
    $superpower_10939 = !$is_cache && handle_session(10939);
    $hashtag_startable = hashtag_is_startable($i);
    $chainhandlecreator = ($focus_handleid > 0 ? $focus_handleid : ($handle_session ? $handle_session['handleid'] : 0));
    $chain_creator = isset($i['chainhandlecreator']) && $i['chainhandlecreator'] == $chainhandlecreator;
    $focus__node = in_array($chainhandletype, $CI->config->item('handleids___12149')); //NODE COIN
    $discovery_uri = (isset($_POST['js_request_uri']) && substr_count($_POST['js_request_uri'], '/') == 2 ? one_two_explode('/', '/', $_POST['js_request_uri']) : false);
    $discovery_seg = (strtolower($CI->uri->segment(1)) != 'ajax' && strtolower($CI->uri->segment(1)) != 'controller' && strlen($CI->uri->segment(2)) ? $CI->uri->segment(1) : false);
    $discovery_mode = $chainhandlecreator && ($discovery_uri || $discovery_seg);
    $hashtag_access = hashtag_access($i['hashtagterm'], 0, $i, false, array(), $is_cache);
    $focus_hashtag_uri = ($discovery_uri ? one_two_explode('/', '', substr($_POST['js_request_uri'], 1)) : false);
    $focus_hashtag_seg = ($discovery_seg ? $CI->uri->segment(2) : false);
    $focus_hashtagterm = ($focus_hashtag_uri ? $focus_hashtag_uri : ($focus_hashtag_seg ? $focus_hashtag_seg : false));

    if ($discovery_mode && !$target_hashtagterm && ($discovery_uri || $discovery_seg)) {
        $target_hashtagterm = ($discovery_uri ? $discovery_uri : $discovery_seg);
    }
    if ($target_hashtagterm && $focus_hashtagterm && $focus_hashtagterm == $i['hashtagterm']) {
        $focus_hashtagterm = false;
    }

    //Log Preview:
    $chainhandlecreator_id = ($chainhandlecreator > 0 ? $chainhandlecreator : 14068 /* GUEST */);

    if ($chainhandlecreator && !is_array($x_completes)) {
        //Fetch discovery
        $x_completes = $CI->Chains->read(array(
            'chainhandletype IN (' . join(',', $CI->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
            'chainhandlecreator' => $chainhandlecreator,
            'chainhashtaginput' => $i['hashtagid'],
        ), array('chainhashtagoutput'));
    }

    $focus_hashtag_or = false;
    if ($discovery_mode && $focus_hashtagterm && !$focus__node && $chainhandlecreator && isset($previous_i['hashtagtype']) && $previous_i['hashtagtype'] != 43758) {
        foreach ($CI->Hashtags->read(array(
            'LOWER(hashtagterm)' => strtolower($focus_hashtagterm),
            'hashtagtype IN (' . join(',', $CI->config->item('handleids___7712')) . ')' => null, //Input Choice
        )) as $focus_i) {
            $focus_hashtag_or = $focus_i;
        }
    }

    $has_sortable = $chainid > 0 && !$focus__node && $hashtag_access >= 3 && in_array($chainhandletype, $CI->config->item('handleids___4603')) && ($i['chainhandletype'] == 34513 || $i['chainhandletype'] == 4228);
    $has_hashtag_discovered = 0;
    if (!$is_cache && $chainhandlecreator) {
        $discoveries = $CI->Chains->read(array(
            'chainhandletype IN (' . join(',', $CI->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
            'chainhandlecreator' => $chainhandlecreator,
            'chainhashtaginput' => $i['hashtagid'],
        ));
        $has_hashtag_discovered = count($discoveries);
    }
    if ($has_hashtag_discovered && $discovery_mode) {
        $i = array_merge($i, $discoveries[0]);
    }

    $target_hashtagterm_discover = null;
    if ($has_hashtag_discovered && !$target_hashtagterm) {
        foreach ($CI->Chains->read(array(
            'chainhandletype IN (' . join(',', $CI->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
            'chainhandlecreator' => $chainhandlecreator,
            'chainhashtaginput' => $i['hashtagid'],
            'chainhashtagoutput > 0' => null,
        ), array('chainhashtagoutput')) as $CI_dis) {
            $target_hashtagterm_discover = $CI_dis['hashtagterm'];
            $target_hashtagterm = $target_hashtagterm_discover;
        }
    }

    $is_locked = ($discovery_mode && !$has_hashtag_discovered && !$focus__node);

    if (($goto_start || !$superpower_10939) && $hashtag_startable) {
        $href = view_memory(42903, 30795) . $i['hashtagterm'] . '/' . view_memory(6404, 4235);
    } elseif ($is_locked) {
        $href = null;
    } elseif ($discovery_mode && $target_hashtagterm) {
        $href = view_memory(42903, 30795) . $target_hashtagterm . '/' . $i['hashtagterm'];
        //} elseif ($target_hashtagterm_discover) {
        //$href = view_memory(42903, 30795) . $target_hashtagterm_discover . '/' . $i['hashtagterm'];
    } elseif ($discovery_mode) {
        $href = view_memory(42903, 33286) . $i['hashtagterm'];
    } else {
        $href = view_memory(42903, 33286) . $i['hashtagterm'];
    }


    //Top action menu:
    $ui = '<div hashtagid="' . $i['hashtagid'] . '" hashtagterm="' . $i['hashtagterm'] . '" discovery_mode="'.intval($discovery_mode).'" hashtagtype="' . $i['hashtagtype'] . '" chainid="' . $chainid . '" href="' . $href . '" class="card_cover card_hashtag_cover ' . ($focus__node ? ' focus-cover slim_flat coll-md-8 coll-sm-10 col-12
     ' : ' edge-cover ' . ($discovery_mode ? ' col-12 ' : ' coll-md-4 coll-6 col-12 ')) . ' no-padding card-12273 s__12273_' . $i['hashtagid'] . ' ' . (strlen($href) ? ' card_click ' : '') . (!$focus_hashtag_or && $is_locked ? ' is_locked' : '') . ($has_sortable ? ' sort_draggable ' : '') . ($chainid ? ' cover_x_' . $chainid . ' ' : '') . '">';

    if ($discovery_mode && $chainhandlecreator && $focus__node) {
        $ui .= '<style> .add_hashtag{ display:none; } </style>';
    }

    $is_required = count($CI->Chains->read(array(
        'chainhandletype IN (' . join(',', $CI->config->item('handleids___42991')) . ')' => null, //Active Writes
        'chainhashtagoutput' => $i['hashtagid'],
        'chainhandleinput' => 28239, //Required
    )));

    if ($is_required) {
        //Add required icon:
        $ui .= '<script> $(document).ready(function () { $(\'.cache_frame_' . $i['hashtagid'] . ' .first_line:first\').append(\'<span class="' . ($focus__node ? 'icon-block-sm' : 'icon-block-xs') . ' asterisk" title="Required">*</span>\'); }); </script>';
    }

    if ($focus_hashtag_or) {
        $ui .= '<div class="this_selector this_selector_' . $i['hashtagid'] . '" selection_hashtagid="' . $i['hashtagid'] . '"><span class="icon-block-sm">' . (count($CI->Chains->read(array(
                'chainhandletype' => 7712, //Input Choice
                'chainhandlecreator' => $chainhandlecreator,
                'chainhashtaginput' => $focus_hashtag_or['hashtagid'],
                'chainhashtagoutput' => $i['hashtagid'],
            ))) ? '<i class="fas fa-square-check fa-sharp"></i>' : '<i class="far fa-square fa-sharp"></i>') . '</span></div>';
    }

    $ui .= '<div class="cover-content ' . ($focus_hashtag_or ? ' cover_selector ' : '') . '">';
    $ui .= '<div class="inner-content">';
    $ui .= '<div class="cover-text">';

    //Show Chain User:
    $ui .= '<div class="creator_frame creator_frame_' . $i['hashtagid'] . '">';

    //Show Creator if any:
    $headline_authors = array();
    foreach ($CI->Chains->read(array(
        'chainhandletype' => 12273, //Hashtag Created
        'chainhashtagoutput' => $i['hashtagid'],
    ), array('chainhandleinput')) as $creator) {

        array_push($headline_authors, $creator['handleid']);
        $follow_btn = null;
        /*
        if ($focus__node && $chainhandlecreator && $chainhandlecreator != $creator['handleid']) {
            $followings = $CI->Chains->read(array(
                'chainhandleinput' => $creator['handleid'],
                'chainhandleoutput' => $chainhandlecreator,
                'chainhandletype IN (' . join(',', $CI->config->item('handleids___42795')) . ')' => null, //Follow
            ), array(), 1, 0, array('chainkey' => 'ASC'));
            $follow_btn = searchingle_select_instant(42795, (count($followings) ? $followings[0]['chainhandletype'] : 0), $hashtag_access, false, $creator['handleid'], (count($followings) ? $followings[0]['chainid'] : 0));
        }
        */

        $ui .= '<div class="creator_headline"><a href="' . view_memory(42903, 42902) . $creator['handleterm'] . '"><span class="icon-block">' . view_cover($creator['handlecover']) . '</span><b class="hidden">' . $creator['handlename'] . '</b><span class="grey mini-font mini-frame">@' . $creator['handleterm'] . '</span></a>' . (!in_array($creator['handleid'], $CI->config->item('handleids___42881')) ? '<span class="grey mini-font mini-padded mini-frame mini_time" title="' . date("Y-m-d H:i:s", strtotime($creator['chaintime'])) . ' PST">' . view_time_difference($creator['chaintime'], true) . '</span>' : '') . $follow_btn . '</div>';

    }


    $ui .= ($href ? '<a href="' . $href . '"' : '<div') . ' title="' . $i['hashtagid'] . '" class="sub__handle space-content grey ' . (!$superpower_10939 && ($discovery_mode || !$focus__node || !$chainhandlecreator) ? ' hidden ' : '') . '">#<span class="ui_hashtagterm_' . $i['hashtagid'] . '">' . $i['hashtagterm'] . '</span>' . ($href ? '</a>' : '</div>');

    //Right menu push here:
    //Bottom Bar
    $bottom_bar_ui = '';

    //Determine Chain Group
    $chainhandletype_id = 4593; //Chain Type
    $chainhandletype_ui = '';
    if (!$focus__node && $chainid && !$is_cache) {
        foreach ($CI->config->item('handles___31770') as $chainhandletype1 => $m1) {
            if (in_array($i['chainhandletype'], $CI->config->item('handleids___' . $chainhandletype1))) {
                foreach ($CI->Chains->read(array(
                    'chainid' => $chainid,
                ), array('chainhandlecreator')) as $chainer) {
                    $chainhandletype_ui .= '<span class="icon-block-sm">';
                    $chainhandletype_ui .= searchingle_select_instant($chainhandletype1, $i['chainhandletype'], $hashtag_access, false, $i['hashtagid'], $chainid);
                    $chainhandletype_ui .= '</span>';
                }
                $chainhandletype_id = $chainhandletype1;
                break;
            }
        }
        if (!$chainhandletype_ui) {
            $chainhandletype_ui .= '<span class="icon-block-sm">';
            $chainhandletype_ui .= searchingle_select_instant(4593, $i['chainhandletype'], false, false, $i['hashtagid'], $chainid);
            $chainhandletype_ui .= '</span>';
        }
    }

    foreach ($CI->config->item('handles___31904') as $chainhandletype_target_bar => $m_target_bar) {

        //See if missing superpower?
        $superpowers_required = array_intersect($CI->config->item('handleids___10957'), $m_target_bar['m__following']);
        if (count($superpowers_required) && (!handle_session(end($superpowers_required)) || $is_cache)) {
            continue;
        }

        //Determine hover state:
        if ($chainhandletype_target_bar == 31770 && !$discovery_mode && $chainhandletype_ui && $superpower_10939) {

            //Chains
            $bottom_bar_ui .= $chainhandletype_ui;

        } elseif ($chainhandletype_target_bar == 4362 && !$is_cache && !$discovery_mode && $handle_session && isset($i['chaintime']) && strtotime($i['chaintime']) > 0 && $chainhandletype_ui && ($hashtag_access >= 3 || ($handle_session && $chainhandlecreator == $i['chainhandlecreator']))) {

            //Chain Time / Creator
            $creator_details = '';
            $time_diff = view_time_difference($i['chaintime'], true);
            $creator_name = '';
            if ($i['chainhandlecreator'] > 0) {
                foreach ($CI->Handles->read(array(
                    'handleid' => $i['chainhandlecreator'],
                )) as $creator) {
                    $creator_name = 'Chained by ' . $creator['handlename'] . ' @' . $creator['handleterm'] . ' on ';
                    $creator_details = '<a href="' . view_memory(42903, 33286) . $i['hashtagterm'] . '"><span class="icon-block-sm">' . view_cover($creator['handlecover']) . '</span></a>';
                }
            }

            $bottom_bar_ui .= '<span class="icon-block-sm"><div class="grey created_time" title="' . $creator_name . date("Y-m-d H:i:s", strtotime($i['chaintime'])) . ' which is ' . $time_diff . ' ago | ID ' . $i['chainid'] . '">' . ($creator_details ? $creator_details : $time_diff) . '</div></span>';

        } elseif ($chainhandletype_target_bar == 4737 && !$discovery_mode && $superpower_10939) {

            //Hashtag Type
            $bottom_bar_ui .= '<span>';
            $bottom_bar_ui .= searchingle_select_instant(4737, $i['hashtagtype'], $hashtag_access, false, $i['hashtagid'], $chainid);
            $bottom_bar_ui .= '</span>';

        } elseif (0 && $chainhandletype_target_bar == 41037 && $focus_hashtag_or && !$is_cache) {

            //Selector

        } elseif ($chainhandletype_target_bar == 13909 && $hashtag_access >= 3 && $has_sortable && !$discovery_mode) {

            //Sort Hashtag
            $bottom_bar_ui .= '<span class="sort_hashtag_frame hidden icon-block-sm">';
            $bottom_bar_ui .= '<span title="' . $m_target_bar['m__title'] . '" class="sort_hashtag_grab">' . $m_target_bar['m__cover'] . '</span>';
            $bottom_bar_ui .= '</span>';

        } elseif ($chainhandletype_target_bar == 14980 && !$is_cache && $hashtag_access >= 1 && !$discovery_mode) {

            //Drop Down
            $action_buttons = null;
            if (!$chainid) {
                $focus_dropdown = 11047; //Hashtag Dropdown
            } elseif ($chainhandletype_id == 4486) { //Hashtag/Hashtag Chains
                $focus_dropdown = 14955; //Hashtag/Hashtag Dropdown
            } elseif ($chainhandletype_id == 13550) { //Hashtag/Handle Chains
                $focus_dropdown = 28787; //Hashtag/Handle Dropdown
            } else {
                //Discoveries
                $focus_dropdown = 32069; //Hashtag/Discoveries Dropdown
            }

            if (is_array($CI->config->item('handles___' . $focus_dropdown))) {
                foreach ($CI->config->item('handles___' . $focus_dropdown) as $handleid_dropdown => $m_dropdown) {

                    //Skip if missing superpower:
                    $superpowers_required = array_intersect($CI->config->item('handleids___10957'), $m_dropdown['m__following']);
                    if (count($superpowers_required) && !handle_session(end($superpowers_required))) {
                        continue;
                    }

                    $anchor = '<span class="icon-block-sm">' . $m_dropdown['m__cover'] . '</span>' . $m_dropdown['m__title'];

                    if ($handleid_dropdown == 12589 && $hashtag_access >= 3) {

                        //Mass Apply
                        $action_buttons .= '<a href="javascript:void(0);" onclick="chain_preview(12589,' . $i['hashtagid'] . ')" class="dropdown-item main__title">' . $anchor . '</a>';

                    } elseif ($handleid_dropdown == 33286 && $discovery_mode && $hashtag_access >= 3) {

                        //Hashtags Mode
                        $action_buttons .= '<a href="' . view_memory(42903, 33286) . $i['hashtagterm'] . '" class="dropdown-item main__title">' . $anchor . '</a>';

                    } elseif ($handleid_dropdown == 31911 && $hashtag_access >= 3) {

                        //Hashtag Editor
                        $action_buttons .= '<a href="javascript:void(0);" onclick="hashtag_editor(' . $i['hashtagid'] . ',' . $chainid . ')" class="dropdown-item main__title">' . $anchor . '</a>';

                    } elseif ($handleid_dropdown == 13007 && $hashtag_access >= 3) {

                        //Reset Alphabetic order
                        $action_buttons .= '<a href="javascript:void(0);" onclick="chain_sort_reset()" class="dropdown-item main__title">' . $anchor . '</a>';

                    } elseif ($handleid_dropdown == 31911 && $hashtag_access >= 3 && $discovery_mode) {

                        //Hashtag Editor
                        $action_buttons .= '<a href="javascript:void(0);" onclick="hashtag_editor(' . $i['hashtagid'] . ',' . $chainid . ')" class="dropdown-item main__title">' . $anchor . '</a>';

                    } elseif ($handleid_dropdown == 10673 && $chainid && $hashtag_access >= 3) {

                        //Unchain
                        $action_buttons .= '<a href="javascript:void(0);" onclick="chain_delete(' . $chainid . ', ' . $chainhandletype . ',\'' . $i['hashtagterm'] . '\')" class="dropdown-item main__title">' . $anchor . '</a>';

                    } elseif ($handleid_dropdown == 30873 && $hashtag_access >= 3) {

                        //Clone Hashtag Tree:
                        $action_buttons .= '<a href="javascript:void(0);" onclick="hashtag_copy(' . $i['hashtagid'] . ', 1)" class="dropdown-item main__title">' . $anchor . '</a>';

                    } elseif ($handleid_dropdown == 33292 && $handle_session) {

                        //Stats
                        $action_buttons .= '<a href="' . view_app_chain(33292) . view_memory(42903, 33286) . $i['hashtagterm'] . '" class="dropdown-item main__title">' . $anchor . '</a>';

                    } elseif ($handleid_dropdown == 29771 && $hashtag_access >= 3) {

                        //Clone Single Hashtag:
                        $action_buttons .= '<a href="javascript:void(0);" onclick="hashtag_copy(' . $i['hashtagid'] . ', 0)" class="dropdown-item main__title">' . $anchor . '</a>';

                    } elseif ($handleid_dropdown == 4341 && $hashtag_access >= 3 && $chainid) {

                        //Chain Details
                        $action_buttons .= '<a href="' . view_app_chain(4341) . '?chainid=' . $chainid . '" class="dropdown-item main__title" target="_blank">' . $anchor . '</a>';

                    } elseif ($handleid_dropdown == 42648 && $hashtag_access >= 3) {

                        //Delete Permanently
                        $action_buttons .= '<li><hr class="dropdown-divider"></li>';
                        $action_buttons .= '<a href="javascript:void();" onclick="hashtag_delete(' . $i['hashtagid'] . ')" class="dropdown-item main__title">' . $anchor . '</a>';

                    } elseif ($handleid_dropdown == 28637 && isset($i['chainhandletype']) && handle_session(12700)) {

                        //Paypal Details
                        $chainvalue = @unserialize($i['chainvalue']);
                        if (isset($chainvalue['txn_id'])) {
                            $action_buttons .= '<a href="https://www.paypal.com/activity/payment/' . $chainvalue['txn_id'] . '" class="dropdown-item main__title" target="_blank">' . $anchor . '</a>';
                        }

                    } elseif (in_array($handleid_dropdown, $CI->config->item('handleids___6287')) && $hashtag_access >= 3) {

                        //Standard button
                        $action_buttons .= '<a href="' . view_app_chain($handleid_dropdown) . view_memory(42903, 33286) . $i['hashtagterm'] . '" class="dropdown-item main__title">' . $anchor . '</a>';

                    }
                }
            }

            //Any items found?
            if ($action_buttons && $focus_dropdown > 0) {
                //Right Action Menu
                $handles___14980 = $CI->config->item('handles___14980'); //Dropdowns

                $bottom_bar_ui .= '<span>';
                $bottom_bar_ui .= '<div class="dropdown inline-block">';
                $bottom_bar_ui .= '<button type="button" class="btn no-left-padding no-right-padding icon-block-sm" id="action_menu_hashtag_' . $i['hashtagid'] . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="' . $handles___14980[$focus_dropdown]['m__title'] . '">' . $handles___14980[$focus_dropdown]['m__cover'] . '</button>';
                $bottom_bar_ui .= '<div class="dropdown-menu" aria-labelledby="action_menu_hashtag_' . $i['hashtagid'] . '">';
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


    //Hashtag Location if any:
    foreach ($CI->Chains->read(array(
        'chainhandletype' => 41949, //Locate
        'chainhashtagoutput' => $i['hashtagid'],
    ), array('chainhandleinput')) as $location) {
        $ui .= view_featured_chains(41949, $location, null, $focus__node);
    }

    //Chain Message if any:
    /*
    if ($chainid && $handle_session) {
        $ui .= '<div class="chainvalue_headline grey hideIfEmpty ignore-click ui_chainvalue_' . $chainid . '" style="padding-left:40px;">' . htmlentities($i['chainvalue']) . '</div>';
    }
    */


    $ui .= '</div>';


    //Hashtag Message (Remaining)
    $ui .= '<div class="ui_hashtagdiscover_' . $i['hashtagid'] . (!$focus__node ? ' space-content ' : '') . '">' . view_hashtag_value($i, $chainhandlecreator, $focus__node, $discovery_mode) . '</div>';


    $hashtag_popup_url = hashtag_popup_url($i);
    if ($hashtag_popup_url) {
        $ui .= '<div class="ignore-click chain_click chain_click_' . $i['hashtagid'] . ' hideIfEmpty"><a href="' . $hashtag_popup_url . '" class="hideIfEmpty" target="_blank" onclick="chain_clicked(' . $i['hashtagid'] . ')">' . $hashtag_popup_url . '</a></div>';
    }


    //Raw Data:
    $ui .= '<div class="ui_hashtagtext_' . $i['hashtagid'] . '
     hidden">' . $i['hashtagtext'] . '</div>';


    $ui .= '</div>';
    $ui .= '</div>';
    $ui .= '</div>';

    //Three main actions: (Excludes reading which is no action)
    $input_ui = '';

    //Any inputs for this hashtag?
    if (isset($previous_i['hashtagtype']) && ($previous_i['hashtagtype'] == 43758 || (in_array($i['hashtagtype'], $CI->config->item('handleids___41055')) && $focus__node && $i['hashtagtype'] != 43758))) {

        //PAYMENT TICKET
        if (isset($_GET['cancel_pay']) && !count($x_completes)) {
            $input_ui .= '<div class="alert alert-danger" role="alert">You cancelled your payment.</div>';
        }

        if (isset($_GET['process_pay']) && !count($x_completes)) {

            $input_ui .= '<div class="alert alert-warning" role="alert"><span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>Processing your payment, please wait</div>';

            //Referesh soon so we can check if completed or not
            js_php_redirect(view_memory(42903, 30795) . $target_hashtagterm . '/' . $i['hashtagterm'] . '?process_pay=1', 987);

        } elseif (isset($previous_i['hashtagtype']) && $previous_i['hashtagtype'] != 43758 && count($x_completes)) {

            foreach ($x_completes as $x_complete) {

                $chainvalue = unserialize($x_complete['chainvalue']);
                $quantity = ($x_complete['chainkey'] >= 2 ? $x_complete['chainkey'] : (isset($chainvalue['quantity']) && $chainvalue['quantity'] >= 2 ? $chainvalue['quantity'] : 1));

                if ($chainvalue['mc_gross'] != 0) {
                    $input_ui .= '<div class="alert alert-success tickets_issued" role="alert"><span class="icon-block"><i class="far fa-check-circle"></i></span>' . ($chainvalue['mc_gross'] > 0 ? 'You paid ' : 'You got a refund of ') . str_replace('.00', '', $chainvalue['mc_gross']) . ' ' . $chainvalue['mc_currency'] . ($quantity > 1 ? ' for ' . $quantity . ' tickets' : '') . ' & should receive a Paypal Email Receipt shortly.</div>';
                }

            }

            $input_ui .= '<input type="hidden" class="paypal_handling" name="handling" value="' . $chainvalue['mc_gross'] . '">';
            $input_ui .= '<input type="hidden" class="hashtagweight" name="quantity" value="' . $chainvalue['quantity'] . '">'; //Dynamic Variable that JS will update

        } else {

            $valid_instant_pay = false; //Until we can find and verify from DB

            $paypal_email = website_setting(30882);

            $currency_types = $CI->Chains->read(array(
                'chainhandletype IN (' . join(',', $CI->config->item('handleids___42991')) . ')' => null, //Active Writes
                'chainhashtagoutput' => (isset($previous_i['hashtagtype']) && $previous_i['hashtagtype'] == 43758 ? $previous_i['hashtagid'] : $i['hashtagid']),
                'chainhandleinput IN (' . join(',', $CI->config->item('handleids___26661')) . ')' => null, //Currency
            ));
            $total_dues = $CI->Chains->read(array(
                'chainhandletype IN (' . join(',', $CI->config->item('handleids___42991')) . ')' => null, //Active Writes
                'chainhashtagoutput' => $i['hashtagid'],
                'chainhandleinput' => 26562, //Total Due
            ));
            $cart_max = $CI->Chains->read(array(
                'chainhandletype IN (' . join(',', $CI->config->item('handleids___42991')) . ')' => null, //Active Writes
                'chainhashtagoutput' => $i['hashtagid'],
                'chainhandleinput' => 29651, //Cart Max Quantity
            ));
            $cart_min = $CI->Chains->read(array(
                'chainhandletype IN (' . join(',', $CI->config->item('handleids___42991')) . ')' => null, //Active Writes
                'chainhashtagoutput' => $i['hashtagid'],
                'chainhandleinput' => 31008, //Cart Min Quantity
            ));


            //Payments Must have Unit Price, otherwise they are NOT a payment until added
            $info_append = '';
            $unit_currency = '';
            $unit_price = 0;
            $unit_fee = 0;
            $max_allowed = (count($cart_max) && is_numeric($cart_max[0]['chainvalue']) && $cart_max[0]['chainvalue'] > 0 ? intval($cart_max[0]['chainvalue']) : view_memory(6404, 29651));
            $spots_remaining = hashtag_spots_remaining($i['hashtagid']);
            $starting_point = ($is_required ? 1 : 0);
            $max_allowed = ($spots_remaining > -1 && $spots_remaining < $max_allowed ? $spots_remaining : $max_allowed);

            $min_allowed = (count($cart_min) && is_numeric($cart_min[0]['chainvalue']) && intval($cart_min[0]['chainvalue']) > $starting_point ? intval($cart_min[0]['chainvalue']) : $starting_point);
            $handles___26661 = $CI->config->item('handles___26661'); //Currency
            if (count($currency_types)) {
                $unit_currency = $handles___26661[$currency_types[0]['chainhandleinput']]['m__message'];
            }


            if ($chainhandlecreator && filter_var($paypal_email, FILTER_VALIDATE_EMAIL) && count($total_dues) && isset($previous_i['hashtagtype']) && $previous_i['hashtagtype'] != 43758 && $total_dues[0]['chainvalue'] > 0 && count($currency_types) == 1) {

                $valid_instant_pay = true;

                $digest_fees = count($CI->Chains->read(array(
                    'chainhandletype IN (' . join(',', $CI->config->item('handleids___42991')) . ')' => null, //Active Writes
                    'chainhashtagoutput' => $i['hashtagid'],
                    'chainhandleinput' => 30589, //Digest Fees
                )));

                //Break down amount & currency
                $unit_price = doubleval($total_dues[0]['chainvalue']);
                $unit_fee = number_format($unit_price * ($digest_fees ? 0 : (doubleval(website_setting(30590, $chainhandlecreator)) + doubleval(website_setting(27017, $chainhandlecreator))) / 100), 2, ".", "");

                //Append information to cart about Paypal:
                $info_append .= '<div class="sub_note">After completing the payment on PayPal click "<span style="color: #990000;">Return to Merchant</span>" to continue back here. By paying you agree to our <a href="' . view_app_chain(14373) . '" target="_blank">Terms of Use</a>.</div>';

            } elseif ($chainhandlecreator && filter_var($paypal_email, FILTER_VALIDATE_EMAIL) && isset($previous_i['hashtagtype']) && $previous_i['hashtagtype'] == 43758 && count($total_dues) && $total_dues[0]['chainvalue'] > 0) {

                $digest_fees = count($CI->Chains->read(array(
                    'chainhandletype IN (' . join(',', $CI->config->item('handleids___42991')) . ')' => null, //Active Writes
                    'chainhashtagoutput' => (isset($previous_i['hashtagtype']) ? $previous_i['hashtagid'] : -1),
                    'chainhandleinput' => 30589, //Digest Fees
                )));

                //Break down amount & currency
                $unit_price = doubleval($total_dues[0]['chainvalue']);
                $unit_fee = number_format($unit_price * ($digest_fees ? 0 : (doubleval(website_setting(30590, $chainhandlecreator)) + doubleval(website_setting(27017, $chainhandlecreator))) / 100), 2, ".", "");

            }


            $current_value = $min_allowed;
            foreach ($CI->Chains->read(array(
                'chainhandletype' => 7712, //Input Choice
                'chainhandlecreator' => $handle_session['handleid'],
                'chainhashtagoutput' => $i['hashtagid'],
            ), array(), 1) as $x_selection) {
                $current_value = $x_selection['chainkey'];
            }


            //Is multi selectable, allow show down for quantity:
            $input_ui .= '<div class="handle-info ticket-notice" title="' . $handles___11035[44242]['m__title'] . '">'
                . '<span class="icon-block">' . $handles___11035[44242]['m__cover'] . '</span>'
                . '<div class="handle_info_box">';

            if ($max_allowed > 0 || $min_allowed > 0) {
                $input_ui .= '<div class="sale_controller sale_controller_' . $i['hashtagid'] . '" unitprice="' . $unit_price . '" unitcurrency="' . $unit_currency . '" hashtagid="' . $i['hashtagid'] . '">';
                $input_ui .= '<a href="javascript:void(0);" onclick="sale_increment(-1,' . $i['hashtagid'] . ',' . $max_allowed . ',' . $min_allowed . ',' . ($unit_fee + $unit_price) . ',' . $unit_fee . ')" class="sale_increment sale_down"><i class="fas fa-minus ' . ($current_value == $min_allowed ? ' hidden ' : '') . '"></i></a>';
                $input_ui .= '<span class="main__title current_count">' . $current_value . '</span>';
                $input_ui .= '<a href="javascript:void(0);" onclick="sale_increment(1,' . $i['hashtagid'] . ',' . $max_allowed . ',' . $min_allowed . ',' . ($unit_fee + $unit_price) . ',' . $unit_fee . ')" class="sale_increment sale_up">' . ($max_allowed == $min_allowed ? '<i class="fas fa-lock islocked"></i>' : '<i class="fas fa-plus"></i>') . '</a>';
                $input_ui .= '</div>';
            } else {
                $input_ui .= '<span class="current_count" style="display: none;">' . $min_allowed . '</span>';
            }

            $input_ui .= $info_append;

            $input_ui .= '</div>';
            $input_ui .= '</div>';


            if ($valid_instant_pay) {

                $handles___14870 = $CI->config->item('handles___14870'); //DOMAINS

                //Load Paypal Pay button:
                $input_ui .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">';

                $input_ui .= '<input type="hidden" class="paypal_handling" name="handling" value="' . $unit_fee . '">';
                $input_ui .= '<input type="hidden" class="hashtagweight" name="quantity" value="' . $min_allowed . '">'; //Dynamic Variable that JS will update
                $input_ui .= '<input type="hidden" name="item_name" value="' . remove_none_utf8(view_hashtag_title($i, true)) . '">';
                $input_ui .= '<input type="hidden" name="item_number" value="' . ($target_hashtagterm ? $target_hashtagterm . ' #' : '') . $i['hashtagterm'] . ' @' . get_domain('m__handle') . ' @' . $handle_session['handleterm'] . '">';

                $input_ui .= '<input type="hidden" name="amount" value="' . $unit_price . '">';
                $input_ui .= '<input type="hidden" name="currency_code" value="' . $unit_currency . '">';
                $input_ui .= '<input type="hidden" name="no_shipping" value="1">';
                $input_ui .= '<input type="hidden" name="notify_url" value="https://' . $handles___14870[2738]['m__message'] . view_app_chain(26595) . '">';
                $input_ui .= '<input type="hidden" name="cancel_return" value="https://' . get_domain('m__message') . view_memory(42903, 30795) . $target_hashtagterm . '/' . $i['hashtagterm'] . '?cancel_pay=1">';
                $input_ui .= '<input type="hidden" name="return" value="https://' . get_domain('m__message') . view_memory(42903, 30795) . $target_hashtagterm . '/' . $i['hashtagterm'] . '?process_pay=1">';
                $input_ui .= '<input type="hidden" name="cmd" value="_xclick">';
                $input_ui .= '<input type="hidden" name="business" value="' . $paypal_email . '">';

                $input_ui .= '<input type="submit" class="adj-btn pay-btn main__title" name="pay_now" id="pay_now" value="Pay Now >" onclick="$(\'.process-btn\').html(\'Loading\');$(\'#pay_now\').val(\'...\');">';

                $input_ui .= '</form>';

                $input_ui .= '<script> $(document).ready(function () { $(\'.hashtag_discovered_btn\').hide(); }); </script>';

            } else {

                //FREE TICKET
                $input_ui .= '<input type="hidden" class="paypal_handling" name="handling" value="' . $unit_fee . '">';
                $input_ui .= '<input type="hidden" class="hashtagweight" name="quantity" value="' . $min_allowed . '">'; //Dynamic Variable that JS will update

            }
        }

    } elseif (in_array($i['hashtagtype'], $CI->config->item('handleids___33532'))) {

        //Find the created hashtag if any:
        $handle_private_replies = $CI->Chains->read(array(
            'chainhandletype' => 4228,
            'chainhashtagoutput' => $i['hashtagid'],
            'chainhandlecreator' => $chainhandlecreator,
        ), array('chainhashtaginput'), 0, 1, array('chainid' => 'DESC'));

        $input_attributes = '';
        $previous_response = ($chainhandlecreator && isset($handle_private_replies[0]['hashtagtext']) ? $handle_private_replies[0]['hashtagtext'] : '');

        if (in_array($i['hashtagtype'], $CI->config->item('handleids___43002'))) {

            //Textarea
            $handles___6201 = $CI->config->item('handles___6201'); //HASHTAG Cache
            $input_ui .= '<textarea class="border dotted-borders x_write algolia_finder algolia__i algolia__e" placeholder="' . (strlen($handles___6201[4736]['m__message']) ? $handles___6201[4736]['m__message'] : $handles___6201[4736]['m__title'] . '...') . '">' . $previous_response . '</textarea>';
            $input_ui .= '<script> $(document).ready(function () { set_autosize($(\'.x_write\')); }); </script>';

        } elseif (in_array($i['hashtagtype'], $CI->config->item('handleids___43003'))) {

            //Input

            if ($i['hashtagtype'] == 31794) {

                //Number
                if (count($CI->Chains->read(array(
                    'chainhandletype IN (' . join(',', $CI->config->item('handleids___42991')) . ')' => null, //Active Writes
                    'chainhashtagoutput' => $i['hashtagid'],
                    'chainhandleinput' => 42181, //Phone
                )))) {
                    //It's a phone number:
                    $input_type = 'text';
                    $placeholder = 'Enter Phone Number...';
                } else {
                    //A regular number:
                    $input_type = 'number';
                    $placeholder = 'Enter Number...';
                }

                //Steps
                foreach ($CI->Chains->read(array(
                    'chainhandletype IN (' . join(',', $CI->config->item('handleids___42991')) . ')' => null, //Active Writes
                    'chainhashtagoutput' => $i['hashtagid'],
                    'chainhandleinput' => 31813, //Steps
                )) as $num_steps) {
                    if (strlen($num_steps['chainvalue']) && is_numeric($num_steps['chainvalue'])) {
                        $input_attributes .= ' step="' . $num_steps['chainvalue'] . '" ';
                    }
                }

                //Min Value
                foreach ($CI->Chains->read(array(
                    'chainhandletype IN (' . join(',', $CI->config->item('handleids___42991')) . ')' => null, //Active Writes
                    'chainhashtagoutput' => $i['hashtagid'],
                    'chainhandleinput' => 31800, //Min Value
                )) as $num_steps) {
                    if (strlen($num_steps['chainvalue']) && is_numeric($num_steps['chainvalue'])) {
                        $input_attributes .= ' min="' . $num_steps['chainvalue'] . '" ';
                    }
                }

                //Max Value
                foreach ($CI->Chains->read(array(
                    'chainhandletype IN (' . join(',', $CI->config->item('handleids___42991')) . ')' => null, //Active Writes
                    'chainhashtagoutput' => $i['hashtagid'],
                    'chainhandleinput' => 31801, //Max Value
                )) as $num_steps) {
                    if (strlen($num_steps['chainvalue']) && is_numeric($num_steps['chainvalue'])) {
                        $input_attributes .= ' max="' . $num_steps['chainvalue'] . '" ';
                    }
                }

            } elseif ($i['hashtagtype'] == 30350) {

                $has_time = count($CI->Chains->read(array(
                    'chainhandletype IN (' . join(',', $CI->config->item('handleids___42991')) . ')' => null, //Active Writes
                    'chainhashtagoutput' => $i['hashtagid'],
                    'chainhandleinput' => 32442, //Select Time
                )));

                $input_type = ($has_time ? 'datetime-local' : 'date');
                $placeholder = ($has_time ? 'Select Date & Time...' : 'Select Date...');

            } elseif ($i['hashtagtype'] == 42915) {

                //URL
                $input_type = 'url';
                $placeholder = 'Paste URL...';

            } elseif ($i['hashtagtype'] == 43005) {

                //Input Text
                $input_type = 'text';
                $placeholder = 'Write...';

            }

            $input_ui .= '<input type="' . $input_type . '" ' . $input_attributes . ' class="border dotted-borders x_write" placeholder="' . $placeholder . '" value="' . $previous_response . '" />';

        }

        //Uploader
        if (in_array($i['hashtagtype'], $CI->config->item('handleids___43004'))) {
            foreach ($handle_private_replies as $x_response) {
                $input_ui .= '<div class="hidden">' . hashtag_view(31777, $x_response) . '</div>';
            }
        }

    }

    //Display Hashtag media:
    /*
    $ui .= '<div class="media_outer_frame hideIfEmpty">
                    <div id="media_outer_' . $i['hashtagid'] . '" class="media_frame media_frame_' . $i['hashtagid'] . ' hideIfEmpty"></div>
                    <div class="doclear">&nbsp;</div>
                </div>';
    $ui .= '<div style="padding:3px 0;"><div class="btn btn-black inner_uploader_' . $i['hashtagid'] . '"><span class="icon-block-sm">' . $handles___11035[7637]['m__cover'] . '</span>' . $handles___11035[7637]['m__title'] . '</div></div>';

    $ui .= '<script> $(document).ready(function () { load_cloudinary(43004, ' . $i['hashtagid'] . ', [\'#' . $i['hashtagid'] . '\'], \'.inner_uploader_' . $i['hashtagid'] . '\'); setTimeout(function () { display_media(\'media_outer_' . $i['hashtagid'] . '\', 43004, ' . $i['hashtagid'] . '); }, 144); }); </script>';
    */

    if (strlen($input_ui)) {
        $ui .= '<div class="ignore-click input_ui input_ui_' . $i['hashtagid'] . '">' . $input_ui . '</div>';
    }

    //Bottom Bar
    $bottom_menu_ui = '';


    foreach ($CI->config->item('handles___44257') as $chainhandletype_target_bar => $m_target_bar) {

        //See if missing superpower?
        $superpowers_required = array_intersect($CI->config->item('handleids___10957'), $m_target_bar['m__following']);
        if (count($superpowers_required) && (!handle_session(end($superpowers_required)) || $is_cache)) {
            continue;
        }

        //Determine hover state:
        if ($chainhandletype_target_bar == 33532 && !$is_cache && $handle_session && $hashtag_access >= 2 && !$is_locked) {

            //Hashtag Reply
            $bottom_menu_ui .= '<span class="mini_button main__title" style="max-width:55px;">';
            $bottom_menu_ui .= '<a href="javascript:void(0);" class="btn btn-sm" onclick="hashtag_editor(0,0,' . $i['hashtagid'] . ')"><span class="icon-block-sm">' . $m_target_bar['m__cover'] . '</span>' . ($focus__node && 0 ? $m_target_bar['m__title'] : '') . '</a>';
            $bottom_menu_ui .= '</span>';

        } elseif ($chainhandletype_target_bar == 42260 && $handle_session && !$is_locked && !$is_cache && 0) {

            //Reactions... Check to see if they have any?
            $reactions = $CI->Chains->read(array(
                'chainhandleinput' => $chainhandlecreator,
                'chainhashtagoutput' => $i['hashtagid'],
                'chainhandletype IN (' . join(',', $CI->config->item('handleids___42260')) . ')' => null, //Reactions
            ), array(), 1);
            $bottom_menu_ui .= '<span class="mini_button" style="max-width:55px;"><div class="main__title">';
            $bottom_menu_ui .= searchingle_select_instant(42260, (count($reactions) ? $reactions[0]['chainhandletype'] : 0), $handle_session, 0 && $focus__node, $i['hashtagid'], (count($reactions) ? $reactions[0]['chainid'] : 0));
            $bottom_menu_ui .= '</div></span>';

        } elseif ($chainhandletype_target_bar == 4235 && (!$discovery_mode && $hashtag_startable && $hashtag_access >= 1)) {

            //Start
            $bottom_menu_ui .= '<span><a href="' . view_memory(42903, 30795) . $i['hashtagterm'] . '/' . view_memory(6404, 4235) . '" class="btn btn-sm btn-black"><span class="icon-block-sm">' . $m_target_bar['m__cover'] . '</span>' . $m_target_bar['m__title'] . '</a></span>';

        } elseif ($chainhandletype_target_bar == 42924 && $discovery_mode && $focus__node) {

            //Next
            $handles___31777 = $CI->config->item('handles___31777');
            $focus_menu = ($has_hashtag_discovered || !isset($handles___31777[hashtag_type_discovery($i)]) ? $m_target_bar : $handles___31777[hashtag_type_discovery($i)]);
            $bottom_menu_ui .= '<span><a href="javascript:void(0);" onclick="hashtag_discovered(0)" class="btn btn-sm post_button hashtag_discovered_btn"><span class="icon-block-sm">' . $focus_menu['m__cover'] . '</span>' . $focus_menu['m__title'] . '</a></span>';

        } elseif ($chainhandletype_target_bar == 31022 && $discovery_mode && $focus__node && $handle_session && !count($x_completes) && !in_array($i['hashtagtype'], $CI->config->item('handleids___43009')) && !hashtag_required($i)) {

            //Skip
            $bottom_menu_ui .= '<span class="mini_button" style="max-width: 75px;"><a href="javascript:void(0);" onclick="hashtag_discovered(1)" class="btn btn-sm"><span class="icon-block-sm">' . $m_target_bar['m__cover'] . '</span>' . $m_target_bar['m__title'] . '</a></span>';

        }
    }


    //Bottom Bar menu
    if (!$focus__node && !$is_locked && !$is_cache) {
        foreach ($CI->config->item('handles___' . ($discovery_mode ? 42877 : 31890)) as $handleid_bottom_bar => $m_bottom_bar) {

            $superpowers_required = array_intersect($CI->config->item('handleids___10957'), $m_bottom_bar['m__following']);
            if (count($superpowers_required) && !handle_session(end($superpowers_required))) {
                continue;
            }

            $coins_ui = hashtags_query($handleid_bottom_bar, $i['hashtagid'], 0, true, $headline_authors);
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
    $handlecover_generator = handlecover_generator(12279);
    return random_adjective() . str_replace('Badger Honey', 'Honey Badger', str_replace('Black Widow', '', ucwords(str_replace('-', ' ', one_two_explode('fa-', ' ', $handlecover_generator)))));
}

function view_list_handle($i, $plain_no_html = false)
{

    $CI =& get_instance();
    $message_append = '';

    //Define Order:
    $handles___42421 = $CI->config->item('handles___42421');
    $order_columns = array();
    foreach ($handles___42421 as $sort_id => $sort) {
        $order_columns['chainhandleinput = \'' . $sort_id . '\' DESC'] = null;
    }

    //Query Relevant Handles:
    foreach ($CI->Chains->read(array(
        'chainhandletype IN (' . join(',', $CI->config->item('handleids___33602')) . ')' => null, //Writer Chains Active
        'chainhashtagoutput' => $i['hashtagid'],
        'chainhandleinput IN (' . join(',', $CI->config->item('handleids___42421')) . ')' => null, //Featured Inputs
    ), array('chainhandleinput'), 0, 0, $order_columns) as $x) {

        //Format data if needed:
        $x['chainvalue'] = data_type_format($x['chainhandleinput'], $x['chainvalue']);

        $message_append .= '<div class="handle-info">'
            . '<span class="icon-block">' . $handles___42421[$x['chainhandleinput']]['m__cover'] . '</span>' . $handles___42421[$x['chainhandleinput']]['m__title'] . (strlen($x['chainvalue']) ? ':' : '')
            . (strlen($x['chainvalue']) ? '<div class="handle_info_box"><div class="sub_note main__title">' . (!$plain_no_html ? nl2br(view_url($x['chainvalue'])) : $x['chainvalue']) . '</div></div>' : '')
            . '</div>';

    }

    return (strlen($message_append) ? ($plain_no_html ? $message_append : '<div class="handle-featured">' . $message_append . '</div>') : false);

}


function view_hashtag_media($i)
{

    $CI =& get_instance();
    $message_append = '';

    //Query Relevant Handles:
    foreach ($CI->Chains->read(array(
        'chainhandletype IN (4258,4259,4260)' => null, //Media TODO
        'chainhashtagoutput' => $i['hashtagid'],
    ), array('chainhandleinput'), 0, 0, array('chainkey' => 'ASC')) as $x) {

        if ($x['chainhandletype'] == 4258) {

            //Video
            $template = '<video id="video_handle_' . $x['chainvalue'] . '" controls class="cld-video-handle cld-fluid cld-video-handle-skin-light" poster="' . $x['handlecover'] . '"></video><script> play_video(\'' . $x['chainvalue'] . '\'); </script>';

        } elseif ($x['chainhandletype'] == 4259) {

            //Audio
            $template = '<audio controls src="' . $x['chainvalue'] . '"></audio>';

        } elseif ($x['chainhandletype'] == 4260) {

            //Image
            $template = '<img src="' . $x['chainvalue'] . '" />';

        } else {
            continue; //Should not happen!
        }

        //Format data if needed:
        $message_append .= '<div class="media_display media_display_' . $x['chainhandletype'] . ($x['chainhandletype'] == 4258 ? ' ignore-click ' : '') . '" id="loaded_media_' . $x['chainid'] . '" class="media_item" media_typeid="' . $x['chainhandletype'] . '" handleid="' . $x['handleid'] . '"  handlecover="' . $x['handlecover'] . '" playback_code="' . $x['chainvalue'] . '" handlename="' . $x['handlename'] . '">' . $template . '</div>';

    }

    return $message_append;

}


function view_pill($focus__node, $chainhandletype, $counter, $m, $ui = null, $is_open = true)
{

    return '<script> $(\'.nav-tabs\').append(\'<li class="nav-item thepill' . $chainhandletype . '"><a class="nav-chain" chainhandletype="' . $chainhandletype . '" href="#' . $m['m__handle'] . '" data-toggle="tooltip" data-placement="top" title="' . number_format($counter, 0) . ' ' . $m['m__title'] . (strlen($m['m__message']) ? ': ' . str_replace('\'', '', str_replace('"', '', $m['m__message'])) : '') . '"><span class="icon-block-xs">' . $m['m__cover'] . '</span><span class="main__title hideIfEmpty xtypecounter' . $chainhandletype . '">' . view_number($counter) . '</span></a></li>\') </script>' .
        '<div class="headlinebody pillbody hidden headline_body_' . $chainhandletype . '" read-counter="' . $counter . '">' . $ui . '</div>';

}


function handle_view($chainhandletype, $e, $extra_class = null, $extra_value = null)
{

    $CI =& get_instance();

    if (!isset($e['handleid']) || !isset($e['handlename'])) {
        log_error('handle_view() Missing core variables', array(
            'chainhandleoutput' => $chainhandletype,
        ));
        return 'Missing core variables';
    }

    $chainid = (isset($e['chainid']) ? $e['chainid'] : 0);
    $is_cache = in_array($chainhandletype, $CI->config->item('handleids___14599'));
    $handle_access = ($is_cache ? 1 : handle_access($e['handleterm'], 0, $e));
    $superpower_10939 = (!$is_cache && handle_session(10939));
    $handle_session = (!$is_cache ? handle_session() : false);
    $handles___11035 = $CI->config->item('handles___11035'); //Encyclopedia
    $focus__node = in_array($chainhandletype, $CI->config->item('handleids___12149')); //NODE COIN
    $is_app = $chainhandletype == 6287;
    $href = ($is_app ? view_app_chain($e['handleid']) : view_memory(42903, 42902) . $e['handleterm']);
    $cover_is_image = filter_var($e['handlecover'], FILTER_VALIDATE_URL);
    $has_sortable = $chainid > 0 && $handle_access >= 3 && in_array($chainhandletype, $CI->config->item('handleids___13911'));


    //Log preview view:
    $chainhandlecreator_id = ($handle_session && isset($handle_session['handleid']) ? $handle_session['handleid'] : 14068 /* GUEST */);

    //Handle UI
    $ui = '<div handleid="' . $e['handleid'] . '" handleterm="' . $e['handleterm'] . '" ' . (isset($e['chainid']) ? ' chainid="' . $e['chainid'] . '" ' : '') . ' href="' . $href . '" class="card_cover cardhandle_cover no-padding card-12274 s__12274_' . $e['handleid'] . ' ' . $extra_class . ($is_app ? ' card-6287 ' : '') . ($has_sortable ? ' sort_draggable ' : '') . ($focus__node ? ' focus-cover slim_flat col-md-8 col-sm-10 col-12 ' : ' edge-cover col-sm-4 col-6 ' . (strlen($href) ? ' card_click ' : '')) . (isset($e['chainid']) ? ' cover_x_' . $e['chainid'] . ' ' : '') . '">';

    $ui .= '<div class="cover-wrapper">';

    //Coin Cover
    $ui .= (!$focus__node ? '<a href="' . $href . '"' : '<div') . ' class="handle_hrefhandle_' . $e['handleid'] . ' coinType12274 ' . ($handle_access >= 3 ? '' : ' ready-only ') . ' black-background-obs cover-chain" ' . ($cover_is_image ? 'style="background-image:url(\'' . $e['handlecover'] . '\');"' : '') . '>';
    $ui .= '<div class="cover-btn ui_handlecover_' . $e['handleid'] . '" raw_cover="' . $e['handlecover'] . '">' . (!$cover_is_image && $e['handlecover'] ? view_cover($e['handlecover'], true) : '') . '</div>';
    $ui .= (!$focus__node ? '</a>' : '</div>');

    $ui .= '</div>';


    //Title Cover
    $ui .= '<div class="cover-content">';
    $ui .= '<div class="inner-content">';


    if ($handle_access >= 3) {
        //Editable:
        $ui .= view_handle_input(6197, $e['handlename'], $e['handleid'], $handle_access, (isset($e['chainkey']) ? ($e['chainkey'] * 100) + 1 : 0), true);
        $ui .= '<div class="hidden text__6197_' . $e['handleid'] . '">' . $e['handlename'] . '</div>';
    } else {
        //Static:
        $ui .= '<input type="hidden" class="text__6197_' . $e['handleid'] . '" value="' . $e['handlename'] . '">';
        $ui .= '<div class="center">';
        $ui .= '<span class="main__title text__6197_' . $e['handleid'] . '">' . $e['handlename'] . '</span>';
        $ui .= '</div>';
    }


    //Handle Handle
    $ui .= '<div class="center-block">';

    $ui .= '<div class="creator_headline grey">@<span class="ignore-click ui_handleterm_' . $e['handleid'] . '" title="ID ' . $e['handleid'] . '">' . $e['handleterm'] . '</span></div>';

    //Handle Location:
    $handles___42777 = $CI->config->item('handles___42777');
    $order_columns = array();
    foreach ($handles___42777 as $sort_id => $sort) {
        $order_columns['chainhandletype = \'' . $sort_id . '\' DESC'] = null;
    }
    foreach ($CI->Chains->read(array(
        'chainhandletype IN (' . join(',', $CI->config->item('handleids___42777')) . ')' => null, //Featured Profile
        'chainhandleoutput' => $e['handleid'],
    ), array('chainhandleinput'), 0, 0, $order_columns) as $location) {
        $ui .= view_featured_chains($location['chainhandletype'], $location, $handles___42777[$location['chainhandletype']], $focus__node);
    }


    if ($is_app && isset($e['chainvalue']) && strlen($e['chainvalue']) && !$is_cache && $superpower_10939) {
        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="' . $e['chainvalue'] . '"><i class="far fa-info-circle"></i></span>';
    } else if ($chainid && $handle_access >= 3 && !$is_cache && $superpower_10939) {
        //Main description:
        $ui .= '<div class="chainvalue_headline grey hideIfEmpty ignore-click ui_chainvalue_' . $chainid . '">' . htmlentities($e['chainvalue']) . '</div>';
    }

    if ($extra_value) {
        $ui .= '<div class="chainvalue_headline grey hideIfEmpty ignore-click">' . $extra_value . '</div>';
    }

    $ui .= '</div>';


    //Start with Chain Note
    $featured_handles = '';


    //Featured Handles
    $bio = null;
    $handles___14036 = $CI->config->item('handles___14036');
    $order_columns = array();
    foreach ($handles___14036 as $sort_id => $sort) {
        $order_columns['chainhandleinput = \'' . $sort_id . '\' DESC'] = null;
    }
    foreach ($CI->Chains->read(array(
        'chainhandleinput IN (' . join(',', $CI->config->item('handleids___14036')) . ')' => null, //Featured Handles
        'chainhandleoutput' => $e['handleid'],
        'chainhandletype IN (' . join(',', $CI->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
    ), array(), 0, 0, $order_columns) as $social_chain) {

        if (in_array($social_chain['chainhandleinput'], $CI->config->item('handleids___32172'))) {
            /*
             *
             * Before showing this we must enture all information is updated...
             *
            if (strlen($social_chain['chainvalue'])) {
                //Must always see, show content here:
                $bio .= '<div class="handle_bio grey center">' . $social_chain['chainvalue'] . '</div>';
            }
            */
            continue;
        }

        //Determine chain type:
        $social_url = false;

        if (in_array(32097, $handles___14036[$social_chain['chainhandleinput']]['m__following'])) {
            $social_url = 'href="mailto:' . $social_chain['chainvalue'] . '"';
        } elseif (in_array(42181, $handles___14036[$social_chain['chainhandleinput']]['m__following'])) {
            //Phone Number
            $social_url = 'href="' . phone_href($social_chain['chainhandleinput'], $social_chain['chainvalue']) . '"';
        }

        $info = (strlen($social_chain['chainvalue']) && !$social_url ? $handles___14036[$social_chain['chainhandleinput']]['m__title'] . ': ' . $social_chain['chainvalue'] : ($social_url ? view_url_clean(one_two_explode('href="', '"', $social_url)) : $handles___14036[$social_chain['chainhandleinput']]['m__title']));

        //Append to chains:
        $featured_handles .= '<span class="' . ($focus__node ? 'icon-block-sm' : 'icon-block-xs') . '">' . ($social_url && $focus__node ? '<a ' . $social_url . ' data-toggle="tooltip" data-placement="top" title="' . $info . '">' . $handles___14036[$social_chain['chainhandleinput']]['m__cover'] . '</a>' : ($focus__node ? '<a href="' . view_memory(42903, 42902) . $handles___14036[$social_chain['chainhandleinput']]['m__handle'] . '" data-toggle="tooltip" data-placement="top" title="' . $info . '">' . $handles___14036[$social_chain['chainhandleinput']]['m__cover'] . '</a>' : '<span data-toggle="tooltip" data-placement="top" title="' . $info . '">' . $handles___14036[$social_chain['chainhandleinput']]['m__cover'] . '</span>')) . '</span>';

    }


    //Start with top bar:
    if (!$is_app && !$is_cache && $handle_access >= 1) {

        //Handle Chain Groups
        $chainhandletype_id = 0;
        $chainhandletype_ui = '';
        if ($chainid) {
            foreach ($CI->config->item('handles___31770') as $chainhandletype1 => $m1) {
                if (in_array($e['chainhandletype'], $CI->config->item('handleids___' . $chainhandletype1))) {
                    foreach ($CI->Chains->read(array(
                        'chainid' => $chainid,
                    ), array('chainhandlecreator')) as $chainer) {
                        $chainhandletype_ui .= '<span class="' . ($focus__node ? 'icon-block-sm' : 'icon-block-xs') . '">';
                        $chainhandletype_ui .= searchingle_select_instant($chainhandletype1, $e['chainhandletype'], $handle_access, false, $e['handleid'], $chainid);
                        $chainhandletype_ui .= '</span>';
                    }
                    $chainhandletype_id = $chainhandletype1;
                    break;
                }
            }
        }

        //Top Bar
        foreach ($CI->config->item('handles___31963') as $chainhandletype_target_bar => $m_target_bar) {

            //See if missing superpower?
            $superpowers_required = array_intersect($CI->config->item('handleids___10957'), $m_target_bar['m__following']);
            if (count($superpowers_required) && !handle_session(end($superpowers_required))) {
                continue;
            }

            if ($chainhandletype_target_bar == 31770 && $chainid && $superpower_10939) {

                $featured_handles .= $chainhandletype_ui;

            } elseif (0 && $chainhandletype_target_bar == 42795 && $handle_session && $handle_session['handleid'] != $e['handleid'] && count($CI->Chains->read(array(
                    'chainhandleoutput' => $e['handleid'],
                    'chainhandleinput' => 4430, //Active Member
                    'chainhandletype IN (' . join(',', $CI->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                )))) {

                //Allow to follow fellow handles:
                $followings = $CI->Chains->read(array(
                    'chainhandleinput' => $e['handleid'],
                    'chainhandleoutput' => $handle_session['handleid'],
                    'chainhandletype IN (' . join(',', $CI->config->item('handleids___42795')) . ')' => null, //Follow
                ), array(), 1, 0, array('chainkey' => 'ASC'));

                if (count($followings) || $handle_access >= 3) {
                    $featured_handles .= '<span class="' . ($focus__node ? 'icon-block-sm' : 'icon-block-xs') . '">' . searchingle_select_instant(42795, (count($followings) ? $followings[0]['chainhandletype'] : 0), $handle_session && $handle_access >= 3, false, $e['handleid'], (count($followings) ? $followings[0]['chainid'] : 0)) . '</span>';
                }

            } elseif ($chainhandletype_target_bar == 41037 && $handle_access >= 3 && !$focus__node) {

                //Selector
                $featured_handles .= '<span class="' . ($focus__node ? 'icon-block-sm' : 'icon-block-xs') . ' ignore-click">';
                $featured_handles .= '<input class="form-check-input" type="checkbox" value="" handleid="' . $e['handleid'] . '" id="selectorhandle_' . $e['handleid'] . '" aria-label="...">';
                $featured_handles .= '</span>';

            } elseif ($chainhandletype_target_bar == 13006 && $has_sortable && $handle_access >= 3) {

                //Sort Handle
                $featured_handles .= '<span class="' . ($focus__node ? 'icon-block-sm' : 'icon-block-xs') . ' sorthandle_frame hidden">';
                $featured_handles .= '<span title="' . $m_target_bar['m__title'] . '" class="sorthandle_grab">' . $m_target_bar['m__cover'] . '</span>';
                $featured_handles .= '</span>';

            } elseif ($chainhandletype_target_bar == 14980 && $handle_access >= 3) {

                $action_buttons = null;

                if (!$chainid) {
                    $focus_dropdown = 12887; //Handle Dropdown
                } elseif ($chainhandletype_id == 32292) { //Handle/Handle Chains
                    $focus_dropdown = 14956; //Handle/Handle Dropdown
                } elseif ($chainhandletype_id == 31777 || $chainhandletype_id == 31777) { //Discoveries
                    $focus_dropdown = 32070; //Handle>Discoveries Dropdown
                } elseif ($chainhandletype_id == 13550) { //Hashtag/Handle Chains
                    $focus_dropdown = 28792; //Handle/Hashtag Dropdown
                } else {
                    $focus_dropdown = 0;
                }

                if ($focus_dropdown > 0 && is_array($CI->config->item('handles___' . $focus_dropdown))) {
                    foreach ($CI->config->item('handles___' . $focus_dropdown) as $handleid_dropdown => $m_dropdown) {

                        //Skip if missing superpower:
                        $superpowers_required = array_intersect($CI->config->item('handleids___10957'), $m_dropdown['m__following']);
                        if (count($superpowers_required) && !handle_session(end($superpowers_required))) {
                            continue;
                        }

                        $anchor = '<span class="icon-block">' . $m_dropdown['m__cover'] . '</span>' . $m_dropdown['m__title'];


                        if ($handleid_dropdown == 4997) {

                            $action_buttons .= '<a href="javascript:void(0);" onclick="chain_preview(4997,' . $e['handleid'] . ')" class="dropdown-item main__title">' . $anchor . '</a>';

                        } elseif ($handleid_dropdown == 6287) {

                            //App Store
                            if (in_array($e['handleid'], $CI->config->item('handleids___6287'))) {
                                $action_buttons .= '<a href="' . view_app_chain($e['handleid']) . '" class="dropdown-item main__title">' . $anchor . '</a>';
                            }

                        } elseif ($handleid_dropdown == 31912 && $handle_access >= 3) {

                            //Edit Handle
                            $action_buttons .= '<a href="javascript:void(0);" onclick="handle_editor(' . $e['handleid'] . ',' . $chainid . ')" class="dropdown-item main__title">' . $anchor . '</a>';

                        } elseif ($handleid_dropdown == 29771 && $handle_access >= 3) {

                            //Clone:
                            $action_buttons .= '<a href="javascript:void(0);" onclick="handle_copy(' . $e['handleid'] . ')" class="dropdown-item main__title">' . $anchor . '</a>';

                        } elseif ($handleid_dropdown == 10673 && $chainid > 0 && $handle_access >= 3 && $superpower_10939) {

                            //UNCHAIN
                            $action_buttons .= '<a href="javascript:void(0);" onclick="chain_delete(' . $chainid . ', ' . $e['chainhandletype'] . ')" class="dropdown-item main__title">' . $anchor . '</span></a>';

                        } elseif ($handleid_dropdown == 42649 && $handle_access >= 3) {

                            //Delete Handle
                            $action_buttons .= '<li><hr class="dropdown-divider"></li>';
                            $action_buttons .= '<a href="javascript:void();" onclick="handle_delete(' . $e['handleid'] . ', ' . $chainid . ', 0)" class="dropdown-item main__title">' . $anchor . '</a>';

                        } elseif ($handleid_dropdown == 13007 && $handle_access >= 3) {

                            //Reset Alphabetic order
                            $action_buttons .= '<a href="javascript:void(0);" onclick="chain_sort_reset()" class="dropdown-item main__title">' . $anchor . '</a>';

                        } elseif (in_array($handleid_dropdown, $CI->config->item('handleids___6287')) && $handle_access >= 3) {

                            //Standard button
                            $action_buttons .= '<a href="' . view_app_chain($handleid_dropdown) . view_memory(42903, 42902) . $e['handleterm'] . '" class="dropdown-item main__title">' . $anchor . '</a>';

                        }
                    }
                }

                //Any items found?
                if ($action_buttons && $focus_dropdown > 0) {
                    //Right Action Menu
                    $handles___14980 = $CI->config->item('handles___14980'); //Dropdowns

                    $featured_handles .= '<span class="' . ($focus__node ? 'icon-block-sm' : 'icon-block-xs') . '">';
                    $featured_handles .= '<div class="dropdown inline-block">';
                    $featured_handles .= '<button type="button" class="btn no-left-padding no-right-padding" id="action_menuhandle_' . $e['handleid'] . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="' . $handles___14980[$focus_dropdown]['m__title'] . '">' . $handles___14980[$focus_dropdown]['m__cover'] . '</button>';
                    $featured_handles .= '<div class="dropdown-menu" aria-labelledby="action_menuhandle_' . $e['handleid'] . '">';
                    $featured_handles .= $action_buttons;
                    $featured_handles .= '</div>';
                    $featured_handles .= '</div>';
                    $featured_handles .= '</span>';
                }
            }
        }
    }


    $ui .= $bio;

    if ($focus__node) {
        $ui .= '<div class="center-block">';
        $ui .= $featured_handles;
        $ui .= '</div>';
    }


    $ui .= '</div>';
    $ui .= '</div>';


    //Bottom Bar
    if (!$is_app && $handle_access >= 1) {

        $ui .= '<div class="card_cards hideIfEmpty">';

        if (!$focus__node) {

            $ui .= $featured_handles;

            //Also Append bottom bar / main menu:
            foreach ($CI->config->item('handles___31916') as $handleid_bottom_bar => $m_bottom_bar) {
                $superpowers_required = array_intersect($CI->config->item('handleids___10957'), $m_bottom_bar['m__following']);
                if (count($superpowers_required) && !handle_session(end($superpowers_required))) {
                    continue;
                }

                $ui .= '<span class="hideIfEmpty">';
                $ui .= handles_query($handleid_bottom_bar, $e['handleid']);
                $ui .= '</span>';
            }
        }

        $ui .= '</div>';
    }


    $ui .= '</div>';

    return $ui;

}


function view_handle_input($cache_handleid, $current_value, $s__id, $hashtag_access, $tabindex = 0, $extra_large = false)
{

    $CI =& get_instance();
    $handles___12112 = $CI->config->item('handles___12112');
    $current_value = htmlentities($current_value);
    $name = 'input' . substr(md5($cache_handleid . $current_value . $s__id . $hashtag_access . $tabindex), 0, 8);

    //Define element attributes:
    $attributes = ($hashtag_access >= 3 ? '' : 'disabled') . ' spellcheck="false" tabindex="' . $tabindex . '" old-value="' . $current_value . '" id="input_' . $cache_handleid . '_' . $s__id . '" class="form-control 
     inline-block editing-mode x_set_class_text text__' . $cache_handleid . '_' . $s__id . ($extra_large ? ' texttype_lg ' : ' texttype_sm ') . ' texthandle_' . $cache_handleid . '" cache_handleid="' . $cache_handleid . '" handleid="' . $s__id . '" ';

    //Also Append Counter to the end?
    if ($extra_large) {

        $focus_element = '<textarea name="' . $name . '" placeholder="' . $handles___12112[$cache_handleid]['m__title'] . '" ' . $attributes . '>' . $current_value . '</textarea>';

    } else {

        $focus_element = '<input type="text" name="' . $name . '" data-lpignore="true" placeholder="__" value="' . $current_value . '" ' . $attributes . ' />';

    }

    return '<span class="span__' . $cache_handleid . ' ' . (!($hashtag_access >= 3) ? ' edit-locked ' : '') . '">' . $focus_element . '</span>';

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
