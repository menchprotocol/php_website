<?php


function idea_sort()
{
    return array('linkplayertype = \'34513\' DESC' => null, 'linknumber' => 'ASC', 'linktime' => 'DESC');
}

function player_sort()
{
    return array('linknumber' => 'ASC', 'linktime' => 'DESC'); //'linkplayertype = \'41011\' DESC' => null,
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

function discover_linkplayertype()
{
    return (isset($_POST['js_request_uri']) && substr($_POST['js_request_uri'], 0, 1) == '/' && substr_count($_POST['js_request_uri'], '/') == 2 ? '/' . strtok(substr($_POST['js_request_uri'], 1), '/') : null);
}

function player_pinned($playerid, $return_itself = false, $first_pin_only = true)
{

    $CI =& get_instance();
    $pinned_down = $CI->config->item('pinned_down');
    if (isset($pinned_down[$playerid])) {
        return ($first_pin_only ? reset($pinned_down[$playerid]) : $pinned_down[$playerid]);
    }

    $pinned_up = $CI->config->item('pinned_up');
    if (isset($pinned_up[$playerid])) {
        return ($first_pin_only ? reset($pinned_up[$playerid]) : $pinned_up[$playerid]);
    }

    return ($first_pin_only ? ($return_itself ? $playerid : 0) : array());

}

function idea_type_discovery($i, $trying_to_skip = false)
{

    if ($trying_to_skip) {
        return 31022;
    }

    $CI =& get_instance();
    if ($i['ideatype'] == 26560) {
        $currency_types = $CI->Links->read(array(
            'linkplayertype IN (' . join(',', $CI->config->item('playerids___42991')) . ')' => null, //Active Writes
            'linkidearight' => $i['ideaid'],
            'linkplayerup IN (' . join(',', $CI->config->item('playerids___26661')) . ')' => null, //Currency
        ));
        $total_dues = $CI->Links->read(array(
            'linkplayertype IN (' . join(',', $CI->config->item('playerids___42991')) . ')' => null, //Active Writes
            'linkidearight' => $i['ideaid'],
            'linkplayerup' => 26562, //Total Due
        ));
        return (count($total_dues) && doubleval($total_dues[0]['linktext']) && count($currency_types) ? 26595 : 42332);
    } else {
        return player_pinned($i['ideatype']);
    }

}


function string_is_icon($string)
{
    return substr_count($string, 'fa-');
}


function idea_number_calculator($i)
{

    //TODO Improve later (This is a very basic logic)
    $CI =& get_instance();
    $count_x = $CI->Links->read(array(
        '(linkidealeft=' . $i['ideaid'] . ' OR linkidearight=' . $i['ideaid'] . ')' => null,
    ), array(), 0, 0, array(), 'COUNT(linkid) as totals');

    //Should we update?
    if ($count_x[0]['totals'] != $i['ideanumber']) {
        return $CI->Ideas->update($i['ideaid'], array(
            'ideanumber' => $count_x[0]['totals'],
        ));
    } else {
        return 0;
    }

}

function player_number_calculator($e)
{

    //TODO Improve later (This is a very basic logic)
    $CI =& get_instance();
    $count_x = $CI->Links->read(array(
        '(linkplayerdown=' . $e['playerid'] . ' OR linkplayerup=' . $e['playerid'] . ' OR linkplayercreator=' . $e['playerid'] . ')' => null,
    ), array(), 0, 0, array(), 'COUNT(linkid) as totals');

    //Should we update?
    if ($count_x[0]['totals'] != $e['playernumber']) {
        return $CI->Players->update($e['playerid'], array(
            'playernumber' => $count_x[0]['totals'],
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


function phone_href($linkplayertype, $number)
{

    $number = preg_replace("/[^0-9]/", "", $number);

    if ($linkplayertype == 13815) {
        //WhatsApp
        return 'https://wa.me/' . $number;
    } elseif ($linkplayertype == 20337) {
        //Telegram
        return 'https://t.me/' . $number;
    } else {
        //general number:
        return 'tel:' . $number;
    }
}

function playercover_generator($playerid)
{
    $CI =& get_instance();
    $fetch = $CI->config->item('players___' . $playerid);
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


function reset_cache($linkplayercreator)
{
    $CI =& get_instance();
    $count = 0;
    foreach ($CI->Links->read(array(
        'linkplayertype' => 44179, //Triggered
        'linkplayerup' => 14599, //Cache App
        'linkplayerdown >' => 0,
    )) as $delete_cahce) {
        //Void:
        $count += $CI->Links->delete($delete_cahce['linkid'], $linkplayercreator);
    }
    return $count;
}

function idea_spots_remaining($ideaid)
{

    $CI =& get_instance();
    $player_session = player_session();

    //Any Limits on Selection?
    $spots_remaining = -1; //No limits
    $max_available = $CI->Links->read(array(
        'linkplayertype IN (' . join(',', $CI->config->item('playerids___42991')) . ')' => null, //Active Writes
        'linkidearight' => $ideaid,
        'linkplayerup' => 26189,
    ), array(), 1);
    if (count($max_available) && is_numeric($max_available[0]['linktext'])) {

        //We have a limit! See if we've met it already:
        $query_filters = array(
            'linkplayertype IN (' . join(',', $CI->config->item('playerids___40986')) . ')' => null, //SUCCESSFUL DISCOVERIES
            'linkidealeft' => $ideaid,
        );
        if ($player_session) {
            //Do not count current user to give them option to edit & resubmit:
            $query_filters['linkplayercreator !='] = $player_session['playerid'];
        }

        //Navigation?
        $must_follow = array();
        foreach ($CI->Links->read(array(
            'linkplayertype' => 32235, //Navigation
            'linkidearight' => $ideaid,
        )) as $follow) {
            array_push($must_follow, $follow['linkplayerup']);
        }

        $current_discoveries = 0;
        if (count($must_follow)) {
            //We must qualify each discovery individually:
            foreach ($CI->Links->read($query_filters) as $e) {
                if (count($must_follow) == count($CI->Links->read(array(
                        'linkplayerdown' => $e['linkplayercreator'],
                        'linkplayerup IN (' . join(',', $must_follow) . ')' => null,
                        'linkplayertype IN (' . join(',', $CI->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
                    )))) {
                    $current_discoveries++;
                }
            }
        } else {
            $query = $CI->Links->read($query_filters, array(), 1, 0, array(), 'COUNT(linkid) as totals');
            $current_discoveries = $query[0]['totals'];
        }


        $spots_remaining = intval($max_available[0]['linktext']) - $current_discoveries;
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

function idea_redirect_url($i)
{
    $CI =& get_instance();
    if (strlen($i['ideatext']) && count($CI->Links->read(array(
            'linkplayertype IN (' . join(',', $CI->config->item('playerids___42991')) . ')' => null, //Active Writes
            'linkidearight' => $i['ideaid'],
            'linkplayerup' => 43871, //Redirect URL
        )))) {
        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $i['ideatext'], $match);
        foreach ($match[0] as $url) {
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                return $url;
            }
        }
    }

    return false;
}

function idea_popup_url($i)
{
    if (!player_session()) {
        return false;
    }
    $CI =& get_instance();
    foreach ($CI->Links->read(array(
        'linkplayertype IN (' . join(',', $CI->config->item('playerids___42991')) . ')' => null, //Active Writes
        'linkidearight' => $i['ideaid'],
        'linkplayerup' => 44266, //Popup URL
    )) as $popup_url) {
        if (filter_var($popup_url['linktext'], FILTER_VALIDATE_URL)) {
            return $popup_url['linktext'];
        }
    }
    return false;
}

function idea_required($i)
{
    $CI =& get_instance();
    return count($CI->Links->read(array(
        'linkplayertype IN (' . join(',', $CI->config->item('playerids___42991')) . ')' => null, //Active Writes
        'linkidearight' => $i['ideaid'],
        'linkplayerup' => 28239, //Required
    )));
}

function get_redirected($url, $message = null, $log_error = false)
{
    //An error handling function that would redirect member to $url with optional $message
    //Do we have a Message?
    $CI =& get_instance();
    $player_session = player_session();

    if ($message) {
        $CI->session->set_flashdata('flash_message', $message);
    }

    if ($log_error) {
        $player_id = ($player_session ? $player_session['playerid'] : 14068);
        //Log thie error:
        log_error($url . ' ' . stripslashes($message), array(
            'linkplayerdown' => $player_id,
            'linkplayercreator' => $player_id,
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

    $es = $CI->Players->read(array(
        'playerid' => $cookie_parts[0],
    ));

    if (count($es) && $cookie_parts[2] == view_hash($cookie_parts[0] . $cookie_parts[1])) {

        //Assign session & log Link:
        $CI->Players->activate($es[0], false, true);
        return $es[0];

    } else {

        //Cookie was invalid
        cookie_delete();
        return false;

    }

}


function view_tree($i)
{

    $CI =& get_instance();

    echo '<div class="slim_title">';

    echo '<div class="hideIfEmpty">';

    if (count($i['idea_next'])) {
        echo '<a href="javascript:void(0);" onclick="$(\'.frame_id_' . $i['ideaid'] . '\').toggleClass(\'hidden\')"><span class="icon-block-sm hidden frame_id_' . $i['ideaid'] . '"><i class="far fa-circle-plus"></i></span></a>';
        echo '<a href="javascript:void(0);" onclick="$(\'.frame_id_' . $i['ideaid'] . '\').toggleClass(\'hidden\')"><span class="icon-block-sm frame_id_' . $i['ideaid'] . '"><i class="far fa-circle-minus"></i></span></a>';
    }


    echo '<a href="/' . $i['ideahashtag'] . '" target="_blank" class="' . (!isset($i['user_idea_discovered']) || count($i['user_idea_discovered']) ? ' main__title ' : '') . '">' . view_idea_title($i, true) . '</a>';
    echo(isset($i['user_idea_discovered']['linknumber']) && intval($i['user_idea_discovered']['linknumber']) > 1 ? $i['user_idea_discovered']['linknumber'] . 'x ' : '');
    echo(isset($i['user_written_response']['ideatext']) && strlen($i['user_written_response']['ideatext']) ? ' ' . $i['user_written_response']['ideatext'] : '');


    if (isset($i['idea_count_discovery']) && intval($i['idea_count_discovery']) > 0) {
        echo '<span class="icon-block-sm"><i class="far fa-eye"></i></span>' . $i['idea_count_discovery'];
    }
    if (isset($i['stats'])) {
        if ($i['stats']['max_level'] && $i['idea_level'] == 0) {
            echo '<span class="icon-block-sm"><i class="fas fa-layer-group"></i></span>' . $i['stats']['max_level'];
        }
        if ($i['idea_level'] > 0) {
            echo '<span class="icon-block-sm"><i class="far fa-layer-group"></i></span>' . $i['idea_level'];
        }
        if ($i['stats']['min_steps'] > 0) {
            echo '<span class="icon-block-sm"><i class="far fa-gauge-min"></i></span>' . $i['stats']['min_steps'];
        }
        if ($i['stats']['max_steps'] > 0) {
            echo '<span class="icon-block-sm"><i class="far fa-gauge-max"></i></span>' . $i['stats']['max_steps'];
        }
        if ($i['stats']['or_steps'] > 0) {
            echo '<span class="icon-block-sm"><i class="far fa-split rotate90"></i></span>' . $i['stats']['or_steps'];
        }
    }
    echo(isset($i['idea_count_discovery']) ? '<div class="grey hide-subline maxwidth hideIfEmpty remove_first_line extra_message">' . view_idea_links($i) . '</div><script> $(document).ready(function () {show_more(' . $i['ideaid'] . '); }); </script>' : '');
    echo '</div>';


    //Idea Filters:
    $filters_ui = '';
    if(isset($i['idea_list_config'])){
        //Idea<>Player Settings:
        $current_playerid = 0;
        foreach ($CI->config->item('players___43006') as $playerid => $m) {
            foreach ($i['idea_list_config']['full_config_' . $playerid] as $filtered_player) {
                if (!$current_playerid) {
                    $current_playerid = $playerid;
                }
                if (strlen($filters_ui) && $current_playerid != $playerid) {
                    $current_playerid = $playerid;
                    $filters_ui .= '<div class="and_filter">-AND-</div>';
                }
                $filters_ui .= '<div><span class="icon-block-sm">' . $m['m__cover'] . '</span>' . $m['m__title'] . ': <a href="/@' . $filtered_player['playerhandle'] . '"><span class="icon-block-sm">' . view_cover($filtered_player['playercover']) . '</span>' . $filtered_player['playertext'] . '</a></div>';
            }
        }
        //Idea<>Idea Settings:
        foreach ($CI->config->item('players___40792') as $playerid => $m) {
            foreach ($i['idea_list_config']['full_config_' . $playerid] as $filtered_idea) {
                if (!$current_playerid) {
                    $current_playerid = $playerid;
                }
                if (strlen($filters_ui) && $current_playerid != $playerid) {
                    $current_playerid = $playerid;
                    $filters_ui .= '<div class="and_filter">-AND-</div>';
                }
                $filters_ui .= '<div><span class="icon-block-sm">' . $m['m__cover'] . '</span>' . $m['m__title'] . ': <a href="/' . $filtered_idea['ideahashtag'] . '">' . view_idea_title($filtered_idea) . '</a></div>';
            }
        }
    }
    if ($filters_ui) {
        $players___11035 = $CI->config->item('players___11035'); //Encyclopedia
        echo '<div class="hideIfEmpty filter_data">';
        echo '<h3>' . $players___11035[40946]['m__cover'] . ' ' . $players___11035[40946]['m__title'] . ':</h3>';
        echo $filters_ui;
        echo '</div>';
    }

    foreach ($i['idea_next'] as $next_i) {
        echo '<div class="sub_frame frame_id_' . $i['ideaid'] . '">';
        view_tree($next_i);
        echo '</div>';
    }

    echo '</div>';
}

function idea_list_config($ideaid, $access_limit = true)
{

    $CI =& get_instance();

    $idea_list_config = array(); //To compile the settings of this sheet:

    foreach ($CI->config->item('players___40792') as $linkplayertype => $m) {
        $idea_list_config[intval($linkplayertype)] = array(); //Assume no links for this type
        $idea_list_config['full_config_' . $linkplayertype] = array(); //Assume no links for this type
    }
    foreach ($CI->config->item('players___43006') as $linkplayertype => $m) {
        $idea_list_config[intval($linkplayertype)] = array(); //Assume no links for this type
        $idea_list_config['full_config_' . $linkplayertype] = array(); //Assume no links for this type
    }

    //Now search for these settings across Players:
    foreach ($CI->Links->read(array(
        'linkplayerup >' => 0,
        'linkidearight' => $ideaid,
        'linkplayertype IN (' . join(',', $CI->config->item('playerids___43006')) . ')' => null,
    ), array('linkplayerup'), 0, 0, array(), '*', null, $access_limit) as $setting_link) {
        array_push($idea_list_config[intval($setting_link['linkplayertype'])], intval($setting_link['linkplayerup']));
        array_push($idea_list_config['full_config_' . $setting_link['linkplayertype']], $setting_link);
    }

    //Now search for these settings across ideas:
    foreach ($CI->Links->read(array(
        'linkidearight >' => 0,
        'linkidealeft' => $ideaid,
        'linkplayertype IN (' . join(',', $CI->config->item('playerids___40792')) . ')' => null,
    ), array('linkidearight'), 0, 0, array(), '*', null, $access_limit) as $setting_link) {
        array_push($idea_list_config[intval($setting_link['linkplayertype'])], intval($setting_link['linkidearight']));
        array_push($idea_list_config['full_config_' . $setting_link['linkplayertype']], $setting_link);
    }

    return $idea_list_config;
}

function idea_settings($ideahashtag, $fetch_contact = false)
{

    $CI =& get_instance();
    $player_column = array();
    $idea_column = array();
    $contact_details = array(
        'full_list' => '',
        'email_list' => '',
        'email_count' => 0,
        'phone_count' => 0,
    );

    foreach ($CI->Ideas->read(array(
        'LOWER(ideahashtag)' => strtolower($ideahashtag),
    )) as $i) {

        $idea_list_config = idea_list_config($i['ideaid']);

        //Generate filter:
        $query_string_all = array();
        if (count($idea_list_config[40791])) {

            //If idea_discovered Any
            $query_string_all = $CI->Links->read(array(
                'linkidealeft IN (' . join(',', $idea_list_config[40791]) . ')' => null,
                'linkplayertype IN (' . join(',', $CI->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
            ), array('linkplayercreator'), 0, 0, array('linkid' => 'DESC'));

        } elseif (count($idea_list_config[44161])) {

            //If idea_discovered All
            $query_string_all = $CI->Links->read(array(
                'linkidealeft IN (' . join(',', $idea_list_config[44161]) . ')' => null,
                'linkplayertype IN (' . join(',', $CI->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
            ), array('linkplayercreator'), 0, 0, array('linkid' => 'DESC'));

        } elseif (count($idea_list_config[27984])) {

            //Include If Has ANY
            $query_string_all = $CI->Links->read(array(
                'linkplayerup IN (' . join(',', $idea_list_config[27984]) . ')' => null,
                'linkplayertype IN (' . join(',', $CI->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
            ), array('linkplayerdown'), 0, 0, array('linknumber' => 'ASC', 'linkid' => 'DESC'));

        } elseif (count($idea_list_config[43513])) {

            //Include If Has ALL
            $query_string_all = $CI->Links->read(array(
                'linkplayerup IN (' . join(',', $idea_list_config[43513]) . ')' => null,
                'linkplayertype IN (' . join(',', $CI->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
            ), array('linkplayerdown'), 0, 0, array('linknumber' => 'ASC', 'linkid' => 'DESC'));

        } else {

            //All Discoveries:
            $query_string_all = $CI->Links->read(array(
                'linkidealeft' => $i['ideaid'],
                'linkplayertype IN (' . join(',', $CI->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
            ), array('linkplayercreator'), 0, 0, array('linknumber' => 'ASC', 'linkid' => 'DESC'));

        }

        //Filter list:
        $query_string_filtered = array();
        $unique_users_count = array();
        foreach ($query_string_all as $key => $x) {
            if (in_array(intval($x['playerid']), $unique_users_count)) {
                continue;
            } elseif (!idea_access(null, $i['ideaid'], $i, $x['playerid'], $idea_list_config)) {
                continue;
            } else {
                //Passed all filters:
                array_push($query_string_filtered, $x);
                array_push($unique_users_count, intval($x['playerid']));
            }
        }


        //Determine columns if any:
        $pinned_columns = array();
        foreach ($CI->Links->read(array(
            'linkidearight' => $i['ideaid'],
            'linkplayertype' => 34513, //Pinned
        ), array('linkplayerup'), 0) as $setting_link) {
            array_push($pinned_columns, intval($setting_link['playerid']));
        }
        if (count($pinned_columns)) {

            //Add to results:
            $idea_list_config[34513] = $pinned_columns;

            $player_column = $CI->Links->read(array(
                'linkplayerup IN (' . join(',', $pinned_columns) . ')' => null,
                'linkplayertype IN (' . join(',', $CI->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
            ), array('linkplayerdown'), 0, 0, player_sort());

            foreach ($CI->Links->read(array(
                'linkplayerup IN (' . join(',', $pinned_columns) . ')' => null,
                'linkplayertype IN (' . join(',', $CI->config->item('playerids___33602')) . ')' => null, //Idea/Player Links Active
                'linkidearight !=' => $i['ideaid'],
            ), array('linkidearight'), 0, 0, array('linknumber' => 'ASC', 'ideatext' => 'ASC')) as $link_i) {
                array_push($idea_column, $link_i);
            }
        }


        if ($fetch_contact) {
            foreach ($query_string_filtered as $count => $x) {

                //Fetch email & phone:
                $fetch_names = $CI->Links->read(array(
                    'linkplayerup' => 42584, //First Name
                    'linkplayerdown' => $x['playerid'],
                    'linkplayertype IN (' . join(',', $CI->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
                ));
                $fetch_emails = $CI->Links->read(array(
                    'linkplayerup' => 3288, //Email
                    'linkplayerdown' => $x['playerid'],
                    'linkplayertype IN (' . join(',', $CI->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
                ));
                $fetch_phones = $CI->Links->read(array(
                    'linkplayerup' => 4783, //Phone
                    'linkplayerdown' => $x['playerid'],
                    'linkplayertype IN (' . join(',', $CI->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
                ));

                $query_string_filtered[$count]['extension_name'] = (count($fetch_names) && strlen($fetch_names[0]['linktext']) ? $fetch_names[0]['linktext'] : $x['playertext']);
                $query_string_filtered[$count]['extension_email'] = (count($fetch_emails) && filter_var($fetch_emails[0]['linktext'], FILTER_VALIDATE_EMAIL) ? $fetch_emails[0]['linktext'] : false);
                $query_string_filtered[$count]['extension_phone'] = (count($fetch_phones) && strlen($fetch_phones[0]['linktext']) >= 10 ? $fetch_phones[0]['linktext'] : false);

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


        //Append Navigation:
        foreach ($idea_column as $key => $idea_var) {
            $must_follow = array();
            foreach ($CI->Links->read(array(
                'linkplayertype' => 32235, //Navigation
                'linkidearight' => $idea_var['ideaid'],
            )) as $follow) {
                array_push($must_follow, $follow['linkplayerup']);
            }
            $idea_column[$key]['must_follow'] = $must_follow;
        }

        return array(
            'i' => $i,
            'list_config' => $idea_list_config,
            'player_column' => $player_column,
            'idea_column' => $idea_column,
            'query_string_filtered' => $query_string_filtered,
            'contact_details' => $contact_details, //Optional addon
        );
    }
}


function count_link_groups($linkplayertype, $linktime_start = null, $linktime_end = null)
{

    $CI =& get_instance();

    $query_filters = array(
        'linkplayertype IN (' . join(',', (is_array($CI->config->item('playerids___' . $linkplayertype)) ? $CI->config->item('playerids___' . $linkplayertype) : array($linkplayertype))) . ')' => null,
    );

    if (strtotime($linktime_start) > 0) {
        $query_filters['linktime >='] = $linktime_start;
    }
    if (strtotime($linktime_end) > 0) {
        $query_filters['linktime <='] = $linktime_end;
    }

    //Fetch Results:
    $query = $CI->Links->read($query_filters, array(), 1, 0, array(), 'COUNT(linkid) as totals');
    return intval($query[0]['totals']);

}


function home_url()
{
    $CI =& get_instance();
    $player_session = player_session();
    return ($player_session ? view_memory(42903, 42902) . $player_session['playerhandle'] : view_memory(42903, 14565));
}

function idea_is_startable($i)
{
    $CI =& get_instance();
    return count($CI->Links->read(array(
        'linkplayertype IN (' . join(',', $CI->config->item('playerids___42991')) . ')' => null, //Active Writes
        'linkidearight' => $i['ideaid'],
        'linkplayerup' => 4235,
    )));
}


function remove_none_utf8($string)
{
    return preg_replace('/[\x00-\x1F\x7F-\xFF]/', ' ', $string);
}


function player_session($superpower_playerid = null, $force_redirect = 0, $session_player_session = false)
{

    if (isset($session_player_session['playerid'])) {
        //We have the player!
        return $session_player_session;
    }
    //Authenticates logged-in members with their session information
    $CI =& get_instance();
    $player_session = $CI->session->userdata('session_up');

    //Let's start checking various ways we can give member access:
    if ($player_session && !$superpower_playerid) {

        //No minimum level required, grant access IF member is logged in:
        return $player_session;

    } elseif ($player_session && in_array($superpower_playerid, $CI->session->userdata('session_superpowers_unlocked'))) {

        //They are part of one of the levels assigned to them:
        return $player_session;

    }

    //Still here?!
    //We could not find a reason to give member access, so block them:
    if (!$force_redirect) {

        return false;

    } else {

        //Block access:
        if ($player_session) {
            $goto_url = view_memory(42903, 42902) . $player_session['playerhandle'];
        } else {
            $goto_url = view_app_link(4269) . (isset($_SERVER['REQUEST_URI']) ? '?url=' . urlencode($_SERVER['REQUEST_URI']) : '');
        }

        //Now redirect:
        return get_redirected($goto_url, '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>' . blocked_reasoning($superpower_playerid) . '</div>');
    }

}


function get_server($var_name)
{
    return (isset($_SERVER[$var_name]) ? $_SERVER[$var_name] : null);
}

function html_input_type($data_type)
{
    $CI =& get_instance();
    $players___42291 = $CI->config->item('players___42291'); //HTML Input Types
    if (isset($players___42291[$data_type]['m__message']) && strlen($players___42291[$data_type]['m__message'])) {
        return $players___42291[$data_type]['m__message'];
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

    if (strlen($suggestion) < 4 || is_numeric($suggestion)) {
        $suggestion = ($focus__node == 12273 ? 'Idea' : 'Player') . $suggestion;
    }


    //Make sure no duplicates:
    if ($focus__node == 12273 && count($CI->Ideas->read(array(
            'LOWER(ideahashtag)' => strtolower($suggestion),
        )))) {
        return generate_handle(12273, $str, $suggestion, $increment);
    } elseif ($focus__node == 12274 && count($CI->Players->read(array(
            'LOWER(playerhandle)' => strtolower($suggestion),
        )))) {
        return generate_handle(12274, $str, $suggestion, $increment);
    } else {
        //All good:
        return $suggestion;
    }

}


function process_media($ideaid, $uploaded_media)
{

    $CI =& get_instance();
    $player_session = player_session();

    //Update Media...
    $media_stats = array(
        'media_playercover' => null,
        'total_current' => 0,
        'total_submitted' => 0,
        'adjust_created' => 0,
        'adjust_duplicated' => 0,
        'adjust_updated' => 0,
        'adjust_removed' => 0,
        'total_media' => 0,
    );


    if (!$player_session) {
        return $media_stats;
    }

    $full_media = array();
    $current_media_playerids = array();
    $sort_count = 0;

    //Fetch current media:
    foreach ($CI->Links->read(array(
        'linkplayertype IN (' . join(',', $CI->config->item('playerids___42294')) . ')' => null, //Media
        'linkidearight' => $ideaid,
    ), array('linkplayerup'), 0, 0, array('linknumber' => 'ASC')) as $media) {
        $media_stats['total_current']++;
        $current_media_playerids[$sort_count] = intval($media['linkplayerup']);
        $full_media[$media['linkplayerup']] = $media;
        $sort_count++;
    }

    //Fetch submitted media:
    $upload_media_playerids = array();
    if (count($uploaded_media) > 0) {

        //We have media to process:
        $sort_count = 0; //Reset sorting to compare to submitted media...
        foreach ($uploaded_media as $upload_media) {

            if ($upload_media['playerid'] > 0) {

                $adjust_updated = false;

                //Update media order?
                if ($current_media_playerids[$sort_count] != $upload_media['playerid']) {
                    //Order has changed, update it:
                    $adjust_updated = true;
                    $CI->Links->update($full_media[$upload_media['playerid']]['linkid'], array(
                        'linknumber' => $sort_count,
                    ));
                }

                //Update the Player title?
                $validate_playertext = validate_playertext($upload_media['playertext']);
                if ($validate_playertext['status'] && $full_media[$upload_media['playerid']]['playertext'] != $upload_media['playertext']) {
                    $adjust_updated = true;
                    $CI->Players->update($upload_media['playerid'], array(
                        'playertext' => trim($upload_media['playertext']),
                    ), $player_session['playerid']);
                }

                $media_stats['media_playercover'] = $upload_media['playercover'];

                if ($adjust_updated) {
                    $media_stats['adjust_updated']++;
                }

            } else {

                //Adding new media...
                //Search eTag to see if we already have it:
                $etag_detected = false;
                if (isset($upload_media['media_cache']['etag']) && strlen($upload_media['media_cache']['etag'])) {
                    //We already have this asset, return player:
                    foreach ($CI->Links->read(array(
                        'linkplayertype IN (' . join(',', $CI->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
                        'linkplayerup' => 42662, //etag
                        'linktext' => $upload_media['media_cache']['etag'],
                    ), array('linkplayerdown'), 1) as $existing_media) {
                        $media_stats['adjust_duplicated']++;
                        $upload_media['playerid'] = $existing_media['playerid'];
                        $etag_detected = true;
                    }
                }

                if (!$upload_media['playerid']) {

                    $media_stats['media_playercover'] = $upload_media['playercover'];

                    //Create Player for this new media:
                    $added_e = $CI->Players->create(array(
                        'playertext' => $upload_media['playertext'],
                        'playercover' => ($upload_media['media_playerid'] == 4259 /* Audio has no thumbnail! */ ? 'far fa-volume-up' : $upload_media['playercover']),
                    ), $player_session['playerid']);
                    if (!$added_e['status']) {
                        log_error('Failed to create a new Player for [' . $upload_media['playertext'] . '] with cover [' . $upload_media['playercover'] . ']', array(
                            'linkplayerdown' => $upload_media['playerid'],
                        ));
                        continue;
                    }

                    //Create new media and assign ID:
                    $media_stats['adjust_created']++;
                    $upload_media['playerid'] = $added_e['player_create']['playerid'];

                    //new asset, create new Player and insert tags...
                    $players___32088 = $CI->config->item('players___32088'); //Platform Variables
                    foreach ($CI->config->item('players___42679') as $linkplayertype => $m) {

                        //Ensure variable name exists so we can check the API call:
                        $target_variable = false;
                        if (isset($players___32088[$linkplayertype]['m__message'])) {
                            //Determine if variable exists...
                            if (in_array($linkplayertype, $CI->config->item('playerids___42763')) && isset($upload_media['media_cache']['video'][$players___32088[$linkplayertype]['m__message']])) {
                                //Video info:
                                $target_variable = $upload_media['media_cache']['video'][$players___32088[$linkplayertype]['m__message']];
                            } elseif (in_array($linkplayertype, $CI->config->item('playerids___42675')) && isset($upload_media['media_cache']['audio'][$players___32088[$linkplayertype]['m__message']])) {
                                //Audio info:
                                $target_variable = $upload_media['media_cache']['audio'][$players___32088[$linkplayertype]['m__message']];
                            } elseif (isset($upload_media['media_cache'][$players___32088[$linkplayertype]['m__message']])) {
                                //Media info:
                                $target_variable = $upload_media['media_cache'][$players___32088[$linkplayertype]['m__message']];
                            }
                        }
                        if (!strlen($target_variable) || $target_variable == '0') {
                            //This variable does not have a value, move on...
                            continue;
                        }

                        //We have a variable, see what it is...
                        if (in_array($linkplayertype, $CI->config->item('playerids___33331'))) {

                            //Single select that needs auto creation of Players if missing:
                            $child_id = 0;
                            foreach ($CI->Links->read(array(
                                'linkplayertype IN (' . join(',', $CI->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
                                'linkplayerup' => $linkplayertype,
                                'playertext' => $target_variable,
                            ), array('linkplayerdown'), 1, 0, array('linkid' => 'ASC')) as $child_player) {
                                $child_id = $child_player['playerid'];
                            }

                            //If not found create the child:
                            if (!$child_id) {
                                $added_child = $CI->Players->create(array(
                                    'playertext' => $target_variable,
                                ));
                                if (!$added_child['status']) {
                                    log_error('Failed to create a new Player for [' . $target_variable . ']', array(
                                        'linkplayerdown' => $linkplayertype,
                                    ));
                                    continue;
                                }

                                //Add links for this new Player:
                                $CI->Links->create(array(
                                    'linkplayercreator' => $player_session['playerid'],
                                    'linkplayerup' => $linkplayertype,
                                    'linkplayerdown' => $added_child['player_create']['playerid'],
                                    'linkplayertype' => 4230,
                                ));

                                //Assign child Player:
                                $child_id = $added_child['player_create']['playerid'];

                            }

                            if ($child_id) {
                                //Child Player found, simply link:
                                $CI->Links->create(array(
                                    'linkplayercreator' => $player_session['playerid'],
                                    'linkplayerup' => $child_id,
                                    'linkplayerdown' => $upload_media['playerid'],
                                    'linkplayertype' => 4230,
                                ));
                            }

                        } else {

                            //Save variable as is:
                            $CI->Links->create(array(
                                'linkplayercreator' => $player_session['playerid'],
                                'linkplayerup' => $linkplayertype,
                                'linkplayerdown' => $upload_media['playerid'],
                                'linktext' => $target_variable,
                                'linkplayertype' => 4230,
                            ));

                        }
                    }
                }


                //By now have the media Player, create necessary links:
                if ($upload_media['playerid'] && $upload_media['media_playerid']) {

                    //Link to Idea:
                    if (!count($CI->Links->read(array(
                        'linkidearight' => $ideaid,
                        'linkplayerup' => $upload_media['playerid'],
                        'linkplayertype' => $upload_media['media_playerid'],
                    )))) {
                        $CI->Links->create(array(
                            'linkplayercreator' => $player_session['playerid'],
                            'linkidearight' => $ideaid,
                            'linkplayerup' => $upload_media['playerid'],
                            'linkplayertype' => $upload_media['media_playerid'],
                            'linktext' => $upload_media['playback_code'],
                            'linknumber' => $sort_count,
                        ));
                    }


                    //Link to Player as Uploader:
                    if (!count($CI->Links->read(array(
                        'linkplayerup' => $player_session['playerid'],
                        'linkplayerdown' => $upload_media['playerid'],
                        'linkplayertype IN (' . join(',', $CI->config->item('playerids___42657')) . ')' => null, //Uploads
                    )))) {
                        $CI->Links->create(array(
                            'linkplayercreator' => $player_session['playerid'],
                            'linkplayerup' => $player_session['playerid'],
                            'linkplayerdown' => $upload_media['playerid'],
                            'linkplayertype' => ($etag_detected ? 42849 : 42659), //Reupload vs Upload
                            'linktext' => $upload_media['playback_code'],
                        ));
                    }


                    //Link to Media Type:
                    if (!count($CI->Links->read(array(
                        'linkplayerup' => $upload_media['media_playerid'],
                        'linkplayerdown' => $upload_media['playerid'],
                        'linkplayertype' => 4230,
                    )))) {
                        $CI->Links->create(array(
                            'linkplayercreator' => $player_session['playerid'],
                            'linkplayerup' => $upload_media['media_playerid'],
                            'linkplayerdown' => $upload_media['playerid'],
                            'linkplayertype' => 4230,
                            'linktext' => $upload_media,
                        ));
                    }

                }
            }

            //Add this to the submitted ones:
            $upload_media_playerids[$sort_count] = $upload_media['playerid'];
            $media_stats['total_submitted']++;
            $sort_count++;

        }
    }

    //Remove current media missing from submitted (Removed during editing):
    foreach (array_diff($current_media_playerids, $upload_media_playerids) as $deleted_media_playerid) {
        $media_stats['adjust_removed']++;
        $CI->Links->delete($full_media[$deleted_media_playerid]['linkid'], $player_session['playerid']); //Media Removed
    }

    //Calculate total media:
    $media_stats['total_media'] = $media_stats['total_current'] + $media_stats['adjust_duplicated'] + $media_stats['adjust_created'] - $media_stats['adjust_removed'];

    return $media_stats;

}


function append_player($linkplayerup, $linkplayercreator, $linktext, $ideaid, $update_if_existing = true)
{

    $CI =& get_instance();

    //First validate data type to ensure it matches:
    foreach ($CI->Links->read(array(
        'linkplayertype IN (' . join(',', $CI->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
        'linkplayerup IN (' . join(',', $CI->config->item('playerids___4592')) . ')' => null, //Data Types
        'linkplayerdown' => $linkplayerup,
    )) as $data_type) {
        $data_type_validate = data_type_validate($data_type['linkplayerup'], $linktext);
        if (!$data_type_validate['status']) {
            //It's not the data type needed:
            return false;
        }
    }

    //Now check existing links:
    $existing_x = $CI->Links->read(array(
        'linkvoid >=' => 0, //Any Link
        'linkplayertype IN (' . join(',', $CI->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
        'linkplayerup' => $linkplayerup,
        'linkplayerdown' => $linkplayercreator,
    ));

    if (count($existing_x)) {

        if ($existing_x[0]['linkvoid'] > 0) {
            return false;
        } elseif (strtolower($existing_x[0]['linktext']) == strtolower($linktext)) {
            //Everything is the same, nothing to do here:
            return false;
        }

        //Content value has changed, update the Link:
        if ($update_if_existing) {
            $CI->Links->update($existing_x[0]['linkid'], array(
                'linktext' => $linktext,
                'linkplayercreator' => $linkplayercreator,
            ));
        }

    } else {

        //Create Link:
        $CI->Links->create(array(
            'linkplayertype' => 4230, //Follow Player
            'linktext' => $linktext,
            'linkplayercreator' => $linkplayercreator,
            'linkplayerup' => $linkplayerup,
            'linkplayerdown' => $linkplayercreator,
        ));

    }

    return true;

}


function data_type_validate($data_type, $data_value, $data_title = null)
{

    $CI =& get_instance();
    $players___4592 = $CI->config->item('players___4592'); //Data types

    if ($data_type == 4319 && !is_numeric($data_value)) {
        //Number:
        return array(
            'status' => 0,
            'message' => $data_title . ' must be set to a valid ' . $players___4592[$data_type]['m__title'],
        );
    } elseif ($data_type == 42181 && (strlen(preg_replace('/[^0-9]/', '', $data_value)) < 10 || strlen(preg_replace('/[^0-9]/', '', $data_value)) > 14)) {
        //Phone Number:
        return array(
            'status' => 0,
            'message' => $data_title . ' must be set to a valid ' . $players___4592[$data_type]['m__title'] . ' with 10-14 numbers including country code.',
        );
    } elseif ($data_type == 4318 && !strtotime($data_value)) {
        return array(
            'status' => 0,
            'message' => $data_title . ' must be set to a valid ' . $players___4592[$data_type]['m__title'],
        );
    } elseif ($data_type == 4255 && !strlen($data_value)) {
        //Text:
        return array(
            'status' => 0,
            'message' => $data_title . ' must be set to a valid ' . $players___4592[$data_type]['m__title'],
        );
    } elseif ($data_type == 32097 && !filter_var($data_value, FILTER_VALIDATE_EMAIL)) {
        //Email:
        return array(
            'status' => 0,
            'message' => $data_title . ' must be set to a valid ' . $players___4592[$data_type]['m__title'],
        );
    } elseif ($data_type == 42947 && (!is_numeric($data_value) || $data_value < 0 || $data_value > 1)) {
        //Percentage:
        return array(
            'status' => 0,
            'message' => $data_title . ' must be set to a number between 0.00 & 1.00.',
        );
    } elseif (in_array($data_type, $CI->config->item('playerids___42189')) && !filter_var($data_value, FILTER_VALIDATE_URL)) {
        //URL:
        return array(
            'status' => 0,
            'message' => $data_title . ' must be set to a valid ' . $players___4592[$data_type]['m__title'],
        );
    } elseif (in_array($data_type, $CI->config->item('playerids___42188'))) {
        //Single Choice of Multi Choice Player types should not be validated here
        log_error('data_type_validate() was asked to validate choice options for @' . $data_type . ' [' . $data_value . '] [' . $data_title . ']', array(
            'linkplayerdown' => $data_type,
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

    if (in_array($data_type, $CI->config->item('playerids___4318')) && strtotime($data_value) > 0) {
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

function sort_by($playerid, $custom_sort = array())
{

    $CI =& get_instance();
    $order_by = array();
    foreach ($CI->config->item('players___' . $playerid) as $sort_id => $sort) {
        $order_by['linkplayerup = \'' . $sort_id . '\' DESC'] = null;
    }

    if (is_array($custom_sort)) {
        return array_merge($order_by, $custom_sort);
    } else {
        return $order_by;
    }
}


function validate_update_handle($str, $ideaid = null, $playerid = null)
{

    $CI =& get_instance();
    $player_session = player_session();

    //Validate:
    if (($ideaid && $playerid) || (!$ideaid && !$playerid)) {

        return array(
            'status' => 0,
            'db_duplicate' => 0,
            'message' => 'Must set either Idea or Player ID! Pick one',
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

    } elseif ($ideaid && array_key_exists(strtolower($str), $CI->config->item('handlplayers___6287'))) {

        return array(
            'status' => 0,
            'db_duplicate' => 1,
            'message' => 'Hashtag "' . $str . '" already in use.',
        );

    }

    //Syntax good! Now let's check the DB for duplicates
    if ($ideaid > 0) {

        foreach ($CI->Ideas->read(array(
            'ideaid !=' => $ideaid,
            'LOWER(ideahashtag)' => strtolower($str),
        ), 0) as $matched) {
            return array(
                'status' => 0,
                'db_duplicate' => 1,
                'message' => 'Hashtag "' . $str . '" already in use.',
            );
        }

        //Since not found we can replace this:
        $CI->Ideas->update($ideaid, array(
            'ideahashtag' => change_handle($str),
        ), $player_session['playerid']);

    } elseif ($playerid > 0) {

        foreach ($CI->Players->read(array(
            'playerid !=' => $playerid,
            'LOWER(playerhandle)' => strtolower($str),
        ), 0) as $matched) {
            //Is it active?
            return array(
                'status' => 0,
                'db_duplicate' => 1,
                'message' => 'Hashtag "' . $str . '" already in use.',
            );
        }

        //Since not active we can replace this:
        $CI->Players->update($playerid, array(
            'playerhandle' => change_handle($str),
        ), $player_session['playerid']);

    }


    //All good, return success:
    return array(
        'status' => 1,
        'db_duplicate' => 0,
        'message' => 'Success',
    );

}


function validate_playertext($str)
{

    //Validate:
    $title_clean = trim($str);
    while (substr_count($title_clean, '  ') > 0) {
        $title_clean = str_replace('  ', ' ', $title_clean);
    }

    if (!strlen(trim($str))) {

        return array(
            'status' => 0,
            'message' => 'Player title missing',
        );

    } elseif (strlen(trim($str)) < 1) {

        return array(
            'status' => 0,
            'message' => 'Enter Player title to continue.',
        );

    } elseif (strlen($str) > view_memory(6404, 6197)) {

        return array(
            'status' => 0,
            'message' => 'Player title must be ' . view_memory(6404, 6197) . ' characters or less',
        );

    }

    //All good, return success:
    return array(
        'status' => 1,
        'playertext_clean' => trim($title_clean),
    );

}

function number_linknumber($str)
{
    //Set linknumber for caching purposes if message value is numerical:
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

function user_website($linkplayercreator)
{
    $CI =& get_instance();
    foreach ($CI->Links->read(array(
        'linkplayerdown' => $linkplayercreator,
        'linkplayertype' => 4230, //New Player Created
    ), array(), 1) as $player_created) {
        return $player_created['linkplayerdomain'];
    }
    foreach ($CI->Links->read(array(
        'linkplayercreator' => $linkplayercreator,
    ), array(), 1) as $player_created) {
        return $player_created['linkplayerdomain'];
    }
    return 0;
}


function random_adjective()
{

    $adjectives = array('Amazing', 'Awesome', 'Adventurous', 'Ambitious', 'Adorable', 'Artistic', 'Agile', 'Acrobatic', 'Attractive', 'Alluring', 'Astonishing', 'Authentic', 'Awkward', 'Ancient', 'American', 'Australian', 'Austrian', 'African', 'Asian', 'Brave', 'Beautiful', 'Bright', 'Busy', 'Big', 'Bold', 'Basic', 'Blissful', 'Bouncy', 'Beneficial', 'Bashful', 'Black', 'Brown', 'Burgundy', 'Broad', 'British', 'Belgian', 'Brazilian', 'Creative', 'Confident', 'Cheerful', 'Calm', 'Cute', 'Clever', 'Curious', 'Charming', 'Courageous', 'Clean', 'Cool', 'Considerate', 'Caring', 'Crazy', 'Classic', 'Chic', 'Cloudy', 'Colombian', 'Chinese', 'Delightful', 'Dreamy', 'Daring', 'Dynamic', 'Dark', 'Decent', 'Drastic', 'Defiant', 'Dedicated', 'Deep', 'Desirable', 'Dirty', 'Dramatic', 'Dizzy', 'Demanding', 'Diligent', 'Dutch', 'Danish', 'Delicious', 'Dazzling', 'Easy', 'Elegant', 'Enthusiastic', 'Eager', 'Efficient', 'Empathetic', 'Excellent', 'Exciting', 'Effective', 'Extravagant', 'Entertaining', 'Exotic', 'Expressive', 'Expensive', 'Elaborate', 'European', 'Egyptian', 'Eastern', 'Elderly', 'Educational', 'Fantastic', 'Fabulous', 'Friendly', 'Funny', 'Fearless', 'Fresh', 'Fascinating', 'Fluffy', 'Fierce', 'Fine', 'Free', 'Frugal', 'French', 'Futuristic', 'Fast', 'Flat', 'Famous', 'Flawless', 'Formal', 'Frizzy', 'Gorgeous', 'Great', 'Gentle', 'Generous', 'Gracious', 'Genuine', 'Glorious', 'Graceful', 'Golden', 'Grand', 'Green', 'Growing', 'Groovy', 'Greek', 'Grumpy', 'Gothic', 'Gargantuan', 'Gigantic', 'German', 'Georgian', 'Happy', 'Hot', 'Humble', 'Honest', 'Healthy', 'Heavy', 'Handsome', 'High', 'Helpful', 'Hilarious', 'Heavenly', 'Harmonious', 'Hardworking', 'Historical', 'Heartfelt', 'Homey', 'Hungry', 'Huge', 'Hispanic', 'Hindu', 'Interesting', 'Intelligent', 'Incredible', 'Inspiring', 'Impressive', 'Imaginative', 'Inquisitive', 'Iconic', 'Indigo', 'Industrious', 'Inevitable', 'Inexpensive', 'Incomparable', 'Idealistic', 'Illustrious', 'Indian', 'Italian', 'Irresistible', 'Irrelevant', 'Icy', 'Joyful', 'Jolly', 'Jovial', 'Jaunty', 'Jaded', 'Jazzy', 'Jumpy', 'Juicy', 'Judgmental', 'Jumbled', 'Japanese', 'Javanese', 'Jewish', 'Jittery', 'Junior', 'Justified', 'Jubilant', 'Jade', 'Jumbo', 'Joint', 'Kind', 'Knowledgeable', 'Keen', 'Kooky', 'Knotty', 'Kinetic', 'Known', 'Keen-eyed', 'Knightly', 'Keen-witted', 'Kempt', 'Knockout', 'Knackered', 'Kindhearted', 'Kenyan', 'Kiddy', 'Knotted', 'Kyrgyzstani', 'Kindred', 'Kentuckian', 'Loud', 'Lively', 'Lazy', 'Loyal', 'Long', 'Lonely', 'Lovely', 'Large', 'Light', 'Low', 'Luxurious', 'Lasting', 'Literal', 'Learned', 'Lucky', 'Magnificent', 'Mysterious', 'Modern', 'Moody', 'Musical', 'Mighty', 'Masculine', 'Mesmerizing', 'Mindful', 'Memorable', 'Multicultural', 'Moral', 'Majestic', 'Mischievous', 'Mouthwatering', 'Mellow', 'Modest', 'Magical', 'Melodic', 'Mature', 'Nervous', 'Natural', 'New', 'Nice', 'Noble', 'Naughty', 'Neat', 'Nonchalant', 'Noisy', 'Narrow', 'Nostalgic', 'Needy', 'Negative', 'Nutritious', 'Nonstop', 'Noteworthy', 'Numerous', 'Notable', 'Nurturing', 'Nifty', 'Obvious', 'Original', 'Optimistic', 'Ordinary', 'Official', 'Outstanding', 'Open', 'Organic', 'Odd', 'Observant', 'Obedient', 'Opaque', 'Obsolete', 'Offensive', 'Oily', 'Old-fashioned', 'Ornate', 'Onyx', 'Overwhelming', 'Oceanic', 'Perfect', 'Patient', 'Positive', 'Powerful', 'Popular', 'Polite', 'Peaceful', 'Playful', 'Pleasant', 'Precious', 'Practical', 'Private', 'Proud', 'Profound', 'Pretty', 'Painful', 'Priceless', 'Puzzled', 'Persistent', 'Passionate', 'Quaint', 'Quick', 'Quiet', 'Quirky', 'Quizzical', 'Queenly', 'Quivering', 'Quotable', 'Qualified', 'Quantifiable', 'Questionable', 'Quarrelsome', 'Queasy', 'Quenched', 'Quack', 'Quilted', 'Quizzing', 'Reliable', 'Responsible', 'Romantic', 'Rich', 'Rude', 'Real', 'Radiant', 'Royal', 'Rough', 'Respectful', 'Red', 'Rational', 'Rustic', 'Radiant', 'Robust', 'Rare', 'Resilient', 'Reckless', 'Ready', 'Rambunctious', 'Strong', 'Smart', 'Serious', 'Sad', 'Special', 'Simple', 'Super', 'Sincere', 'Safe', 'Stunning', 'Sweet', 'Shy', 'Successful', 'Satisfied', 'Shiny', 'Silent', 'Sparkling', 'Strong-willed', 'Scary', 'Surprised', 'Tall', 'Talkative', 'Tasty', 'Tender', 'Terrific', 'Terrible', 'Thoughtful', 'Thrifty', 'Timely', 'Tough', 'Traditional', 'Trustworthy', 'Tremendous', 'Tricky', 'Tolerant', 'Tenacious', 'Tiny', 'Tired', 'Top', 'Trembling', 'Ugly', 'Ultimate', 'Unbelievable', 'Uncertain', 'Uncommon', 'Unconditional', 'Unconscious', 'Understanding', 'Unforgettable', 'Unhappy', 'Unique', 'United', 'Universal', 'Unusual', 'Upbeat', 'Uplifting', 'Urbane', 'Urgent', 'Useful', 'Useless', 'Valuable', 'Vague', 'Valid', 'Vast', 'Various', 'Vengeful', 'Vibrant', 'Victorious', 'Vigorous', 'Villainous', 'Vital', 'Vivacious', 'Vocal', 'Volatile', 'Volcanic', 'Voracious', 'Vulnerable', 'Vicious', 'Velvet', 'Verbal', 'Warm', 'Wild', 'Witty', 'Wise', 'Wonderful', 'Worried', 'Wondrous', 'Wealthy', 'Whimsical', 'Wicked', 'Wide', 'Wavy', 'Watery', 'Weighty', 'Wooden', 'Weak', 'Wary', 'Winning', 'Well-groomed', 'Wholesome', 'Xeric', 'Xerophytic', 'Xerotic', 'Xyloid', 'Xylonic', 'Xylophagous', 'Xanthic', 'Xanthous', 'Xerarch', 'Xylotomous', 'Xerographic', 'Xenial', 'Xenogenetic', 'Xenolithic', 'Xylophilous', 'Yellow', 'Young', 'Yielding', 'Yearly', 'Yummy', 'Yawning', 'Yucky', 'Yearning', 'Yeasty', 'Yielding', 'Youthful', 'Yare', 'Yclept', 'Yellowish', 'Yearlong', 'Youth', 'Zealous', 'Zesty', 'Zigzag', 'Zillionth', 'Zinciferous', 'Zingy', 'Zippered', 'Zippy', 'Zoological', 'Zonal', 'Ambitious', 'Amiable', 'Analytical', 'Assertive', 'Authentic', 'Bold', 'Calm', 'Charismatic', 'Charming', 'Cheerful', 'Compassionate', 'Confident', 'Conscientious', 'Considerate', 'Creative', 'Curious', 'Dependable', 'Diligent', 'Disciplined', 'Easygoing', 'Empathetic', 'Enthusiastic', 'Extraverted', 'Flexible', 'Friendly', 'Generous', 'Genuine', 'Gracious', 'Hardworking', 'Honest', 'Humble', 'Independent', 'Innovative', 'Insightful', 'Intelligent', 'Kind', 'Logical', 'Loyal', 'Open-minded', 'Optimistic', 'Outgoing', 'Passionate', 'Patient', 'Persistent', 'Practical', 'Rational', 'Reliable', 'Resourceful', 'Responsible', 'Self-confident', 'Happy', 'Sad', 'Angry', 'Fearful', 'Anxious', 'Excited', 'Frustrated', 'Nostalgic', 'Hopeful', 'Envious', 'Jealous', 'Empathetic', 'Curious', 'Surprised', 'Disappointed', 'Grateful', 'Confused', 'Content', 'Lonely', 'Loved', 'Joyful', 'Melancholic', 'Irritated', 'Apprehensive', 'Restless', 'Ecstatic', 'Distraught', 'Panicked', 'Annoyed', 'Numb', 'Scared', 'Enraged', 'Heartbroken', 'Amused', 'Overwhelmed', 'Grateful', 'Conflicted', 'Peaceful', 'Devastated', 'Empowered');

    return $adjectives[array_rand($adjectives)];
}


function dispatch_sms($to_phone, $single_message, $playerid = 0, $x_data = array(), $template_ideaid = 0, $linkplayerdomain = 0, $log_tr = true, $demo_only = false)
{

    $CI =& get_instance();
    $twilio_account_sid = website_setting(30859);
    $twilio_auth_token = website_setting(30860);
    $twilio_from_number = website_setting(27673);
    if (!$twilio_from_number || !$twilio_auth_token || !$twilio_account_sid) {

        //No way to send an SMS:
        if ($log_tr) {
            log_error('dispatch_sms() missing either: ' . $twilio_account_sid . ' / ' . $twilio_auth_token . ' / ' . $twilio_from_number, array(
                'linkplayerdown' => $playerid,
                'linkplayercreator' => $playerid,
                'linkplayerdomain' => $linkplayerdomain,
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

    //Log Link:
    if ($log_tr) {

        $target_player = ($sms_success ? 27676 : 27678);
        $player_session = player_session();
        $playerid = ($playerid > 0 ? $playerid : ($player_session ? $player_session['playerid'] : 14068));
        if ($template_ideaid && count($CI->Ideas->read(array(
                'ideaid' => $template_ideaid,
            )))) {
            foreach ($CI->Ideas->read(array(
                'ideaid' => $template_ideaid,
            )) as $idea_template) {
                $CI->Links->idea_discovered($target_player, $playerid, 0, $idea_template, array(), array(
                    'linktext' => $single_message,
                ));
            }
        } elseif ($playerid > 0) {

            $CI->Links->create(array_merge($x_data, array(
                'linkplayertype' => 44179, //Triggered
                'linkplayerup' => $target_player,
                'linkplayerdown' => $playerid,
                'linkplayercreator' => $playerid,
                'linktext' => $single_message,
                'linkidearight' => $template_ideaid,
            )));
        }


    }

    return true;

}

function dispatch_email($to_emails, $subject, $email_body, $playerid = 0, $x_data = array(), $template_ideaid = 0, $linkplayerdomain = 0, $log_tr = true, $demo_only = false)
{

    $CI =& get_instance();
    $domain_name = get_domain('m__title', $playerid, $linkplayerdomain);
    $domain_email = website_setting(28614, $playerid, $linkplayerdomain);

    if (!strlen($domain_email)) {
        $domain_name = 'MENCH';
        $domain_name = 'support@mench.com';
        log_error('Domain email is missing! (' . $domain_name . ') (' . $domain_email . ') (' . join(' & ', $to_emails) . ')', array(
            'linkplayerdown' => $playerid,
        ));
    }

    $email_domain = '"' . $domain_name . '" <' . $domain_email . '>';
    $name = 'New User';
    $ReplyToAddresses = array($email_domain);

    if ($playerid > 0) {

        $es = $CI->Players->read(array(
            'playerid' => $playerid,
        ));
        if (count($es)) {

            $name = $es[0]['playertext'];

            //Also fetch email for this user to populate the reply to:
            $fetch_emails = $CI->Links->read(array(
                'linkplayerup' => 3288, //Email
                'linkplayerdown' => $playerid,
                'linkplayertype IN (' . join(',', $CI->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
            ));
            if (count($fetch_emails) && filter_var($fetch_emails[0]['linktext'], FILTER_VALIDATE_EMAIL)) {
                array_push($ReplyToAddresses, trim($fetch_emails[0]['linktext']));
            }
        }
    }

    //Email has no word limit to add header & footer:
    $players___6287 = $CI->config->item('players___6287'); //APP
    $base_domain = 'https://' . get_domain('m__message', $playerid, $linkplayerdomain);

    $email_message = '<div class="line">' . randomize_text(29749) . ' ' . $name . ' ' . randomize_text(29750) . '</div>';
    $email_message .= $email_body . "\n";
    $email_message .= '<div class="line">' . randomize_text(12691) . '</div>';
    $email_message .= '<div class="line">' . get_domain('m__title', $playerid, $linkplayerdomain) . '</div>';


    if ($playerid > 0 && count($es) && (!$template_ideaid || !count($CI->Links->read(array(
                'linkplayertype IN (' . join(',', $CI->config->item('playerids___42256')) . ')' => null, //Writes
                'linkplayerup' => 31779, //Mandatory Emails
                'linkidearight' => $template_ideaid,
            ))))) {
        //User specific notifications:
        $email_message .= '<div class="line"><a href="' . $base_domain . view_app_link(28904) . '?playerhandle=' . $es[0]['playerhandle'] . '&time=' . time() . '&hash=' . view_hash(time() . $es[0]['playerhandle']) . '" style="font-size:13px;">' . $players___6287[28904]['m__title'] . '</a></div>';
    }


    $general_style = 'width:100%; max-width:610px; font-size:16px; margin-bottom:8px; line-height:134%;';

    //Email HTML Transformations:
    $email_message = str_replace('>Show more<', '><', $email_message); //Hide the show more content if any
    $email_message = str_replace('<img ', '<img style="' . $general_style . '" ', $email_message);
    $email_message = str_replace('<div class="line', '<div style="' . $general_style . '" class="line', $email_message);
    $email_message = str_replace("\n", '<div style="padding:3px 0 0; line-height:100%;">&nbsp;</div>', $email_message);
    $email_message = str_replace('href="/', 'style="display:inline-block;" href="' . $base_domain . '/', $email_message);

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

    //Log Link:
    if ($log_tr) {

        $player_session = player_session();
        $playerid = ($playerid > 0 ? $playerid : ($player_session ? $player_session['playerid'] : 14068));
        if ($template_ideaid && count($CI->Ideas->read(array(
                'ideaid' => $template_ideaid,
            )))) {
            foreach ($CI->Ideas->read(array(
                'ideaid' => $template_ideaid,
            )) as $idea_template) {
                $CI->Links->idea_discovered(29399, $playerid, 0, $idea_template, array(), array(
                    'linktext' => $subject . "\n" . $email_message,
                ));
            }
        } elseif ($playerid > 0) {

            $CI->Links->create(array_merge($x_data, array(
                'linkplayertype' => 44179, //Triggered
                'linkplayerup' => 29399,
                'linkplayerdown' => $playerid,
                'linkplayercreator' => $playerid,
                'linktext' => $subject . "\n" . $email_message,
                'linkidearight' => $template_ideaid,
            )));
        }

        //Can we also mark the discovery as complete?
        if ($playerid && isset($x_data['linkidealeft']) && $x_data['linkidealeft'] > 0 && isset($x_data['linkidearight'])) {
            foreach ($CI->Ideas->read(array(
                'ideaid' => $x_data['linkidealeft'],
            )) as $email_i) {
                $CI->Links->idea_discovered(idea_type_discovery($email_i), $playerid, $x_data['linkidearight'], $email_i, $x_data);
            }
        }

    }


    return $response;

}


function website_setting($setting_id = 0, $initiator_playerid = 0, $linkplayerdomain = 0, $force_website = true)
{

    $CI =& get_instance();
    $player_id = 0; //Assume no domain unless found below

    if (!$initiator_playerid) {
        $player_session = player_session();
        if ($player_session && $player_session['playerid'] > 0) {
            $initiator_playerid = $player_session['playerid'];
        }
    }

    if ($linkplayerdomain && $force_website) {

        $player_id = $linkplayerdomain;

    } else {

        $server_name = get_server('SERVER_NAME');
        if (strlen($server_name)) {
            foreach ($CI->config->item('players___14870') as $linkplayertype => $m) {
                if (substr_count($m['m__message'], $server_name) == 1) {
                    $player_id = $linkplayertype;
                    break;
                }
            }
        }

        $player_id = ($player_id ? $player_id : ($linkplayerdomain > 0 ? $linkplayerdomain : 2738 /* Mench */));

    }


    if (!$setting_id) {
        return $player_id;
    }


    $players___domain_sett = $CI->config->item('players___' . $setting_id); //DOMAINS

    if (!isset($players___domain_sett[$player_id]) || !strlen($players___domain_sett[$player_id]['m__message'])) {
        $target_return = (in_array($setting_id, $CI->config->item('playerids___6404')) ? view_memory(6404, $setting_id) : false);
    } else {
        $target_return = $players___domain_sett[$player_id]['m__message'];
    }

    return $target_return;

}


function get_domain($var_field, $initiator_playerid = 0, $linkplayerdomain = 0, $force_website = true)
{
    $CI =& get_instance();
    $domain_e = website_setting(0, $initiator_playerid, $linkplayerdomain, $force_website);
    $players___14870 = $CI->config->item('players___14870'); //DOMAINS
    return $players___14870[$domain_e][$var_field];
}


function player_access($playerhandle = null, $playerid = 0, $e = false, $replacement_playerid = false)
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
    $player_session = player_session();
    if (!$replacement_playerid && player_session(10939)) {
        return 3;
    } elseif (!$replacement_playerid && $player_session && ($playerhandle == $player_session['playerhandle'] || $playerid == $player_session['playerid'])) {
        return 3;
    }

    if (strlen($playerhandle)) {
        $filters['LOWER(playerhandle)'] = strtolower($playerhandle);
    } elseif (intval($playerid)) {
        $filters['playerid'] = $playerid;
    } elseif (!$e || (!$player_session && !$replacement_playerid)) {
        return 0;
    }

    if (!$e) {
        //Check privacy first:
        foreach ($CI->Players->read($filters) as $match_e) {
            $e = $match_e;
            break;
        }
    }

    $is_public = true;
    $is_author = false;
    if ($player_session) {
        $is_author = count($CI->Links->read(array(
            'linkplayertype IN (' . join(',', $CI->config->item('playerids___13548')) . ')' => null, //AUTHORED SOURCES
            'linkplayerup' => ($replacement_playerid > 0 ? $replacement_playerid : $player_session['playerid']),
            'linkplayerdown' => $e['playerid'],
        )));
    }

    return ($is_author ? 3 : ($is_public ? 2 : 1));

}


function idea_access($ideahashtag = null, $ideaid = 0, $i = false, $replacement_playerid = false, $idea_list_config = array(), $is_cahce = false)
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
    $player_session = player_session();
    $discovery_mode = ($replacement_playerid > 0 ? true : ((isset($_POST['js_request_uri']) && substr_count($_POST['js_request_uri'], '/') == 2) || (!isset($_POST['js_request_uri']) && strlen($CI->uri->segment(2)) && !array_key_exists(strtolower($CI->uri->segment(1)), $CI->config->item('handlplayers___6287'))) ? true : false));

    if ($is_cahce) {
        return 1;
    }

    if (!$discovery_mode && player_session(12700)) {
        return 3;
    }


    if (!$i) {
        if (strlen($ideahashtag)) {
            $filters['LOWER(ideahashtag)'] = strtolower($ideahashtag);
        } elseif (intval($ideaid)) {
            $filters['ideaid'] = $ideaid;
        } elseif (!$i) {
            return 0;
        }
        //Check privacy first:
        foreach ($CI->Ideas->read($filters) as $match_i) {
            $i = $match_i;
            break;
        }
    }

    $linkplayercreator = ($replacement_playerid > 0 ? $replacement_playerid : ($player_session ? $player_session['playerid'] : 0));
    $is_author = false;
    if (!$discovery_mode && $linkplayercreator) {
        $is_author = count($CI->Links->read(array( //IDEA SOURCE
            'linkplayertype IN (' . join(',', $CI->config->item('playerids___31919')) . ')' => null, //IDEA AUTHOR
            'linkplayerup' => $linkplayercreator,
            'linkidearight' => $i['ideaid'],
        )));
    }

    if (!$discovery_mode && $is_author) {

        //Authors can always edit:
        return 3;

    } elseif (!$discovery_mode && count($CI->Links->read(array(
            'linkplayertype IN (' . join(',', $CI->config->item('playerids___42953')) . ')' => null, //Mentioned Players
            'linkplayerup' => $linkplayercreator,
            'linkidearight' => $i['ideaid'],
        )))) {

        //Mentioned can always reply:
        return 2;

    } else {


        //Inventory Limits:
        if (!count($idea_list_config) && idea_spots_remaining($ideaid) == 0) {
            return 0;
        }


        // IDEA RELATION CHECK:
        $idea_list_config = idea_list_config($ideaid);


        //If idea_discovered All
        if (count($idea_list_config[44161])) {
            $the_counter = 0;
            if ($linkplayercreator) {
                foreach ($idea_list_config[44161] as $focusideaid) {
                    if (count($CI->Links->read(array(
                        'linkplayercreator' => $linkplayercreator,
                        'linkidealeft' => $focusideaid,
                        'linkplayertype IN (' . join(',', $CI->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                    )))) {
                        $the_counter++;
                    }
                }
            }
            if ($the_counter < count($idea_list_config[44161])) {
                return 0;
            }
        }

        //If idea_discovered Any
        if (count($idea_list_config[40791])) {
            $the_counter = 0;
            if ($linkplayercreator) {
                foreach ($idea_list_config[40791] as $focusideaid) {
                    if (count($CI->Links->read(array(
                        'linkplayercreator' => $linkplayercreator,
                        'linkidealeft' => $focusideaid,
                        'linkplayertype IN (' . join(',', $CI->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                    )))) {
                        $the_counter++;
                        break;
                    }
                }
            }
            if (!$the_counter) {
                return 0;
            }
        }


        //If Not idea_discovered All
        if (count($idea_list_config[44162])) {
            $the_counter = 0;
            if ($linkplayercreator) {
                foreach ($idea_list_config[44162] as $focusideaid) {
                    if (count($CI->Links->read(array(
                        'linkplayercreator' => $linkplayercreator,
                        'linkidealeft' => $focusideaid,
                        'linkplayertype IN (' . join(',', $CI->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                    )))) {
                        $the_counter++;
                    }
                }
                if ($the_counter >= count($idea_list_config[44162])) {
                    return 0;
                }
            } else {
                return 0;
            }
        }


        //If Not idea_discovered Any
        if (count($idea_list_config[40793])) {
            $the_counter = 0;
            if ($linkplayercreator) {
                foreach ($idea_list_config[40793] as $focusideaid) {
                    if (count($CI->Links->read(array(
                        'linkplayercreator' => $linkplayercreator,
                        'linkidealeft' => $focusideaid,
                        'linkplayertype IN (' . join(',', $CI->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                    )))) {
                        $the_counter++;
                        break;
                    }
                }
            } else {
                return 0;
            }
            if ($the_counter > 0) {
                return 0;
            }
        }


        // SOURCE RELATION CHECK:


        //Include If Has ANY
        if (count($idea_list_config[27984])) {
            $the_counter = 0;
            if ($linkplayercreator) {
                foreach ($idea_list_config[27984] as $focusplayerid) {
                    if ((($linkplayercreator && $linkplayercreator == $focusplayerid) || count($CI->Links->read(array(
                            'linkplayertype IN (' . join(',', $CI->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
                            'linkplayerup' => $focusplayerid,
                            'linkplayerdown' => $linkplayercreator,
                        ))))) {
                        $the_counter++;
                        break;
                    }
                }
            }
            if (!$the_counter) {
                return 0;
            }
        }


        //Include If Has ALL
        if (count($idea_list_config[43513])) {
            $the_counter = 0;
            if ($linkplayercreator) {
                foreach ($idea_list_config[43513] as $focusplayerid) {
                    if ((($linkplayercreator && $linkplayercreator == $focusplayerid) || count($CI->Links->read(array(
                            'linkplayertype IN (' . join(',', $CI->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
                            'linkplayerup' => $focusplayerid,
                            'linkplayerdown' => $linkplayercreator,
                        ))))) {
                        $the_counter++;
                    }
                }
            }
            if ($the_counter < count($idea_list_config[43513])) {
                return 0;
            }
        }


        //Exclude If Has ANY
        if (count($idea_list_config[43514])) {
            $the_counter = 0;
            if ($linkplayercreator) {
                foreach ($idea_list_config[43514] as $focusplayerid) {
                    if (($linkplayercreator == $focusplayerid) || count($CI->Links->read(array(
                            'linkplayertype IN (' . join(',', $CI->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
                            'linkplayerup' => $focusplayerid,
                            'linkplayerdown' => $linkplayercreator,
                        )))) {
                        //Found an exclusion, so skip this:
                        $the_counter++;
                        break;
                    }
                }
            }
            if ($the_counter > 0) {
                return 0;
            }
        }

        //Exclude If Has ALL
        if (count($idea_list_config[26600])) {
            $the_counter = 0;
            if ($linkplayercreator) {
                foreach ($idea_list_config[26600] as $focusplayerid) {
                    if (($linkplayercreator == $focusplayerid) || count($CI->Links->read(array(
                            'linkplayertype IN (' . join(',', $CI->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
                            'linkplayerup' => $focusplayerid,
                            'linkplayerdown' => $linkplayercreator,
                        )))) {
                        //Found an exclusion, so skip this:
                        $the_counter++;
                    }
                }
            }
            if ($the_counter == count($idea_list_config[26600])) {
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

function idea_started($playerid, $ideahashtag)
{
    $CI =& get_instance();
    return count($CI->Links->read(array(
        'linkidealeft = linkidearight' => NULL,
        'LOWER(ideahashtag)' => strtolower($ideahashtag),
        'linkplayercreator' => $playerid,
        'linkplayertype IN (' . join(',', $CI->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
    ), array('linkidearight')));
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

    if ($focus__node && !in_array($focus__node, $CI->config->item('playerids___12761'))) {
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


    $players___4737 = $CI->config->item('players___4737'); //Idea Status

    //Define the support objects indexed on algolia:
    $s__id = intval($s__id);
    $limits = array();


    if ($focus__node == 12273) {
        $focus_field_id = 'ideaid';
    } elseif ($focus__node == 12274) {
        $focus_field_id = 'playerid';
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

        //Do both ideas and Players:
        $fetch_objects = $CI->config->item('playerids___12761');

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
                $filters['ideaid'] = $s__id;
            }

            $db_rows[$loop_obj] = $CI->Ideas->read($filters, 0);

        } elseif ($loop_obj == 12274) {

            //SOURCES
            if ($s__id) {
                $filters['playerid'] = $s__id;
            }

            $db_rows[$loop_obj] = $CI->Players->read($filters, 0);

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
                    idea_number_calculator($s);
                } elseif ($focus__node == 12274) {
                    player_number_calculator($s);
                }
            }


            //Attempt to fetch Algolia object ID from object Metadata:
            if ($focus__node) {

                $external_name = ($focus__node == 12273 ? 'ideaexternal' : 'playerexternal');

                if (intval($s[$external_name]) > 0) {
                    //We found it! Let's just update existing algolia record
                    $export_row['objectID'] = intval($s[$external_name]);
                }

            } else {

                //Clear possible metadata algolia ID's that have been cached:
                if ($loop_obj == 12273) {
                    $CI->Ideas->update($s['ideaid'], array(
                        'ideaexternal' => 0,
                    ));
                } elseif ($loop_obj == 12274) {
                    $CI->Players->update($s['playerid'], array(
                        'playerexternal' => 0,
                    ));
                }

            }

            //To hold followings info
            $export_row['_tags'] = array();
            $export_row['s__keywords'] = '';

            //Now build object-specific index:
            if ($loop_obj == 12273) {

                //IDEAS
                //See if this idea has a time-range:
                $export_row['s__type'] = $loop_obj;
                $export_row['s__id'] = intval($s['ideaid']);
                $export_row['s__handle'] = $s['ideahashtag'];
                $export_row['s__url'] = view_memory(42903, 33286) . $s['ideahashtag']; //Default to idea, forward to discovery is lacking superpowers
                $export_row['s__cover'] = '';
                $export_row['s__title'] = $s['ideatext'];
                $export_row['s__cache'] = $s['ideacache'];
                $export_row['s__weight'] = intval($s['ideanumber']);

                if (idea_is_startable($s)) {
                    array_push($export_row['_tags'], 'public_index');
                }

                //Top/Bottom Idea Keywords
                foreach ($CI->Links->read(array(
                    'linkplayertype IN (' . join(',', $CI->config->item('playerids___42345')) . ')' => null, //Active Sequence 2-Ways
                    'linkidealeft' => $s['ideaid'],
                ), array('linkidearight'), 0, 0, array('linknumber' => 'ASC')) as $i) {
                    $export_row['s__keywords'] .= $i['ideatext'] . ' ';
                }
                foreach ($CI->Links->read(array(
                    'linkplayertype IN (' . join(',', $CI->config->item('playerids___42345')) . ')' => null, //Active Sequence 2-Ways
                    'linkidearight' => $s['ideaid'],
                ), array('linkidealeft'), 0, 0, array('linknumber' => 'ASC')) as $i) {
                    $export_row['s__keywords'] .= $i['ideatext'] . ' ';
                }

                //Idea Players Keywords
                foreach ($CI->Links->read(array(
                    'linkplayertype IN (' . join(',', $CI->config->item('playerids___33602')) . ')' => null, //Idea/Player Links Active
                    'linkidearight' => $s['ideaid'],
                ), array('linkplayerup'), 0) as $x) {

                    //Authored?
                    $is_author = in_array($x['linkplayertype'], $CI->config->item('playerids___31919'));
                    if ($is_author) {
                        array_push($export_row['_tags'], 'z_' . $x['playerid']);
                    }

                    //Keywords?
                    if ($is_author || strlen($x['linktext'])) {
                        $export_row['s__keywords'] .= $x['playertext'] . ' ' . (strlen($x['linktext']) ? $x['linktext'] . ' ' : '');
                    }

                }

            } elseif ($loop_obj == 12274) {

                //SOURCES
                $export_row['s__type'] = $loop_obj;
                $export_row['s__id'] = intval($s['playerid']);
                $export_row['s__handle'] = $s['playerhandle'];
                $export_row['s__url'] = view_memory(42903, 42902) . $s['playerhandle'];
                $export_row['s__cover'] = $s['playercover'];
                $export_row['s__title'] = $s['playertext'];
                $export_row['s__cache'] = '';
                $export_row['s__weight'] = intval($s['playernumber']);

                //Is this an image?
                if (strlen($s['playercover'])) {
                    array_push($export_row['_tags'], 'has_image');
                }

                array_push($export_row['_tags'], 'public_index');

                //Fetch Following:
                foreach ($CI->Links->read(array(
                    'linkplayertype IN (' . join(',', $CI->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
                    'linkplayerdown' => $s['playerid'], //This follower Player
                ), array('linkplayerup'), 0, 0, array('playertext' => 'DESC')) as $x) {

                    //Add tags:
                    array_push($export_row['_tags'], 'z_' . $x['playerid']);

                    //Add Keywords:
                    $export_row['s__keywords'] .= $x['playertext'] . (strlen($x['linktext']) ? ' ' . $x['linktext'] : '') . ' ';

                }

                //Append Discovery Written Responses to Keywords
                foreach ($CI->Links->read(array(
                    'linkplayertype IN (' . join(',', $CI->config->item('playerids___29133')) . ')' => null, //Written Responses
                    'linkplayercreator' => $s['playerid'], //This follower Player
                ), array('linkplayercreator'), 0, 0, array('linktime' => 'DESC')) as $x) {
                    $export_row['s__keywords'] .= $x['linktext'] . ' ';
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
                        $CI->Ideas->update($all_db_rows[$key][$focus_field_id], array(
                            'ideaexternal' => $algolia_id,
                        ));
                    } elseif ($focus__node == 12274) {
                        $CI->Players->update($all_db_rows[$key][$focus_field_id], array(
                            'playerexternal' => $algolia_id,
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

                if (isset($all_db_rows[$key]['ideaid'])) {
                    $CI->Ideas->update($all_db_rows[$key][(isset($all_db_rows[$key]['ideaid']) ? 'ideaid' : 'playerid')], array(
                        'ideaexternal' => intval($algolia_id),
                    ));
                } else {
                    $CI->Players->update($all_db_rows[$key][(isset($all_db_rows[$key]['ideaid']) ? 'ideaid' : 'playerid')], array(
                        'playerexternal' => intval($algolia_id),
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


function idea_creation_time($ideaid)
{
    $CI =& get_instance();
    foreach ($CI->Links->read(array(
        'linkid' => $ideaid,
    )) as $x) {
        return $x['linktime'];
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


function link_view($x)
{

    $CI =& get_instance();
    $ui = '<tr width="100%">';
    foreach ($CI->config->item('players___4341') as $playerid => $m) {

        if (in_array(6160, $m['m__following'])) {

            //SOURCE
            $ui .= '<td style="width:25px !important;">';
            $ui .= '<div style="width:25px !important; overflow:hidden;">';
            if (isset($x[$m['m__handle']]) && intval($x[$m['m__handle']]) > 0) {
                foreach ($CI->Players->read(array('playerid' => $x[$m['m__handle']])) as $focus_e) {
                    $ui .= '<a href="' . view_memory(42903, 42902) . $focus_e['playerhandle'] . '" target="_blank" data-toggle="tooltip" title="'.$focus_e['playertext'].'" class="icon-block-sm">' . view_cover($focus_e['playercover'], '<i class="far fa-at"></i>') . '</a>';
                }
            }
            $ui .= '</div>';
            $ui .= '</td>';

        } elseif (in_array(6202, $m['m__following'])) {

            //IDEA
            $ui .= '<td style="width:89px !important;">';
            $ui .= '<div style="width:89px !important; overflow:hidden;">';
            if (isset($x[$m['m__handle']]) && intval($x[$m['m__handle']]) > 0) {
                foreach ($CI->Ideas->read(array('ideaid' => $x[$m['m__handle']])) as $focus_i) {
                    $ui .= '<a href="' . view_memory(42903, 33286) . $focus_i['ideahashtag'] . '" data-toggle="popover">#' . $focus_i['ideahashtag'] . '</a>';
                }
            }
            $ui .= '</div>';
            $ui .= '</td>';

        } elseif ($playerid==4367) {

            //Link

            //Determine link group:
            $playerhandle_sign = '';
            if(in_array($x['linkplayertype'], array(4250,4251))){
                $players___4593 = $CI->config->item('players___4593'); //Link Type
                $playerhandle_sign = '<span class="group_sign" title="'.$players___4593[$x['linkplayertype']]['m__title'].'">'.$players___4593[$x['linkplayertype']]['m__cover'].'</span>';
            } else {
                foreach($CI->config->item('players___31770') as $groupid => $groupm) {
                    if(in_array($x['linkplayertype'], $CI->config->item('playerids___'.$groupid))){
                        $playerhandle_sign = '<span class="group_sign" title="'.$groupm['m__title'].'">'.$groupm['m__cover'].'</span>';
                        break;
                    }
                }
            }

            $ui .= '<td style="width:72px !important;">';
            $ui .= '<div style="width:72px !important; overflow:hidden;">';
            if($x[$m['m__handle']]>0){
                $ui .= '<a href="' . view_app_link(4341) . '?linkid=' . $x[$m['m__handle']] . '" target="_blank">' . $playerhandle_sign.$x[$m['m__handle']] . '</a>';
            }
            $ui .= '</div>';
            $ui .= '</td>';

        } elseif ($playerid==44395) {

            //Void:
            $ui .= '<td style="width:60px !important;">';
            if($x[$m['m__handle']]>0){
                $ui .= '<a href="' . view_app_link(4341) . '?linkid=' . $x[$m['m__handle']] . '" target="_blank">' .$x[$m['m__handle']] . '</a>';
            }
            $ui .= '</td>';

        } elseif ($playerid == 4362) {

            //TIME
            $ui .= '<td style="width:45px !important;">';
            $ui .= '<div style="width:45px !important; overflow:hidden;">';
            $ui .= '<span data-toggle="tooltip" data-placement="top" title="' . $x['linktime'] . ' PST">' . view_time_difference($x['linktime'], true) . '</span>';
            $ui .= '</div>';
            $ui .= '</td>';

        } elseif ($playerid == 4370) {

            //Number
            $ui .= '<td style="width:60px !important;">';
            if($x['linknumber']>0){
                $ui .= $x['linknumber'];
            }
            $ui .= '</td>';

        } elseif ($playerid == 4372) {

            //Text
            $ui .= '<td>';
            $ui .=  (strip_tags($x['linktext']) == $x['linktext'] || strlen(strip_tags($x['linktext'])) < view_memory(6404, 6197) ? $x['linktext'] : '<span class="hidden html_message_' . $x['linkid'] . '">' . $x['linktext'] . '</span><a class="html_message_' . $x['linkid'] . '" href="javascript:void(0);" onclick="$(\'.html_message_' . $x['linkid'] . '\').toggleClass(\'hidden\');">View HTML Message</a>');
            $ui .= '</td>';

        }
    }

    $ui .= '</tr>';

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
    if ($micro) {
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


    foreach ($time_units as $unit => $period) {
        if ($time < $unit && $unit > 1) continue;
        $numberOfUnits = number_format(($time / $unit), 0);
        if ($numberOfUnits < 1 && $unit == 1) {
            $numberOfUnits = 1; //Change "0 seconds" to "1 second"
        }

        return $numberOfUnits . ($micro ? '' : ' ') . $period . (($numberOfUnits > 1 && !$micro) ? 's' : '');
    }
}

function view_app_link($app_id)
{
    return view_memory(42903, 6287) . view_memory(6287, $app_id, 'm__handle');
}

function view_memory($following, $follower, $filed = 'm__message')
{
    $CI =& get_instance();
    $memory_tree = @$CI->config->item('players___' . $following);
    if (is_array($memory_tree) && count($memory_tree) && isset($memory_tree[$follower][$filed])) {
        return $memory_tree[$follower][$filed];
    } else {
        return null;
    }
}


function view_cache($following, $playerid, $micro_status = true, $data_placement = 'top', $ideaid = 0)
{

    /*
     *
     * UI for Platform Cache Players
     *
     * */

    $CI =& get_instance();
    $config_array = $CI->config->item('players___' . $following);
    if (!isset($config_array[$playerid])) {
        return false;
    }
    $cache = $config_array[$playerid];
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
        return '<span class="' . ($micro_status ? 'cache_micro_' . $following . '_' . $ideaid : '') . '" ' . ($micro_status && !is_null($data_placement) ? ' title="' . ($micro_status ? $cache['m__title'] : '') . (strlen($cache['m__message']) > 0 ? ($micro_status ? ': ' : '') . $cache['m__message'] : '') . '"' : 'style="cursor:pointer;"') . '>' . $cache['m__cover'] . ' ' . ($micro_status ? '' : $cache['m__title']) . '</span>';
    }
}


function view_card($href, $is_current, $linkplayertype, $o__type, $o__title, $linktext = null)
{
    $CI =& get_instance();
    $players___4593 = $CI->config->item('players___4593');
    return '<a href="' . ($is_current ? 'javascript:alert(\'You are here already!\');' : $href) . '" class="dropdown-item ' . ($is_current ? ' active ' : '') . '">' .
        (in_array($linkplayertype, $CI->config->item('playerids___32172')) ? '<span class="icon-block-xs">' . $players___4593[$linkplayertype]['m__cover'] . '</span>' : '') .
        (strlen($o__type) ? '<span class="icon-block-xs">' . $o__type . '</span>' : '&nbsp;') . //Type or Cover
        $o__title .
        (strlen($linktext) && player_session(12701) ? '<div class="message2">' . strip_tags($linktext) . '</div>' : '') .
        '</a>';
}

function view_more($href, $is_current, $linkplayertype, $o__type, $o__title, $linktext = null)
{
    return '<a href="' . ($is_current ? 'javascript:alert(\'You are here already!\');' : $href) . '" class="dropdown-item ' . ($is_current ? ' active ' : '') . '">' .
        ($linkplayertype ? '<span class="icon-block-xs">' . $linkplayertype . '</span>' : '') .
        (strlen($o__type) ? '<span class="icon-block-xs">' . $o__type . '</span>' : '&nbsp;') . //Type or Cover
        $o__title .
        (strlen($linktext) && player_session(12701) ? '<div class="message2">' . strip_tags($linktext) . '</div>' : '') .
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
    $player_session = player_session();

    if ($log_error) {

        $CI =& get_instance();
        log_message('error', 'MENCH ERROR: ' . $error_message
            . ($player_session ? ' | PLAYER: ' . print_r($player_session, true) : '')
            . ($player_session ? ' | ERROR DATA: ' . print_r($error_data, true) : '')
        );

        $CI->Links->create(array_merge($error_data, array(
            'linkplayerup' => 4246, //Platform Bug Reports
            'linkplayertype' => 44179, //Triggered
            'linktext' => $error_message,
            'linkplayercreator' => (isset($error_data['linkplayercreator']) && $error_data['linkplayercreator'] > 0 ? $error_data['linkplayercreator'] : ($player_session ? $player_session['playerid'] : 0)),
        )));

    }

    return array(
        'status' => 0,
        'message' => $error_message,
        'player_session' => $player_session,
        'error_data' => $error_data,
    );

}


function players_query($linkplayertype, $playerid, $current_page = 0, $append_card_icon = true)
{

    /*
     *
     * Loads Player
     *
     * */

    $CI =& get_instance();
    $first_segment = $CI->uri->segment(1);

    if (!in_array($linkplayertype, $CI->config->item('playerids___4527')) || !is_array($CI->config->item('playerids___' . $linkplayertype)) || !count($CI->config->item('playerids___' . $linkplayertype))) {
        log_error('players_query() @' . $linkplayertype . ' Empty Array in Cache @4527');
        return false;
    }

    if (in_array($linkplayertype, $CI->config->item('playerids___42377'))) {

        //Down Player Link Groups:
        $order_columns = player_sort();
        $joins_objects = array('linkplayerdown');
        $query_filters = array(
            'linkplayerup' => $playerid,
            'linkplayertype IN (' . join(',', $CI->config->item('playerids___' . $linkplayertype)) . ')' => null, //SOURCE LINKS
        );

    } elseif (in_array($linkplayertype, $CI->config->item('playerids___42276'))) {

        //Up Player Link Groups:
        $order_columns = player_sort();
        $joins_objects = array('linkplayerup');
        $query_filters = array(
            'linkplayerdown' => $playerid,
            'linkplayertype IN (' . join(',', $CI->config->item('playerids___' . $linkplayertype)) . ')' => null, //SOURCE LINKS
        );

    } elseif (in_array($linkplayertype, $CI->config->item('playerids___11028'))) {

        //Player Tree
        $order_columns = array('linknumber' => 'ASC', 'linktime' => 'DESC');
        $joins_objects = array('linkplayerdown');
        $query_filters = array(
            'linkplayerup' => $playerid,
            'linkplayertype IN (' . join(',', $CI->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
        );

    } elseif (in_array($linkplayertype, $CI->config->item('playerids___42261'))) {

        //IDEAS
        $query_filters = array(
            'linkplayertype IN (' . join(',', $CI->config->item('playerids___' . $linkplayertype)) . ')' => null,
            'linkplayerup' => $playerid,
        );

        $joins_objects = array('linkidearight');
        $order_columns = idea_sort();

    } elseif (in_array($linkplayertype, $CI->config->item('playerids___12144'))) {

        //Discoveries

        //Determine Sort:
        $order_columns = array();
        /*
        foreach($CI->config->item('players___6255') as $sort_id => $sort) {
            $order_columns['linkplayertype = \''.$sort_id.'\' DESC'] = null;
        }
        */
        $order_columns['linkid'] = 'DESC';

        //DISCOVERIES
        $joins_objects = array('linkidealeft');
        $query_filters = array(
            'linkplayercreator' => $playerid,
            'linkplayertype IN (' . join(',', $CI->config->item('playerids___' . $linkplayertype)) . ')' => null, //DISCOVERY GROUP
        );

    } else {

        return null;

    }


    //Return Results:
    if ($current_page > 0) {

        $limit = view_memory(6404, 11064);
        $query = $CI->Links->read($query_filters, $joins_objects, $limit, ($current_page - 1) * $limit, $order_columns);
        return $query;

    } else {

        $players___11035 = $CI->config->item('players___11035');
        if (!isset($players___11035[$linkplayertype]['m__title'])) {
            log_error('@' . $linkplayertype . ' Missing from Nav @11035', array(
                'linkplayerdown' => $linkplayertype,
            ));
            $players___11035[$linkplayertype] = array(
                'm__title' => '',
                'm__cover' => '',
            );
        }
        $query = $CI->Links->read($query_filters, $joins_objects, 1, 0, array(), 'COUNT(linkid) as totals');
        $count_query = $query[0]['totals'];
        $visual_counter = '<span class="mini-hidden adjust-left">' . view_number($count_query) . '<span>';
        $title_desc = number_format($count_query, 0) . ' ' . $players___11035[$linkplayertype]['m__title'];

        if ($append_card_icon) {

            if (!$count_query) {
                return null;
            }

            $card_icon = '<span class="icon-block-xs">' . $players___11035[$linkplayertype]['m__cover'] . '</span>';

            $ui = '<div class="dropdown inline-block">';
            $ui .= '<button type="button" class="btn no-left-padding no-right-padding loadplayer_cards button_of_' . $playerid . '_' . $linkplayertype . '" id="cardplayer_group_' . $linkplayertype . '_' . $playerid . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" load_linkplayertype="' . $linkplayertype . '" load_playerid="' . $playerid . '" load_counter="' . $count_query . '" load_first_segment="' . $first_segment . '"><span title="' . $title_desc . '" data-toggle="tooltip" data-placement="top">' . $card_icon . $visual_counter . '</span></button>';
            $ui .= '<div class="dropdown-menu dropdown_' . $linkplayertype . ' coinsplayer_' . $playerid . '_' . $linkplayertype . '" aria-labelledby="cardplayer_group_' . $linkplayertype . '_' . $playerid . '">';
            //Menu To be loaded dynamically via AJAX
            $ui .= '</div>';
            $ui .= '</div>';

            return $ui;

        } else {
            return intval($count_query);
        }
    }

}


function ideas_query($linkplayertype, $ideaid, $current_page = 0, $append_card_icon = true, $headline_authors = array())
{

    /*
     *
     * Loads Idea
     *
     * */

    $CI =& get_instance();
    $first_segment = $CI->uri->segment(1);

    if (in_array($linkplayertype, $CI->config->item('playerids___42261'))) {

        //SOURCES
        $joins_objects = array('linkplayerup');
        $query_filters = array(
            'linkplayertype IN (' . join(',', $CI->config->item('playerids___' . $linkplayertype)) . ')' => null,
            'linkidearight' => $ideaid,
        );
        if ($linkplayertype == 42256 && count($headline_authors)) {
            //Exclude Headline Authors since they have already been listed:
            $query_filters['linkplayerup NOT IN (' . join(',', $headline_authors) . ')'] = null;
        }

        $order_columns = idea_sort();

    } elseif (in_array($linkplayertype, $CI->config->item('playerids___42380'))) {

        //IDEA Link Groups Previous
        $order_columns = array('linkid' => 'DESC');
        $joins_objects = array('linkidealeft');
        $query_filters = array(
            'linkplayertype IN (' . join(',', $CI->config->item('playerids___' . $linkplayertype)) . ')' => null, //IDEA LINKS
            'linkidearight' => $ideaid,
        );

    } elseif (in_array($linkplayertype, $CI->config->item('playerids___42265'))) {

        //IDEA Link Groups Next
        $order_columns = array('linknumber' => 'ASC');
        $joins_objects = array('linkidearight');
        $query_filters = array(
            'linkplayertype IN (' . join(',', $CI->config->item('playerids___' . $linkplayertype)) . ')' => null,
            'linkidealeft' => $ideaid,
        );

    } elseif (in_array($linkplayertype, $CI->config->item('playerids___12144'))) {

        //DISCOVERIES
        $order_columns = array('linkid' => 'DESC');
        $joins_objects = array('linkplayercreator');
        $query_filters = array(
            'linkplayertype IN (' . join(',', $CI->config->item('playerids___' . $linkplayertype)) . ')' => null, //DISCOVERIES
            'linkidealeft' => $ideaid,
        );

    } else {

        return null;

    }


    //Return Results:
    if ($current_page > 0) {

        $limit = view_memory(6404, 11064);
        return $CI->Links->read($query_filters, $joins_objects, $limit, ($current_page - 1) * $limit, $order_columns);

    } else {

        $players___11035 = $CI->config->item('players___11035'); //COINS
        $query = $CI->Links->read($query_filters, $joins_objects, 1, 0, array(), 'COUNT(linkid) as totals');
        $count_query = $query[0]['totals'];
        $visual_counter = '<span class="mini-hidden adjust-left">' . view_number($count_query) . '<span>';
        $title_desc = number_format($count_query, 0) . (isset($players___11035[$linkplayertype]['m__title']) ? ' ' . $players___11035[$linkplayertype]['m__title'] : '');

        if ($append_card_icon) {

            if (!$count_query) {
                return null;
            }

            $card_icon = '<span class="icon-block-sm">' . $players___11035[$linkplayertype]['m__cover'] . '</span>';

            $ui = '<div class="dropdown inline-block">';
            $ui .= '<button type="button" class="btn no-left-padding no-right-padding load_idea_cards button_of_' . $ideaid . '_' . $linkplayertype . '" id="card_group_idea_' . $linkplayertype . '_' . $ideaid . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" load_linkplayertype="' . $linkplayertype . '" load_ideaid="' . $ideaid . '" load_counter="' . $count_query . '" load_first_segment="' . $first_segment . '"><span title="' . $title_desc . '" data-toggle="tooltip" data-placement="top">' . $card_icon . $visual_counter . '</span></button>';

            //Menu To be loaded dynamically via AJAX:
            $ui .= '<div class="dropdown-menu dropdown_' . $linkplayertype . ' coins_idea_' . $ideaid . '_' . $linkplayertype . '" aria-labelledby="card_group_idea_' . $linkplayertype . '_' . $ideaid . '"></div>';

            $ui .= '</div>';

            return $ui;

        } else {
            return intval($count_query);
        }

    }

}

function view_dynamic_headline($dynamic_playerid, $m, $selected_e = null)
{

    $CI =& get_instance();
    $players___11035 = $CI->config->item('players___11035'); //Encyclopedia

    $headline = '<span class="icon-block-sm">' . $m['m__cover'] . '</span>' . $m['m__title'] . ': ';

    if (in_array($dynamic_playerid, $CI->config->item('playerids___28239'))) {
        $headline .= '<span class="icon-block-sm" title="' . $players___11035[28239]['m__message'] . '" data-toggle="tooltip" data-placement="top" style="font-size:0.34em;">' . $players___11035[28239]['m__cover'] . '</span>';
    }
    if (in_array($dynamic_playerid, $CI->config->item('playerids___32145'))) {
        $headline .= '<span class="icon-block-sm" title="' . $players___11035[32145]['m__title'] . '" data-toggle="tooltip" data-placement="top">' . $players___11035[32145]['m__cover'] . '</span>';
    }

    if (isset($players___11035[$dynamic_playerid]) && strlen($players___11035[$dynamic_playerid]['m__message'])) {
        $headline .= '<span class="doregular info_blob ' . (strlen($players___11035[$dynamic_playerid]['m__message']) < 55 ? ' short_blob ' : '') . '"><span>' . $players___11035[$dynamic_playerid]['m__message'] . '</span></span>';
    }

    return $headline;
}


function view_instant_select($focus__id, $down_playerid = 0, $right_ideaid = 0)
{

    /*
     * Either single or multi select UI elements...
     * */

    $CI =& get_instance();
    $players___42179 = $CI->config->item('players___42179'); //Dynamic Input Fields
    $players___11035 = $CI->config->item('players___11035'); //Encyclopedia
    $players___4527 = $CI->config->item('players___4527'); //Memory
    $is_compact = in_array($focus__id, $CI->config->item('playerids___42191'));
    $single_select = in_array($focus__id, $CI->config->item('playerids___33331'));
    $multi_select = in_array($focus__id, $CI->config->item('playerids___33332'));
    $access_locked = in_array($focus__id, $CI->config->item('playerids___32145'));
    $focus_select = $CI->config->item($single_select ? 'players___33331' : 'players___33332');

    if (!$single_select && !$multi_select) {
        //Must be either:
        log_error('view_instant_select() @' . $focus__id . ' not in single select @33331 or multi select 33332', array(
            'linkplayerdown' => $focus__id,
            'linkidearight' => $right_ideaid,
        ));
        return false;
    }

    $already_selected = array();
    $selection_ids = array();
    $selection_options = $CI->Links->read(array(
        'linkplayerup' => $focus__id,
        'linkplayertype IN (' . join(',', $CI->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
    ), array('linkplayerdown'), 0, 0, array('linknumber' => 'ASC'));
    foreach ($selection_options as $list_item) {
        array_push($selection_ids, $list_item['playerid']);
    }

    //UI for Single select or multi?
    $ui = '<div class="dynamic_selection">';
    if (!$is_compact) {
        $ui .= '<h3 class="mini-font grey">' . view_dynamic_headline($focus__id, $focus_select[$focus__id]) . '</h3>';
    }
    $ui .= '<div class="list-group list-radio-select grey-line radio-' . $focus__id . ($is_compact ? ' is_compact ' : '') . '">';

    if ($down_playerid > 0) {

        //Player Focus:
        if (count($selection_ids)) {
            foreach ($CI->Links->read(array(
                'linkplayerup IN (' . join(',', $selection_ids) . ')' => null, //All possible answers
                'linkplayerdown' => $down_playerid,
                'linkplayertype IN (' . join(',', $CI->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
            )) as $sel) {
                array_push($already_selected, $sel['linkplayerup']);
            }
        }

        if (!count($already_selected) && $single_select && player_session()) {
            //FIND DEFAULT if set in session of this user:
            $var_id = @$CI->session->userdata('session_custom_ui_' . $focus__id);
            foreach ($selection_ids as $playerid2) {
                if ($var_id == $playerid2) {
                    $already_selected = array($playerid2);
                    break;
                }
            }
        }

    } elseif ($right_ideaid > 0) {

        //Idea focus:
        foreach ($CI->Links->read(array(
            'linkplayerup IN (' . join(',', $selection_ids) . ')' => null, //All possible answers
            'linkidearight' => $right_ideaid,
            'linkplayertype IN (' . join(',', $CI->config->item('playerids___33602')) . ')' => null, //Idea/Player Links Active
        )) as $sel) {
            array_push($already_selected, $sel['linkplayerup']);
        }

    }

    $unselected_count = 0;
    $overflow_unselected_limit = 5;
    $has_selected = count($already_selected);
    $has_multiple = count($selection_options) > 1;
    $overflow_reached = false;
    $exclude_fonts = (in_array($focus__id, $CI->config->item('playerids___42417')) ? 'exclude_fonts' : '');
    $players___42179 = $CI->config->item('players___42179'); //Dynamic Input Fields

    foreach ($selection_options as $list_item) {

        //Has superpower?
        if (isset($players___42179[$list_item['playerid']]['m__following']) && count($players___42179[$list_item['playerid']]['m__following'])) {
            $superpowers_required = array_intersect($CI->config->item('playerids___10957'), $players___42179[$list_item['playerid']]['m__following']);
            if (count($superpowers_required) && !player_session(end($superpowers_required))) {
                continue;
            }
        }

        $selected = in_array($list_item['playerid'], $already_selected);
        if (!$overflow_reached && $unselected_count >= $overflow_unselected_limit && !$selected && !$is_compact) {
            $overflow_reached = true;
        }

        $headline = '<span class="inner_headline">' . (strlen($list_item['playercover']) ? '<span class="icon-block-sm change-results">' . view_cover($list_item['playercover']) . '</span>' : '') . $list_item['playertext'] . '</span>';
        if (in_array($list_item['playerid'], $CI->config->item('playerids___32145'))) {
            $headline .= '<span class="icon-block-sm" title="' . $players___11035[32145]['m__title'] . '" data-toggle="tooltip" data-placement="top">' . $players___11035[32145]['m__cover'] . '</span>';
        }
        if ($selected) {
            $headline .= '<span class="icon-block-sm checked_icon"><i class="far fa-check"></i></span>';
        }
        if (in_array($list_item['playerid'], $CI->config->item('playerids___11035')) && strlen($players___11035[$list_item['playerid']]['m__message']) > 0) {
            $headline .= '<span class="doregular info_blob ' . (strlen($players___11035[$list_item['playerid']]['m__message']) < 55 ? ' short_blob ' : '') . '"><span>' . $players___11035[$list_item['playerid']]['m__message'] . '</span></span>';
        }


        if ($selected) {
            if ($access_locked) {
                $ui .= '<span class="list-group-item custom_ui_' . $focus__id . '_' . $list_item['playerid'] . ' ' . $exclude_fonts . ' itemsetting_' . $focus__id . ' selection_preview selection_preview_' . $focus__id . ' itemsetting active" title="' . stripslashes($list_item['playertext']) . '">' . $headline . '</span>';
            } elseif ($has_multiple) {
                $ui .= '<a href="javascript:void(0);" onclick="$(\'.selection_item_' . $focus__id . '\').removeClass(\'hidden\');$(\'.selection_preview_' . $focus__id . '\').addClass(\'hidden\');" class="list-group-item custom_ui_' . $focus__id . '_' . $list_item['playerid'] . ' ' . $exclude_fonts . ' itemsetting_' . $focus__id . ' selection_preview selection_preview_' . $focus__id . ' itemsetting active" title="' . stripslashes($list_item['playertext']) . '">' . $headline . '<span class="icon-block-sm"><i class="far fa-pen-to-square"></i></span></a>';
            }
        }

        if (!$access_locked) {
            $ui .= '<a href="javascript:void(0);" onclick="player_select_apply(' . $focus__id . ',' . $list_item['playerid'] . ',' . ($multi_select ? 1 : 0) . ',' . $down_playerid . ',' . $right_ideaid . ')" class="list-group-item itemsetting custom_ui_' . $focus__id . '_' . $list_item['playerid'] . ' ' . $exclude_fonts . ' item-' . $list_item['playerid'] . ' itemsetting_' . $focus__id . ' selection_item_' . $focus__id . (($has_selected && $has_multiple) || $overflow_reached ? ' hidden' : '') . ($selected ? ' active ' : '') . '" title="' . stripslashes($list_item['playertext']) . '">' . $headline . '</a>';
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


function searchingle_select_form($cache_playerid, $selected_playerid, $show_dropdown_arrow = false, $show_title = false)
{

    $CI =& get_instance();
    $players___this = $CI->config->item('players___' . $cache_playerid);
    $players___4527 = $CI->config->item('players___4527'); //Memory
    $players___11035 = $CI->config->item('players___11035'); //Encyclopedia

    if (!$selected_playerid || !isset($players___this[$selected_playerid])) {
        return false;
    }

    //Make sure it's not locked:
    $ui = '<div class="dropdown inline-block dropd_form_' . $cache_playerid . '" selected_value="' . $selected_playerid . '">';

    $ui .= '<button type="button" class="btn no-left-padding dropdown-toggle" id="dropdown_form_' . $cache_playerid . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';

    $ui .= '<span class="current_content"><span class="icon-block-sm">' . $players___this[$selected_playerid]['m__cover'] . '</span>' . ($show_title ? $players___this[$selected_playerid]['m__title'] : '') . '</span>' . ($show_dropdown_arrow ? '<span class="icon-block-sm"><i class="far fa-angle-down"></i></span>' : '');

    $ui .= '</button>';

    $ui .= '<div class="dropdown-menu dropmenu_form_' . $cache_playerid . '" aria-labelledby="dropdown_form_' . $cache_playerid . '">';

    if (!$show_title) {
        $ui .= '<div class="dropdown-item main__title intro_header"><span class="icon-block-sm">' . $players___4527[$cache_playerid]['m__cover'] . '</span>' . $players___4527[$cache_playerid]['m__title'] . ':' . (isset($players___11035[$cache_playerid]) && strlen($players___11035[$cache_playerid]['m__message']) ? '<span class="doregular info_blob ' . (strlen($players___11035[$cache_playerid]['m__message']) < 55 ? ' short_blob ' : '') . '"><span>' . $players___11035[$cache_playerid]['m__message'] . '</span></span>' : '') . '</div>';
    }

    foreach ($players___this as $playerid => $m) {

        if (in_array($playerid, $CI->config->item('playerids___32145'))) {
            continue; //Locked Dropdown
        }
        $superpowers_required = array_intersect($CI->config->item('playerids___10957'), $m['m__following']);
        if (count($superpowers_required) && !player_session(end($superpowers_required))) {
            continue;
        }

        $ui .= '<a class="dropdown-item main__title optiond_' . $playerid . ' ' . ($playerid == $selected_playerid ? ' active ' : '') . '" href="javascript:void();" this_id="' . $playerid . '" onclick="update_form_select(' . $cache_playerid . ', ' . $playerid . ', 0, ' . intval($show_title) . ')"><span class="content_' . $playerid . '"><span class="icon-block-sm">' . $m['m__cover'] . '</span>' . $m['m__title'] . '</span>' . (isset($players___11035[$playerid]) && strlen($players___11035[$playerid]['m__message']) ? '<span class="doregular info_blob ' . (strlen($players___11035[$playerid]['m__message']) < 55 ? ' short_blob ' : '') . '"><span>' . $players___11035[$playerid]['m__message'] . '</span></span>' : '') . '</a>';

    }

    $ui .= '</div>';
    $ui .= '</div>';

    return $ui;
}


function searchingle_select_instant($cache_playerid, $selected_playerid, $idea_access = 0, $show_title = true, $o__id = 0, $linkid = 0)
{

    $CI =& get_instance();
    $players___this = $CI->config->item('players___' . $cache_playerid);
    $player_session = player_session();
    $players___11035 = $CI->config->item('players___11035'); //Encyclopedia
    $unselected_radio = in_array($cache_playerid, $CI->config->item('playerids___33331')) && !$selected_playerid;
    $players___4527 = $CI->config->item('players___4527'); //Memory

    if ($selected_playerid && !isset($players___this[$selected_playerid])) {

        return false;

        /*
    } elseif(!$selected_playerid && $idea_access && $player_session){

        //See if this user has any of these options:
        foreach($CI->Links->read(array(
            'linkplayerup IN (' . join(',', $CI->config->item('playerids___'.$cache_playerid)) . ')' => null, //SOURCE LINKS
            'linkplayerdown' => $player_session['playerid'],
            'linkplayertype IN (' . join(',', $CI->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
        )) as $x) {
            //Supports one for now
            $selected_playerid = $x['linkplayerup'];
            break;
        }
    */
    }

    //Make sure it's not locked:
    $idea_access = (!in_array($cache_playerid, $CI->config->item('playerids___32145')) && !in_array($selected_playerid, $CI->config->item('playerids___32145')) ? $idea_access : 0);

    $ui = '<div class="dropdown ' . ($show_title ? 'dropdown_type_' . $cache_playerid : '') . ' inline-block dropd_instant_' . $cache_playerid . '_' . $o__id . '_' . $linkid . '" selected_value="' . $selected_playerid . '">';

    $ui .= '<button type="button" ' . ($idea_access >= 3 ? 'class="btn no-left-padding ' . ($show_title ? 'dropdown-toggle' : 'no-right-padding dropdown-lock') . '" id="dropdown_instant_' . $cache_playerid . '_' . $o__id . '_' . $linkid . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : 'class="btn adj-btn ' . (!$show_title ? 'no-padding' : '') . ' edit-locked" ') . '>';

    $ui .= '<span class="current_content">' . (isset($players___this[$selected_playerid]['m__cover']) ? '<span class="icon-block-sm">' . $players___this[$selected_playerid]['m__cover'] . '</span>' . ($show_title ? $players___this[$selected_playerid]['m__title'] : '') : '<span class="icon-block-sm">' . $players___11035[$cache_playerid]['m__cover'] . '</span>' . ($show_title ? $players___11035[$cache_playerid]['m__title'] : '')) . '</span>'; //.( $show_title ? '<span class="icon-block-sm"><i class="far fa-angle-down"></i></span>' : '' )

    $ui .= '</button>';

    if ($idea_access >= 3) {

        $ui .= '<div class="dropdown-menu dropmenu_instant_' . $cache_playerid . '" o__id="' . $o__id . '" linkid="' . $linkid . '" aria-labelledby="dropdown_instant_' . $cache_playerid . '_' . $o__id . '_' . $linkid . '">';

        if (!$show_title) {
            $ui .= '<div class="dropdown-item main__title intro_header"><span class="icon-block-sm">' . $players___4527[$cache_playerid]['m__cover'] . '</span>' . $players___4527[$cache_playerid]['m__title'] . ':' . (isset($players___11035[$cache_playerid]) && strlen($players___11035[$cache_playerid]['m__message']) ? '<span class="doregular info_blob ' . (strlen($players___11035[$cache_playerid]['m__message']) < 55 ? ' short_blob ' : '') . '"><span>' . $players___11035[$cache_playerid]['m__message'] . '</span></span>' : '') . '</div>';
        }

        foreach ($players___this as $playerid => $m) {

            if (in_array($playerid, $CI->config->item('playerids___32145'))) {
                continue; //Locked Dropdown
            }
            $superpowers_required = array_intersect($CI->config->item('playerids___10957'), $m['m__following']);
            if (count($superpowers_required) && !player_session(end($superpowers_required))) {
                continue;
            }

            $superpowers_required = array_intersect($CI->config->item('playerids___10957'), $m['m__following']);
            $removal_option = in_array($playerid, $CI->config->item('playerids___42850'));

            $ui .= '<a class="dropdown-item drop_item_instant_' . $playerid . '_' . $o__id . '_' . $linkid . ' main__title optiond_' . $playerid . '_' . $o__id . '_' . $linkid . ' ' . ($playerid == $selected_playerid ? ' active ' : '') . ($removal_option ? ' removal_option ' . ($unselected_radio ? ' hidden ' : '') : '') . '" href="javascript:void();" this_id="' . $playerid . '" onclick="selector(' . $cache_playerid . ', ' . $playerid . ', ' . $o__id . ', ' . $linkid . ', ' . intval($show_title) . ')"><span class="icon-block-sm">' . $m['m__cover'] . '</span>' . $m['m__title'] . (isset($players___11035[$playerid]) && strlen($players___11035[$playerid]['m__message']) ? '<span class="doregular info_blob ' . (strlen($players___11035[$playerid]['m__message']) < 55 ? ' short_blob ' : '') . '"><span>' . $players___11035[$playerid]['m__message'] . '</span></span>' : '') . '</a>';


        }

        $ui .= '</div>';
    }


    $ui .= '</div>';

    return $ui;
}


function randomize_text($playerid)
{
    $CI =& get_instance();
    $players___12687 = $CI->config->item('players___12687');
    $line_messages = explode("\n", $players___12687[$playerid]['m__message']);
    return $line_messages[rand(0, (count($line_messages) - 1))];
}

function blocked_reasoning($superpower_playerid = 0)
{

    if (!player_session()) {

        return 'Sign-in to continue';

    } elseif ($superpower_playerid && !player_session($superpower_playerid)) {

        $CI =& get_instance();
        $players___10957 = $CI->config->item('players___10957');
        return 'Error: You are missing access to ' . $players___10957[$superpower_playerid]['m__title'];

    } else {

        return null;

    }

}


function view_hash($string)
{
    $CI =& get_instance();
    return substr(md5($string . $CI->config->item('secret_hash')), 0, 10);
}


function view_idea_title($i, $string_only = false)
{

    if (!isset($i['ideatext'])) {
        return null;
    }

    //Break down by lines:
    foreach (explode("\n", $i['ideatext']) as $line) {
        if (strlen($line) && !filter_var($line, FILTER_VALIDATE_URL)) {
            return ($string_only ? $line : '<span class="main__title">' . $line . '</span>');
        }
    }

    //If not yet found we need to use other data to generate title:
    return (isset($i['ideahashtag']) && strlen($i['ideahashtag']) ? $i['ideahashtag'] : (isset($i['ideaid']) && intval($i['ideaid']) ? 'Idea Number ' . $i['ideaid'] : 'Idea' . rand(100000000000, 999999999999)));

}

function view_valid_handle_player($string, $check_db = false)
{
    $CI =& get_instance();
    return (substr($string, 0, 1) == '@' && ctype_alnum(substr($string, 1)) && (!$check_db || count($CI->Players->read(array(
            'LOWER(playerhandle)' => strtolower(substr($string, 1)),
        )))) ? substr($string, 1) : false);
}

function view_valid_handle_idea($string, $check_db = false)
{
    $CI =& get_instance();
    return (substr($string, 0, 1) == '#' && ctype_alnum(substr($string, 1)) && (!$check_db || count($CI->Ideas->read(array(
            'LOWER(ideahashtag)' => strtolower(substr($string, 1)),
        )))) ? substr($string, 1) : false);
}

function view_valid_handle_reverse_idea($string, $check_db = false)
{
    $CI =& get_instance();
    return (substr($string, 0, 2) == '!#' && ctype_alnum(substr($string, 2)) && (!$check_db || count($CI->Ideas->read(array(
            'LOWER(ideahashtag)' => strtolower(substr($string, 2)),
        )))) ? substr($string, 2) : false);
}


function view_idea_links($i, $playerid = 0, $replace_links = true, $focus__node = false)
{

    if (!isset($i['ideaid'])) {
        return null;
    }

    //Append Custom Reference Link contents, if any:
    $CI =& get_instance();

    if ($replace_links) {
        $i['ideacache'] = str_replace('spanaa', 'a', $i['ideacache']);
    }

    if ($playerid > 0) {
        foreach ($CI->Links->read(array(
            'linkidearight' => $i['ideaid'],
            'linkplayertype' => 31835, //References
        ), array('linkplayerup'), 0) as $message_references) {
            if (!substr_count(strtolower($i['ideacache']), '>@' . strtolower($message_references['playerhandle']))) {
                //Maybe because it was duplicated, etc... REMOVE IT:
                $CI->Links->delete($message_references['linkid']);
                continue;
            }
            foreach ($CI->Links->read(array(
                'linkplayerup' => $message_references['playerid'],
                'linkplayerdown' => $playerid,
                'linkplayertype IN (' . join(',', $CI->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
                'LENGTH(linktext) > 0' => null,
            ), array(), 1) as $reference_profile) {
                if (strlen($reference_profile['linktext'])) {
                    if (filter_var($reference_profile['linktext'], FILTER_VALIDATE_URL)) {
                        $i['ideacache'] = str_ireplace('@' . $message_references['playerhandle'] . '</a>', '</a>' . '<a href="' . $reference_profile['linktext'] . '" target="_blank">' . $reference_profile['linktext'] . '</a>', $i['ideacache']);

                    } else {
                        $i['ideacache'] = str_ireplace('@' . $message_references['playerhandle'], (filter_var($reference_profile['linktext'], FILTER_VALIDATE_URL) ? '' : '@' . $message_references['playerhandle'] . ' ') . $reference_profile['linktext'], $i['ideacache']);
                    }
                }
            }
        }
    }

    return
        $i['ideacache'] . view_idea_media($i) . ($focus__node || !substr_count($i['ideacache'], 'show_more_line') ? view_list_player($i, !$replace_links) : '');
}


function ideacache($save_ideaid, $str)
{

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
    $idea_references = array(
        4256 => array(), //Generic URL
        31834 => array(), //Idea Synonym
        42337 => array(), //Idea Antonym
        31835 => array(), //Player Mention
    );

    $ui_template = array(
        4256 => '<spanaa href="%s" target="_blank"><span class="url_truncate">%s</span></spanaa>',
        31834 => '<spanaa href="' . view_memory(42903, 33286) . '%s" data-toggle="popover" class="ref_idea">%s</spanaa>', //Ideation
        42337 => '<spanaa href="' . view_memory(42903, 33286) . '%s" data-toggle="popover" class="ref_idea">%s</spanaa>', //Ideation
        31835 => '<spanaa href="' . view_memory(42903, 42902) . '%s" data-toggle="popover" class="ref_player">%s</spanaa>', //Sourcing
    );


    //See what we can find:
    $word_count = 0;
    $word_limit = 89;
    $line_inwards = 3;
    $link_words = 13; //The number of words a link is counted as

    $ideacache = '<div class="i_cache cache_frame_' . $save_ideaid . '">';
    $line_count = 0;
    $hidden_started = false;
    $hidden_closed = false;

    foreach (explode("\n", $str) as $line_index => $line) {

        if (strlen($line)) {
            $line_count++;
        }
        $ideacache_line = '';

        foreach (explode(' ', $line) as $word_index => $word) {

            $reference_type = 0;
            if ($word_count >= $word_limit && !$hidden_started && (!$line_inwards || $word_index >= $line_inwards)) {
                $ideacache_line .= '<span class="hidden inner_line">';
                $hidden_started = true;
            }
            $ideacache_line .= ($word_index > 0 ? ' ' : '');

            if (filter_var($word, FILTER_VALIDATE_URL)) {

                //Generic URL:
                $reference_type = 4256;
                array_push($idea_references[$reference_type], $word);
                $ideacache_line .= @sprintf($ui_template[$reference_type], $word, $word);
                $word_count += $link_words;

            } elseif (view_valid_handle_player($word, true)) {

                //Idea Synonym
                $reference_type = 31835;
                array_push($idea_references[$reference_type], $word);
                $ideacache_line .= @sprintf($ui_template[$reference_type], substr($word, 1), $word);
                $word_count++;

            } elseif (view_valid_handle_reverse_idea($word, true)) {

                //Idea Antonym
                $reference_type = 42337;
                array_push($idea_references[$reference_type], $word);
                $ideacache_line .= @sprintf($ui_template[$reference_type], substr($word, 2), $word);
                $word_count++;

            } elseif (view_valid_handle_idea($word, true)) {

                //Player Mention
                $reference_type = 31834;
                array_push($idea_references[$reference_type], $word);
                $ideacache_line .= @sprintf($ui_template[$reference_type], substr($word, 1), $word);
                $word_count++;

            } else {

                //This word is not referencing anything!
                $ideacache_line .= htmlentities($word);
                $word_count++;

            }
        }


        $ideacache .= '<div class="line ' . (!$line_index ? 'first_line' : '') . (($save_ideaid && $word_count >= $word_limit && $line_count > 2) ? ' hidden ' : '') . '">';
        $ideacache .= $ideacache_line;
        if ($hidden_started && !$hidden_closed) {
            $ideacache .= '</span>';
            $hidden_closed = true;
        }
        $ideacache .= '</div>';

    }


    if ($save_ideaid && ($hidden_started || ($word_count >= $word_limit && $line_count > 2))) {
        //Add show more button:
        $ideacache .= '<div class="line show_more_line"><spanaa href="javascript:void(0);">Show more</spanaa></div>';
    }


    $ideacache .= '</div>';

    if (intval($save_ideaid) > 0) {

        //Save Found references to remove the ones who exist in DB:
        $references_add_to_db = $idea_references;
        $player_session = player_session();
        foreach ($CI->Links->read(array(
            'linkplayertype IN (' . join(',', $CI->config->item('playerids___4736')) . ')' => null, //Idea Message Links 3x
            'linkidearight' => $save_ideaid,
        )) as $x) {

            //Is this still valid?
            if (!in_array($x['linktext'], $idea_references[$x['linkplayertype']])) {

                //Not valid, must be removed:
                $CI->Links->delete($x['linkid'], $player_session['playerid']);

            } else {

                //Remove from add new to DB list (Since we dont need to add this):
                foreach ($references_add_to_db[$x['linkplayertype']] as $key => $val) {
                    if ($val == $x['linktext']) {
                        unset($references_add_to_db[$x['linkplayertype']][$key]);
                        break;
                    }
                }
            }
        }

        //Add whatever was not found to DB:
        foreach ($references_add_to_db as $db_type => $db_vals) {
            foreach ($db_vals as $db_val) {

                //Additional Player/idea reference?
                $linkidealeft = 0;
                $linkplayerup = 0;
                $linktext = '';

                if ($db_type == 31834) {
                    $linkplayertype = 31834;
                    foreach ($CI->Ideas->read(array(
                        'LOWER(ideahashtag)' => strtolower(substr($db_val, 1)),
                    )) as $target) {
                        $linkidealeft = $target['ideaid'];
                    }
                } elseif ($db_type == 42337) {
                    $linkplayertype = 42337;
                    foreach ($CI->Ideas->read(array(
                        'LOWER(ideahashtag)' => strtolower(substr($db_val, 2)),
                    )) as $target) {
                        $linkidealeft = $target['ideaid'];
                    }
                } elseif ($db_type == 31835) {
                    $linkplayertype = 31835;
                    foreach ($CI->Players->read(array(
                        'LOWER(playerhandle)' => strtolower(substr($db_val, 1)),
                    )) as $target) {
                        $str = str_replace('@' . $target['playerid'], '@' . $target['playerhandle'], $str); //TODO Remove!
                        $linkplayerup = $target['playerid'];
                    }
                } else {
                    $linkplayertype = $db_type; //Message URLs
                    $player_session = player_session();
                    $linkplayerup = ($player_session ? $player_session['playerid'] : 14068);
                    foreach ($CI->Links->read(array(
                        'linkid' => $save_ideaid,
                    ), array()) as $x) {
                        $linkplayerup = $x['linkplayerup'];
                        break;
                    }
                    $linktext = $db_val;
                }

                $CI->Links->create(array(
                    'linktime' => idea_creation_time($save_ideaid),
                    'linkplayertype' => $linkplayertype,
                    'linkplayercreator' => $player_session['playerid'],
                    'linktext' => $linktext,
                    'linkidearight' => $save_ideaid,
                    'linkidealeft' => $linkidealeft,
                    'linkplayerup' => $linkplayerup,
                ));

            }
        }
    }

    return $ideacache;

}


function view_featured_links($linkplayertype, $location, $m = null, $focus__node)
{
    $CI =& get_instance();
    $players___11035 = $CI->config->item('players___11035'); //Encyclopedia
    return '<div class="creator_headline" ' . (is_array($m) ? ' data-toggle="tooltip" data-placement="top" title="' . $m['m__title'] . (strlen($m['m__message']) ? ': ' . $m['m__message'] : ' @' . $location['playerhandle']) . (strlen($location['linktext']) ? ': ' . $location['linktext'] : '') . '" ' : '') . '>' . ($focus__node ? '<a href="' . view_memory(42903, 42902) . $location['playerhandle'] . '">' : '') . '<span class="grey ' . ($linkplayertype == 41949 ? 'icon-block' : 'icon-block-xs') . '">' . $players___11035[$linkplayertype]['m__cover'] . '</span><span class="grey mini-frame ' . ($linkplayertype == 41949 ? 'mini-font' : '') . '">' . $location['playertext'] . '</span>' . ($focus__node ? '</a>' : '') . '</div>';
}


function view_idea_nav($discovery_mode, $focus_i, $x_completes = false)
{

    $CI =& get_instance();
    $coins_count = array();
    $body_content = '';
    $player_session = player_session();
    $ideation_pen = player_session(10939);
    $players___loading_order = $CI->config->item('players___' . ($discovery_mode ? 26005 : 26005));

    if ($player_session && !is_array($x_completes)) {
        $x_completes = $CI->Links->read(array(
            'linkplayertype IN (' . join(',', $CI->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
            'linkplayercreator' => $player_session['playerid'],
            'linkidealeft' => $focus_i['ideaid'],
        ), array('linkidearight'));
    }

    $discovery_next_hide = $player_session && $discovery_mode && !count($x_completes) && count($CI->Links->read(array(
            'linkplayertype IN (' . join(',', $CI->config->item('playerids___42991')) . ')' => null, //Active Writes
            'linkidearight' => $focus_i['ideaid'],
            'linkplayerup' => 44250, //Hide Next Ideas
        )));

    $ui = '';
    $ui .= '<ul class="nav nav-tabs nav12273 nav__' . $focus_i['ideaid'] . ' hideIfEmpty">';
    foreach ($CI->config->item('players___' . ($discovery_mode ? 42877 : 31890)) as $linkplayertype => $m) {

        $superpowers_required = array_intersect($CI->config->item('playerids___10957'), $m['m__following']);
        if (count($superpowers_required) && !player_session(end($superpowers_required))) {
            continue;
        }
        if (in_array($linkplayertype, $CI->config->item('playerids___42376')) && !$player_session) {
            //Private content without being a member, so dont even show the counters:
            continue;
        }


        $coins_count[$linkplayertype] = ideas_query($linkplayertype, $focus_i['ideaid'], 0, false);
        if (!$coins_count[$linkplayertype] && ($discovery_mode || in_array($linkplayertype, $CI->config->item('playerids___12144')))) {
            continue;
        }

        $input_content = '';
        if (!$discovery_mode && $ideation_pen) {

            if (in_array($linkplayertype, $CI->config->item('playerids___42261'))) {

                $input_content .= '<div class="new_list new-list-' . $linkplayertype . '"><div class="col-12 container-center"><div class="dropdown_' . $linkplayertype . ' list-adder">
                    <div class="input-group border">
                        <input type="text"
                               class="form-control form-control-thick algolia_finder algolia__e algolia__ce dotransparent add-input"
                               maxlength="' . view_memory(6404, 6197) . '"
                               placeholder="Create New or Link Existing @Players">
                    </div></div></div></div>';
                $body_content .= '<script> $(document).ready(function () { player_load_finder(' . $linkplayertype . '); }); </script>';

            } elseif (0 && in_array($linkplayertype, $CI->config->item('playerids___11020'))) {

                //ADD IDEAS
                $input_content .= '<div class="new_list new-list-' . $linkplayertype . '"><div class="col-12 container-center"><div class="dropdown_' . $linkplayertype . ' list-adder">
                    <div class="input-group border">
                        <input type="text"
                               class="form-control form-control-thick algolia_finder algolia__i algolia__ci dotransparent add-input"
                               maxlength="' . view_memory(6404, 6197) . '"
                               placeholder="Create New or Link Existing #ideas">
                    </div></div></div></div>';
                $body_content .= '<script> $(document).ready(function () { idea_load_search(' . $linkplayertype . '); }); </script>';
            }

        }

        if (in_array($linkplayertype, $CI->config->item('playerids___42945')) || $coins_count[$linkplayertype] > 0) {
            $body_content .= '<div class="headlinebody pillbody headline_body_' . $linkplayertype . ' hidden" read-counter="' . $coins_count[$linkplayertype] . '">' . $input_content . '<div class="tab_content"></div></div>';


            if ($linkplayertype != 12840 || !$discovery_next_hide) {
                $ui .= '<li class="nav-item thepill' . $linkplayertype . '"><a class="nav-link handle_nav_' . $m['m__handle'] . '" linkplayertype="' . $linkplayertype . '" href="#' . $m['m__handle'] . '" title="' . $m['m__title'] . '"><span class="icon-block">' . $m['m__cover'] . '</span><span class="hideIfEmpty xtypecounter' . $linkplayertype . '">' . view_number($coins_count[$linkplayertype]) . '</span><span class="hidden xtypetitle xtypetitle_' . $linkplayertype . '">&nbsp;' . $m['m__title'] . '&nbsp;</span></a></li>';
            }

        }

    }
    $ui .= '</ul>';
    $ui .= $body_content;

    if (!$discovery_next_hide) {
        $ui .= '<script> $(document).ready(function () { load_hashtag_menu(\'Next\'); }); </script>';
    }


    if (in_array($focus_i['ideatype'], $CI->config->item('playerids___34826')) && $player_session && $discovery_mode && !count($x_completes)) {
        foreach ($CI->Links->read(array(
            'linkplayertype IN (' . join(',', $CI->config->item('playerids___42991')) . ')' => null, //Active Writes
            'linkidearight' => $focus_i['ideaid'],
            'linkplayerup' => 44262, //Skip Next If Unidea_discovered
        )) as $skip) {
            //Not yet idea_discovered, lets go next automatically:
            $ui .= '<script> $(document).ready(function () { setTimeout(function () { idea_discovered(0); }, ' . (is_numeric($skip['linktext']) && intval($skip['linktext']) > 0 ? intval($skip['linktext']) : '2584') . '); }); </script>';
            break;
        }
    }


    return $ui;

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


function idea_view($linkplayertype, $i, $previous_i = null, $target_ideahashtag = null, $focus_playerid = 0, $x_completes = false)
{

    //Search to see if an idea has a thumbnail:
    $CI =& get_instance();

    $linkid = (isset($i['linkid']) && $i['linkid'] > 0 ? $i['linkid'] : 0);
    $players___11035 = $CI->config->item('players___11035'); //Encyclopedia
    $is_cache = in_array($linkplayertype, $CI->config->item('playerids___14599'));
    $goto_start = in_array($linkplayertype, $CI->config->item('playerids___42988'));
    $player_session = player_session();
    $superpower_10939 = !$is_cache && player_session(10939);
    $idea_startable = idea_is_startable($i);
    $linkplayercreator = ($focus_playerid > 0 ? $focus_playerid : ($player_session ? $player_session['playerid'] : 0));
    $link_creator = isset($i['linkplayercreator']) && $i['linkplayercreator'] == $linkplayercreator;
    $focus__node = in_array($linkplayertype, $CI->config->item('playerids___12149')); //NODE COIN
    $discovery_uri = (isset($_POST['js_request_uri']) && substr_count($_POST['js_request_uri'], '/') == 2 ? one_two_explode('/', '/', $_POST['js_request_uri']) : false);
    $discovery_seg = (strtolower($CI->uri->segment(1)) != 'ajax' && strtolower($CI->uri->segment(1)) != 'controller' && strlen($CI->uri->segment(2)) ? $CI->uri->segment(1) : false);
    $discovery_mode = $linkplayercreator && ($discovery_uri || $discovery_seg);
    $idea_access = idea_access($i['ideahashtag'], 0, $i, false, array(), $is_cache);
    $focus_idea_uri = ($discovery_uri ? one_two_explode('/', '', substr($_POST['js_request_uri'], 1)) : false);
    $focus_idea_seg = ($discovery_seg ? $CI->uri->segment(2) : false);
    $focus_ideahashtag = ($focus_idea_uri ? $focus_idea_uri : ($focus_idea_seg ? $focus_idea_seg : false));

    if ($discovery_mode && !$target_ideahashtag && ($discovery_uri || $discovery_seg)) {
        $target_ideahashtag = ($discovery_uri ? $discovery_uri : $discovery_seg);
    }
    if ($target_ideahashtag && $focus_ideahashtag && $focus_ideahashtag == $i['ideahashtag']) {
        $focus_ideahashtag = false;
    }

    //Log Preview:
    $linkplayercreator_id = ( $linkplayercreator>0 ? $linkplayercreator : 14068 /* GUEST */ );
    $CI->Links->create(array(
        'linkplayertype' => 1576044, //Idea Previewed
        'linkplayercreator' => $linkplayercreator_id,
        'linkplayerup' => $linkplayercreator_id,
        'linkidealeft' => $i['ideaid'],
    ));

    if ($linkplayercreator && !is_array($x_completes)) {
        //Fetch discovery
        $x_completes = $CI->Links->read(array(
            'linkplayertype IN (' . join(',', $CI->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
            'linkplayercreator' => $linkplayercreator,
            'linkidealeft' => $i['ideaid'],
        ), array('linkidearight'));
    }

    $focus_idea_or = false;
    if ($discovery_mode && $focus_ideahashtag && !$focus__node && $linkplayercreator && $previous_i['ideatype'] != 43758) {
        foreach ($CI->Ideas->read(array(
            'LOWER(ideahashtag)' => strtolower($focus_ideahashtag),
            'ideatype IN (' . join(',', $CI->config->item('playerids___7712')) . ')' => null, //Input Choice
        )) as $focus_i) {
            $focus_idea_or = $focus_i;
        }
    }

    $has_sortable = $linkid > 0 && !$focus__node && $idea_access >= 3 && in_array($linkplayertype, $CI->config->item('playerids___4603')) && ($linkplayertype != 42256 || $i['linkplayertype'] == 34513);
    $has_idea_discovered = 0;
    if (!$is_cache && $linkplayercreator) {
        $discoveries = $CI->Links->read(array(
            'linkplayertype IN (' . join(',', $CI->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
            'linkplayercreator' => $linkplayercreator,
            'linkidealeft' => $i['ideaid'],
        ));
        $has_idea_discovered = count($discoveries);
    }
    if ($has_idea_discovered && $discovery_mode) {
        $i = array_merge($i, $discoveries[0]);
    }

    if ($has_idea_discovered && !$target_ideahashtag) {
        foreach ($CI->Links->read(array(
            'linkplayertype IN (' . join(',', $CI->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
            'linkplayercreator' => $linkplayercreator,
            'linkidealeft' => $i['ideaid'],
            'linkidearight > 0' => null,
        ), array('linkidearight')) as $CI_dis) {
            $target_ideahashtag = $CI_dis['ideahashtag'];
        }
    }

    $is_locked = ($discovery_mode && !$has_idea_discovered && !$focus__node);

    if (($goto_start || !$superpower_10939) && $idea_startable) {
        $href = view_memory(42903, 30795) . $i['ideahashtag'] . '/' . view_memory(6404, 4235);
    } elseif ($is_locked) {
        $href = null;
    } elseif ($discovery_mode && $target_ideahashtag) {
        $href = view_memory(42903, 30795) . $target_ideahashtag . '/' . $i['ideahashtag'];
    } elseif ($discovery_mode) {
        $href = view_memory(42903, 33286) . $i['ideahashtag'];
    } else {
        $href = view_memory(42903, 33286) . $i['ideahashtag'];
    }


    //Top action menu:
    $ui = '<div ideaid="' . $i['ideaid'] . '" ideahashtag="' . $i['ideahashtag'] . '" ideatype="' . $i['ideatype'] . '" linkid="' . $linkid . '" href="' . $href . '" class="card_cover card_idea_cover ' . ($focus__node ? ' focus-cover slim_flat coll-md-8 coll-sm-10 col-12
     ' : ' edge-cover ' . ($discovery_mode ? ' col-12 ' : ' coll-md-4 coll-6 col-12 ')) . ' no-padding card-12273 s__12273_' . $i['ideaid'] . ' ' . (strlen($href) ? ' card_click ' : '') . (!$focus_idea_or && $is_locked ? ' is_locked' : '') . ($has_sortable ? ' sort_draggable ' : '') . ($linkid ? ' cover_x_' . $linkid . ' ' : '') . '">';

    if ($discovery_mode && $linkplayercreator && $focus__node) {
        $ui .= '<style> .add_idea{ display:none; } </style>';
    }
    if (1 || ($discovery_mode && ($is_locked || $focus_idea_or))) {
        $ui .= '<script> $(document).ready(function () {show_more(' . $i['ideaid'] . '); }); </script>';
    }

    $is_required = count($CI->Links->read(array(
        'linkplayertype IN (' . join(',', $CI->config->item('playerids___42991')) . ')' => null, //Active Writes
        'linkidearight' => $i['ideaid'],
        'linkplayerup' => 28239, //Required
    )));

    if ($is_required) {
        //Add required icon:
        $ui .= '<script> $(document).ready(function () { $(\'.cache_frame_' . $i['ideaid'] . ' .first_line\').append(\'<span class="' . ($focus__node ? 'icon-block-sm' : 'icon-block-xs') . ' asterisk" title="Required">*</span>\'); }); </script>';
    }

    if ($focus_idea_or) {
        $ui .= '<div class="this_selector this_selector_' . $i['ideaid'] . '" selection_ideaid="' . $i['ideaid'] . '"><span class="icon-block-sm">' . (count($CI->Links->read(array(
                'linkplayertype' => 7712, //Input Choice
                'linkplayercreator' => $linkplayercreator,
                'linkidealeft' => $focus_idea_or['ideaid'],
                'linkidearight' => $i['ideaid'],
            ))) ? '<i class="fas fa-square-check fa-sharp"></i>' : '<i class="far fa-square fa-sharp"></i>') . '</span></div>';
    }

    $ui .= '<div class="cover-content ' . ($focus_idea_or ? ' cover_selector ' : '') . '">';
    $ui .= '<div class="inner-content">';
    $ui .= '<div class="cover-text">';

    //Show Link User:
    $ui .= '<div class="creator_frame creator_frame_' . $i['ideaid'] . '">';

    //Show Creator if any:
    $headline_authors = array();
    foreach ($CI->Links->read(array(
        'linkplayertype' => 4250, //Idea Created
        'linkidearight' => $i['ideaid'],
    ), array('linkplayerup')) as $creator) {

        array_push($headline_authors, $creator['playerid']);
        $follow_btn = null;
        if ($focus__node && $linkplayercreator && $linkplayercreator != $creator['playerid']) {
            $followings = $CI->Links->read(array(
                'linkplayerup' => $creator['playerid'],
                'linkplayerdown' => $linkplayercreator,
                'linkplayertype IN (' . join(',', $CI->config->item('playerids___42795')) . ')' => null, //Follow
            ), array(), 1, 0, array('linknumber' => 'ASC'));
            $follow_btn = searchingle_select_instant(42795, (count($followings) ? $followings[0]['linkplayertype'] : 0), $idea_access, false, $creator['playerid'], (count($followings) ? $followings[0]['linkid'] : 0));
        }

        $ui .= '<div class="creator_headline"><a href="' . view_memory(42903, 42902) . $creator['playerhandle'] . '"><span class="icon-block">' . view_cover($creator['playercover']) . '</span><b class="hidden">' . $creator['playertext'] . '</b><span class="grey mini-font mini-frame">@' . $creator['playerhandle'] . '</span></a>' . (!in_array($creator['playerid'], $CI->config->item('playerids___42881')) ? '<span class="grey mini-font mini-padded mini-frame mini_time" title="' . date("Y-m-d H:i:s", strtotime($creator['linktime'])) . ' PST">' . view_time_difference($creator['linktime'], true) . '</span>' : '') . $follow_btn . '</div>';

    }


    $ui .= ($href ? '<a href="' . $href . '"' : '<div') . ' title="' . $i['ideaid'] . '" class="sub__handle space-content grey ' . (!$superpower_10939 && ($discovery_mode || !$focus__node || !$linkplayercreator) ? ' hidden ' : '') . '">#<span class="ui_ideahashtag_' . $i['ideaid'] . '">' . $i['ideahashtag'] . '</span>' . ($href ? '</a>' : '</div>');

    //Right menu push here:
    //Bottom Bar
    $bottom_bar_ui = '';

    //Determine Link Group
    $linkplayertype_id = 4593; //Link Type
    $linkplayertype_ui = '';
    if (!$focus__node && $linkid && !$is_cache) {
        foreach ($CI->config->item('players___31770') as $linkplayertype1 => $m1) {
            if (in_array($i['linkplayertype'], $CI->config->item('playerids___' . $linkplayertype1))) {
                foreach ($CI->Links->read(array(
                    'linkid' => $linkid,
                ), array('linkplayercreator')) as $linker) {
                    $linkplayertype_ui .= '<span class="icon-block-sm">';
                    $linkplayertype_ui .= searchingle_select_instant($linkplayertype1, $i['linkplayertype'], $idea_access, false, $i['ideaid'], $linkid);
                    $linkplayertype_ui .= '</span>';
                }
                $linkplayertype_id = $linkplayertype1;
                break;
            }
        }
        if (!$linkplayertype_ui) {
            $linkplayertype_ui .= '<span class="icon-block-sm">';
            $linkplayertype_ui .= searchingle_select_instant(4593, $i['linkplayertype'], false, false, $i['ideaid'], $linkid);
            $linkplayertype_ui .= '</span>';
        }
    }

    foreach ($CI->config->item('players___31904') as $linkplayertype_target_bar => $m_target_bar) {

        //See if missing superpower?
        $superpowers_required = array_intersect($CI->config->item('playerids___10957'), $m_target_bar['m__following']);
        if (count($superpowers_required) && (!player_session(end($superpowers_required)) || $is_cache)) {
            continue;
        }

        //Determine hover state:
        if ($linkplayertype_target_bar == 31770 && !$discovery_mode && $linkplayertype_ui && $superpower_10939) {

            //Links
            $bottom_bar_ui .= $linkplayertype_ui;

        } elseif ($linkplayertype_target_bar == 4362 && !$is_cache && !$discovery_mode && $player_session && isset($i['linktime']) && strtotime($i['linktime']) > 0 && $linkplayertype_ui && ($idea_access >= 3 || ($player_session && $linkplayercreator == $i['linkplayercreator']))) {

            //Link Time / Creator
            $creator_details = '';
            $time_diff = view_time_difference($i['linktime'], true);
            $creator_name = '';
            if ($i['linkplayercreator'] > 0) {
                foreach ($CI->Players->read(array(
                    'playerid' => $i['linkplayercreator'],
                )) as $creator) {
                    $creator_name = 'Linked by ' . $creator['playertext'] . ' @' . $creator['playerhandle'] . ' on ';
                    $creator_details = '<a href="' . view_memory(42903, 33286) . $i['ideahashtag'] . '"><span class="icon-block-sm">' . view_cover($creator['playercover']) . '</span></a>';
                }
            }

            $bottom_bar_ui .= '<span class="icon-block-sm"><div class="grey created_time" title="' . $creator_name . date("Y-m-d H:i:s", strtotime($i['linktime'])) . ' which is ' . $time_diff . ' ago | ID ' . $i['linkid'] . '">' . ($creator_details ? $creator_details : $time_diff) . '</div></span>';

        } elseif ($linkplayertype_target_bar == 4737 && !$discovery_mode && $superpower_10939) {

            //Player Reference
            $bottom_bar_ui .= '<span>';
            $bottom_bar_ui .= searchingle_select_instant(4737, $i['ideatype'], $idea_access, false, $i['ideaid'], $linkid);
            $bottom_bar_ui .= '</span>';

        } elseif (0 && $linkplayertype_target_bar == 41037 && $focus_idea_or && !$is_cache) {

            //Selector

        } elseif ($linkplayertype_target_bar == 13909 && $idea_access >= 3 && $has_sortable && !$discovery_mode) {

            //Sort Idea
            $bottom_bar_ui .= '<span class="sort_idea_frame hidden icon-block-sm">';
            $bottom_bar_ui .= '<span title="' . $m_target_bar['m__title'] . '" class="sort_idea_grab">' . $m_target_bar['m__cover'] . '</span>';
            $bottom_bar_ui .= '</span>';

        } elseif ($linkplayertype_target_bar == 14980 && !$is_cache && $idea_access >= 1 && !$discovery_mode) {

            //Drop Down
            $action_buttons = null;
            if (!$linkid) {
                $focus_dropdown = 11047; //Idea Dropdown
            } elseif ($linkplayertype_id == 4486) { //Idea/Idea Links
                $focus_dropdown = 14955; //Idea/Idea Dropdown
            } elseif ($linkplayertype_id == 13550) { //Idea/Player Links
                $focus_dropdown = 28787; //Idea/Player Dropdown
            } else {
                //Discoveries
                $focus_dropdown = 32069; //Idea/Discoveries Dropdown
            }

            if (is_array($CI->config->item('players___' . $focus_dropdown))) {
                foreach ($CI->config->item('players___' . $focus_dropdown) as $playerid_dropdown => $m_dropdown) {

                    //Skip if missing superpower:
                    $superpowers_required = array_intersect($CI->config->item('playerids___10957'), $m_dropdown['m__following']);
                    if (count($superpowers_required) && !player_session(end($superpowers_required))) {
                        continue;
                    }

                    $anchor = '<span class="icon-block-sm">' . $m_dropdown['m__cover'] . '</span>' . $m_dropdown['m__title'];

                    if ($playerid_dropdown == 12589 && $idea_access >= 3) {

                        //Mass Apply
                        $action_buttons .= '<a href="javascript:void(0);" onclick="link_preview(12589,' . $i['ideaid'] . ')" class="dropdown-item main__title">' . $anchor . '</a>';

                    } elseif ($playerid_dropdown == 33286 && $discovery_mode && $idea_access >= 3) {

                        //Ideation Mode
                        $action_buttons .= '<a href="' . view_memory(42903, 33286) . $i['ideahashtag'] . '" class="dropdown-item main__title">' . $anchor . '</a>';

                    } elseif ($playerid_dropdown == 31911 && $idea_access >= 3) {

                        //Idea Editor
                        $action_buttons .= '<a href="javascript:void(0);" onclick="idea_editor(' . $i['ideaid'] . ',' . $linkid . ')" class="dropdown-item main__title">' . $anchor . '</a>';

                    } elseif ($playerid_dropdown == 13007 && $idea_access >= 3) {

                        //Reset Alphabetic order
                        $action_buttons .= '<a href="javascript:void(0);" onclick="link_sort_reset()" class="dropdown-item main__title">' . $anchor . '</a>';

                    } elseif ($playerid_dropdown == 31911 && $idea_access >= 3 && $discovery_mode) {

                        //Idea Editor
                        $action_buttons .= '<a href="javascript:void(0);" onclick="idea_editor(' . $i['ideaid'] . ',' . $linkid . ')" class="dropdown-item main__title">' . $anchor . '</a>';

                    } elseif ($playerid_dropdown == 10673 && $linkid && $idea_access >= 3) {

                        //Unlink
                        $action_buttons .= '<a href="javascript:void(0);" onclick="link_delete(' . $linkid . ', ' . $linkplayertype . ',\'' . $i['ideahashtag'] . '\')" class="dropdown-item main__title">' . $anchor . '</a>';

                    } elseif ($playerid_dropdown == 30873 && $idea_access >= 3) {

                        //Clone Idea Tree:
                        $action_buttons .= '<a href="javascript:void(0);" onclick="idea_copy(' . $i['ideaid'] . ', 1)" class="dropdown-item main__title">' . $anchor . '</a>';

                    } elseif ($playerid_dropdown == 33292 && $player_session) {

                        //Stats
                        $action_buttons .= '<a href="' . view_app_link(33292) . view_memory(42903, 33286) . $i['ideahashtag'] . '" class="dropdown-item main__title">' . $anchor . '</a>';

                    } elseif ($playerid_dropdown == 29771 && $idea_access >= 3) {

                        //Clone Single Idea:
                        $action_buttons .= '<a href="javascript:void(0);" onclick="idea_copy(' . $i['ideaid'] . ', 0)" class="dropdown-item main__title">' . $anchor . '</a>';

                    } elseif ($playerid_dropdown == 4341 && $idea_access >= 3 && $linkid) {

                        //Link Details
                        $action_buttons .= '<a href="' . view_app_link(4341) . '?linkid=' . $linkid . '" class="dropdown-item main__title" target="_blank">' . $anchor . '</a>';

                    } elseif ($playerid_dropdown == 42648 && $idea_access >= 3) {

                        //Delete Permanently
                        $action_buttons .= '<li><hr class="dropdown-divider"></li>';
                        $action_buttons .= '<a href="javascript:void();" onclick="idea_delete(' . $i['ideaid'] . ')" class="dropdown-item main__title">' . $anchor . '</a>';

                    } elseif ($playerid_dropdown == 28637 && isset($i['linkplayertype']) && player_session(12700)) {

                        //Paypal Details
                        $linktext = @unserialize($i['linktext']);
                        if (isset($linktext['txn_id'])) {
                            $action_buttons .= '<a href="https://www.paypal.com/activity/payment/' . $linktext['txn_id'] . '" class="dropdown-item main__title" target="_blank">' . $anchor . '</a>';
                        }

                    } elseif (in_array($playerid_dropdown, $CI->config->item('playerids___6287')) && $idea_access >= 3) {

                        //Standard button
                        $action_buttons .= '<a href="' . view_app_link($playerid_dropdown) . view_memory(42903, 33286) . $i['ideahashtag'] . '" class="dropdown-item main__title">' . $anchor . '</a>';

                    }
                }
            }

            //Any items found?
            if ($action_buttons && $focus_dropdown > 0) {
                //Right Action Menu
                $players___14980 = $CI->config->item('players___14980'); //Dropdowns

                $bottom_bar_ui .= '<span>';
                $bottom_bar_ui .= '<div class="dropdown inline-block">';
                $bottom_bar_ui .= '<button type="button" class="btn no-left-padding no-right-padding icon-block-sm" id="action_menu_idea_' . $i['ideaid'] . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="' . $players___14980[$focus_dropdown]['m__title'] . '">' . $players___14980[$focus_dropdown]['m__cover'] . '</button>';
                $bottom_bar_ui .= '<div class="dropdown-menu" aria-labelledby="action_menu_idea_' . $i['ideaid'] . '">';
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


    //Idea Location if any:
    foreach ($CI->Links->read(array(
        'linkplayertype' => 41949, //Locate
        'linkidearight' => $i['ideaid'],
    ), array('linkplayerup')) as $location) {
        $ui .= view_featured_links(41949, $location, null, $focus__node);
    }

    //Link Message if any:
    if ($linkid && $player_session) {
        $ui .= '<div class="linktext_headline grey hideIfEmpty ignore-click ui_linktext_' . $linkid . (in_array($i['linkplayertype'], $CI->config->item('playerids___42294')) ? ' hidden ' : '') . '" style="padding-left:40px;">' . htmlentities($i['linktext']) . '</div>';
    }


    $ui .= '</div>';


    //Idea Message (Remaining)
    $ui .= '<div class="ui_ideacache_' . $i['ideaid'] . (!$focus__node ? ' space-content ' : '') . '">' . view_idea_links($i, $linkplayercreator, ($focus__node || 1), $focus__node) . '</div>';

    $idea_popup_url = idea_popup_url($i);
    if ($idea_popup_url) {
        $ui .= '<div class="ignore-click link_click link_click_' . $i['ideaid'] . ' hideIfEmpty"><a href="' . $idea_popup_url . '" class="hideIfEmpty" target="_blank" onclick="link_clicked(' . $i['ideaid'] . ')">' . $idea_popup_url . '</a></div>';
    }


    //Raw Data:
    $ui .= '<div class="ui_ideatext_' . $i['ideaid'] . '
     hidden">' . $i['ideatext'] . '</div>';


    $ui .= '</div>';
    $ui .= '</div>';
    $ui .= '</div>';


    if ($linkplayercreator && isset($previous_i['ideatype'])) {

        //Three main actions: (Excludes reading which is no action)
        $input_ui = '';

        //Any inputs for this idea?
        if ($previous_i['ideatype'] == 43758 || (in_array($i['ideatype'], $CI->config->item('playerids___41055')) && $focus__node && $i['ideatype'] != 43758)) {

            //PAYMENT TICKET
            if (isset($_GET['cancel_pay']) && !count($x_completes)) {
                $input_ui .= '<div class="alert alert-danger" role="alert">You cancelled your payment.</div>';
            }

            if (isset($_GET['process_pay']) && !count($x_completes)) {

                $input_ui .= '<div class="alert alert-warning" role="alert"><span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>Processing your payment, please wait</div>';

                //Referesh soon so we can check if completed or not
                js_php_redirect(view_memory(42903, 30795) . $target_ideahashtag . '/' . $i['ideahashtag'] . '?process_pay=1', 987);

            } elseif ($previous_i['ideatype'] != 43758 && count($x_completes)) {

                foreach ($x_completes as $x_complete) {

                    $linktext = unserialize($x_complete['linktext']);
                    $quantity = ($x_complete['linknumber'] >= 2 ? $x_complete['linknumber'] : (isset($linktext['quantity']) && $linktext['quantity'] >= 2 ? $linktext['quantity'] : 1));

                    if ($linktext['mc_gross'] != 0) {
                        $input_ui .= '<div class="alert alert-success tickets_issued" role="alert"><span class="icon-block"><i class="far fa-check-circle"></i></span>' . ($linktext['mc_gross'] > 0 ? 'You paid ' : 'You got a refund of ') . str_replace('.00', '', $linktext['mc_gross']) . ' ' . $linktext['mc_currency'] . ($quantity > 1 ? ' for ' . $quantity . ' tickets' : '') . ' & should receive a Paypal Email Receipt shortly.</div>';
                    }

                }

                $input_ui .= '<input type="hidden" class="paypal_handling" name="handling" value="' . $linktext['mc_gross'] . '">';
                $input_ui .= '<input type="hidden" class="ideanumber" name="quantity" value="' . $linktext['quantity'] . '">'; //Dynamic Variable that JS will update

            } else {

                $valid_instant_pay = false; //Until we can find and verify from DB

                $paypal_email = website_setting(30882);

                $currency_types = $CI->Links->read(array(
                    'linkplayertype IN (' . join(',', $CI->config->item('playerids___42991')) . ')' => null, //Active Writes
                    'linkidearight' => ($previous_i['ideatype'] == 43758 ? $previous_i['ideaid'] : $i['ideaid']),
                    'linkplayerup IN (' . join(',', $CI->config->item('playerids___26661')) . ')' => null, //Currency
                ));
                $total_dues = $CI->Links->read(array(
                    'linkplayertype IN (' . join(',', $CI->config->item('playerids___42991')) . ')' => null, //Active Writes
                    'linkidearight' => $i['ideaid'],
                    'linkplayerup' => 26562, //Total Due
                ));
                $cart_max = $CI->Links->read(array(
                    'linkplayertype IN (' . join(',', $CI->config->item('playerids___42991')) . ')' => null, //Active Writes
                    'linkidearight' => $i['ideaid'],
                    'linkplayerup' => 29651, //Cart Max Quantity
                ));
                $cart_min = $CI->Links->read(array(
                    'linkplayertype IN (' . join(',', $CI->config->item('playerids___42991')) . ')' => null, //Active Writes
                    'linkidearight' => $i['ideaid'],
                    'linkplayerup' => 31008, //Cart Min Quantity
                ));


                //Payments Must have Unit Price, otherwise they are NOT a payment until added
                $info_append = '';
                $unit_currency = '';
                $unit_price = 0;
                $unit_fee = 0;
                $max_allowed = (count($cart_max) && is_numeric($cart_max[0]['linktext']) && $cart_max[0]['linktext'] > 0 ? intval($cart_max[0]['linktext']) : view_memory(6404, 29651));
                $spots_remaining = idea_spots_remaining($i['ideaid']);
                $starting_point = ($is_required ? 1 : 0);
                $max_allowed = ($spots_remaining > -1 && $spots_remaining < $max_allowed ? $spots_remaining : $max_allowed);

                $min_allowed = (count($cart_min) && is_numeric($cart_min[0]['linktext']) && intval($cart_min[0]['linktext']) > $starting_point ? intval($cart_min[0]['linktext']) : $starting_point);
                $players___26661 = $CI->config->item('players___26661'); //Currency
                if (count($currency_types)) {
                    $unit_currency = $players___26661[$currency_types[0]['linkplayerup']]['m__message'];
                }


                if (filter_var($paypal_email, FILTER_VALIDATE_EMAIL) && count($total_dues) && $previous_i['ideatype'] != 43758 && $total_dues[0]['linktext'] > 0 && count($currency_types) == 1) {

                    $valid_instant_pay = true;

                    $digest_fees = count($CI->Links->read(array(
                        'linkplayertype IN (' . join(',', $CI->config->item('playerids___42991')) . ')' => null, //Active Writes
                        'linkidearight' => $i['ideaid'],
                        'linkplayerup' => 30589, //Digest Fees
                    )));

                    //Break down amount & currency
                    $unit_price = doubleval($total_dues[0]['linktext']);
                    $unit_fee = number_format($unit_price * ($digest_fees ? 0 : (doubleval(website_setting(30590, $linkplayercreator)) + doubleval(website_setting(27017, $linkplayercreator))) / 100), 2, ".", "");

                    //Append information to cart about Paypal:
                    $info_append .= '<div class="sub_note">After completing the payment on PayPal click "<span style="color: #990000;">Return to Merchant</span>" to continue back here. By paying you agree to our <a href="' . view_app_link(14373) . '" target="_blank">Terms of Use</a>.</div>';

                } elseif (filter_var($paypal_email, FILTER_VALIDATE_EMAIL) && $previous_i['ideatype'] == 43758 && count($total_dues) && $total_dues[0]['linktext'] > 0) {

                    $digest_fees = count($CI->Links->read(array(
                        'linkplayertype IN (' . join(',', $CI->config->item('playerids___42991')) . ')' => null, //Active Writes
                        'linkidearight' => $previous_i['ideaid'],
                        'linkplayerup' => 30589, //Digest Fees
                    )));

                    //Break down amount & currency
                    $unit_price = doubleval($total_dues[0]['linktext']);
                    $unit_fee = number_format($unit_price * ($digest_fees ? 0 : (doubleval(website_setting(30590, $linkplayercreator)) + doubleval(website_setting(27017, $linkplayercreator))) / 100), 2, ".", "");

                }


                $current_value = $min_allowed;
                foreach ($CI->Links->read(array(
                    'linkplayertype' => 7712, //Input Choice
                    'linkplayercreator' => $player_session['playerid'],
                    'linkidearight' => $i['ideaid'],
                ), array(), 1) as $x_selection) {
                    $current_value = $x_selection['linknumber'];
                }


                //Is multi selectable, allow show down for quantity:
                $input_ui .= '<div class="source-info ticket-notice" title="' . $players___11035[44242]['m__title'] . '">'
                    . '<span class="icon-block">' . $players___11035[44242]['m__cover'] . '</span>'
                    . '<div class="player_info_box">';

                if ($max_allowed > 0 || $min_allowed > 0) {
                    $input_ui .= '<div class="sale_controller sale_controller_' . $i['ideaid'] . '" unitprice="' . $unit_price . '" unitcurrency="' . $unit_currency . '" ideaid="' . $i['ideaid'] . '">';
                    $input_ui .= '<a href="javascript:void(0);" onclick="sale_increment(-1,' . $i['ideaid'] . ',' . $max_allowed . ',' . $min_allowed . ',' . ($unit_fee + $unit_price) . ',' . $unit_fee . ')" class="sale_increment sale_down"><i class="fas fa-minus ' . ($current_value == $min_allowed ? ' hidden ' : '') . '"></i></a>';
                    $input_ui .= '<span class="main__title current_count">' . $current_value . '</span>';
                    $input_ui .= '<a href="javascript:void(0);" onclick="sale_increment(1,' . $i['ideaid'] . ',' . $max_allowed . ',' . $min_allowed . ',' . ($unit_fee + $unit_price) . ',' . $unit_fee . ')" class="sale_increment sale_up">' . ($max_allowed == $min_allowed ? '<i class="fas fa-lock islocked"></i>' : '<i class="fas fa-plus"></i>') . '</a>';
                    $input_ui .= '</div>';
                } else {
                    $input_ui .= '<span class="current_count" style="display: none;">' . $min_allowed . '</span>';
                }

                $input_ui .= $info_append;

                $input_ui .= '</div>';
                $input_ui .= '</div>';


                if ($valid_instant_pay) {

                    $players___14870 = $CI->config->item('players___14870'); //DOMAINS

                    //Load Paypal Pay button:
                    $input_ui .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">';

                    $input_ui .= '<input type="hidden" class="paypal_handling" name="handling" value="' . $unit_fee . '">';
                    $input_ui .= '<input type="hidden" class="ideanumber" name="quantity" value="' . $min_allowed . '">'; //Dynamic Variable that JS will update
                    $input_ui .= '<input type="hidden" name="item_name" value="' . remove_none_utf8(view_idea_title($i, true)) . '">';
                    $input_ui .= '<input type="hidden" name="item_number" value="' . ($target_ideahashtag ? $target_ideahashtag . ' #' : '') . $i['ideahashtag'] . ' @' . get_domain('m__handle') . ' @' . $player_session['playerhandle'] . '">';

                    $input_ui .= '<input type="hidden" name="amount" value="' . $unit_price . '">';
                    $input_ui .= '<input type="hidden" name="currency_code" value="' . $unit_currency . '">';
                    $input_ui .= '<input type="hidden" name="no_shipping" value="1">';
                    $input_ui .= '<input type="hidden" name="notify_url" value="https://' . $players___14870[2738]['m__message'] . view_app_link(26595) . '">';
                    $input_ui .= '<input type="hidden" name="cancel_return" value="https://' . get_domain('m__message') . view_memory(42903, 30795) . $target_ideahashtag . '/' . $i['ideahashtag'] . '?cancel_pay=1">';
                    $input_ui .= '<input type="hidden" name="return" value="https://' . get_domain('m__message') . view_memory(42903, 30795) . $target_ideahashtag . '/' . $i['ideahashtag'] . '?process_pay=1">';
                    $input_ui .= '<input type="hidden" name="cmd" value="_xclick">';
                    $input_ui .= '<input type="hidden" name="business" value="' . $paypal_email . '">';

                    $input_ui .= '<input type="submit" class="adj-btn pay-btn main__title" name="pay_now" id="pay_now" value="Pay Now >" onclick="$(\'.process-btn\').html(\'Loading\');$(\'#pay_now\').val(\'...\');">';

                    $input_ui .= '</form>';

                    $input_ui .= '<script> $(document).ready(function () { $(\'.idea_discovered_btn\').hide(); }); </script>';

                } else {

                    //FREE TICKET
                    $input_ui .= '<input type="hidden" class="paypal_handling" name="handling" value="' . $unit_fee . '">';
                    $input_ui .= '<input type="hidden" class="ideanumber" name="quantity" value="' . $min_allowed . '">'; //Dynamic Variable that JS will update

                }
            }

        } elseif (in_array($i['ideatype'], $CI->config->item('playerids___33532'))) {

            //Find the created idea if any:
            $player_private_replies = $CI->Links->read(array(
                'linkplayertype' => 33532, //Private Reply
                'linkidealeft' => $i['ideaid'],
                'linkplayercreator' => $linkplayercreator,
            ), array('linkidearight'), 0, 1, array('linkid' => 'DESC'));

            $input_attributes = '';
            $previous_response = (isset($player_private_replies[0]['ideatext']) ? $player_private_replies[0]['ideatext'] : '');

            if (in_array($i['ideatype'], $CI->config->item('playerids___43002'))) {

                //Textarea
                $players___6201 = $CI->config->item('players___6201'); //IDEA Cache
                $input_ui .= '<textarea class="border dotted-borders x_write algolia_finder algolia__i algolia__e" placeholder="' . (strlen($players___6201[4736]['m__message']) ? $players___6201[4736]['m__message'] : $players___6201[4736]['m__title'] . '...') . '">' . $previous_response . '</textarea>';
                $input_ui .= '<script> $(document).ready(function () { set_autosize($(\'.x_write\')); }); </script>';

            } elseif (in_array($i['ideatype'], $CI->config->item('playerids___43003'))) {

                //Input

                if ($i['ideatype'] == 31794) {

                    //Number
                    if (count($CI->Links->read(array(
                        'linkplayertype IN (' . join(',', $CI->config->item('playerids___42991')) . ')' => null, //Active Writes
                        'linkidearight' => $i['ideaid'],
                        'linkplayerup' => 42181, //Phone
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
                    foreach ($CI->Links->read(array(
                        'linkplayertype IN (' . join(',', $CI->config->item('playerids___42991')) . ')' => null, //Active Writes
                        'linkidearight' => $i['ideaid'],
                        'linkplayerup' => 31813, //Steps
                    )) as $num_steps) {
                        if (strlen($num_steps['linktext']) && is_numeric($num_steps['linktext'])) {
                            $input_attributes .= ' step="' . $num_steps['linktext'] . '" ';
                        }
                    }

                    //Min Value
                    foreach ($CI->Links->read(array(
                        'linkplayertype IN (' . join(',', $CI->config->item('playerids___42991')) . ')' => null, //Active Writes
                        'linkidearight' => $i['ideaid'],
                        'linkplayerup' => 31800, //Min Value
                    )) as $num_steps) {
                        if (strlen($num_steps['linktext']) && is_numeric($num_steps['linktext'])) {
                            $input_attributes .= ' min="' . $num_steps['linktext'] . '" ';
                        }
                    }

                    //Max Value
                    foreach ($CI->Links->read(array(
                        'linkplayertype IN (' . join(',', $CI->config->item('playerids___42991')) . ')' => null, //Active Writes
                        'linkidearight' => $i['ideaid'],
                        'linkplayerup' => 31801, //Max Value
                    )) as $num_steps) {
                        if (strlen($num_steps['linktext']) && is_numeric($num_steps['linktext'])) {
                            $input_attributes .= ' max="' . $num_steps['linktext'] . '" ';
                        }
                    }

                } elseif ($i['ideatype'] == 30350) {

                    $has_time = count($CI->Links->read(array(
                        'linkplayertype IN (' . join(',', $CI->config->item('playerids___42991')) . ')' => null, //Active Writes
                        'linkidearight' => $i['ideaid'],
                        'linkplayerup' => 32442, //Select Time
                    )));

                    $input_type = ($has_time ? 'datetime-local' : 'date');
                    $placeholder = ($has_time ? 'Select Date & Time...' : 'Select Date...');

                } elseif ($i['ideatype'] == 42915) {

                    //URL
                    $input_type = 'url';
                    $placeholder = 'Paste URL...';

                } elseif ($i['ideatype'] == 43005) {

                    //Input Text
                    $input_type = 'text';
                    $placeholder = 'Write...';

                }

                $input_ui .= '<input type="' . $input_type . '" ' . $input_attributes . ' class="border dotted-borders x_write" placeholder="' . $placeholder . '" value="' . $previous_response . '" />';

            }

            //Uploader
            if (in_array($i['ideatype'], $CI->config->item('playerids___43004'))) {

                if ($i['ideahashtag'] == 'ProfilePicture' && $player_session) {

                    //TODO REMOVE HACK: This is a profile picture hack:
                    $input_ui .= '<div style="padding:3px 0;"><a href="javascript:void(0);" onclick="player_editor(' . $linkplayercreator . ',0);setTimeout(function () { $(\'.uploader_42359\').click(); }, 987);" class="btn btn-black inner_uploader_' . $i['ideaid'] . '"><span class="icon-block-sm">' . $players___11035[7637]['m__cover'] . '</span>' . $players___11035[7637]['m__title'] . '</a></div>';

                } else {
                    $input_ui .= '<div class="media_outer_frame hideIfEmpty">
                        <div id="media_outer_' . $i['ideaid'] . '" class="media_frame media_frame_' . $i['ideaid'] . ' hideIfEmpty"></div>
                        <div class="doclear">&nbsp;</div>
                    </div>';
                    $input_ui .= '<div style="padding:3px 0;"><div class="btn btn-black inner_uploader_' . $i['ideaid'] . '"><span class="icon-block-sm">' . $players___11035[7637]['m__cover'] . '</span>' . $players___11035[7637]['m__title'] . '</div></div>';
                    $input_ui .= '<script> $(document).ready(function () { load_cloudinary(43004, ' . $i['ideaid'] . ', [\'#' . $i['ideaid'] . '\'], \'.inner_uploader_' . $i['ideaid'] . '\'); setTimeout(function () { display_media(\'media_outer_' . $i['ideaid'] . '\', 43004, ' . $i['ideaid'] . '); }, 144); }); </script>';

                    foreach ($player_private_replies as $x_response) {
                        $input_ui .= '<div class="hidden">' . idea_view(6255, $x_response) . '</div>';
                        $input_ui .= '<script> $(document).ready(function () { setTimeout(function () { display_media(\'media_outer_' . $i['ideaid'] . '\', 43004, ' . $x_response['ideaid'] . '); }, 144); }); </script>';
                    }
                }

            }

        }

        if (strlen($input_ui)) {
            $ui .= '<div class="ignore-click input_ui input_ui_' . $i['ideaid'] . '">' . $input_ui . '</div>';
        }

        //End of Discovery input
    }


    //Bottom Bar
    $bottom_menu_ui = '';


    foreach ($CI->config->item('players___44257') as $linkplayertype_target_bar => $m_target_bar) {

        //See if missing superpower?
        $superpowers_required = array_intersect($CI->config->item('playerids___10957'), $m_target_bar['m__following']);
        if (count($superpowers_required) && (!player_session(end($superpowers_required)) || $is_cache)) {
            continue;
        }

        //Determine hover state:
        if ($linkplayertype_target_bar == 33532 && !$is_cache && $player_session && $idea_access >= 2 && !$is_locked) {

            //Private Reply
            $bottom_menu_ui .= '<span class="mini_button main__title" style="max-width:55px;">';
            $bottom_menu_ui .= '<a href="javascript:void(0);" class="btn btn-sm" onclick="idea_editor(0,0,' . ($idea_access >= 3 ? 4228 : 30901) . ',' . $i['ideaid'] . ')"><span class="icon-block-sm">' . $m_target_bar['m__cover'] . '</span>' . ($focus__node && 0 ? $m_target_bar['m__title'] : '') . '</a>';
            $bottom_menu_ui .= '</span>';

        } elseif (0 && $linkplayertype_target_bar == 42819 && !$is_cache && player_session(10939) && $idea_access >= 3 && !$is_locked) {

            //New Player
            $bottom_menu_ui .= '<span class="mini_button main__title">';
            $bottom_menu_ui .= '<a href="javascript:void(0);" onclick="idea_editor(0,0,' . ($idea_access >= 3 ? 4228 : 30901) . ',' . $i['ideaid'] . ')"><span class="icon-block-sm">' . $m_target_bar['m__cover'] . '</span>' . ($focus__node ? $m_target_bar['m__title'] : '') . '</a>';
            $bottom_menu_ui .= '</span>';

        } elseif ($linkplayertype_target_bar == 42260 && $player_session && !$is_locked && !$is_cache && 0) {

            //Reactions... Check to see if they have any?
            $reactions = $CI->Links->read(array(
                'linkplayerup' => $linkplayercreator,
                'linkidearight' => $i['ideaid'],
                'linkplayertype IN (' . join(',', $CI->config->item('playerids___42260')) . ')' => null, //Reactions
            ), array(), 1);
            $bottom_menu_ui .= '<span class="mini_button" style="max-width:55px;"><div class="main__title">';
            $bottom_menu_ui .= searchingle_select_instant(42260, (count($reactions) ? $reactions[0]['linkplayertype'] : 0), $player_session, 0 && $focus__node, $i['ideaid'], (count($reactions) ? $reactions[0]['linkid'] : 0));
            $bottom_menu_ui .= '</div></span>';

        } elseif ($linkplayertype_target_bar == 4235 && (!$discovery_mode && $idea_startable && $idea_access >= 1)) {

            //Start
            $bottom_menu_ui .= '<span><a href="' . view_memory(42903, 30795) . $i['ideahashtag'] . '/' . view_memory(6404, 4235) . '" class="btn btn-sm btn-black"><span class="icon-block-sm">' . $m_target_bar['m__cover'] . '</span>' . $m_target_bar['m__title'] . '</a></span>';

        } elseif ($linkplayertype_target_bar == 42924 && $discovery_mode && $focus__node) {

            //Next
            $players___6255 = $CI->config->item('players___6255');
            $focus_menu = ($has_idea_discovered ? $m_target_bar : $players___6255[idea_type_discovery($i)]);
            $bottom_menu_ui .= '<span><a href="javascript:void(0);" onclick="idea_discovered(0)" class="btn btn-sm post_button idea_discovered_btn"><span class="icon-block-sm">' . $focus_menu['m__cover'] . '</span>' . $focus_menu['m__title'] . '</a></span>';

        } elseif ($linkplayertype_target_bar == 31022 && $discovery_mode && $focus__node && $player_session && !count($x_completes) && !in_array($i['ideatype'], $CI->config->item('playerids___43009')) && !idea_required($i)) {

            //Skip
            $bottom_menu_ui .= '<span class="mini_button" style="max-width: 75px;"><a href="javascript:void(0);" onclick="idea_discovered(1)" class="btn btn-sm"><span class="icon-block-sm">' . $m_target_bar['m__cover'] . '</span>' . $m_target_bar['m__title'] . '</a></span>';

        }
    }


    //Bottom Bar menu
    if (!$focus__node && !$is_locked && !$is_cache) {
        foreach ($CI->config->item('players___' . ($discovery_mode ? 42877 : 31890)) as $playerid_bottom_bar => $m_bottom_bar) {

            $superpowers_required = array_intersect($CI->config->item('playerids___10957'), $m_bottom_bar['m__following']);
            if (count($superpowers_required) && !player_session(end($superpowers_required))) {
                continue;
            }

            if (in_array($playerid_bottom_bar, $CI->config->item('playerids___42376')) && !$player_session) {
                //Private content without being a member, so dont even show the counters:
                continue;
            }

            $coins_ui = ideas_query($playerid_bottom_bar, $i['ideaid'], 0, true, $headline_authors);
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
    $playercover_generator = playercover_generator(12279);
    return random_adjective() . str_replace('Badger Honey', 'Honey Badger', str_replace('Black Widow', '', ucwords(str_replace('-', ' ', one_two_explode('fa-', ' ', $playercover_generator)))));
}

function view_list_player($i, $plain_no_html = false)
{

    $CI =& get_instance();
    $message_append = '';

    //Define Order:
    $players___42421 = $CI->config->item('players___42421');
    $order_columns = array();
    foreach ($players___42421 as $sort_id => $sort) {
        $order_columns['linkplayerup = \'' . $sort_id . '\' DESC'] = null;
    }

    //Query Relevant Players:
    foreach ($CI->Links->read(array(
        'linkplayertype IN (' . join(',', $CI->config->item('playerids___33602')) . ')' => null, //Writer Links Active
        'linkidearight' => $i['ideaid'],
        'linkplayerup IN (' . join(',', $CI->config->item('playerids___42421')) . ')' => null, //Featured Inputs
    ), array('linkplayerup'), 0, 0, $order_columns) as $x) {

        //Format data if needed:
        $x['linktext'] = data_type_format($x['linkplayerup'], $x['linktext']);

        $message_append .= '<div class="source-info">'
            . '<span class="icon-block">' . $players___42421[$x['linkplayerup']]['m__cover'] . '</span>' . $players___42421[$x['linkplayerup']]['m__title'] . (strlen($x['linktext']) ? ':' : '')
            . (strlen($x['linktext']) ? '<div class="player_info_box"><div class="sub_note main__title">' . (!$plain_no_html ? nl2br(view_url($x['linktext'])) : $x['linktext']) . '</div></div>' : '')
            . '</div>';

    }

    return (strlen($message_append) ? ($plain_no_html ? $message_append : '<div class="source-featured">' . $message_append . '</div>') : false);

}


function view_idea_media($i)
{

    $CI =& get_instance();
    $message_append = '';

    //Query Relevant Players:
    foreach ($CI->Links->read(array(
        'linkplayertype IN (' . join(',', $CI->config->item('playerids___42294')) . ')' => null, //Media
        'linkidearight' => $i['ideaid'],
    ), array('linkplayerup'), 0, 0, array('linknumber' => 'ASC')) as $x) {

        if ($x['linkplayertype'] == 4258) {

            //Video
            $template = '<video id="video_player_' . $x['linktext'] . '" controls class="cld-video-player cld-fluid cld-video-player-skin-light" poster="' . $x['playercover'] . '"></video><script> play_video(\'' . $x['linktext'] . '\'); </script>';

        } elseif ($x['linkplayertype'] == 4259) {

            //Audio
            $template = '<audio controls src="' . $x['linktext'] . '"></audio>';

        } elseif ($x['linkplayertype'] == 4260) {

            //Image
            $template = '<img src="' . $x['linktext'] . '"></video>';

        } else {
            continue; //Should not happen!
        }

        //Format data if needed:
        $message_append .= '<div class="media_display media_display_' . $x['linkplayertype'] . ($x['linkplayertype'] == 4258 ? ' ignore-click ' : '') . '" id="loaded_media_' . $x['linkid'] . '" class="media_item" media_playerid="' . $x['linkplayertype'] . '" playerid="' . $x['playerid'] . '"  playercover="' . $x['playercover'] . '" playback_code="' . $x['linktext'] . '" playertext="' . $x['playertext'] . '">' . $template . '</div>';

    }

    return $message_append;

}


function view_pill($focus__node, $linkplayertype, $counter, $m, $ui = null, $is_open = true)
{

    return '<script> $(\'.nav-tabs\').append(\'<li class="nav-item thepill' . $linkplayertype . '"><a class="nav-link" linkplayertype="' . $linkplayertype . '" href="#' . $m['m__handle'] . '" data-toggle="tooltip" data-placement="top" title="' . number_format($counter, 0) . ' ' . $m['m__title'] . (strlen($m['m__message']) ? ': ' . str_replace('\'', '', str_replace('"', '', $m['m__message'])) : '') . '"><span class="icon-block-xs">' . $m['m__cover'] . '</span><span class="main__title hideIfEmpty xtypecounter' . $linkplayertype . '">' . view_number($counter) . '</span></a></li>\') </script>' .
        '<div class="headlinebody pillbody hidden headline_body_' . $linkplayertype . '" read-counter="' . $counter . '">' . $ui . '</div>';

}


function player_view($linkplayertype, $e, $extra_class = null)
{

    $CI =& get_instance();

    if (!isset($e['playerid']) || !isset($e['playertext'])) {
        log_error('player_view() Missing core variables', array(
            'linkplayerdown' => $linkplayertype,
        ));
        return 'Missing core variables';
    }

    $linkid = (isset($e['linkid']) ? $e['linkid'] : 0);
    $player_access = player_access($e['playerhandle'], 0, $e);
    $superpower_10939 = player_session(10939);
    $player_session = player_session();
    $players___11035 = $CI->config->item('players___11035'); //Encyclopedia
    $focus__node = in_array($linkplayertype, $CI->config->item('playerids___12149')); //NODE COIN
    $is_app = $linkplayertype == 6287;
    $href = ($is_app ? view_app_link($e['playerid']) : view_memory(42903, 42902) . $e['playerhandle']);
    $cover_is_image = filter_var($e['playercover'], FILTER_VALIDATE_URL);
    $has_sortable = $linkid > 0 && $player_access >= 3 && in_array($linkplayertype, $CI->config->item('playerids___13911'));


    //Log preview view:
    $linkplayercreator_id = ( $player_session ? $player_session['playerid'] : 14068 /* GUEST */ );
    $CI->Links->create(array(
        'linkplayertype' => 1576051, //Player Popover
        'linkplayerup' => $e['playerid'],
        'linkplayerdown' => $linkplayercreator_id,
        'linkplayercreator' => $linkplayercreator_id,
    ));

    //Player UI
    $ui = '<div playerid="' . $e['playerid'] . '" playerhandle="' . $e['playerhandle'] . '" ' . (isset($e['linkid']) ? ' linkid="' . $e['linkid'] . '" ' : '') . ' href="' . $href . '" class="card_cover cardplayer_cover no-padding card-12274 s__12274_' . $e['playerid'] . ' ' . $extra_class . ($is_app ? ' card-6287 ' : '') . ($has_sortable ? ' sort_draggable ' : '') . ($focus__node ? ' focus-cover slim_flat col-md-8 col-sm-10 col-12 ' : ' edge-cover col-sm-4 col-6 ' . (strlen($href) ? ' card_click ' : '')) . (isset($e['linkid']) ? ' cover_x_' . $e['linkid'] . ' ' : '') . '">';

    $ui .= '<div class="cover-wrapper">';

    //Coin Cover
    $ui .= (!$focus__node ? '<a href="' . $href . '"' : '<div') . ' class="handle_hrefplayer_' . $e['playerid'] . ' coinType12274 ' . ($player_access >= 3 ? '' : ' ready-only ') . ' black-background-obs cover-link" ' . ($cover_is_image ? 'style="background-image:url(\'' . $e['playercover'] . '\');"' : '') . '>';
    $ui .= '<div class="cover-btn ui_playercover_' . $e['playerid'] . '" raw_cover="' . $e['playercover'] . '">' . (!$cover_is_image && $e['playercover'] ? view_cover($e['playercover'], true) : '') . '</div>';
    $ui .= (!$focus__node ? '</a>' : '</div>');

    $ui .= '</div>';


    //Title Cover
    $ui .= '<div class="cover-content">';
    $ui .= '<div class="inner-content">';


    if ($player_access >= 3) {
        //Editable:
        $ui .= view_player_input(6197, $e['playertext'], $e['playerid'], $player_access, (isset($e['linknumber']) ? ($e['linknumber'] * 100) + 1 : 0), true);
        $ui .= '<div class="hidden text__6197_' . $e['playerid'] . '">' . $e['playertext'] . '</div>';
    } else {
        //Static:
        $ui .= '<input type="hidden" class="text__6197_' . $e['playerid'] . '" value="' . $e['playertext'] . '">';
        $ui .= '<div class="center">';
        $ui .= '<span class="main__title text__6197_' . $e['playerid'] . '">' . $e['playertext'] . '</span>';
        $ui .= '</div>';
    }


    //Player Handle
    $ui .= '<div class="center-block">';

    $ui .= '<div class="creator_headline grey">@<span class="ignore-click ui_playerhandle_' . $e['playerid'] . '" title="ID ' . $e['playerid'] . '">' . $e['playerhandle'] . '</span></div>';

    //Player Location:
    $players___42777 = $CI->config->item('players___42777');
    $order_columns = array();
    foreach ($players___42777 as $sort_id => $sort) {
        $order_columns['linkplayertype = \'' . $sort_id . '\' DESC'] = null;
    }
    foreach ($CI->Links->read(array(
        'linkplayertype IN (' . join(',', $CI->config->item('playerids___42777')) . ')' => null, //Featured Profile
        'linkplayerdown' => $e['playerid'],
    ), array('linkplayerup'), 0, 0, $order_columns) as $location) {
        $ui .= view_featured_links($location['linkplayertype'], $location, $players___42777[$location['linkplayertype']], $focus__node);
    }


    if ($is_app && isset($e['linktext']) && strlen($e['linktext'])) {
        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="' . $e['linktext'] . '"><i class="far fa-info-circle"></i></span>';
    } else if ($linkid && $player_access >= 3) {
        //Main description:
        $ui .= '<div class="linktext_headline grey hideIfEmpty ignore-click ui_linktext_' . $linkid . (in_array($e['linkplayertype'], $CI->config->item('playerids___42294')) ? ' hidden ' : '') . '">' . htmlentities($e['linktext']) . '</div>';
    }

    $ui .= '</div>';


    //Start with Link Note
    $featured_players = '';


    //Featured Players
    $bio = null;
    $players___14036 = $CI->config->item('players___14036');
    $order_columns = array();
    foreach ($players___14036 as $sort_id => $sort) {
        $order_columns['linkplayerup = \'' . $sort_id . '\' DESC'] = null;
    }
    foreach ($CI->Links->read(array(
        'linkplayerup IN (' . join(',', $CI->config->item('playerids___14036')) . ')' => null, //Featured Players
        'linkplayerdown' => $e['playerid'],
        'linkplayertype IN (' . join(',', $CI->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
    ), array(), 0, 0, $order_columns) as $social_link) {

        if (in_array($social_link['linkplayerup'], $CI->config->item('playerids___32172'))) {
            /*
             *
             * Before showing this we must enture all information is updated...
             *
            if (strlen($social_link['linktext'])) {
                //Must always see, show content here:
                $bio .= '<div class="player_bio grey center">' . $social_link['linktext'] . '</div>';
            }
            */
            continue;
        }

        //Determine link type:
        $social_url = false;

        if (in_array(4256, $players___14036[$social_link['linkplayerup']]['m__following'])) {
            //We made sure not the current website:
            $social_url = 'href="' . $social_link['linktext'] . '" target="_blank"';
        } elseif (in_array(32097, $players___14036[$social_link['linkplayerup']]['m__following'])) {
            $social_url = 'href="mailto:' . $social_link['linktext'] . '"';
        } elseif (in_array(42181, $players___14036[$social_link['linkplayerup']]['m__following'])) {
            //Phone Number
            $social_url = 'href="' . phone_href($social_link['linkplayerup'], $social_link['linktext']) . '"';
        }

        $info = (strlen($social_link['linktext']) && !$social_url ? $players___14036[$social_link['linkplayerup']]['m__title'] . ': ' . $social_link['linktext'] : ($social_url ? view_url_clean(one_two_explode('href="', '"', $social_url)) : $players___14036[$social_link['linkplayerup']]['m__title']));

        //Append to links:
        $featured_players .= '<span class="' . ($focus__node ? 'icon-block-sm' : 'icon-block-xs') . '">' . ($social_url && $focus__node ? '<a ' . $social_url . ' data-toggle="tooltip" data-placement="top" title="' . $info . '">' . $players___14036[$social_link['linkplayerup']]['m__cover'] . '</a>' : ($focus__node ? '<a href="' . view_memory(42903, 42902) . $players___14036[$social_link['linkplayerup']]['m__handle'] . '" data-toggle="tooltip" data-placement="top" title="' . $info . '">' . $players___14036[$social_link['linkplayerup']]['m__cover'] . '</a>' : '<span data-toggle="tooltip" data-placement="top" title="' . $info . '">' . $players___14036[$social_link['linkplayerup']]['m__cover'] . '</span>')) . '</span>';

    }


    //Start with top bar:
    if (!$is_app && $player_access >= 1) {

        //Player Link Groups
        $linkplayertype_id = 0;
        $linkplayertype_ui = '';
        if ($linkid) {
            foreach ($CI->config->item('players___31770') as $linkplayertype1 => $m1) {
                if (in_array($e['linkplayertype'], $CI->config->item('playerids___' . $linkplayertype1))) {
                    foreach ($CI->Links->read(array(
                        'linkid' => $linkid,
                    ), array('linkplayercreator')) as $linker) {
                        $linkplayertype_ui .= '<span class="' . ($focus__node ? 'icon-block-sm' : 'icon-block-xs') . '">';
                        $linkplayertype_ui .= searchingle_select_instant($linkplayertype1, $e['linkplayertype'], $player_access, false, $e['playerid'], $linkid);
                        $linkplayertype_ui .= '</span>';
                    }
                    $linkplayertype_id = $linkplayertype1;
                    break;
                }
            }
        }

        //Top Bar
        foreach ($CI->config->item('players___31963') as $linkplayertype_target_bar => $m_target_bar) {

            //See if missing superpower?
            $superpowers_required = array_intersect($CI->config->item('playerids___10957'), $m_target_bar['m__following']);
            if (count($superpowers_required) && !player_session(end($superpowers_required))) {
                continue;
            }

            if ($linkplayertype_target_bar == 31770 && $linkid && $superpower_10939) {

                $featured_players .= $linkplayertype_ui;

            } elseif (0 && $linkplayertype_target_bar == 42795 && $player_session && $player_session['playerid'] != $e['playerid'] && count($CI->Links->read(array(
                    'linkplayerdown' => $e['playerid'],
                    'linkplayerup' => 4430, //Active Member
                    'linkplayertype IN (' . join(',', $CI->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
                )))) {

                //Allow to follow fellow players:
                $followings = $CI->Links->read(array(
                    'linkplayerup' => $e['playerid'],
                    'linkplayerdown' => $player_session['playerid'],
                    'linkplayertype IN (' . join(',', $CI->config->item('playerids___42795')) . ')' => null, //Follow
                ), array(), 1, 0, array('linknumber' => 'ASC'));

                if (count($followings) || $player_access >= 3) {
                    $featured_players .= '<span class="' . ($focus__node ? 'icon-block-sm' : 'icon-block-xs') . '">' . searchingle_select_instant(42795, (count($followings) ? $followings[0]['linkplayertype'] : 0), $player_session && $player_access >= 3, false, $e['playerid'], (count($followings) ? $followings[0]['linkid'] : 0)) . '</span>';
                }

            } elseif ($linkplayertype_target_bar == 41037 && $player_access >= 3 && !$focus__node) {

                //Selector
                $featured_players .= '<span class="' . ($focus__node ? 'icon-block-sm' : 'icon-block-xs') . ' ignore-click">';
                $featured_players .= '<input class="form-check-input" type="checkbox" value="" playerid="' . $e['playerid'] . '" id="selectorplayer_' . $e['playerid'] . '" aria-label="...">';
                $featured_players .= '</span>';

            } elseif ($linkplayertype_target_bar == 13006 && $has_sortable && $player_access >= 3) {

                //Sort Player
                $featured_players .= '<span class="' . ($focus__node ? 'icon-block-sm' : 'icon-block-xs') . ' sortplayer_frame hidden">';
                $featured_players .= '<span title="' . $m_target_bar['m__title'] . '" class="sortplayer_grab">' . $m_target_bar['m__cover'] . '</span>';
                $featured_players .= '</span>';

            } elseif ($linkplayertype_target_bar == 14980 && $player_access >= 3) {

                $action_buttons = null;

                if (!$linkid) {
                    $focus_dropdown = 12887; //Player Dropdown
                } elseif ($linkplayertype_id == 32292) { //Player/Player Links
                    $focus_dropdown = 14956; //Player/Player Dropdown
                } elseif ($linkplayertype_id == 6255) { //Discoveries
                    $focus_dropdown = 32070; //Player>Discoveries Dropdown
                } elseif ($linkplayertype_id == 13550) { //Idea/Player Links
                    $focus_dropdown = 28792; //Player/Idea Dropdown
                } else {
                    $focus_dropdown = 0;
                }

                if ($focus_dropdown > 0 && is_array($CI->config->item('players___' . $focus_dropdown))) {
                    foreach ($CI->config->item('players___' . $focus_dropdown) as $playerid_dropdown => $m_dropdown) {

                        //Skip if missing superpower:
                        $superpowers_required = array_intersect($CI->config->item('playerids___10957'), $m_dropdown['m__following']);
                        if (count($superpowers_required) && !player_session(end($superpowers_required))) {
                            continue;
                        }

                        $anchor = '<span class="icon-block">' . $m_dropdown['m__cover'] . '</span>' . $m_dropdown['m__title'];


                        if ($playerid_dropdown == 4997) {

                            $action_buttons .= '<a href="javascript:void(0);" onclick="link_preview(4997,' . $e['playerid'] . ')" class="dropdown-item main__title">' . $anchor . '</a>';

                        } elseif ($playerid_dropdown == 6287) {

                            //App Store
                            if (in_array($e['playerid'], $CI->config->item('playerids___6287'))) {
                                $action_buttons .= '<a href="' . view_app_link($e['playerid']) . '" class="dropdown-item main__title">' . $anchor . '</a>';
                            }

                        } elseif ($playerid_dropdown == 31912 && $player_access >= 3) {

                            //Edit Player
                            $action_buttons .= '<a href="javascript:void(0);" onclick="player_editor(' . $e['playerid'] . ',' . $linkid . ')" class="dropdown-item main__title">' . $anchor . '</a>';

                        } elseif ($playerid_dropdown == 29771 && $player_access >= 3) {

                            //Clone:
                            $action_buttons .= '<a href="javascript:void(0);" onclick="player_copy(' . $e['playerid'] . ')" class="dropdown-item main__title">' . $anchor . '</a>';

                        } elseif ($playerid_dropdown == 10673 && $linkid > 0 && $player_access >= 3 && $superpower_10939) {

                            //UNLINK
                            $action_buttons .= '<a href="javascript:void(0);" onclick="link_delete(' . $linkid . ', ' . $e['linkplayertype'] . ')" class="dropdown-item main__title">' . $anchor . '</span></a>';

                        } elseif ($playerid_dropdown == 42649 && $player_access >= 3) {

                            //Delete Player
                            $action_buttons .= '<li><hr class="dropdown-divider"></li>';
                            $action_buttons .= '<a href="javascript:void();" onclick="player_delete(' . $e['playerid'] . ', ' . $linkid . ', 0)" class="dropdown-item main__title">' . $anchor . '</a>';

                        } elseif ($playerid_dropdown == 13007 && $player_access >= 3) {

                            //Reset Alphabetic order
                            $action_buttons .= '<a href="javascript:void(0);" onclick="link_sort_reset()" class="dropdown-item main__title">' . $anchor . '</a>';

                        } elseif (in_array($playerid_dropdown, $CI->config->item('playerids___6287')) && $player_access >= 3) {

                            //Standard button
                            $action_buttons .= '<a href="' . view_app_link($playerid_dropdown) . view_memory(42903, 42902) . $e['playerhandle'] . '" class="dropdown-item main__title">' . $anchor . '</a>';

                        }
                    }
                }

                //Any items found?
                if ($action_buttons && $focus_dropdown > 0) {
                    //Right Action Menu
                    $players___14980 = $CI->config->item('players___14980'); //Dropdowns

                    $featured_players .= '<span class="' . ($focus__node ? 'icon-block-sm' : 'icon-block-xs') . '">';
                    $featured_players .= '<div class="dropdown inline-block">';
                    $featured_players .= '<button type="button" class="btn no-left-padding no-right-padding" id="action_menuplayer_' . $e['playerid'] . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="' . $players___14980[$focus_dropdown]['m__title'] . '">' . $players___14980[$focus_dropdown]['m__cover'] . '</button>';
                    $featured_players .= '<div class="dropdown-menu" aria-labelledby="action_menuplayer_' . $e['playerid'] . '">';
                    $featured_players .= $action_buttons;
                    $featured_players .= '</div>';
                    $featured_players .= '</div>';
                    $featured_players .= '</span>';
                }
            }
        }
    }


    $ui .= $bio;

    if ($focus__node) {
        $ui .= '<div class="center-block">';
        $ui .= $featured_players;
        $ui .= '</div>';
    }


    $ui .= '</div>';
    $ui .= '</div>';


    //Bottom Bar
    if (!$is_app && $player_access >= 1) {

        $ui .= '<div class="card_cards hideIfEmpty">';

        if (!$focus__node) {

            $ui .= $featured_players;

            //Also Append bottom bar / main menu:
            foreach ($CI->config->item('players___31916') as $playerid_bottom_bar => $m_bottom_bar) {
                $superpowers_required = array_intersect($CI->config->item('playerids___10957'), $m_bottom_bar['m__following']);
                if (count($superpowers_required) && !player_session(end($superpowers_required))) {
                    continue;
                }
                if (in_array($playerid_bottom_bar, $CI->config->item('playerids___42376')) && !$player_session) {
                    //Private content without being a member, so dont even show the counters:
                    continue;
                }

                $ui .= '<span class="hideIfEmpty">';
                $ui .= players_query($playerid_bottom_bar, $e['playerid']);
                $ui .= '</span>';
            }
        }

        $ui .= '</div>';
    }


    $ui .= '</div>';

    return $ui;

}


function view_player_input($cache_playerid, $current_value, $s__id, $idea_access, $tabindex = 0, $extra_large = false)
{

    $CI =& get_instance();
    $players___12112 = $CI->config->item('players___12112');
    $current_value = htmlentities($current_value);
    $name = 'input' . substr(md5($cache_playerid . $current_value . $s__id . $idea_access . $tabindex), 0, 8);

    //Define element attributes:
    $attributes = ($idea_access >= 3 ? '' : 'disabled') . ' spellcheck="false" tabindex="' . $tabindex . '" old-value="' . $current_value . '" id="input_' . $cache_playerid . '_' . $s__id . '" class="form-control 
     inline-block editing-mode x_set_class_text text__' . $cache_playerid . '_' . $s__id . ($extra_large ? ' texttype_lg ' : ' texttype_sm ') . ' textplayer_' . $cache_playerid . '" cache_playerid="' . $cache_playerid . '" playerid="' . $s__id . '" ';

    //Also Append Counter to the end?
    if ($extra_large) {

        $focus_element = '<textarea name="' . $name . '" placeholder="' . $players___12112[$cache_playerid]['m__title'] . '" ' . $attributes . '>' . $current_value . '</textarea>';

    } else {

        $focus_element = '<input type="text" name="' . $name . '" data-lpignore="true" placeholder="__" value="' . $current_value . '" ' . $attributes . ' />';

    }

    return '<span class="span__' . $cache_playerid . ' ' . (!($idea_access >= 3) ? ' edit-locked ' : '') . '">' . $focus_element . '</span>';

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
