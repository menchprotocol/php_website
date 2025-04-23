<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Controller extends CI_Controller
{

    public $player_session;

    function __construct()
    {

        parent::__construct();

        $this->output->enable_profiler(FALSE);

        $this->player_session = player_session();


        date_default_timezone_set('America/Los_Angeles');

        @session_start();

        //AUTO Login player if has cookie?
        $is_ajax = false;
        $player_user = false;
        $first_segment = ($is_ajax && isset($_POST['js_request_uri']) ? $_POST['js_request_uri'] : $this->uri->segment(1));
        $_SERVER['REQUEST_URI'] = (isset($_POST['js_request_uri']) ? $_POST['js_request_uri'] : @$_SERVER['REQUEST_URI']);
        $_SERVER['REQUEST_URI'] = (strlen($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : view_app_link(4269));
        $player_session = player_session();
        $is_login_verified = isset($_GET['playerhandle']) && $_GET['playerhandle'] != 'SuccessfulWhale' && isset($_GET['hash']) && isset($_GET['time']) && ($_GET['time'] + 604800) > time() && strlen($_GET['playerhandle']) && view_hash($_GET['time'] . $_GET['playerhandle']) == $_GET['hash'];

        if (
            !$player_session
            && !array_key_exists(strtolower($first_segment), $this->config->item('handlplayers___14582'))
            && (isset($_COOKIE['auth_cookie']) || $is_login_verified) //We can auto login with either method:
        ) {

            if ($is_login_verified) {

                foreach ($this->Players->read(array(
                    'LOWER(playerhandle)' => strtolower($_GET['playerhandle']),
                )) as $player_session) {

                    //Login:
                    $this->Players->activate($player_session, true);

                    //Log them in:
                    if (!$is_ajax) {
                        header("Location: " . $_SERVER['REQUEST_URI'], true, 307);
                        exit;
                    }

                }

            } elseif (isset($_COOKIE['auth_cookie'])) {

                $player_session = verify_cookie();
                if ($player_session) {
                    //Log them in:
                    if (!$is_ajax) {
                        header("Location: " . $_SERVER['REQUEST_URI'], true, 307);
                        exit;
                    }
                }
            }

            //Log them in:
            if (!$is_ajax) {
                header("Location: " . view_app_link(4269) . (strlen($_SERVER['REQUEST_URI']) ? '?url=' . urlencode($_SERVER['REQUEST_URI']) : ''), true, 307);
                exit;
            }

        }

    }

    function index()
    {
        //Home:
        $this->load(14565);
    }

    function passthrough($newhandle) {
        redirect($newhandle, 'location', 301);
    }

    function load($app_playerid = 14563 /* Error if none provided */, $focus_handle = 0, $focus_hashtag = 0, $target_hashtag = 0)
    {

        $memory_detected = is_array($this->config->item('playerids___6287')) && count($this->config->item('playerids___6287'));
        if (!$memory_detected) {
            //Since we don't have the memory created we must load the app that does so:
            $app_playerid = 4527;
        }

        //Any ideas passed?
        $players___6287 = $this->config->item('players___6287'); //APP
        $flash_message = false;
        $focus_e = null; //Sourcing
        $focus_i = null; //Ideation/Discovery
        $target_i = null; //Discovery


        if (isset($_GET['playerhandle']) && $_GET['playerhandle'] == 'SuccessfulWhale') {
            $_GET['playerhandle'] = '';
            $focus_handle = '';
        } elseif ($focus_handle && strlen($focus_handle) && !isset($_GET['playerhandle'])) {
            $_GET['playerhandle'] = $focus_handle;
        }
        if ($focus_hashtag && strlen($focus_hashtag) && !isset($_GET['ideahashtag'])) {
            $_GET['ideahashtag'] = $focus_hashtag;
        }
        if (!isset($_GET['playerhandle'])) {
            $_GET['playerhandle'] = 0;
        }
        if (!isset($_GET['ideahashtag'])) {
            $_GET['ideahashtag'] = 0;
        }


        if ($target_hashtag && strlen($target_hashtag)) {
            //Verify:
            foreach ($this->Ideas->read(array(
                'LOWER(ideahashtag)' => strtolower($target_hashtag),
            )) as $idea_found) {
                $target_i = $idea_found;
            }
        }


        if (strlen($_GET['ideahashtag'])) {

            //Validate Focus Idea:
            if ($target_i && $_GET['ideahashtag'] == view_memory(6404, 4235)) {

                //This is the starting point:
                $_GET['ideahashtag'] = $target_hashtag;
                $focus_i = $target_i;

            } else {

                foreach ($this->Ideas->read(array(
                    'LOWER(ideahashtag)' => strtolower($_GET['ideahashtag']),
                )) as $idea_found) {
                    $focus_i = $idea_found;
                }

            }

            if (!$focus_i) {
                //See if we can find via ID?
                if (is_numeric($_GET['ideahashtag'])) {
                    foreach ($this->Ideas->read(array(
                        'ideaid' => $_GET['ideahashtag'],
                    )) as $idea_found) {
                        $focus_i = $idea_found;
                    }
                }
            }

            if ($app_playerid == 33286 && $focus_i && $focus_i['ideahashtag'] !== $_GET['ideahashtag']) {
                //Adjust URL Case Sensitive:
                return get_redirected(view_memory(42903, 33286) . $focus_i['ideahashtag']);
            }
        }


        if (isset($_GET['playerhandle']) && strlen($_GET['playerhandle'])) {
            foreach ($this->Players->read(array(
                'LOWER(playerhandle)' => strtolower($_GET['playerhandle']),
            )) as $player_found) {
                $focus_e = $player_found;
            }
            if (!$focus_e) {
                //See if we need to lookup the ID:
                if (is_numeric($_GET['playerhandle'])) {
                    //Maybe its an ID?
                    foreach ($this->Players->read(array(
                        'playerid' => $_GET['playerhandle'],
                    )) as $player_found) {
                        $focus_e = $player_found;
                    }
                }
            }
            if ($app_playerid == 42902 && $focus_e && $focus_e['playerhandle'] !== $_GET['playerhandle']) {
                //Adjust URL Case Sensitive:
                return get_redirected(view_memory(42903, 42902) . $focus_e['playerhandle']);
            }
        }


        if ($memory_detected && !in_array($app_playerid, $this->config->item('playerids___6287'))) {
            //Invalid App:
            return get_redirected(view_memory(42903, 42902) . $players___6287[$app_playerid]['m__handle'], '<div class="alert alert-danger" role="alert">@' . $players___6287[$app_playerid]['m__handle'] . ' Is not an APP, yet 🤔</div>');
        } elseif ($memory_detected && !in_array($app_playerid, $this->config->item('playerids___42922'))) {
            //Validate Required App input:
            if (in_array($app_playerid, $this->config->item('playerids___42905')) && !$focus_e) {
                return get_redirected(home_url(), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Error: @' . $_GET['playerhandle'] . ' is not a valid Player handle.</div>');
            } elseif (in_array($app_playerid, $this->config->item('playerids___44329')) && (!$focus_i || !$target_i)) {
                return get_redirected(home_url(), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Error: Both #' . $_GET['ideahashtag'] . ' & #' . $target_hashtag . ' must be valid hashtags.</div>');
            } elseif (in_array($app_playerid, $this->config->item('playerids___42911')) && !$focus_i) {
                return get_redirected(home_url(), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Error: #' . $_GET['ideahashtag'] . ' is not a valid idea hashtag.</div>');
            }
        }


        $linkplayerdown = ($focus_e ? $focus_e['playerid'] : 0);
        $linkidearight = ($focus_i ? $focus_i['ideaid'] : 0);
        $linkidealeft = ($target_i ? $target_i['ideaid'] : 0);

        //Run App
        $player_session = false;
        $player_http_request = (isset($_SERVER['SERVER_NAME']) ? 1 : 0);

        if ($memory_detected && in_array($app_playerid, $this->config->item('playerids___42920'))) {
            boost_power();
        }

        if ($memory_detected && $player_http_request) {

            //Needs superpowers?
            $player_session = player_session();

            if ($player_session && isset($player_session['e__id'])) {
                //Old player, must log out:
                header("Location: /logout", true, 301);
                return false;
            }

            //Auto Login?
            if (isset($_GET['hash']) && isset($_GET['time']) && $focus_e) {

                //Validate Hash:
                if ($_GET['hash'] == view_hash($_GET['time'] . $focus_e['playerhandle'])) {

                    if ($focus_i) {
                        if (idea_is_startable($focus_i)) {
                            $flash_message = '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="far fa-play"></i></span>You have started discovering this idea. Scroll to the bottom & go next to continue.</div>';
                        } else {
                            $this->Links->idea_discovered(idea_type_discovery($focus_i), $focus_e['playerid'], ($target_i ? $target_i['ideaid'] : 0), $focus_i);
                            $this->Links->idea_discovered(29393, $focus_e['playerid'], ($target_i ? $target_i['ideaid'] : 0), $focus_i);

                            //Inform user of changes:
                            $flash_message = '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="far fa-check-circle"></i></span>Ideas has been idea_discovered</div>';
                        }
                    }

                    //If not logged in, log them in:
                    if (!$player_session) {
                        $session_data = $this->Players->activate($player_session, true);
                    }

                }
            }
        }


        //Cache App?
        $ui = null;
        $new_cache = false;
        $cache_linktime = null;
        $linkplayercreator = ($player_http_request ? ($player_session ? $player_session['playerid'] : 14068 /* GUEST */) : 7274 /* CRON JOB */);
        $skip_idea_privacy_check = !$memory_detected || in_array($app_playerid, $this->config->item('playerids___43388'));
        $player_access = player_access(null, $focus_e['playerid'], $focus_e);
        $idea_access = idea_access(null, $focus_i['ideaid'], $focus_i);
        $target_idea_access = idea_access(null, $target_i['ideaid'], $target_i);

        //MEMBER REDIRECT?
        if ($player_http_request && $memory_detected) {

            //Missing App, Player or Idea Access?
            $missing_access = false; //Assume they have access
            $superpowers_required = array_intersect($this->config->item('playerids___10957'), $players___6287[$app_playerid]['m__following']);
            if ($player_session && in_array($app_playerid, $this->config->item('playerids___14639'))) {
                //Should redirect them:
                return get_redirected(view_memory(42903, 42902) . $player_session['playerhandle']);
            } elseif (!$player_session && in_array($app_playerid, $this->config->item('playerids___14740'))) {
                //Should redirect them:
                $missing_access = 'Login or register a free account to continue.';
            } elseif (count($superpowers_required) && !player_session(end($superpowers_required))) {
                $players___10957 = $this->config->item('players___10957');
                $missing_access = 'Error: You Cannot Access ' . $players___6287[$app_playerid]['m__title'] . ' as it requires the superpower of ' . $players___10957[end($superpowers_required)]['m__title'] . '.';
            } elseif ($focus_e && !$player_access) {
                $missing_access = 'Error: You Cannot Access @' . $focus_e['playerhandle'] . ' due to Privacy Settings.';
            } elseif (!$skip_idea_privacy_check && $focus_i && !$idea_access) {
                $missing_access = 'Error: You Cannot Access Focus #' . $focus_i['ideahashtag'] . ' due to Privacy Settings.';
            } elseif (!$skip_idea_privacy_check && $target_i && !$target_idea_access) {
                $missing_access = 'Error: You Cannot Access Target #' . $target_i['ideahashtag'] . ' due to Privacy Settings.';
            }


            if ($missing_access) {
                //Redirect:
                return get_redirected((!$player_session ? view_app_link(4269) . '?url=' . urlencode($_SERVER['REQUEST_URI']) : home_url()), '<div class="alert alert-warning" role="alert">' . $missing_access . '</div>');
            }
        }


        if ($memory_detected) {

            if (in_array($app_playerid, $this->config->item('playerids___14599')) && !in_array($app_playerid, $this->config->item('playerids___12741'))) {

                if (!isset($_GET['reset_cache'])) {
                    //Fetch Most Recent Cache:
                    foreach ($this->Links->read(array(
                        'linkplayerdomain' => website_setting(0),
                        'linkplayertype' => 44179, //Triggered
                        'linkplayerup' => 14599, //Cache App
                        'linkplayerdown' => $app_playerid,
                    ), array(), 1, 0, array('linktime' => 'DESC')) as $latest_cache) {
                        if (strtotime($latest_cache['linktime']) <= (time() - view_memory(6404, 14599))) {
                            //Its expired, void it:
                            $this->Links->delete($latest_cache['linkid']);
                        } else {
                            $ui = $latest_cache['linktext'];
                            $cache_linktime = '<div class="texttransparent center main__title">Updated ' . view_time_difference($latest_cache['linktime']) . ' Ago</div>';
                        }
                    }
                }

                if (!$ui) {
                    //No recent cache found, create a new one:
                    $new_cache = true;
                }
            }
        }


        $title = null;
        if ($focus_i) {
            $title .= view_idea_title($focus_i, true) . ' | ';
        }
        if ($target_i) {
            $title .= view_idea_title($target_i, true) . ' | ';
        }
        if ($focus_e) {
            $title .= $focus_e['playertext'] . ' @' . $focus_e['playerhandle'] . ' | ';
        }
        if (!$title) {
            //Append app name since no title:
            $title .= $players___6287[$app_playerid]['m__title'] . ' | ';
        }
        //Always Append Website at the end:
        $title .= ($memory_detected ? get_domain('m__title') : 'Loading Memory');


        $view_input = array(
            'app_playerid' => $app_playerid,
            'linkplayercreator' => $linkplayercreator,
            'player_session' => $player_session,
            'player_http_request' => $player_http_request,
            'memory_detected' => $memory_detected,

            'focus_e' => $focus_e,
            'focus_i' => $focus_i,
            'target_i' => $target_i,

            '$player_access' => $player_access,
            '$idea_access' => $idea_access,
            '$target_idea_access' => $target_idea_access,

            'title' => $title,
            'flash_message' => $flash_message,
        );

        if (!$ui) {
            //Prep view:
            $app_handler = ($memory_detected ? strtolower($players___6287[$app_playerid]['m__handle']) : 'memory');
            $raw_app = $this->load->view($app_handler, $view_input, true);
            $ui .= $raw_app;
        }


        if ($new_cache) {
            $cache_x = $this->Links->create(array(
                'linkplayerdomain' => website_setting(0),
                'linkplayertype' => 44179, //Triggered
                'linkplayerup' => 14599, //Cache App
                'linkplayerdown' => $app_playerid,

                'linkplayercreator' => $linkplayercreator,
                'linktext' => $ui,
                'linkidealeft' => $linkidealeft,
                'linkidearight' => $linkidearight,
            ));
        }


        //App title?
        if ($memory_detected && in_array($app_playerid, $this->config->item('playerids___42928'))) {
            $ui = '<h1><span style="font-size:2em !important;">' . $players___6287[$app_playerid]['m__cover'] . '</span> ' . $players___6287[$app_playerid]['m__title'] . '</h1>' . $ui;
        }


        //Check to ensure they have started:
        if ($app_playerid == 30795 && $target_i && $focus_i && $player_session && $target_i['ideahashtag'] == $focus_i['ideahashtag']) {

            //Starting point, make sure all good:
            if (!idea_is_startable($target_i)) {

                //Not a valid starting point:
                return get_redirected(home_url(), '<div class="alert alert-warning" role="alert">#' . $target_i['ideahashtag'] . ' is not an active starting point.</div>');

            } elseif (!idea_started($player_session['playerid'], $target_i['ideahashtag'])) {

                //Not yet started, add to their starting point:
                $completion_status = $this->Links->idea_discovered(4235, $player_session['playerid'], 0, $target_i);

                //Now return next idea:
                $next__url = $this->Links->idea_next($player_session['playerid'], $target_i['ideahashtag'], $target_i);

                if ($next__url) {
                    //Go Next:
                    return get_redirected(view_memory(42903, 30795) . $target_i['ideahashtag'] . '/' . $next__url);
                }

            }

        }


        //Delivery App
        if (!$memory_detected) {

            echo $ui;

        } else {

            if (in_array($app_playerid, $this->config->item('playerids___12741'))) {

                //Raw UI:
                echo $raw_app;

            } else {

                //Regular UI:
                //Load App:
                echo $this->load->view('websiteheader', $view_input, true);
                echo $ui;
                echo $cache_linktime;
                echo $this->load->view('websitefooter', array(), true);

            }
        }
    }


    /*
     * 
     * AJAX FUNCTION CALLS:
     * 
     * */


    function link_popover()
    {

        if (isset($_POST['handle_string']) && strlen($_POST['handle_string']) > 1 && in_array(substr($_POST['handle_string'], 0, 1), array('#', '@'))) {
            if (substr($_POST['handle_string'], 0, 1) == '#') {
                foreach ($this->Ideas->read(array(
                    'LOWER(ideahashtag)' => strtolower(substr($_POST['handle_string'], 1)),
                )) as $i) {
                    echo idea_view(6255, $i);
                    return true;
                }
            } elseif (substr($_POST['handle_string'], 0, 1) == '@') {
                foreach ($this->Players->read(array(
                    'LOWER(playerhandle)' => strtolower(substr($_POST['handle_string'], 1)),
                )) as $e) {
                    echo player_view(12274, $e);
                    return true;
                }
            }

            //Did not find, had error:
            echo '<div class="alert alert-danger" role="alert">Could not find ' . $_POST['handle_string'] . '</div>';
            return false;
        }

        //Did not find, had error:
        echo '<div class="alert alert-danger" role="alert">Missing handle_string variable</div>';
        return false;

    }

    function idea_editor()
    {

        $player_session = player_session(null, 0, $this->player_session);
        if (!$player_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['ideaid']) || !isset($_POST['linkid']) || !isset($_POST['current_ideatype'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core IDs',
            ));
        }


        $ideaid = 0; //New idea
        $ideatype = intval($_POST['current_ideatype']);
        $created_ideaid = 0;

        if ($_POST['ideaid'] > 0) {

            $is = $this->Ideas->read(array(
                'ideaid' => $_POST['ideaid'],
            ));
            if (!count($is)) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Idea is no longer active',
                ));
            } elseif (!idea_access($is[0]['ideahashtag'], 0, $is[0])) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'You are missing permission to edit this idea',
                ));
            }


            $ideaid = intval($is[0]['ideaid']);
            if (!$ideatype) {
                $ideatype = intval($is[0]['ideatype']);
            }

        } else {

            //Create a new idea:
            $idea_new = $this->Ideas->create(array(
                'ideatext' => null,
                'ideatype' => $_POST['current_ideatype'],
            ), $player_session['playerid']);

            $ideaid = $idea_new['idea_create']['ideaid'];
            $created_ideaid = $ideaid;

        }


        //Fetch dynamic data based on idea type:
        $return_inputs = array();
        $players___4737 = $this->config->item('players___4737'); // Idea Status
        $players___42179 = $this->config->item('players___42179'); //Dynamic Input Fields
        $players___11035 = $this->config->item('players___11035'); //Encyclopedia

        foreach (array_intersect($this->config->item('playerids___' . $ideatype), $this->config->item('playerids___42179')) as $dynamic_playerid) {

            $superpowers_required = array_intersect($this->config->item('playerids___10957'), $players___42179[$dynamic_playerid]['m__following']);
            if (count($superpowers_required) && !player_session(end($superpowers_required), 0, $this->player_session)) {
                continue;
            }

            //Let's first determine the data type:
            $data_types = array_intersect($players___42179[$dynamic_playerid]['m__following'], $this->config->item('playerids___4592'));

            if (count($data_types) != 1) {
                //This is strange, we are expecting 1 match only report this:
                log_error('Found ' . count($data_types) . ' Data Types (Expecting exactly 1) for @' . $dynamic_playerid . ': Check @4592 to see what is wrong', array(
                    'linkplayercreator' => $player_session['playerid'],
                    'linkplayerdown' => $dynamic_playerid,
                    'linkidearight' => $ideaid,
                ));
                continue; //Go to the next dynamic data type
            }

            //We found 1 match as expected:
            foreach ($data_types as $data_type_this) {
                $data_type = $data_type_this;
                break;
            }

            if (in_array($data_type, $this->config->item('playerids___42188'))) {

                //Single or Multiple Choice:
                array_push($return_inputs, array(
                    'd__id' => $dynamic_playerid,
                    'd__is_radio' => 1,
                    'd_linkid' => 0,
                    'd__html' => view_instant_select($dynamic_playerid, 0, $ideaid),
                    'd__value' => ($ideaid > 0 ? $ideaid : ''),
                    'd__type_name' => '',
                    'd__placeholder' => '',
                    'd__profile_header' => '',
                ));

            } else {

                $this_data_type = $this->config->item('players___' . $data_type);
                $players___4592 = $this->config->item('players___4592'); //Data types
                $players___42179 = $this->config->item('players___42179'); //Dynamic Input Field
                $players___11035 = $this->config->item('players___11035'); //Encyclopedia

                //Fetch the current value:
                $counted = 0;
                $unique_values = array();
                if ($ideaid > 0) { //Must have an original ID to possibly have a value...
                    foreach ($this->Links->read(array(
                        'linkplayertype IN (' . join(',', $this->config->item('playerids___42252')) . ')' => null, //Plain Link
                        'linkidearight' => $ideaid,
                        'linkplayerup' => $dynamic_playerid,
                    ), array('linkplayerup')) as $selected_e) {
                        if (strlen($selected_e['linktext']) && !in_array($selected_e['linktext'], $unique_values)) {
                            $counted++;
                            array_push($unique_values, $selected_e['linktext']);
                            array_push($return_inputs, array(
                                'd__id' => $dynamic_playerid,
                                'd__is_radio' => 0,
                                'd_linkid' => $selected_e['linkid'],
                                'd__html' => view_dynamic_headline($dynamic_playerid, $players___42179[$dynamic_playerid], $selected_e),
                                'd__value' => $selected_e['linktext'],
                                'd__type_name' => html_input_type($data_type),
                                'd__placeholder' => (strlen($this_data_type[$dynamic_playerid]['m__message']) ? $this_data_type[$dynamic_playerid]['m__message'] : $players___4592[$data_type]['m__title'] . '...'),
                                'd__profile_header' => '',
                            ));
                        }
                    }
                }


                if (!$counted) {
                    foreach ($this->Players->read(array(
                        'playerid' => $dynamic_playerid,
                    )) as $selected_e) {
                        array_push($return_inputs, array(
                            'd__id' => $dynamic_playerid,
                            'd__is_radio' => 0,
                            'd_linkid' => 0,
                            'd__html' => view_dynamic_headline($dynamic_playerid, $players___42179[$dynamic_playerid], $selected_e),
                            'd__value' => '',
                            'd__type_name' => html_input_type($data_type),
                            'd__placeholder' => (strlen($this_data_type[$dynamic_playerid]['m__message']) ? $this_data_type[$dynamic_playerid]['m__message'] : $players___4592[$data_type]['m__title'] . '...'),
                            'd__profile_header' => '',
                        ));
                    }
                }
            }
        }

        $return_array = array(
            'status' => 1,
            'return_inputs' => $return_inputs,
            'created_ideaid' => $created_ideaid,
        );

        //Return everything we found:
        return view_json($return_array);

    }


    function idea_delete()
    {

        $player_session = player_session(null, 0, $this->player_session);
        $migrateid = 0;

        if (!$player_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['ideaid']) || !isset($_POST['focus__id']) || !isset($_POST['migratehandle'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core IDs',
            ));
        } elseif (idea_access(null, $_POST['ideaid']) < 3) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Access to delete this idea',
            ));
        } elseif (strlen($_POST['migratehandle']) > 1) {
            $valid_handle = $this->Ideas->read(array(
                'ideaid !=' => $_POST['ideaid'],
                'LOWER(ideahashtag)' => strtolower(str_replace('#', '', $_POST['migratehandle'])),
            ));
            if (!count($valid_handle)) {
                return view_json(array(
                    'status' => 0,
                    'message' => $_POST['migratehandle'] . ' is not an active hashtag',
                ));
            }
            $migrateid = $valid_handle[0]['ideaid'];
        }

        $delete_redirect = '';
        $delete_element = '';
        //Determine what to do after deleted:
        if ($_POST['ideaid'] == $_POST['focus__id']) {

            //Find Published Followings:
            foreach ($this->Links->read(array(
                'linkplayertype IN (' . join(',', $this->config->item('playerids___42268')) . ')' => null, //IDEA LINKS
                'linkidearight' => $_POST['ideaid'],
            ), array('linkidealeft'), 1) as $previous_i) {
                $delete_redirect = view_memory(42903, 33286) . $previous_i['ideahashtag'];
            }

            //If not found, find active followings:
            if (!$delete_redirect) {
                foreach ($this->Links->read(array(
                    'linkplayertype IN (' . join(',', $this->config->item('playerids___42268')) . ')' => null, //IDEA LINKS
                    'linkidearight' => $_POST['ideaid'],
                ), array('linkidealeft'), 1) as $previous_i) {
                    $delete_redirect = view_memory(42903, 33286) . $previous_i['ideahashtag'];
                }
            }

            //If still not found, go to main page if no followings found:
            if (!$delete_redirect) {
                foreach ($this->Ideas->read(array(
                    'ideaid' => $_POST['ideaid'],
                )) as $i) {
                    $delete_redirect = view_memory(42903, 33286) . $i['ideahashtag'];
                }
            }

        } else {

            //Just delete from UI using JS:
            $delete_element = '.s__12273_' . $_POST['ideaid'];

        }

        //Delete all Links:
        $links_removed = $this->Ideas->delete($_POST['ideaid'], $player_session['playerid'], $migrateid);

        return view_json(array(
            'status' => ($links_removed > 0 ? 1 : 0),
            'message' => 'Idea successfully removed',
            'delete_redirect' => $delete_redirect,
            'delete_element' => $delete_element,
        ));

    }


    function player_delete()
    {

        $player_session = player_session(null, 0, $this->player_session);
        $migrateid = 0;

        if (!$player_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['playerid']) || !isset($_POST['focus__id']) || !isset($_POST['migratehandle'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core IDs',
            ));
        } elseif (player_access(null, $_POST['playerid']) < 3) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Access to delete this idea',
            ));
        } elseif (strlen($_POST['migratehandle']) > 1) {
            $valid_handle = $this->Players->read(array(
                'playerid !=' => $_POST['playerid'],
                'LOWER(playerhandle)' => strtolower(str_replace('@', '', $_POST['migratehandle'])),
            ));
            if (!count($valid_handle)) {
                return view_json(array(
                    'status' => 0,
                    'message' => $_POST['migratehandle'] . ' is not an active handle',
                ));
            }
            $migrateid = $valid_handle[0]['playerid'];
        }


        //Determine what to do after deleted:
        $delete_redirect = '';
        $delete_element = '';

        if ($_POST['playerid'] == $_POST['focus__id']) {

            //Find Published Followings:
            foreach ($this->Links->read(array(
                'linkplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
                'linkplayerdown' => $_POST['playerid'],
            ), array('linkplayerup'), 1, 0, array('playertext' => 'DESC')) as $up_e) {
                $delete_redirect = view_memory(42903, 42902) . $up_e['playerhandle'];
            }

            //If still not found, go to main page if no followings found:
            if (!$delete_redirect) {
                foreach ($this->Players->read(array('playerid' => $_POST['playerid'])) as $e2) {
                    $delete_redirect = view_memory(42903, 42902) . e2['playerhandle'];
                }
            }
        } else {

            //Just delete from UI using JS:
            $delete_element = '.s__12274_' . $_POST['playerid'];

        }

        //Delete all Links:
        $links_removed = $this->Players->delete($_POST['playerid'], $player_session['playerid'], $migrateid);

        return view_json(array(
            'status' => ($links_removed > 0 ? 1 : 0),
            'message' => 'Player successfully removed',
            'delete_redirect' => $delete_redirect,
            'delete_element' => $delete_element,
        ));

    }

    function idea_update()
    {

        $player_session = player_session(null, 0, $this->player_session);
        if (!$player_session) {

            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));

        } elseif (!isset($_POST['save_ideatext'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Idea',
            ));

        } elseif (!isset($_POST['focus__node']) || !isset($_POST['focus__id'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing focus Card/ID',
            ));

        } elseif (!isset($_POST['save_ideahashtag'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing hashtag',
            ));

        } elseif (!isset($_POST['save_ideaid']) || !intval($_POST['save_ideaid'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Idea ID',
            ));

        } elseif (!isset($_POST['next_ideaid']) || !isset($_POST['previous_ideaid']) || !isset($_POST['save_linkplayertype'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Next/Previous ID',
            ));

        } elseif (!isset($_POST['save_linkid']) || !isset($_POST['save_linktext'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Link Data',
            ));

        } elseif (!isset($_POST['save_ideatype']) || !in_array($_POST['save_ideatype'], $this->config->item('playerids___4737'))) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid idea Type',
            ));
        } elseif (strlen($_POST['save_ideatext']) > view_memory(6404, 4736)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Idea message must be less than ' . view_memory(6404, 4736) . ' characters.',
            ));
        }


        $is = $this->Ideas->read(array(
            'ideaid' => $_POST['save_ideaid'],
        ));
        if (!count($is)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Idea Not Valid',
            ));
        }


        $focus__node = ($_POST['focus__node'] == 12273 && $_POST['focus__id'] == $_POST['save_ideaid']);
        if (!isset($_POST['uploaded_media']) || !is_array($_POST['uploaded_media'])) {
            $_POST['uploaded_media'] = array();
        }

        //Might be new if pre-drafting:
        if (!strlen($is[0]['ideatext'])) {

            //See if references only:
            if (strlen($_POST['save_ideatext']) && !count($_POST['uploaded_media']) && !substr_count($_POST['save_ideatext'], "\n") && intval($_POST['save_linkplayertype']) && (intval($_POST['next_ideaid']) || intval($_POST['previous_ideaid']))) {

                $all_hashtags = true;
                $idea_references = array();
                foreach (explode(' ', trim($_POST['save_ideatext'])) as $word) {
                    $found_hashtag = false;
                    if (substr($word, 0, 1) == '#') {
                        $valid_hashtag = false;
                        foreach ($this->Ideas->read(array(
                            'LOWER(ideahashtag)' => strtolower(substr($word, 1)),
                        )) as $idea_found) {
                            $found_hashtag = true;
                            $valid_hashtag = true;
                            array_push($idea_references, $idea_found);
                        }
                        if (!$valid_hashtag && player_session(10939, 0, $this->player_session)) {
                            return view_json(array(
                                'status' => 0,
                                'message' => 'ERROR: ' . $word . ' is not a valid/active Idea',
                            ));
                        }
                    }
                    if (!$found_hashtag) {
                        $all_hashtags = false;
                        break; //It must be a hashtag only reference
                    }
                }

                if ($all_hashtags && count($idea_references) && $_POST['save_linkplayertype'] > 0) {

                    //Return success:
                    foreach ($this->Ideas->read(array(
                        'ideaid' => (intval($_POST['next_ideaid']) > 0 ? intval($_POST['next_ideaid']) : intval($_POST['previous_ideaid'])),
                    )) as $focus_i) {

                        //Append all of these hashtags:
                        foreach ($idea_references as $reference_i) {
                            if (intval($_POST['next_ideaid']) > 0) {
                                $status = $this->Ideas->link($focus_i, $_POST['save_linkplayertype'], $reference_i, $player_session['playerid']);
                            } elseif (intval($_POST['previous_ideaid']) > 0) {
                                $status = $this->Ideas->link($reference_i, $_POST['save_linkplayertype'], $focus_i, $player_session['playerid']);
                            }
                            if (!$status['status']) {
                                return view_json($status);
                            }
                        }

                        //What to focus on depends on how many total ideas added:
                        $return_i = (count($idea_references) >= 2 ? $focus_i : $reference_i);

                        return view_json(array(
                            'status' => 1,
                            'return_ideacache_links' => '',
                            'return_ideacache_full' => idea_view($_POST['focus_group'], $return_i),
                            'redirect_idea' => view_memory(42903, 33286) . $return_i['ideahashtag'],
                            'message' => count($idea_references) . ' ideas linked',
                        ));
                    }
                }
            }

            //Update new idea fields:
            $this->Ideas->update($is[0]['ideaid'], array(
                'ideatype' => $_POST['save_ideatype'],
            ), $player_session['playerid']);
            $is[0]['ideatype'] = trim($_POST['save_ideatype']);

        }

        //Process Media:
        $media_stats = process_media($is[0]['ideaid'], $_POST['uploaded_media']);


        //Validate Idea Message:
        if (!$media_stats['total_media'] && !strlen(trim($_POST['save_ideatext']))) {
            //Since we do not have media, we must have a message:
            return view_json(array(
                'status' => 0,
                'message' => 'Write or Upload something to save.',
            ));
        }


        //Process dynamic inputs if any:
        $players___42179 = $this->config->item('players___42179'); //Dynamic Input Fields
        if ($_POST['save_ideaid'] > 0) {
            for ($p = 1; $p <= view_memory(6404, 42206); $p++) {

                if (!isset($_POST['save_dynamic_' . $p])) {
                    break; //Nothing more to process
                }

                $input_parts = explode('____', $_POST['save_dynamic_' . $p], 3);
                if (!isset($input_parts[0]) || !isset($input_parts[1])) {
                    continue;
                }
                $d_linkid = $input_parts[0];
                $dynamic_playerid = $input_parts[1];
                $dynamic_value = trim($input_parts[2]);

                //Required fields must have an input:
                if (in_array($dynamic_playerid, $this->config->item('playerids___28239')) && !strlen($dynamic_value) && !in_array($dynamic_playerid, $this->config->item('playerids___33331')) && !in_array($dynamic_playerid, $this->config->item('playerids___33332'))) {
                    return view_json(array(
                        'status' => 0,
                        'message' => 'Missing Required Field: ' . $players___42179[$dynamic_playerid]['m__title'],
                    ));
                }

                //Validate input based on its data type, if provided:
                if (strlen($dynamic_value)) {
                    foreach (array_intersect($players___42179[$dynamic_playerid]['m__following'], $this->config->item('playerids___4592')) as $data_type_this) {
                        $data_type_validate = data_type_validate($data_type_this, $dynamic_value, $players___42179[$dynamic_playerid]['m__title']);
                        if (!$data_type_validate['status']) {
                            //We had an error:
                            return view_json($data_type_validate);
                        }
                    }
                }

                //Fetch the current value:
                if ($d_linkid > 0) {
                    $values = $this->Links->read(array(
                        'linkid' => $d_linkid,
                    ));
                }

                if (!$d_linkid || !count($values)) {
                    $values = $this->Links->read(array(
                        'linkplayertype IN (' . join(',', $this->config->item('playerids___42252')) . ')' => null, //Plain Link
                        'linkidearight' => $is[0]['ideaid'],
                        'linkplayerup' => $dynamic_playerid,
                    ));
                }


                //Update if needed:
                if (!strlen($dynamic_value)) {

                    //Remove Link if we have one:
                    if (count($values) && $dynamic_playerid != 11035 /* HACK: Summary are key links that should not be removed */) {
                        $this->Links->delete($values[0]['linkid'], $player_session['playerid']);
                    }

                } elseif (!count($values)) {

                    //Create New Link:
                    $this->Links->create(array(
                        'linkplayercreator' => $player_session['playerid'],
                        'linkplayertype' => 4983, //Co-Author
                        'linkplayerup' => $dynamic_playerid,
                        'linkidearight' => $is[0]['ideaid'],
                        'linktext' => $dynamic_value,
                        'linknumber' => number_linknumber($dynamic_value),
                    ));

                } elseif ($values[0]['linktext'] != $dynamic_value) {

                    //Update Link:
                    $this->Links->update($values[0]['linkid'], array(
                        'linktext' => $dynamic_value,
                        'linkplayercreator' => $player_session['playerid'],
                    ));

                }
            }
        }


        if (strlen($_POST['save_ideahashtag']) && $is[0]['ideahashtag'] !== trim($_POST['save_ideahashtag'])) {

            $validate_update_handle = validate_update_handle($_POST['save_ideahashtag'], $is[0]['ideaid'], null);
            if (!$validate_update_handle['status']) {
                return view_json(array(
                    'status' => 0,
                    'message' => $validate_update_handle['message'],
                ));
            }

            //Save hashtag since changed:
            $this->Ideas->update($is[0]['ideaid'], array(
                'ideahashtag' => trim($_POST['save_ideahashtag']),
            ), $player_session['playerid']);

            //Now Handles everywhere they are referenced:
            foreach ($this->Links->read(array(
                'linkidealeft' => $is[0]['ideaid'],
                'linkplayertype IN (' . join(',', $this->config->item('playerids___42341')) . ')' => null, //Idea References
            ), array('linkidearight')) as $ref) {

                $this->Ideas->update($ref['ideaid'], array(
                    'ideatext' => str_replace('#' . $is[0]['ideahashtag'], '#' . trim($_POST['save_ideahashtag']), $ref['ideatext']),
                ), $player_session['playerid']);

            }

            //Assign new value:
            $is[0]['ideahashtag'] = trim($_POST['save_ideahashtag']);

        }


        //Also have to add as a comment to another idea?
        if (intval($_POST['next_ideaid']) > 0 && $_POST['save_linkplayertype'] > 0) {
            $this->Links->create(array(
                'linkplayercreator' => $player_session['playerid'],
                'linkidealeft' => $_POST['next_ideaid'],
                'linkidearight' => $is[0]['ideaid'],
                'linkplayertype' => $_POST['save_linkplayertype'],
            ));
        } elseif (intval($_POST['previous_ideaid']) > 0 && $_POST['save_linkplayertype'] > 0) {
            $this->Links->create(array(
                'linkplayercreator' => $player_session['playerid'],
                'linkidealeft' => $is[0]['ideaid'],
                'linkidearight' => $_POST['previous_ideaid'],
                'linkplayertype' => $_POST['save_linkplayertype'],
            ));
        }


        //Do we have a link reference message that need to be saved?
        if ($_POST['save_linkid'] > 0 && $_POST['save_linktext'] != 'IGNORE_INPUT') {
            //Fetch Link:
            foreach ($this->Links->read(array(
                'linkid' => $_POST['save_linkid'],
            )) as $this_x) {

                $is[0] = array_merge($is[0], $this_x);

                if ($this_x['linktext'] != trim($_POST['save_linktext'])) {
                    $this->Links->update($this_x['linkid'], array(
                        'linktext' => trim($_POST['save_linktext']),
                        'linkplayercreator' => $player_session['playerid'],
                    ));
                }
            }
        }

        //Update Text:
        $text_updated = $this->Ideas->update($is[0]['ideaid'], array(
            'ideatext' => trim($_POST['save_ideatext']),
        ), $player_session['playerid']);


        foreach ($this->Ideas->read(array(
            'ideaid' => $is[0]['ideaid'],
        )) as $new_i) {
            //Update Search Index:
            update_algolia(12273, $new_i['ideaid']);

            return view_json(array(
                'status' => 1,
                'return_ideacache_links' => view_idea_links($new_i, $player_session['playerid'], $focus__node, $focus__node),
                'return_ideacache_full' => idea_view($_POST['focus_group'], $new_i),
                'save_ideaid' => $is[0]['ideaid'],
                'save_ideatext' => trim($_POST['save_ideatext']),
                'text_updated' => $text_updated,
                'redirect_idea' => (isset($new_i['ideahashtag']) ? view_memory(42903, 33286) . $new_i['ideahashtag'] : null),
                'message' => $media_stats['total_current'] . ' current & ' . $media_stats['total_submitted'] . ' submitted media: ' . $media_stats['total_submitted'] . ' Created, ' . $media_stats['adjust_updated'] . ' Updated & ' . $media_stats['adjust_removed'] . ' Removed while detected ' . $media_stats['adjust_duplicated'] . ' duplicate uploads.',
            ));
        }

    }



    function idea_cover()
    {

        if (!isset($_POST['ideaid']) || !isset($_POST['linkplayertype']) || !isset($_POST['first_segment']) || !isset($_POST['counter'])) {
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Missing core variables</div>';
        } else {

            if (in_array($_POST['linkplayertype'], $this->config->item('playerids___42376')) && !idea_access(null, $_POST['ideaid'])) {

                echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-lock"></i></span>Private</div>';

            } else {

                $discover_linkplayertype = discover_linkplayertype();

                $ui = '';
                $listed_items = 0;
                if (in_array($_POST['linkplayertype'], $this->config->item('playerids___42261')) || in_array($_POST['linkplayertype'], $this->config->item('playerids___42284'))) {

                    //SOURCES
                    $players___4593 = $this->config->item('players___4593'); //Link Types
                    $current_playerhandle = view_valid_handle_player($_POST['first_segment']);
                    foreach (ideas_query($_POST['linkplayertype'], $_POST['ideaid'], 1, false) as $player_session) {
                        if (isset($player_session['playerid'])) {
                            $ui .= view_card(view_memory(42903, 42902) . $player_session['playerhandle'], $current_playerhandle && $player_session['playerhandle'] == $current_playerhandle, $player_session['linkplayertype'], view_cover($player_session['playercover'], true), $player_session['playertext'], $player_session['linktext']);
                            $listed_items++;
                        }
                    }

                } elseif (in_array($_POST['linkplayertype'], $this->config->item('playerids___11020'))) {

                    //IDEAS
                    $players___4737 = $this->config->item('players___4737'); //Idea Types
                    $players___4593 = $this->config->item('players___4593'); //Link Types
                    $current_ideahashtag = (substr($_POST['first_segment'], 0, 1) == '~' ? substr($_POST['first_segment'], 1) : false);

                    foreach (ideas_query($_POST['linkplayertype'], $_POST['ideaid'], 1, false) as $next_i) {
                        if (isset($next_i['ideaid'])) {
                            $ui .= view_card($discover_linkplayertype . view_memory(42903, 33286) . $next_i['ideahashtag'], $next_i['ideahashtag'] == $current_ideahashtag, $next_i['linkplayertype'], (in_array($next_i['ideatype'], $this->config->item('playerids___32172')) ? $players___4737[$next_i['ideatype']]['m__cover'] : ''), view_idea_title($next_i, true), $next_i['linktext']);
                            $listed_items++;
                        }
                    }

                }

                if ($listed_items < $_POST['counter']) {
                    //We have more to show:
                    foreach ($this->Ideas->read(array(
                        'ideaid' => $_POST['ideaid'],
                    )) as $i) {
                        $ui .= view_more($discover_linkplayertype . view_memory(42903, 33286) . $i['ideahashtag'], false, '&nbsp;', '&nbsp;', 'View All');
                    }
                }

                echo $ui;

            }
        }
    }

    function idea_sort_load()
    {

        /*
         *
         * Saves the order of read ideas based on
         * member preferences.
         *
         * */

        $player_session = player_session(null, 0, $this->player_session);

        if (!$player_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['new_x_order']) || !is_array($_POST['new_x_order']) || count($_POST['new_x_order']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing sorting ideas',
            ));
        } elseif (!isset($_POST['linkplayertype']) || !in_array($_POST['linkplayertype'], $this->config->item('playerids___4603'))) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Link Type',
            ));
        }

        //Update the order of their discoveries:
        $updated = 0;
        foreach ($_POST['new_x_order'] as $linknumber => $linkid) {
            if (intval($linkid) > 0 && intval($linknumber) > 0) {
                //Update order of this Link:
                if ($this->Links->update(intval($linkid), array(
                    'linknumber' => $linknumber,
                    'linkplayercreator' => $player_session['playerid'],
                ))) {
                    $updated++;
                }
            }
        }

        //All good:
        return view_json(array(
            'status' => 1,
            'message' => $updated . ' Sorted',
        ));
    }

    function idea_list()
    {
        //Authenticate Member:
        if (!isset($_POST['ideaid']) || intval($_POST['ideaid']) < 1 || !isset($_POST['counter']) || !isset($_POST['linkplayertype']) || intval($_POST['linkplayertype']) < 1) {
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Missing core variables</div>';
        } else {

            $ideas_query = ideas_query($_POST['linkplayertype'], $_POST['ideaid'], 1);
            $ui = '';
            $is = $this->Ideas->read(array(
                'ideaid' => $_POST['ideaid'],
            ));
            if (!count($is) || !$ideas_query) {
                return false;
            }

            if (in_array($_POST['linkplayertype'], $this->config->item('playerids___42376')) && !idea_access(null, $is[0]['ideaid'], $is[0])) {
                return '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-lock"></i></span>Private</div>';
            }

            if (in_array($_POST['linkplayertype'], $this->config->item('playerids___42380'))) {

                //IDEA Link Groups Previous
                $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-' . $_POST['linkplayertype'] . '">';
                foreach ($ideas_query as $previous_i) {
                    $ui .= idea_view(11019, $previous_i);
                }
                $ui .= '</div>';

            } elseif (in_array($_POST['linkplayertype'], $this->config->item('playerids___42265'))) {

                //IDEA Link Groups Next
                $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-' . $_POST['linkplayertype'] . '">';
                foreach ($ideas_query as $next_i) {
                    $ui .= idea_view($_POST['linkplayertype'], $next_i, $is[0]);
                }
                $ui .= '</div>';

            } elseif (in_array($_POST['linkplayertype'], $this->config->item('playerids___42284'))) {

                $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-' . $_POST['linkplayertype'] . '">';
                foreach ($ideas_query as $item) {
                    $ui .= player_view(6255, $item);
                }
                $ui .= '</div>';

            } elseif (in_array($_POST['linkplayertype'], $this->config->item('playerids___42261'))) {

                //Players
                $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-' . $_POST['linkplayertype'] . '">';
                foreach ($ideas_query as $player_ref) {
                    $ui .= player_view($_POST['linkplayertype'], $player_ref, null);
                }
                $ui .= '</div>';

            }

            echo $ui;

        }
    }


    function player_list()
    {

        //Authenticate Member:
        if (!isset($_POST['playerid']) || intval($_POST['playerid']) < 1 || !isset($_POST['linkplayertype']) || intval($_POST['linkplayertype']) < 1) {
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Missing core variables</div>';
            return false;
        }

        $limit = view_memory(6404, 11064);
        $player_session = player_session();

        //Check Permission:
        if (in_array($_POST['linkplayertype'], $this->config->item('playerids___42376')) && !player_access(null, $_POST['playerid'])) {
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-lock"></i></span>Private</div>';
            return false;
        }

        $players_query = players_query($_POST['linkplayertype'], $_POST['playerid'], 1);
        $es = $this->Players->read(array(
            'playerid' => $_POST['playerid'],
        ));
        if (!count($es)) {
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-lock"></i></span>Invalid Player ID</div>';
            return false;
        }
        if (!$players_query) {
            return false;
        }

        $focus_playerid = ($_POST['playerid'] > 0 ? $_POST['playerid'] : ($player_session ? $player_session['playerid'] : 0));
        $ui = '';

        if (in_array($_POST['linkplayertype'], $this->config->item('playerids___42261'))) {

            //Ideas:
            $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-' . $_POST['linkplayertype'] . '">';
            foreach ($players_query as $i) {
                $ui .= idea_view($_POST['linkplayertype'], $i, null, null, $focus_playerid);
            }
            $ui .= '</div>';

        } elseif (in_array($_POST['linkplayertype'], $this->config->item('playerids___11028'))) {

            //Players:
            $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-' . $_POST['linkplayertype'] . '">';
            foreach ($players_query as $e) {
                $ui .= player_view($_POST['linkplayertype'], $e, null);
            }
            $ui .= '</div>';

        } elseif (in_array($_POST['linkplayertype'], $this->config->item('playerids___12144'))) {

            //Discoveries:
            $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-' . $_POST['linkplayertype'] . '">';
            foreach ($players_query as $i) {
                $ui .= idea_view($_POST['linkplayertype'], $i, null, null, $focus_playerid);
            }
            $ui .= '</div>';

        }

        echo $ui;

    }

    function player_cover()
    {

        if (!isset($_POST['playerid']) || !isset($_POST['linkplayertype']) || !isset($_POST['first_segment']) || !isset($_POST['counter'])) {

            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Missing core variables</div>';

        } else {

            if (in_array($_POST['linkplayertype'], $this->config->item('playerids___42376')) && !player_access(null, $_POST['playerid'])) {

                echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-lock"></i></span>Private</div>';

            } else {

                $ui = '';
                $listed_items = 0;

                if (in_array($_POST['linkplayertype'], $this->config->item('playerids___11028'))) {

                    //SOURCES
                    $current_playerhandle = view_valid_handle_player($_POST['first_segment']);
                    $players___4593 = $this->config->item('players___4593'); //Link Types

                    foreach (players_query($_POST['linkplayertype'], $_POST['playerid'], 1, false) as $player_session) {
                        if (isset($player_session['playerid'])) {
                            $ui .= view_card(view_memory(42903, 42902) . $player_session['playerhandle'], $player_session['playerhandle'] == $current_playerhandle, $player_session['linkplayertype'], view_cover($player_session['playercover'], true), $player_session['playertext'], $player_session['linktext']);
                            $listed_items++;
                        }
                    }

                } elseif (in_array($_POST['linkplayertype'], $this->config->item('playerids___42261')) || in_array($_POST['linkplayertype'], $this->config->item('playerids___42284'))) {

                    //IDEAS
                    $current_ideahashtag = (substr($_POST['first_segment'], 0, 1) == '~' ? substr($_POST['first_segment'], 1) : false);
                    $players___4737 = $this->config->item('players___4737'); //Idea Types
                    $players___4593 = $this->config->item('players___4593'); //Link Types
                    $discover_linkplayertype = discover_linkplayertype();

                    foreach (players_query($_POST['linkplayertype'], $_POST['playerid'], 1, false) as $next_i) {
                        if (isset($next_i['ideaid'])) {
                            $ui .= view_card($discover_linkplayertype . view_memory(42903, 33286) . $next_i['ideahashtag'], $next_i['ideahashtag'] == $current_ideahashtag, $next_i['linkplayertype'], (in_array($next_i['ideatype'], $this->config->item('playerids___32172')) ? $players___4737[$next_i['ideatype']]['m__cover'] : ''), view_idea_title($next_i, true), $next_i['linktext']);
                            $listed_items++;
                        }
                    }

                }

                if ($listed_items < $_POST['counter']) {
                    //We have more to show:
                    foreach ($this->Players->read(array(
                        'playerid' => $_POST['playerid'],
                    )) as $player_this) {
                        $ui .= view_more(view_memory(42903, 42902) . $player_this['playerhandle'], false, '&nbsp;', '&nbsp;', 'View All');
                    }
                }

                echo $ui;

            }
        }
    }

    function player_sort_save()
    {

        //Authenticate Member:
        $player_session = player_session(10939, 0, $this->player_session);
        if (!$player_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(10939),
            ));
        } elseif (!isset($_POST['playerid']) || intval($_POST['playerid']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid playerid',
            ));
        } elseif (!isset($_POST['new_linknumber']) || !is_array($_POST['new_linknumber']) || count($_POST['new_linknumber']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Nothing passed for sorting',
            ));
        } else {

            //Validate Player:
            $es = $this->Players->read(array(
                'playerid' => $_POST['playerid'],
            ));

            //Count followers:
            $listplayer_count = $this->Links->read(array(
                'linkplayerup' => $_POST['playerid'],
                'linkplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
            ), array('linkplayerdown'), 0, 0, array(), 'COUNT(playerid) as totals');

            if (count($es) < 1) {

                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid playerid',
                ));

            } elseif ($listplayer_count[0]['totals'] > view_memory(6404, 11064)) {

                return view_json(array(
                    'status' => 0,
                    'message' => 'Cannot sort Players if greater than ' . view_memory(6404, 11064),
                ));

            } else {

                //Update them all:
                $updated = 0;
                foreach ($_POST['new_linknumber'] as $rank => $linkid) {
                    if ($linkid > 0) {
                        $updated += $this->Links->update($linkid, array(
                            'linknumber' => intval($rank),
                        ));
                    }
                }

                //Display message:
                return view_json(array(
                    'status' => 1,
                    'message' => $updated . ' Links updated',
                ));

            }
        }
    }


    function idea_copy()
    {

        //Auth member and check required variables:
        $player_session = player_session(10939, 0, $this->player_session);

        if (!$player_session) {
            return view__json(array(
                'status' => 0,
                'messagCloe' => view__unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['ideaid']) || intval($_POST['ideaid']) < 1) {
            return view__json(array(
                'status' => 0,
                'message' => 'Invalid Following Player',
            ));
        } elseif (!isset($_POST['do_recursive'])) {
            return view__json(array(
                'status' => 0,
                'message' => 'Missing template parameter',
            ));
        }

        return view_json($this->Ideas->copy(intval($_POST['ideaid']), intval($_POST['do_recursive']), $player_session['playerid']));

    }


    function player_copy()
    {

        //Auth member and check required variables:
        $player_session = player_session(10939, 0, $this->player_session);

        if (!$player_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(10939),
            ));
        } elseif (intval($_POST['playerid']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Player',
            ));
        } elseif (!strlen($_POST['copy_player_title'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Player Title',
            ));
        }

        //Validate Player:
        $fetch_o = $this->Players->read(array(
            'playerid' => $_POST['playerid'],
        ));
        if (count($fetch_o) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid followings Player ID',
            ));
        }


        //Create:
        $added_e = $this->Players->create(array(
            'playertext' => $_POST['copy_player_title'],
            'playercover' => $fetch_o[0]['playercover'],
        ), $player_session['playerid']);
        if (!$added_e['status']) {
            //We had an error, return it:
            return view_json($added_e);
        } else {
            //Assign new Player:
            $focus_e = $added_e['player_create'];
        }


        //Followers:
        foreach ($this->Links->read(array(
            'linkplayerup' => $_POST['playerid'],
            'linkplayertype IN (' . join(',', $this->config->item('playerids___41303')) . ')' => null, //Clone Player Links
        ), array(), 0) as $x) {

            //Make sure none existent in new Player:
            if (!count($this->Links->read(array(
                'linkplayertype' => $x['linkplayertype'],
                'linkplayerup' => $focus_e['playerid'],
                'linkplayerdown' => $x['linkplayerdown'],
                'linktext' => $x['linktext'],
            )))) {
                $this->Links->create(array(
                    'linkplayercreator' => $player_session['playerid'],
                    'linknumber' => $x['linknumber'],
                    'linkplayertype' => $x['linkplayertype'],
                    'linkplayerup' => $focus_e['playerid'],
                    'linkplayerdown' => $x['linkplayerdown'],
                    'linktext' => $x['linktext'],
                ));
            }
        }

        //Followings:
        foreach ($this->Links->read(array(
            'linkplayerdown' => $_POST['playerid'],
            'linkplayertype IN (' . join(',', $this->config->item('playerids___41303')) . ')' => null, //Clone Player Links
        ), array(), 0) as $x) {
            if (!count($this->Links->read(array(
                'linkplayertype' => $x['linkplayertype'],
                'linkplayerup' => $x['linkplayerup'],
                'linkplayerdown' => $focus_e['playerid'],
                'linktext' => $x['linktext'],
            )))) {
                $this->Links->create(array(
                    'linkplayercreator' => $player_session['playerid'],
                    'linknumber' => $x['linknumber'],
                    'linkplayertype' => $x['linkplayertype'],
                    'linkplayerup' => $x['linkplayerup'],
                    'linkplayerdown' => $focus_e['playerid'],
                    'linktext' => $x['linktext'],
                ));
            }
        }

        //Ideas:
        foreach ($this->Links->read(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___41302')) . ')' => null, //Clone Idea Player Links
            'linkplayerup' => $_POST['playerid'],
        ), array(), 0) as $x) {
            if (!count($this->Links->read(array(
                'linkplayertype' => $x['linkplayertype'],
                'linkplayerup' => $focus_e['playerid'],
                'linkplayerdown' => $x['linkplayerdown'],
                'linkidealeft' => $x['linkidealeft'],
                'linkidearight' => $x['linkidearight'],
                'linktext' => $x['linktext'],
            )))) {
                $this->Links->create(array(
                    'linkplayercreator' => $player_session['playerid'],
                    'linknumber' => $x['linknumber'],
                    'linkplayertype' => $x['linkplayertype'],
                    'linkplayerup' => $focus_e['playerid'],
                    'linkplayerdown' => $x['linkplayerdown'],
                    'linkidealeft' => $x['linkidealeft'],
                    'linkidearight' => $x['linkidearight'],
                    'linktext' => $x['linktext'],
                ));
            }
        }

        return view_json(array(
            'status' => 1,
            'player_createhandle' => $focus_e['playerhandle'],
        ));


    }

    function idea_create()
    {

        /*
         *
         * Either creates a IDEA Link between focus_id & link_ideaid
         * OR will create a new idea with outcome ideatext and then Link it
         * to focus_id (In this case link_ideaid=0)
         *
         * */

        //Authenticate Member:
        $member_e = player_session(10939, 0, $this->player_session);
        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(10939),
            ));
        } elseif (!isset($_POST['linkplayertype']) || !isset($_POST['focus_id']) || !isset($_POST['focus_card'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core Variables',
            ));
        } elseif (!isset($_POST['idea_createtext']) || !isset($_POST['link_ideaid'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing either Idea Outcome OR Follower Idea ID',
            ));
        }

        $validate_ideatext = validate_ideatext($_POST['idea_createtext']);
        if (!$validate_ideatext['status']) {
            //We had an error, return it:
            return view_json($validate_ideatext);
        }


        if (!$_POST['link_ideaid'] && view_valid_handle_idea($_POST['idea_createtext'])) {
            foreach ($this->Ideas->read(array(
                'LOWER(ideahashtag)' => strtolower(view_valid_handle_idea($_POST['idea_createtext'])),
            )) as $i) {
                $_POST['link_ideaid'] = $i['ideaid'];
            }
        }

        $x_i = array();

        if ($_POST['link_ideaid'] > 0) {
            //Fetch Link idea to determine idea type:
            $x_i = $this->Ideas->read(array(
                'ideaid' => intval($_POST['link_ideaid']),
            ));
            if (count($x_i) == 0) {
                //validate Idea:
                return view_json(array(
                    'status' => 0,
                    'message' => 'Idea #' . $_POST['link_ideaid'] . ' is not active.',
                ));
            }
        }

        //All seems good, go ahead and try to create/link the Idea:
        return view_json($this->Ideas->create_or_link($_POST['focus_card'], $_POST['linkplayertype'], trim($_POST['idea_createtext']), $member_e['playerid'], $_POST['focus_id'], $_POST['link_ideaid']));

    }


    function player_create()
    {

        //Auth member and check required variables:
        $player_session = player_session(10939, 0, $this->player_session);

        if (!$player_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(10939),
            ));
        } elseif (intval($_POST['focus__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Following Player',
            ));
        } elseif (!isset($_POST['linkplayertype'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Player Creation Type',
            ));
        } elseif (!isset($_POST['player_current_id']) || !isset($_POST['player_new_string']) || (intval($_POST['player_current_id']) < 1 && strlen($_POST['player_new_string']) < 1)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Either New Player ID or Player Name',
            ));
        }

        $adding_to_i = ($_POST['focus__node'] == 12273);

        if ($adding_to_i) {

            //Validate Idea:
            $fetch_o = $this->Ideas->read(array(
                'ideaid' => $_POST['focus__id'],
            ));
            if (count($fetch_o) < 1) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid followings Player ID',
                ));
            }

        } else {

            //Validate Player:
            $fetch_o = $this->Players->read(array(
                'playerid' => $_POST['focus__id'],
            ));
            if (count($fetch_o) < 1) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid followings Player ID',
                ));
            }

        }


        //Set some variables:
        $_POST['player_new_string'] = trim($_POST['player_new_string']);
        $_POST['linkplayertype'] = intval($_POST['linkplayertype']);
        $is_upwards = in_array($_POST['linkplayertype'], $this->config->item('playerids___14686'));

        if (!intval($_POST['player_current_id']) && view_valid_handle_player($_POST['player_new_string'])) {
            foreach ($this->Players->read(array(
                'LOWER(playerhandle)' => strtolower(substr($_POST['player_new_string'], 1)),
            )) as $e) {
                $_POST['player_current_id'] = $e['playerid'];
            }
        }
        $adding_to_existing = (intval($_POST['player_current_id']) > 0);

        //Are we adding an existing Player?
        if ($adding_to_existing) {

            //Validate this existing Player:
            $es = $this->Players->read(array(
                'playerid' => $_POST['player_current_id'],
            ));

            if (count($es) < 1) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Player @' . $_POST['player_current_id'] . ' is not active',
                ));
            }

            //All good, assign:
            $focus_e = $es[0];

        } else {

            //We are creating a new Player:
            $added_e = $this->Players->create(array(
                'playertext' => $_POST['player_new_string'],
            ), $player_session['playerid']);
            if (!$added_e['status']) {
                //We had an error, return it:
                return view_json($added_e);
            } else {
                //Assign new Player:
                $focus_e = $added_e['player_create'];
            }

        }


        //We need to check to ensure this is not a duplicate Link if adding an existing Player:
        $ur2 = array();

        if ($adding_to_i) {

            //Add Reference:
            $ur2 = $this->Links->create(array(
                'linkplayercreator' => $player_session['playerid'],
                'linkplayertype' => 4983, //Co-Author
                'linkplayerup' => $focus_e['playerid'],
                'linkidearight' => $fetch_o[0]['ideaid'],
            ));

        } else {

            //Add Up/Down Player:

            //Add Links only if not previously added by the URL function:
            if ($is_upwards) {

                //Following
                $linkplayerdown = $fetch_o[0]['playerid'];
                $linkplayerup = $focus_e['playerid'];
                $linknumber = 0; //Never sort following, only sort followers

            } else {

                //Followers
                $linkplayerup = $fetch_o[0]['playerid'];
                $linkplayerdown = $focus_e['playerid'];
                $linknumber = 0;

            }


            $linktext = null;

            //Create Link:
            $ur2 = $this->Links->create(array(
                'linkplayercreator' => $player_session['playerid'],
                'linkplayertype' => 4230,
                'linktext' => $linktext,
                'linkplayerdown' => $linkplayerdown,
                'linkplayerup' => $linkplayerup,
                'linknumber' => $linknumber,
            ));
        }

        //Return Player:
        return view_json(array(
            'status' => 1,
            'player_new_echo' => player_view($_POST['linkplayertype'], array_merge($focus_e, $ur2), null),
        ));

    }

    function player_editor()
    {

        $player_session = player_session(null, 0, $this->player_session);
        $players___11035 = $this->config->item('players___11035');
        $players___42776 = $this->config->item('players___42776');
        $players___4592 = $this->config->item('players___4592'); //Data types
        if (!$player_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['playerid']) || !isset($_POST['linkid'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core IDs',
            ));
        }

        $es = $this->Players->read(array(
            'playerid' => $_POST['playerid'],
        ));
        if (!count($es)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Player is no longer active',
            ));
        } elseif (!player_access($es[0]['playerhandle'], 0, $es[0])) {
            return view_json(array(
                'status' => 0,
                'message' => 'You are missing permission to edit this Player',
            ));
        }


        //Fetch dynamic data based on idea type:
        $order_42145 = sort_by(42145);
        $scanned_players = array();
        $return_inputs = array();
        $input_pointer = 0;
        $profile_header = '';

        //Fetch Player Templates, if any:
        foreach ($this->Links->read(array(
            'linkplayerup IN (' . join(',', $this->config->item('playerids___42178')) . ')' => null, //Dynamic Players
            'linkplayerdown' => $es[0]['playerid'],
            'linkplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
        ), array('linkplayerup'), 0, 0, sort_by(42178)) as $player_group) {

            if (in_array($player_group['playerid'], $scanned_players)) {
                continue;
            }
            array_push($scanned_players, $player_group['playerid']);

            foreach ($this->Links->read(array(
                'linkplayerdown' => $player_group['playerid'],
                'linkplayerup IN (' . join(',', $this->config->item('playerids___42145')) . ')' => null, //Dynamic Input Templates
                'linkplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
            ), array('linkplayerup'), 0, 0, $order_42145) as $player_template) {

                $profile_header = '<div class="profile_header main__title"><span class="icon-block-sm">' . view_cover($player_template['playercover']) . '</span>' . $player_template['playertext'] . '<a href="' . view_memory(42903, 42902) . $player_group['playerhandle'] . '" target="_blank" data-toggle="tooltip" data-placement="top" title="Because you follow ' . $player_group['playertext'] . '... Click to Open in a New Window"><span class="icon-block-sm">' . view_cover($player_group['playercover']) . '</span></a></div>';


                //Load template:
                if (!is_array($this->config->item('players___' . $player_template['playerid']))) {
                    //Report Error:
                    log_error('player_sessionditor_load() ERROR: @' . $player_template['playerid'] . ' is NOT in memory cache', array(
                        'linkplayerdown' => $player_template['playerid'],
                    ));
                    continue;
                } elseif (in_array($player_template['playerid'], $scanned_players)) {
                    continue;
                }
                array_push($scanned_players, $player_template['playerid']);


                foreach ($this->config->item('players___' . $player_template['playerid']) as $dynamic_playerid => $m) {

                    //Make sure it's a dynamic input field:
                    if (!in_array($dynamic_playerid, $this->config->item('playerids___42179'))) {
                        continue;
                    } elseif (in_array($dynamic_playerid, $scanned_players)) {
                        continue;
                    }
                    array_push($scanned_players, $dynamic_playerid);

                    //Let's first determine the data type:
                    $data_types = array_intersect($m['m__following'], $this->config->item('playerids___4592'));

                    if (count($data_types) != 1) {

                        //This is strange, we are expecting 1 match only report this:
                        log_error('Found ' . count($data_types) . ' Data Types (@' . $es[0]['playerid'] . ') (Expecting exactly 1) for @' . $dynamic_playerid . ': Check @4592 to see what is wrong', array(
                            'linkplayerdown' => $dynamic_playerid,
                            'linkplayercreator' => $player_session['playerid'],
                        ));
                        continue; //Go to the next dynamic data type

                    } elseif ($input_pointer >= view_memory(6404, 42206)) {
                        //Monitor if we ever reach the maximum:
                        log_error('Dynamic Fields Reach their maximum limit of ' . view_memory(6404, 42206) . '  which may require field expansion', array(
                            'linkplayerdown' => $dynamic_playerid,
                            'linkplayercreator' => $player_session['playerid'],
                            'linkidearight' => $_POST['playerid'],
                        ));
                    }

                    //We found 1 match as expected:
                    $input_pointer++;
                    foreach ($data_types as $data_type_this) {
                        $data_type = $data_type_this;
                        break;
                    }

                    if (in_array($data_type, $this->config->item('playerids___42188'))) {

                        //Single or Multiple Choice:
                        array_push($return_inputs, array(
                            'd__id' => $dynamic_playerid,
                            'd__is_radio' => 1,
                            'd_linkid' => 0,
                            'd__html' => view_instant_select($dynamic_playerid, $es[0]['playerid'], 0),
                            'd__value' => ($es[0]['playerid'] > 0 ? $es[0]['playerid'] : ''),
                            'd__type_name' => '',
                            'd__placeholder' => '',
                            'd__profile_header' => $profile_header,
                        ));

                    } else {

                        $this_data_type = $this->config->item('players___' . $data_type);
                        $players___42179 = $this->config->item('players___42179'); //Dynamic Input Field
                        $players___11035 = $this->config->item('players___11035'); //Encyclopedia

                        //Fetch the current value(s):
                        $counted = 0;
                        $unique_values = array();
                        foreach ($this->Links->read(array(
                            'linkplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
                            'linkplayerdown' => $es[0]['playerid'],
                            'linkplayerup' => $dynamic_playerid,
                        ), array('linkplayerup')) as $selected_e) {
                            if (strlen($selected_e['linktext']) && !in_array($selected_e['linktext'], $unique_values)) {
                                array_push($unique_values, $selected_e['linktext']);
                                $counted++;
                                array_push($return_inputs, array(
                                    'd__id' => $dynamic_playerid,
                                    'd__is_radio' => 0,
                                    'd_linkid' => $selected_e['linkid'],
                                    'd__html' => view_dynamic_headline($dynamic_playerid, $m, $selected_e),
                                    'd__value' => $selected_e['linktext'],
                                    'd__type_name' => html_input_type($data_type),
                                    'd__placeholder' => (strlen($this_data_type[$dynamic_playerid]['m__message']) ? $this_data_type[$dynamic_playerid]['m__message'] : $players___4592[$data_type]['m__title'] . '...'),
                                    'd__profile_header' => $profile_header,
                                ));
                            }
                        }

                        if (!$counted) {
                            foreach ($this->Players->read(array(
                                'playerid' => $dynamic_playerid,
                            )) as $selected_e) {
                                array_push($return_inputs, array(
                                    'd__id' => $dynamic_playerid,
                                    'd__is_radio' => 0,
                                    'd_linkid' => 0,
                                    'd__html' => view_dynamic_headline($dynamic_playerid, $m, $selected_e),
                                    'd__value' => '',
                                    'd__type_name' => html_input_type($data_type),
                                    'd__placeholder' => (strlen($this_data_type[$dynamic_playerid]['m__message']) ? $this_data_type[$dynamic_playerid]['m__message'] : $players___4592[$data_type]['m__title'] . '...'),
                                    'd__profile_header' => $profile_header,
                                ));
                            }
                        }
                    }
                }
            }
        }


        //Add universal inputs only if missing bio profiles:
        if (!array_intersect($scanned_players, $this->config->item('playerids___42885'))) {
            foreach ($this->Players->read(array(
                'playerid IN (' . join(',', $this->config->item('playerids___42776')) . ')' => null, //Universal Dynamic Inputs
            )) as $selected_e) {
                foreach (array_intersect($players___42776[$selected_e['playerid']]['m__following'], $this->config->item('playerids___4592')) as $data_type) {
                    //Any value?
                    $values = $this->Links->read(array(
                        'linkplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
                        'linkplayerdown' => $es[0]['playerid'],
                        'linkplayerup' => $selected_e['playerid'],
                    ));
                    array_push($return_inputs, array(
                        'd__id' => $selected_e['playerid'],
                        'd__is_radio' => 0,
                        'd_linkid' => 0,
                        'd__html' => view_dynamic_headline($selected_e['playerid'], $players___42776[$selected_e['playerid']], $selected_e),
                        'd__value' => (isset($values[0]['linktext']) && strlen($values[0]['linktext']) > 0 ? $values[0]['linktext'] : ''),
                        'd__type_name' => html_input_type($data_type),
                        'd__placeholder' => (strlen($players___42776[$selected_e['playerid']]['m__message']) ? $players___42776[$selected_e['playerid']]['m__message'] : $players___4592[$data_type]['m__title'] . '...'),
                        'd__profile_header' => '', //No header for universals
                    ));
                    break;
                }
            }
        }

        //Return everything we found:
        return view_json(array(
            'status' => 1,
            'return_inputs' => $return_inputs,
        ));

    }

    function player_save_edit()
    {

        $player_session = player_session(null, 0, $this->player_session);
        if (!$player_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['save_playerid'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Coin ID',
            ));
        } elseif (!isset($_POST['save_playertext'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Player Title',
            ));
        } elseif (!isset($_POST['save_playerhandle'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Player Handle',
            ));
        } elseif (!isset($_POST['save_playercover'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Player Cover',
            ));
        } elseif (!isset($_POST['save_linkid']) || !isset($_POST['save_linktext'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Link Data',
            ));
        }


        $es = $this->Players->read(array(
            'playerid' => $_POST['save_playerid'],
        ));
        if (!count($es)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Player Not Active',
            ));
        }


        //Validate Dynamic Inputs:
        $players___42179 = $this->config->item('players___42179'); //Dynamic Input Fields

        //Process dynamic inputs if any:
        for ($p = 1; $p <= view_memory(6404, 42206); $p++) {

            if (!isset($_POST['save_dynamic_' . $p])) {
                break; //Nothing more to process
            }

            $input_parts = explode('____', $_POST['save_dynamic_' . $p], 3);
            if (!isset($input_parts[0]) || !isset($input_parts[1])) {
                continue;
            }
            $d_linkid = $input_parts[0];
            $dynamic_playerid = $input_parts[1];
            $dynamic_value = trim($input_parts[2]);


            //Required fields must have an input:
            if (in_array($dynamic_playerid, $this->config->item('playerids___28239')) && !strlen($dynamic_value) && !in_array($dynamic_playerid, $this->config->item('playerids___33331')) && !in_array($dynamic_playerid, $this->config->item('playerids___33332'))) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Missing Required Field: ' . $players___42179[$dynamic_playerid]['m__title'],
                ));
            }

            //Validate input based on its data type, if provided:
            if (strlen($dynamic_value)) {
                foreach (array_intersect($players___42179[$dynamic_playerid]['m__following'], $this->config->item('playerids___4592')) as $data_type_this) {
                    $data_type_validate = data_type_validate($data_type_this, $dynamic_value, $players___42179[$dynamic_playerid]['m__title']);
                    if (!$data_type_validate['status']) {
                        //We had an error:
                        return view_json($data_type_validate);
                    }
                }
            }


            //Fetch the current value:
            if ($d_linkid > 0) {
                $values = $this->Links->read(array(
                    'linkid' => $d_linkid,
                ));
            }

            if (!$d_linkid || !count($values)) {
                $values = $this->Links->read(array(
                    'linkplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
                    'linkplayerup' => $dynamic_playerid,
                    'linkplayerdown' => $es[0]['playerid'],
                ));
            }


            //Update if needed:
            if (!strlen($dynamic_value)) {

                //Remove Link if we have one:
                if (count($values) && $dynamic_playerid != 11035 /* HACK: Summary are key links that should not be removed */) {
                    $this->Links->delete($values[0]['linkid'], $player_session['playerid']);
                }

            } elseif (!count($values)) {

                //Create Link:
                $this->Links->create(array(
                    'linkplayercreator' => $player_session['playerid'],
                    'linkplayertype' => 4230,
                    'linkplayerup' => $dynamic_playerid,
                    'linkplayerdown' => $es[0]['playerid'],
                    'linktext' => $dynamic_value,
                    'linknumber' => number_linknumber($dynamic_value),
                ));

            } elseif ($values[0]['linktext'] != $dynamic_value) {

                //Update Link:
                $this->Links->update($values[0]['linkid'], array(
                    'linktext' => $dynamic_value,
                    'linkplayercreator' => $player_session['playerid'],
                ));

            }
        }


        //Validate Player Handle & save if needed:
        if ($es[0]['playerhandle'] !== trim($_POST['save_playerhandle'])) {
            $validate_update_handle = validate_update_handle(trim($_POST['save_playerhandle']), null, $es[0]['playerid']);
            if (!$validate_update_handle['status']) {
                return view_json(array(
                    'status' => 0,
                    'message' => $validate_update_handle['message'],
                ));
            }
        }

        //Validate Player Title & save if needed:
        $validate_playertext = validate_playertext($_POST['save_playertext']);
        if ($es[0]['playertext'] != trim($_POST['save_playertext'])) {
            if (!$validate_playertext['status']) {
                return view_json(array(
                    'status' => 0,
                    'message' => $validate_playertext['message'],
                ));
            }
            $es[0]['playertext'] = $validate_playertext['playertext_clean'];
        }

        //Save Player Cover if needed:
        if ($es[0]['playercover'] != trim($_POST['save_playercover'])) {
            //TODO validate playercover?
            $es[0]['playercover'] = trim($_POST['save_playercover']);
        }

        //Update:
        $this->Players->update($es[0]['playerid'], array(
            'playertext' => $validate_playertext['playertext_clean'],
            'playercover' => trim($_POST['save_playercover']),
            'playerhandle' => trim($_POST['save_playerhandle']),
        ), $player_session['playerid']);


        //Sync handle reference:
        $new_handle_string = trim($_POST['save_playerhandle']);
        if ($es[0]['playerhandle'] != $new_handle_string) {
            //Update Handles everywhere they are referenced:
            foreach ($this->Links->read(array(
                'linkplayerup' => $es[0]['playerid'],
                'linkplayertype' => 31835, //Player Mention
            ), array('linkidearight')) as $ref) {
                $this->Ideas->update($ref['ideaid'], array(
                    'ideatext' => str_replace('@' . $es[0]['playerhandle'], '@' . $new_handle_string, $ref['ideatext']),
                ), $player_session['playerid']);
            }
            $es[0]['playerhandle'] = $new_handle_string;
        }


        //Do we have a link reference message that need to be saved?
        if ($_POST['save_linkid'] > 0 && $_POST['save_linktext'] != 'IGNORE_INPUT') {

            //Fetch Link:
            foreach ($this->Links->read(array(
                'linkid' => $_POST['save_linkid'],
            )) as $this_x) {

                $es[0] = array_merge($es[0], $this_x);

                if ($this_x['linktext'] != trim($_POST['save_linktext'])) {
                    $this->Links->update($this_x['linkid'], array(
                        'linktext' => trim($_POST['save_linktext']),
                        'linkplayercreator' => $player_session['playerid'],
                    ));
                }
            }
        }


        //Reset member session data if this data belongs to the logged-in member:
        if ($_POST['save_playerid'] == $player_session['playerid']) {
            $this->Players->activate($es[0], true);
        }


        return view_json(array(
            'status' => 1,
            'message' => 'Updated ',
        ));


    }

    function player_select_apply()
    {
        /*
         *
         * Saves the radio selection of some account fields
         *
         * */

        $player_session = player_session(null, 0, $this->player_session);
        if (!$player_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['focus__id']) || intval($_POST['focus__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing followings Player',
            ));
        } elseif (!isset($_POST['selected_playerid']) || intval($_POST['selected_playerid']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing selected Player',
            ));
        } elseif (!isset($_POST['down_playerid']) || !isset($_POST['right_ideaid'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Down/Right Element',
            ));
        } elseif (!isset($_POST['enable_mulitiselect']) || !isset($_POST['was_previously_selected'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing multi-select setting',
            ));
        }

        $stats = array(
            'total' => 0,
            'was_previously_selected' => intval($_POST['was_previously_selected']),
            'deleted' => 0,
            'added' => 0,
        );


        if ($_POST['down_playerid'] > 0) {

            //Dispatch Any Emails Necessary:
            if (isset($_POST['selected_playerid']) && intval($_POST['selected_playerid']) > 0) {
                foreach ($this->Links->read(array(
                    'linkplayertype' => 33600, //Draft
                    'linkplayerup' => $_POST['selected_playerid'],
                ), array('linkidearight'), 0) as $i) {
                    if (count($this->Links->read(array(
                        'linkplayertype' => 33600, //Draft
                        'linkplayerup' => 31065, //Choice Update Email Templates
                        'linkidearight' => $i['ideaid'], //Is this the template?
                    )))) {
                        //Found the email template to send:
                        $total_sent = $this->Links->broadcast(array($player_session), $i, website_setting(0), false);
                        break; //Just the first template match
                    }
                }
            }
        }

        $is_required = in_array($_POST['focus__id'], $this->config->item('playerids___28239')); //Required Settings

        if (!$_POST['enable_mulitiselect'] || $_POST['was_previously_selected']) {

            //Since this is not a multi-select we want to delete all existing options

            //Fetch all possible answers based on followings Player:
            $query_filters = array(
                'linkplayerup' => $_POST['focus__id'],
                'linkplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
            );

            if ((!$is_required || $_POST['enable_mulitiselect']) && $_POST['was_previously_selected']) {
                //Just delete this single item, not the other ones:
                $query_filters['linkplayerdown'] = $_POST['selected_playerid'];
            }

            //List all possible answers:
            $possible_answers = array();
            foreach ($this->Links->read($query_filters, array('linkplayerdown'), 0, 0) as $answer_e) {
                $stats['total']++;
                array_push($possible_answers, $answer_e['playerid']);
            }

            //Delete previously selected options:
            if ($_POST['down_playerid']) {
                $delete_query = $this->Links->read(array(
                    'linkplayerup IN (' . join(',', $possible_answers) . ')' => null,
                    'linkplayerdown' => $_POST['down_playerid'],
                    'linkplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
                ));
            } elseif ($_POST['right_ideaid']) {
                $delete_query = $this->Links->read(array(
                    'linkplayerup IN (' . join(',', $possible_answers) . ')' => null,
                    'linkidearight' => $_POST['right_ideaid'],
                    'linkplayertype IN (' . join(',', $this->config->item('playerids___33602')) . ')' => null, //Idea/Player Links Active
                ));
            }

            foreach ($delete_query as $delete) {
                $stats['deleted']++;
                //Should usually delete a single option:
                $this->Links->delete($delete['linkid'], $player_session['playerid']);
            }

        }

        //Add new option if not previously there:
        if ((!$_POST['enable_mulitiselect'] && $is_required) || !$_POST['was_previously_selected']) {
            if ($_POST['down_playerid']) {
                $stats['added']++;
                $this->Links->create(array(
                    'linkplayercreator' => $player_session['playerid'],
                    'linkplayerup' => $_POST['selected_playerid'],
                    'linkplayertype' => 4230,
                    'linkplayerdown' => $_POST['down_playerid'],
                ));
            } elseif ($_POST['right_ideaid']) {

                if (!count($this->Links->read(array(
                    'linkplayertype IN (' . join(',', $this->config->item('playerids___31919')) . ')' => null, //IDEA AUTHOR
                    'linkplayerup' => $_POST['selected_playerid'],
                    'linkidearight' => $_POST['right_ideaid'],
                )))) {
                    $stats['added']++;
                    $this->Links->create(array(
                        'linkplayercreator' => $player_session['playerid'],
                        'linkplayertype' => 4983, //Co-Author
                        'linkplayerup' => $_POST['selected_playerid'],
                        'linkidearight' => $_POST['right_ideaid'],
                    ));
                }

            }
        }


        //Update Session:
        if ($_POST['down_playerid'] && $player_session) {
            $this->Players->activate($player_session, true);
        }


        //All good:
        return view_json(array(
            'status' => 1,
            'message' => 'Updated: ' . print_r($stats, true),
        ));
    }

    function player_authenticate()
    {


        if (!isset($_POST['account_id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing user ID',
            ));
        } elseif (!isset($_POST['input_code']) || !intval($_POST['input_code'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid code',
            ));
        } elseif (!isset($_POST['account_email_phone'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing account_email_phone',
            ));
        } elseif (!isset($_POST['referrer_url'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing referrer URL',
            ));
        } elseif (!isset($_POST['sign_ideaid'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing idea referrer',
            ));
        }

        $_POST['account_email_phone'] = trim(strtolower($_POST['account_email_phone']));

        //Validate member ID
        if ($_POST['account_id'] > 0) {

            $es = $this->Players->read(array(
                'playerid' => $_POST['account_id'],
            ));
            if (!count($es)) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid account ID.',
                ));
            }

        } else {

            $_POST['new_account_email'] = trim(strtolower($_POST['new_account_email']));
            if (!filter_var($_POST['account_email_phone'], FILTER_VALIDATE_EMAIL) && !filter_var($_POST['new_account_email'], FILTER_VALIDATE_EMAIL)) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Enter your email to continue',
                ));
            }

        }


        //Auth Code:
        $is_authenticated = false;
        foreach ($this->Links->read(array(
            'linkplayertype' => 44179, //Triggered
            'linkplayerup' => 32078, //Sign In Key
            'LOWER(linktext) LIKE \'' . strtolower($_POST['account_email_phone']) . '%\'' => null,
        ), array(), 1, 0, array('linktime' => 'DESC')) as $sent_key) {
            if (strtotime($sent_key['linktime']) <= (time() - 86400)) {
                //Expired
                $this->Links->delete($sent_key['linkid'], $_POST['account_id']); //Code Verified
                break;
            }
            $session_key = $this->session->userdata('session_key');
            $key_parts = explode('/', $sent_key['linktext'], 2);
            if (strlen($session_key) && $key_parts[1] == md5($session_key . $_POST['input_code'])) {
                //Void access code:
                $is_authenticated = $this->Links->delete($sent_key['linkid'], $_POST['account_id']); //Code Verified
            }
        }
        if (!$is_authenticated) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid code, try again.',
            ));
        }


        //Validate member ID
        if ($_POST['account_id'] > 0) {

            //Assign session & log Link:
            $this->Players->activate($es[0]);

        } else {

            //Add new account
            $_POST['account_email_phone'] = trim(strtolower($_POST['account_email_phone']));
            $is_email = filter_var($_POST['account_email_phone'], FILTER_VALIDATE_EMAIL);

            //Prep inputs & validate further:
            $acc_email = ($is_email ? $_POST['account_email_phone'] : $_POST['new_account_email']);
            $player_result = $this->Players->join(strstr($acc_email, '@', true), $acc_email, (!$is_email ? $_POST['account_email_phone'] : ''));
            if (!$player_result['status']) {
                return view_json($player_result);
            }

            $es[0] = $player_result['e'];

        }


        //Set default sign in URL:
        $sign_url = view_memory(42903, 42902) . $es[0]['playerhandle'];

        //See if we can find a better one:
        if (intval($_POST['sign_ideaid']) > 0) {
            foreach ($this->Ideas->read(array(
                'ideaid' => $_POST['sign_ideaid'],
            )) as $i) {
                $sign_url = $i['ideahashtag'] . '/' . view_memory(6404, 4235);
            }
        } elseif (isset($_POST['referrer_url']) && strlen(urldecode($_POST['referrer_url'])) > 1) {
            $sign_url = urldecode($_POST['referrer_url']);
        }

        return view_json(array(
            'status' => 1,
            'sign_url' => $sign_url,
        ));

    }

    function player_toggle_follow()
    {

        $player_session = player_session(10939, 0, $this->player_session);
        if (!$player_session) {

            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(10939),
            ));

        } elseif (!isset($_POST['linkplayercreator']) || !isset($_POST['playerid']) || !isset($_POST['ideaid']) || !isset($_POST['linkid'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core Variable',
            ));

        } else {

            $_POST['require_writing'] = intval($_POST['require_writing']);

            $already_added = $this->Links->read(array(
                'linkplayerup' => $_POST['playerid'],
                'linkplayerdown' => $_POST['linkplayercreator'],
                'linkplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
            ), array('linkplayerup'));

            if (count($already_added)) {

                if (intval($_POST['require_writing'])) {

                    //Updating current value if changed:
                    if (strlen($_POST['written_answer']) && trim($_POST['written_answer']) != $already_added[0]['linktext']) {
                        $this->Links->update($already_added[0]['linkid'], array(
                            'linktext' => $_POST['written_answer'],
                            'linkplayercreator' => $player_session['playerid'],
                        ));
                    } elseif (!strlen($_POST['written_answer'])) {
                        $this->Links->delete($already_added[0]['linkid'], $player_session['playerid']);
                    }

                    return view_json(array(
                        'status' => 1,
                        'message' => $_POST['written_answer'],
                    ));

                } else {

                    //Already exists, let's remove:
                    $this->Links->delete($already_added[0]['linkid'], $player_session['playerid']);

                    return view_json(array(
                        'status' => 1,
                        'message' => '',
                    ));

                }

            } else {

                if (intval($_POST['require_writing']) && !strlen($_POST['written_answer'])) {

                    //Nothing to do
                    return view_json(array(
                        'status' => 1,
                        'message' => '',
                    ));

                } else {

                    foreach ($this->Players->read(array(
                        'playerid' => $_POST['playerid'],
                    )) as $e) {

                        //Does not exist, Add:
                        $this->Links->create(array(
                            'linkplayerup' => $_POST['playerid'],
                            'linkplayerdown' => $_POST['linkplayercreator'],
                            'linkplayercreator' => $player_session['playerid'],
                            'linktext' => $_POST['written_answer'],
                            'linkplayertype' => 4230,
                        ));

                        return view_json(array(
                            'status' => 1,
                            'message' => (intval($_POST['require_writing']) ? $_POST['written_answer'] : view_cover($e['playercover'], true)),
                        ));

                    }
                }
            }
        }
    }

    function player_verify()
    {

        if (!isset($_POST['account_email_phone'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'missing account details',
            ));
        }

        //Cleanup input email:
        $players___11035 = $this->config->item('players___11035'); //Encyclopedia
        $_POST['account_email_phone'] = trim(strtolower($_POST['account_email_phone']));
        $valid_email = filter_var($_POST['account_email_phone'], FILTER_VALIDATE_EMAIL);
        if (!$valid_email && strlen($_POST['account_email_phone']) >= 10) {
            $_POST['account_email_phone'] = preg_replace('/[^0-9]+/', '', $_POST['account_email_phone']);
        }
        $possible_phone = !$valid_email && strlen($_POST['account_email_phone']) >= 10;

        if (!$valid_email && !$possible_phone) {
            return view_json(array(
                'status' => 0,
                'message' => (strlen($_POST['account_email_phone']) ? '[' . $_POST['account_email_phone'] . '] is Invalid!' : 'Enter your email to continue...'),
            ));
        } elseif (!isset($_POST['sign_ideaid'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing data ID',
            ));
        }


        if (intval($_POST['sign_ideaid']) > 0) {
            //Fetch the idea:
            $referrer_i = $this->Ideas->read(array(
                'ideaid' => $_POST['sign_ideaid'],
            ));
        } else {
            $referrer_i = array();
        }


        //Search for email/phone to see if it exists
        $linkplayercreator = 0;
        foreach ($this->Links->read(array(
            'LOWER(linktext)' => strtolower($_POST['account_email_phone']),
            'linkplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
            'linkplayerup' => (filter_var($_POST['account_email_phone'], FILTER_VALIDATE_EMAIL) ? 3288 : 4783), //Email / Phone
        ), array('linkplayerdown'), 1, 0, array('linkid' => 'ASC')) as $map_e) {
            $u = $map_e;
            $linkplayercreator = $map_e['playerid'];
        }

        //Send Sign In Key
        $passcode = rand(1000, 9999);
        $session_key = random_string(55);

        //Append to session:
        $session_data = $this->session->all_userdata();
        $session_data['session_key'] = $session_key;
        $this->session->set_userdata($session_data);

        $html_message = $passcode . ' is your ' . $players___11035[32078]['m__title'] . ' for your ' . get_domain('m__title') . ' account.';

        if ($valid_email) {

            //Email:
            dispatch_email(array($_POST['account_email_phone']), $html_message, '<div class="line">' . $html_message . '</div>', $linkplayercreator, array(), 0, 0, false);


        } elseif ($possible_phone) {

            //SMS:
            dispatch_sms($_POST['account_email_phone'], $html_message, 0, array(), 0, 0, false);

        }

        //Log new key:
        $this->Links->create(array(
            'linkplayertype' => 44179, //Triggered
            'linkplayerup' => 32078, //Sign In Key
            'linkplayerdown' => $linkplayercreator, //Member making request
            'linkplayercreator' => $linkplayercreator, //Member making request
            'linkidealeft' => intval($_POST['sign_ideaid']),
            'linktext' => $_POST['account_email_phone'] . '/' . md5($session_key . $passcode),
        ));

        return view_json(array(
            'status' => 1,
            'account_id' => $linkplayercreator,
            'valid_email' => ($valid_email ? 1 : 0),
            'account_preview' => ($linkplayercreator ? '<span class="icon-block">' . view_cover($u['playercover'], true) . '</span>' . $u['playertext'] : ''),
            'clean_contact' => $_POST['account_email_phone'],
        ));

    }

    function player_text_update()
    {

        //Authenticate Member:
        $player_session = player_session(null, 0, $this->player_session);
        $players___12112 = $this->config->item('players___12112');

        if (!$player_session) {

            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
                'original_val' => '',
            ));

        } elseif (!isset($_POST['playerid']) || !isset($_POST['cache_playerid']) || !isset($_POST['idea_createtext'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing core variables',
                'original_val' => '',
            ));

        } elseif ($_POST['cache_playerid'] == 6197 /* SOURCE FULL NAME */) {

            $es = $this->Players->read(array(
                'playerid' => $_POST['playerid'],
            ));
            if (!count($es)) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Player ID #3',
                    'original_val' => '',
                ));
            }


            $validate_playertext = validate_playertext($_POST['idea_createtext']);
            if (!$validate_playertext['status']) {
                return view_json(array_merge($validate_playertext, array(
                    'original_val' => $es[0]['playertext'],
                )));
            }

            //All good, go ahead and update:
            $this->Players->update($es[0]['playerid'], array(
                'playertext' => $validate_playertext['playertext_clean'],
            ), $player_session['playerid']);

            //Reset member session data if this data belongs to the logged-in member:
            if ($es[0]['playerid'] == $player_session['playerid']) {
                //set Session with new data:
                $es[0]['playertext'] = $validate_playertext['playertext_clean'];
                $this->Players->activate($es[0], true);
            }

            return view_json(array(
                'status' => 1,
            ));

        } else {

            return view_json(array(
                'status' => 0,
                'message' => 'Unknown Update Type [' . $_POST['cache_playerid'] . ']',
                'original_val' => '',
            ));

        }
    }

    function link_preview()
    {

        if (!isset($_POST['apply_id']) || !isset($_POST['s__id'])) {
            die('Missing core data');
        }

        //Log Modal View
        $player_session = player_session(null, 0, $this->player_session);

        if (!isset($_POST['apply_id']) || !isset($_POST['s__id'])) {
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Missing Core Data</div>';
        } else {
            if ($_POST['apply_id'] == 4997) {

                //Player list:
                $counter = players_query(42373, $_POST['s__id'], 0, false);
                if (!$counter) {
                    echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>No Players yet</div>';
                } else {
                    echo '<div class="alert" role="alert"><span class="icon-block"><i class="far fa-list"></i></span>Will apply to ' . $counter . ' Player' . search($counter) . ':</div>';
                    echo '<div class="row justify-content">';
                    $ids = array();
                    foreach (players_query(42373, $_POST['s__id'], 1, true) as $e) {
                        array_push($ids, $e['playerid']);
                        echo player_view(12274, $e);
                    }
                    echo '</div>';
                    echo '<div class="dotransparent" title="Total of ' . count($ids) . '">' . join(', ', $ids) . '</div>';
                }

            } elseif ($_POST['apply_id'] == 12589) {

                //idea list:
                $is_next = $this->Links->read(array(
                    'linkplayertype IN (' . join(',', $this->config->item('playerids___42267')) . ')' => null, //IDEA LINKS
                    'linkidealeft' => $_POST['s__id'],
                ), array('linkidearight'), 0, 0, array('linknumber' => 'ASC'));
                $counter = count($is_next);

                if (!$counter) {
                    echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>No Ideas yet</div>';
                } else {
                    echo '<div class="alert" role="alert"><span class="icon-block"><i class="far fa-list"></i></span>Will apply to ' . $counter . ' idea' . search($counter) . ':</div>';
                    echo '<div class="row justify-content">';
                    $ids = array();
                    foreach ($is_next as $i) {
                        array_push($ids, $i['ideaid']);
                        echo idea_view(12273, $i);
                    }
                    echo '</div>';
                    echo '<div class="dotransparent">' . join(',', $ids) . '</div>';
                }

            } else {
                echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Unknown Apply ID</div>';
            }
        }
    }

    function link_page_load()
    {

        $focus_e = array();
        $previous_i = array();

        if (!isset($_POST['focus__node'])) {
            die('Missing input. Refresh and try again.');
        }
        $success = false;

        if ($_POST['focus__node'] == 12274) {

            //SOURCE
            $focus_es = $this->Players->read(array(
                'playerid' => $_POST['focus__id'],
            ));
            $focus_e = $focus_es[0];

            foreach (players_query($_POST['linkplayertype'], $_POST['focus__id'], $_POST['current_page']) as $s) {
                if (in_array($_POST['linkplayertype'], $this->config->item('playerids___11028'))) {
                    echo player_view($_POST['linkplayertype'], $s);
                    $success = true;
                } else if ($_POST['linkplayertype'] == 6255 || in_array($_POST['linkplayertype'], $this->config->item('playerids___42284')) || in_array($_POST['linkplayertype'], $this->config->item('playerids___42261')) || in_array($_POST['linkplayertype'], $this->config->item('playerids___11020'))) {
                    echo idea_view($_POST['linkplayertype'], $s, $previous_i, null, $focus_e['playerid']);
                    $success = true;
                }
            }

        } elseif ($_POST['focus__node'] == 12273) {

            //IDEA
            $previous_is = $this->Ideas->read(array(
                'ideaid' => $_POST['focus__id'],
            ));
            $previous_i = $previous_is[0];

            foreach (ideas_query($_POST['linkplayertype'], $_POST['focus__id'], $_POST['current_page']) as $s) {
                if (in_array($_POST['linkplayertype'], $this->config->item('playerids___11020'))) {
                    echo idea_view($_POST['linkplayertype'], $s, $previous_i);
                    $success = true;
                } else if ($_POST['linkplayertype'] == 6255 || in_array($_POST['linkplayertype'], $this->config->item('playerids___42261')) || in_array($_POST['linkplayertype'], $this->config->item('playerids___42284')) || in_array($_POST['linkplayertype'], $this->config->item('playerids___11028'))) {
                    echo player_view($_POST['linkplayertype'], $s);
                    $success = true;
                }
            }
        }

        if (!$success) {
            die('Nothing more to load :)');
        }

    }

    function link_sort_reset()
    {

        //Authenticate Member:
        $player_session = player_session(10939, 0, $this->player_session);

        if (!$player_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(10939),
            ));
        } elseif (!isset($_POST['focus__node']) || !in_array($_POST['focus__node'], $this->config->item('playerids___28956'))) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid focus__node',
            ));
        } elseif (!isset($_POST['focus__id']) || intval($_POST['focus__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid focus__id',
            ));
        }

        if ($_POST['focus__node'] == 12273) {
            //Ideas order based on alphabetical order
            $order = 0;
            foreach ($this->Links->read(array(
                'linkplayertype IN (' . join(',', $this->config->item('playerids___42267')) . ')' => null, //IDEA LINKS
                'linkidealeft' => $_POST['focus__id'],
            ), array('linkidearight'), 0, 0, array('ideatext' => 'ASC')) as $x) {
                $order++;
                $this->Links->update($x['linkid'], array(
                    'linknumber' => $order,
                ));
            }
        } elseif ($_POST['focus__node'] == 12274) {
            //Players reset order
            foreach ($this->Links->read(array(
                'linkplayerup' => $_POST['focus__id'],
                'linkplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
            ), array('linkplayerdown'), 0, 0) as $x) {
                $this->Links->update($x['linkid'], array(
                    'linknumber' => 0,
                ));
            }
        }

        //Display message:
        view_json(array(
            'status' => 1,
        ));
    }

    function idea_discovered()
    {

        $player_session = player_session(null, 0, $this->player_session);
        if (!$player_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['target_ideahashtag']) || !isset($_POST['target_ideaid']) || !isset($_POST['player_submitted_data']) || !isset($_POST['do_skip'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core Data',
            ));
        }

        if (!isset($_POST['selection_ideaid'])) {
            $_POST['selection_ideaid'] = array();
        }
        if (!isset($_POST['player_submitted_data']['idea_createtext'])) {
            $_POST['player_submitted_data']['idea_createtext'] = null;
        }
        if (!isset($_POST['player_submitted_data']['uploaded_media'])) {
            $_POST['player_submitted_data']['uploaded_media'] = array();
        }
        if (!isset($_POST['next_idea_data'])) {
            $_POST['next_idea_data'] = array();
        }

        //Discover Focus Idea:
        $primary_ideaid = null;
        foreach ($this->Ideas->read(array(
            'ideaid' => $_POST['player_submitted_data']['ideaid'],
        )) as $focus_i) {

            $input__selection = in_array($focus_i['ideatype'], $this->config->item('playerids___7712'));
            $input__upload = in_array($focus_i['ideatype'], $this->config->item('playerids___43004'));
            $skipping_not_allowed = in_array($focus_i['ideatype'], $this->config->item('playerids___43009'));
            $input__text = in_array($focus_i['ideatype'], $this->config->item('playerids___43002')) || in_array($focus_i['ideatype'], $this->config->item('playerids___43003'));
            $total_selected = count($_POST['selection_ideaid']);
            $trying_to_skip = !$skipping_not_allowed &&
                (
                    intval($_POST['do_skip'])
                    || ($input__selection && !$total_selected)
                    || ($input__text && !$input__upload && !strlen($_POST['player_submitted_data']['idea_createtext']))
                    || (!$input__text && $input__upload && !count($_POST['player_submitted_data']['uploaded_media']))
                    || ($input__text && $input__upload && !count($_POST['player_submitted_data']['uploaded_media']) && !strlen($_POST['player_submitted_data']['idea_createtext']))
                );
            $idea_required = idea_required($focus_i);

            if (!$primary_ideaid) {
                $primary_ideaid = ($total_selected ? end($_POST['selection_ideaid']) : $focus_i['ideaid']);
            }

            //If skipping, make sure they can:
            if ($idea_required && $trying_to_skip) {
                return view_json(array(
                    'status' => 0,
                    'message' => ($input__selection ? 'Make a selection to continue...' : 'Respond to continue...'),
                ));
            }

            //Now complete relevant next ideas, if any:
            if ($input__selection) {

                $is_single_selection = in_array($focus_i['ideatype'], $this->config->item('playerids___33331'));


                if (!$is_single_selection) {

                    //How about the min selection?
                    if ($idea_required) {
                        foreach ($this->Links->read(array(
                            'linkplayertype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
                            'linkidearight' => $focus_i['ideaid'],
                            'linkplayerup' => 40834, //Min Selection
                        ), array(), 1) as $limit) {
                            if (intval($limit['linktext']) > 0 && $total_selected < intval($limit['linktext'])) {
                                return view_json(array(
                                    'status' => 0,
                                    'message' => 'Select ' . $limit['linktext'] . ' or more ideas to go next.',
                                ));
                            }
                        }
                    }

                    //How about max selection?
                    foreach ($this->Links->read(array(
                        'linkplayertype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
                        'linkidearight' => $focus_i['ideaid'],
                        'linkplayerup' => 40833, //Max Selection
                    ), array(), 1) as $limit) {
                        if (intval($limit['linktext']) > 0 && $total_selected > intval($limit['linktext'])) {
                            return view_json(array(
                                'status' => 0,
                                'message' => 'You cannot select more than ' . $limit['linktext'] . ' items.',
                            ));
                        }
                    }

                }


                //Delete ALL previous answers that are not currently selected, if any:
                $already_answered = array();
                foreach ($this->Links->read(array(
                    'linkplayertype' => 7712, //Input Choice
                    'linkplayercreator' => $player_session['playerid'],
                    'linkidealeft' => $focus_i['ideaid'],
                ), array('linkidearight')) as $x_selection) {

                    if (in_array($x_selection['ideaid'], $_POST['selection_ideaid'])) {
                        //Current selection is already in the database from before:
                        array_push($already_answered, $x_selection['ideaid']);
                        continue; //Nothing we need to do here...
                    }

                    $this->Links->delete($x_selection['linkid'], $player_session['playerid']);

                    //Remove discovery if we can:
                    if (!in_array($x_selection['ideatype'], $this->config->item('playerids___42905'))) {
                        foreach ($this->Links->read(array(
                            'linkplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                            'linkidealeft' => $x_selection['ideaid'],
                            'linkplayercreator' => $player_session['playerid'],
                        ), array(), 0) as $x_discovery) {
                            $this->Links->delete($x_discovery['linkid'], $player_session['playerid']);
                        }
                    }
                }

                //Save New Answers if not already:
                foreach ($_POST['selection_ideaid'] as $answer_ideaid) {
                    if (!in_array($answer_ideaid, $already_answered)) {
                        $this->Links->create(array(
                            'linkplayertype' => 7712, //Input Choice
                            'linkplayercreator' => $player_session['playerid'],
                            'linkidealeft' => $focus_i['ideaid'],
                            'linkidearight' => $answer_ideaid,
                        ));
                    }
                }

            }

            //Issue DISCOVERY/IDEA COIN:
            $completion_status = $this->Links->idea_discovered(idea_type_discovery($focus_i, $trying_to_skip), $player_session['playerid'], $_POST['target_ideaid'], $focus_i, $_POST['player_submitted_data'], array(
                'linknumber' => $_POST['player_submitted_data']['ideanumber'],
            ));
            if (!$completion_status['status']) {
                //We had an error with data within target_ideaid:
                return view_json($completion_status);
            }


            //Look through ALL next ideas and see which ones we can complete, if any:
            foreach ($_POST['next_idea_data'] as $index => $next_idea_data) {

                if ($input__selection && !in_array($next_idea_data['ideaid'], $_POST['selection_ideaid'])) {
                    //Not selected, move on:
                    continue;
                }

                if (!isset($next_idea_data['uploaded_media'])) {
                    $next_idea_data['uploaded_media'] = array();
                }

                foreach ($this->Ideas->read(array(
                    'ideaid' => $next_idea_data['ideaid'],
                )) as $idea_next) {

                    //Analyze input:
                    $input__required = in_array($idea_next['ideatype'], $this->config->item('playerids___43039'));
                    if ($input__required) {
                        continue;
                    }
                    $input__text = in_array($idea_next['ideatype'], $this->config->item('playerids___43002')) || in_array($idea_next['ideatype'], $this->config->item('playerids___43003'));
                    $input__upload = in_array($idea_next['ideatype'], $this->config->item('playerids___43004'));
                    $skipping_not_allowed = in_array($idea_next['ideatype'], $this->config->item('playerids___43009'));


                    //Cleanup phone number:
                    if($input__text && strlen($next_idea_data['idea_createtext']) && !is_numeric($next_idea_data['idea_createtext']) && count($this->Links->read(array(
                            'linkplayertype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
                            'linkidearight' => $idea_next['ideaid'],
                            'linkplayerup' => 42181, //Phone
                        )))){
                        $next_idea_data['idea_createtext'] = preg_replace("/[^0-9]+/", "", $next_idea_data['idea_createtext']);
                        if(strlen($next_idea_data['idea_createtext'])<10){
                            return view_json(array(
                                'status' => 0,
                                'message' => 'Phone numbers cannot be less than 10 digits',
                            ));
                        }
                    }

                    $trying_to_skip = (
                        ($input__text && !$input__upload && !strlen($next_idea_data['idea_createtext'])) ||
                        (!$input__text && $input__upload && !count($next_idea_data['uploaded_media'])) ||
                        ($input__text && $input__upload && !count($next_idea_data['uploaded_media']) && !strlen($next_idea_data['idea_createtext']))
                    );
                    $idea_required = !$skipping_not_allowed && idea_required($idea_next);

                    if ($idea_required && $trying_to_skip) {
                        return view_json(array(
                            'status' => 0,
                            'message' => 'Enter a valid response to '.view_idea_title($idea_next, true).' instead of "'.$next_idea_data['idea_createtext'].'"',
                        ));
                    }

                    //Try to complete:
                    $completion_status = $this->Links->idea_discovered(idea_type_discovery($idea_next, $trying_to_skip), $player_session['playerid'], $_POST['target_ideaid'], $idea_next, $next_idea_data, array(
                        'linknumber' => $next_idea_data['ideanumber'],
                    ));
                    if ($idea_required && !$completion_status['status']) {
                        //We had an error with data within target_ideaid:
                        //return view_json($completion_status);
                    }
                }
            }

            //Find Next:
            $idea_redirect_url = false;
            foreach ($this->Ideas->read(array(
                'ideaid' => $primary_ideaid,
            )) as $primary_i) {
                $idea_redirect_url = idea_redirect_url($primary_i);
            }
            if (!$idea_redirect_url) {
                $idea_next = $this->Links->idea_next($player_session['playerid'], $_POST['target_ideahashtag'], $focus_i);
            }

            //All good:
            return view_json(array(
                'status' => 1,
                'message' => 'Saved & Next',
                'next__url' => ($idea_redirect_url ? $idea_redirect_url : ($idea_next ? $idea_next : 'start')),
            ));

        }

        //All good:
        return view_json(array(
            'status' => 0,
            'message' => 'Invalid Idea',
        ));

    }

    function player_select()
    {

        if (!isset($_POST['focus__id']) || !isset($_POST['o__id']) || !isset($_POST['element_id']) || !isset($_POST['player_createid']) || !isset($_POST['migratehandle']) || !isset($_POST['linkid'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing core data',
            ));
        }

        //Validate migration handles if any:
        $_POST['migratehandle'] = trim($_POST['migratehandle']);
        $first_letter = substr($_POST['migratehandle'], 0, 1);
        if ($first_letter == '@' && strlen($_POST['migratehandle']) > 1) {
            if (!count($this->Players->read(array(
                'LOWER(playerhandle)' => strtolower(substr($_POST['migratehandle'], 1)),
            )))) {
                return view_json(array(
                    'status' => 0,
                    'message' => $_POST['migratehandle'] . ' is an invalid Player Handle. Try again if you want to migrate this Player links or leave the field blank.',
                ));
            }
        } elseif ($first_letter == '#' && strlen($_POST['migratehandle']) > 1) {
            if (!count($this->Ideas->read(array(
                'LOWER(ideahashtag)' => strtolower(substr($_POST['migratehandle'], 1)),
            )))) {
                return view_json(array(
                    'status' => 0,
                    'message' => $_POST['migratehandle'] . ' is an invalid Idea Hashtag. Try again if you want to migrate this idea links or leave the field blank.',
                ));
            }
        } else {
            $_POST['migratehandle'] = '';
        }

        if (is_array($_POST['o__id'])) {
            $mass_result = array();
            foreach ($_POST['o__id'] as $o__id) {
                array_push($mass_result, $this->Links->select($_POST['focus__id'], $o__id, $_POST['element_id'], $_POST['player_createid'], $_POST['migratehandle'], $_POST['linkid']));
            }
            return view_json($mass_result);
        } else {
            return view_json($this->Links->select($_POST['focus__id'], $_POST['o__id'], $_POST['element_id'], $_POST['player_createid'], $_POST['migratehandle'], $_POST['linkid']));
        }

    }


    function link_delete()
    {

        /*
         *
         * When members indicate they want to stop
         * a IDEA this function saves the changes
         * necessary and delete the idea from their
         * discoveries.
         *
         * */

        $player_session = player_session(null, 0, $this->player_session);

        if (!$player_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['linkid']) || intval($_POST['linkid']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Link ID',
            ));
        }

        //Remove Idea
        $this->Links->delete($_POST['linkid'], $player_session['playerid']);

        return view_json(array(
            'status' => 1,
        ));
    }

    function link_load()
    {

        /*
         * Loads the list of Links based on the
         * filters passed on.
         *
         * */

        if(!isset($_POST['x_filters'])){
            return false;
        }

        $query_filters = unserialize($_POST['x_filters']);
        $joined_by = unserialize($_POST['x_joined_by']);
        $current_page = (isset($_POST['current_page']) && intval($_POST['current_page']) >= 2 ? intval($_POST['current_page']) : 1);
        $next_page = ($current_page + 1);
        $query_offset = (($current_page - 1) * view_memory(6404, 11064));
        $player_session = player_session(null, 0, $this->player_session);

        $message = '';
        $overall_stats = '';

        //Fetch Links and total Link counts:
        $x = $this->Links->read($query_filters, $joined_by, view_memory(6404, 11064), $query_offset);
        $x_count = $this->Links->read($query_filters, $joined_by, 0, 0, array(), 'COUNT(linkid) as total_count');
        $total_items_loaded = ($query_offset + count($x));
        $has_more_links = ($x_count[0]['total_count'] > 0 && $total_items_loaded < $x_count[0]['total_count']);


        //Display filter:
        if ($total_items_loaded > 0) {
            //Subsequent messages:
            $overall_stats = '<tr class="main__title x-info grey"><td colspan="100%">' . ($x_count[0]['total_count'] > $total_items_loaded ? ($total_items_loaded >= ($query_offset + 1) ? $total_items_loaded . ' OF ' : '') : '') . number_format($x_count[0]['total_count'], 0) . ' LINKS:</td></tr>';
        }


        if (count($x) > 0) {

            foreach ($x as $x) {
                $message .= link_view($x);
            }

            //Do we have more to show?
            if (!$has_more_links) {
                $message .= '<tr class="main__title x-info grey"><td colspan="100%"><div class="alert alert-success" role="alert"><span class="icon-block"><i class="far fa-check-circle"></i></span>All ' . $x_count[0]['total_count'] . ' Links have been loaded</div></td></tr>';


            }

        } else {

            //Show no Link warning:
            $message .= '<tr class="main__title x-info grey"><td colspan="100%"><div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>No Links found with the selected filters. Modify filters and try again.</div></td></tr>';

        }

        return view_json(array(
            'status' => 1,
            'message' => $message,
            'has_more_links' => $has_more_links,
            'overall_stats' => $overall_stats,

        ));


    }

    function link_graph()
    {

        //See if we have any idea or Player targets to limit our stats:
        $has_handle = isset($_POST['playerhandle']) && strlen($_POST['playerhandle']) && $_POST['playerhandle'];
        $has_hashtag = isset($_POST['ideahashtag']) && strlen($_POST['ideahashtag']) && $_POST['ideahashtag'];

        if ($has_handle) {

            //See stats for this Player:
            $es = $this->Players->read(array(
                'LOWER(playerhandle)' => strtolower($_POST['playerhandle']),
            ));
            if (!count($es)) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Handle',
                ));
            }

        } elseif ($has_hashtag) {

            //See stats for this idea:
            $is = $this->Ideas->read(array(
                'LOWER(ideahashtag)' => strtolower($_POST['ideahashtag']),
            ));
            if (!count($is)) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Hashtag',
                ));
            }

            $copy = $this->Ideas->ids($is[0], 'ALL');
        }


        //Count Links:
        $return_array = array();
        foreach ($this->config->item('players___33292') as $linkplayertype1 => $m1) { //Gameplay

            $level1_total = 0;

            if($linkplayertype1==1309754){


                if ($has_handle) {
                    $void_filter['(linkvoid >0 AND ( linkplayerdown = ' . $es[0]['playerid'] . ' OR linkplayerup = ' . $es[0]['playerid'] . ' OR linkplayercreator = ' . $es[0]['playerid'] . ' ))'] = null;
                } elseif ($has_hashtag) {
                    $void_filter['(linkvoid >0 AND ( linkidealeft = ' . $is[0]['ideaid'] . ' OR linkidearight = ' . $is[0]['ideaid'] . ' ))'] = null;
                } else {
                    //Void Links
                    $void_filter = array(
                        'linkvoid >' => 0, //Links that have been voided
                    );
                }
                $sub_counter = $this->Links->read($void_filter, array(), 0, 0, array(), 'COUNT(linkid) as totals');
                $return_array[$linkplayertype1] = intval($sub_counter[0]['totals']);
                continue;
            }

            foreach ($this->config->item('players___' . $linkplayertype1) as $linkplayertype2 => $m2) { //Nodes/Links

                $player_pinned = player_pinned($linkplayertype2, true);
                $level2_total = 0;
                if (!is_array($this->config->item('players___' . $player_pinned)) || !count($this->config->item('players___' . $player_pinned))) {
                    continue;
                }
                foreach ($this->config->item('players___' . $player_pinned) as $linkplayertype3 => $m3) { //Player/Idea/Discovery

                    if ($linkplayertype2 == 12273) {

                        if ($has_handle) {

                            $sub_counter = $this->Links->read(array(
                                'linkplayertype IN (' . join(',', $this->config->item('playerids___33602')) . ')' => null, //Idea/Player Links Active
                                'linkplayerup' => $es[0]['playerid'],
                            ), array('linkidearight'), 0, 0, array(), 'COUNT(linkid) as totals');

                        } elseif ($has_hashtag && count($copy['recursive_idea_ids'])) {

                            //See stats for this idea:
                            $sub_counter = $this->Ideas->read(array(
                                'ideaid IN (' . join(',', $copy['recursive_idea_ids']) . ')' => null,
                            ), 0, 0, array(), 'COUNT(ideaid) as totals');

                        } else {

                            $sub_counter = $this->Ideas->read(array(), 0, 0, array(), 'COUNT(ideaid) as totals');

                        }

                    } elseif ($linkplayertype2 == 12274) {

                        if ($has_handle) {

                            $sub_counter = $this->Links->read(array(
                                'linkplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
                                'linkplayerup' => $es[0]['playerid'],
                            ), array('linkplayerdown'), 0, 0, array(), 'COUNT(linkid) as totals');

                        } elseif ($has_hashtag && count($copy['recursive_idea_ids'])) {

                            //See stats for this idea:
                            $sub_counter = $this->Links->read(array(
                                'linkplayertype IN (' . join(',', $this->config->item('playerids___33602')) . ')' => null, //Idea/Player Links Active
                                'linkidearight IN (' . join(',', $copy['recursive_idea_ids']) . ')' => null,
                            ), array('linkplayerup'), 0, 0, array(), 'COUNT(linkid) as totals');

                        } else {

                            $sub_counter = $this->Players->read(array(), 0, 0, array(), 'COUNT(playerid) as totals');

                        }

                    } else {

                        if ($has_handle) {

                            $sub_counter = $this->Links->read(array(
                                'linkplayertype' => $linkplayertype3,
                                '( linkplayerdown = ' . $es[0]['playerid'] . ' OR linkplayerup = ' . $es[0]['playerid'] . ' OR linkplayercreator = ' . $es[0]['playerid'] . ' )' => null,
                            ), array(), 0, 0, array(), 'COUNT(linkid) as totals');

                        } elseif ($has_hashtag && count($copy['recursive_idea_ids'])) {

                            $sub_counter = $this->Links->read(array(
                                'linkplayertype' => $linkplayertype3,
                                '( linkidealeft IN (' . join(',', $copy['recursive_idea_ids']) . ') OR linkidearight IN (' . join(',', $copy['recursive_idea_ids']) . '))' => null,
                            ), array(), 0, 0, array(), 'COUNT(linkid) as totals');

                        } else {

                            $sub_counter = $this->Links->read(array(
                                'linkplayertype' => $linkplayertype3,
                            ), array(), 0, 0, array(), 'COUNT(linkid) as totals');

                        }

                    }

                    $level2_total += $sub_counter[0]['totals'];
                    $return_array[$linkplayertype3] = intval($sub_counter[0]['totals']);

                    if ($linkplayertype2 == 12273 || $linkplayertype2 == 12274) {
                        break;
                    }

                }

                $level1_total += $level2_total;
                $return_array[$linkplayertype2] = intval($level2_total);

            }

            $return_array[$linkplayertype1] = intval($level1_total);

        }
        return view_json(array(
            'status' => 1,
            'return_array' => $return_array,
        ));
    }

}