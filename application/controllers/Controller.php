<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Controller extends CI_Controller
{

    public $handle_session;

    function __construct()
    {

        parent::__construct();

        $this->output->enable_profiler(FALSE);

        $this->handle_session = handle_session();


        date_default_timezone_set('America/Los_Angeles');

        @session_start();

        //AUTO Login handle if has cookie?
        $is_ajax = false;
        $handle_user = false;
        $memory_detected = is_array($this->config->item('handleids___6287')) && count($this->config->item('handleids___6287'));
        $first_segment = ($is_ajax && isset($_POST['js_request_uri']) ? $_POST['js_request_uri'] : $this->uri->segment(1));
        $_SERVER['REQUEST_URI'] = (isset($_POST['js_request_uri']) ? $_POST['js_request_uri'] : @$_SERVER['REQUEST_URI']);
        $_SERVER['REQUEST_URI'] = (strlen($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : view_app_chain(4269));
        $handle_session = handle_session();
        $is_login_verified = isset($_GET['handlelogin']) && isset($_GET['hash']) && isset($_GET['time']) && ($_GET['time'] + 604800) > time() && strlen($_GET['handlelogin']) && view_hash($_GET['time'] . $_GET['handlelogin']) == $_GET['hash'];

        if (
            $memory_detected &&
            !$handle_session
            && !array_key_exists(strtolower($first_segment), $this->config->item('handlhandles___14582'))
            && (isset($_COOKIE['auth_cookie']) || $is_login_verified) //We can auto login with either method:
        ) {

            if ($is_login_verified) {

                foreach ($this->Handles->read(array(
                    'LOWER(handleterm)' => strtolower($_GET['handlelogin']),
                )) as $handle_session) {

                    //Login:
                    $this->Handles->activate($handle_session, true);

                    //Log them in:
                    if (!$is_ajax) {
                        header("Location: " . $_SERVER['REQUEST_URI'], true, 307);
                        exit;
                    }

                }

            } elseif (isset($_COOKIE['auth_cookie'])) {

                $handle_session = verify_cookie();
                if ($handle_session) {
                    //Log them in:
                    if (!$is_ajax) {
                        header("Location: " . $_SERVER['REQUEST_URI'], true, 307);
                        exit;
                    }
                }
            }

            //Log them in:
            if (!$is_ajax) {
                header("Location: " . view_app_chain(4269) . (strlen($_SERVER['REQUEST_URI']) ? '?url=' . urlencode($_SERVER['REQUEST_URI']) : ''), true, 307);
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

    function load($app_handleid = 14563 /* Error if none provided */, $focus_handle = 0, $focus_hashtag = 0, $target_hashtag = 0)
    {

        $memory_detected = is_array($this->config->item('handleids___6287')) && count($this->config->item('handleids___6287'));
        if (!$memory_detected) {
            //Since we don't have the memory created we must load the app that does so:
            $app_handleid = 4527;
        }

        //Any hashtags passed?
        $handles___6287 = $this->config->item('handles___6287'); //APP
        $flash_message = false;
        $focus_e = null; //Handles
        $focus_i = null; //Hashtags
        $target_i = null; //Discovery


        if ($focus_handle && strlen($focus_handle) && !isset($_GET['handleterm'])) {
            $_GET['handleterm'] = $focus_handle;
        }
        if ($focus_hashtag && strlen($focus_hashtag) && !isset($_GET['hashtagterm'])) {
            $_GET['hashtagterm'] = $focus_hashtag;
        }
        if (!isset($_GET['handleterm'])) {
            $_GET['handleterm'] = 0;
        }
        if (!isset($_GET['hashtagterm'])) {
            $_GET['hashtagterm'] = 0;
        }


        if ($target_hashtag && strlen($target_hashtag)) {
            //Verify:
            foreach ($this->Hashtags->read(array(
                'LOWER(hashtagterm)' => strtolower($target_hashtag),
            )) as $hashtag_found) {
                $target_i = $hashtag_found;
            }
        }


        if (strlen($_GET['hashtagterm'])) {

            //Validate Focus Hashtag:
            if ($target_i && $_GET['hashtagterm'] == view_memory(6404, 4235)) {

                //This is the starting point:
                $_GET['hashtagterm'] = $target_hashtag;
                $focus_i = $target_i;

            } else {

                foreach ($this->Hashtags->read(array(
                    'LOWER(hashtagterm)' => strtolower($_GET['hashtagterm']),
                )) as $hashtag_found) {
                    $focus_i = $hashtag_found;
                }

            }

            if (!$focus_i) {
                //See if we can find via ID?
                if (is_numeric($_GET['hashtagterm'])) {
                    foreach ($this->Hashtags->read(array(
                        'hashtagid' => $_GET['hashtagterm'],
                    )) as $hashtag_found) {
                        $focus_i = $hashtag_found;
                    }
                }
            }

            if ($app_handleid == 33286 && $focus_i && $focus_i['hashtagterm'] !== $_GET['hashtagterm']) {
                //Adjust URL Case Sensitive:
                return get_redirected(view_memory(42903, 33286) . $focus_i['hashtagterm']);
            }
        }


        if (isset($_GET['handleterm']) && strlen($_GET['handleterm'])) {
            foreach ($this->Handles->read(array(
                'LOWER(handleterm)' => strtolower($_GET['handleterm']),
            )) as $handle_found) {
                $focus_e = $handle_found;
            }
            if (!$focus_e) {
                //See if we need to lookup the ID:
                if (is_numeric($_GET['handleterm'])) {
                    //Maybe its an ID?
                    foreach ($this->Handles->read(array(
                        'handleid' => $_GET['handleterm'],
                    )) as $handle_found) {
                        $focus_e = $handle_found;
                    }
                }
            }
            if ($app_handleid == 42902 && $focus_e && $focus_e['handleterm'] !== $_GET['handleterm']) {
                //Adjust URL Case Sensitive:
                return get_redirected(view_memory(42903, 42902) . $focus_e['handleterm']);
            }
        }


        if ($memory_detected && !in_array($app_handleid, $this->config->item('handleids___6287'))) {
            //Invalid App:
            return get_redirected(view_memory(42903, 42902) . $handles___6287[$app_handleid]['m__handle'], '<div class="alert alert-danger" role="alert">@' . $handles___6287[$app_handleid]['m__handle'] . ' Is not an APP, yet 🤔</div>');
        } elseif ($memory_detected && !in_array($app_handleid, $this->config->item('handleids___42922'))) {
            //Validate Required App input:
            if (in_array($app_handleid, $this->config->item('handleids___42905')) && !$focus_e) {
                return get_redirected(home_url(), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Error: @' . $_GET['handleterm'] . ' is not a valid Handle handle.</div>');
            } elseif (in_array($app_handleid, $this->config->item('handleids___44329')) && (!$focus_i || !$target_i)) {
                return get_redirected(home_url(), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Error: Both #' . $_GET['hashtagterm'] . ' & #' . $target_hashtag . ' must be valid hashtags.</div>');
            } elseif (in_array($app_handleid, $this->config->item('handleids___42911')) && !$focus_i) {
                return get_redirected(home_url(), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Error: #' . $_GET['hashtagterm'] . ' is not a valid hashtag hashtag.</div>');
            }
        }


        $chainhandleoutput = ($focus_e ? $focus_e['handleid'] : 0);
        $chainhashtagoutput = ($focus_i ? $focus_i['hashtagid'] : 0);
        $chainhashtaginput = ($target_i ? $target_i['hashtagid'] : 0);

        //Run App
        $handle_session = false;
        $handle_http_request = (isset($_SERVER['SERVER_NAME']) ? 1 : 0);

        if ($memory_detected && in_array($app_handleid, $this->config->item('handleids___42920'))) {
            boost_power();
        }

        if ($memory_detected && $handle_http_request) {

            //Needs superpowers?
            $handle_session = handle_session();

            if ($handle_session && !isset($handle_session['handleid']) && $app_handleid!=7291) {
                //Old handle, must log out:
                header("Location: /logout", true, 301);
                return false;
            }

            //Auto Login?
            if (isset($_GET['hash']) && isset($_GET['time']) && $focus_e) {

                //Validate Hash:
                if ($_GET['hash'] == view_hash($_GET['time'] . $focus_e['handleterm'])) {

                    if ($focus_i) {
                        if (hashtag_is_startable($focus_i)) {
                            $flash_message = '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="far fa-play"></i></span>You have started discovering this hashtag. Scroll to the bottom & go next to continue.</div>';
                        } else {
                            $this->Chains->hashtag_discovered(4559, $focus_e['handleid'], ($target_i ? $target_i['hashtagid'] : 0), $focus_i);

                            //Inform user of changes:
                            $flash_message = '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="far fa-check-circle"></i></span>Hashtags has been discovered</div>';
                        }
                    }

                    //If not logged in, log them in:
                    if (!$handle_session) {
                        $session_data = $this->Handles->activate($handle_session, true);
                    }

                }
            }
        }


        //Cache App?
        $ui = null;
        $new_cache = false;
        $cache_chaintime = null;
        $chainhandlecreator = ($handle_http_request ? ($handle_session ? $handle_session['handleid'] : 14068 /* GUEST */) : 7274 /* CRON JOB */);
        $skip_hashtag_privacy_check = !$memory_detected || in_array($app_handleid, $this->config->item('handleids___43388'));
        $handle_access = handle_access(null, $focus_e['handleid'], $focus_e);
        $hashtag_access = hashtag_access(null, $focus_i['hashtagid'], $focus_i);
        $target_hashtag_access = hashtag_access(null, $target_i['hashtagid'], $target_i);

        //MEMBER REDIRECT?
        if ($handle_http_request && $memory_detected) {

            //Missing App, Handle or Hashtag Access?
            $missing_access = false; //Assume they have access
            $superpowers_required = array_intersect($this->config->item('handleids___10957'), $handles___6287[$app_handleid]['m__following']);
            if ($handle_session && in_array($app_handleid, $this->config->item('handleids___14639'))) {
                //Should redirect them:
                return get_redirected(view_memory(42903, 42902) . $handle_session['handleterm']);
            } elseif (!$handle_session && in_array($app_handleid, $this->config->item('handleids___14740'))) {
                //Should redirect them:
                $missing_access = 'Login or register a free account to continue.';
            } elseif (count($superpowers_required) && !handle_session(end($superpowers_required))) {
                $handles___10957 = $this->config->item('handles___10957');
                $missing_access = 'Error: You Cannot Access ' . $handles___6287[$app_handleid]['m__title'] . ' as it requires the superpower of ' . $handles___10957[end($superpowers_required)]['m__title'] . '.';
            } elseif ($focus_e && !$handle_access) {
                $missing_access = 'Error: You Cannot Access @' . $focus_e['handleterm'] . ' due to Privacy Settings.';
            } elseif (!$skip_hashtag_privacy_check && $focus_i && !$hashtag_access) {
                $missing_access = 'Error: You Cannot Access Focus #' . $focus_i['hashtagterm'] . ' due to Privacy Settings.';
            } elseif (!$skip_hashtag_privacy_check && $target_i && !$target_hashtag_access) {
                $missing_access = 'Error: You Cannot Access Target #' . $target_i['hashtagterm'] . ' due to Privacy Settings.';
            }

            if ($missing_access) {
                //Redirect:
                return get_redirected((!$handle_session ? view_app_chain(4269) . '?url=' . urlencode($_SERVER['REQUEST_URI']) : home_url()), '<div class="alert alert-warning" role="alert">' . $missing_access . '</div>');
            }
        }


        if ($memory_detected) {

            if (in_array($app_handleid, $this->config->item('handleids___14599')) && !in_array($app_handleid, $this->config->item('handleids___12741'))) {

                if (!isset($_GET['reset_cache'])) {
                    //Fetch Most Recent Cache:
                    foreach ($this->Chains->read(array(
                        'chainhandledomain' => website_setting(0),
                        'chainhandletype' => 44176, //Handle View
                        'chainhandleinput' => 14599, //Cache App
                        'chainhandleoutput' => $app_handleid,
                    ), array(), 1, 0, array('chaintime' => 'DESC')) as $latest_cache) {
                        if (strtotime($latest_cache['chaintime']) <= (time() - view_memory(6404, 14599))) {
                            //Its expired, void it:
                            $this->Chains->delete($latest_cache['chainid']);
                        } else {
                            $ui = $latest_cache['chainvalue'];
                            $cache_chaintime = '<div class="texttransparent center main__title">Updated ' . view_time_difference($latest_cache['chaintime']) . ' Ago</div>';
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
            $title .= view_hashtag_title($focus_i, true) . ' | ';
        }
        if ($target_i) {
            $title .= view_hashtag_title($target_i, true) . ' | ';
        }
        if ($focus_e) {
            $title .= $focus_e['handlename'] . ' @' . $focus_e['handleterm'] . ' | ';
        }
        if (!$title) {
            //Append app name since no title:
            $title .= $handles___6287[$app_handleid]['m__title'] . ' | ';
        }
        //Always Append Website at the end:
        $title .= ($memory_detected ? get_domain('m__title') : 'Loading Memory');


        $view_input = array(
            'app_handleid' => $app_handleid,
            'chainhandlecreator' => $chainhandlecreator,
            'handle_session' => $handle_session,
            'handle_http_request' => $handle_http_request,
            'memory_detected' => $memory_detected,

            'focus_e' => $focus_e,
            'focus_i' => $focus_i,
            'target_i' => $target_i,

            '$handle_access' => $handle_access,
            '$hashtag_access' => $hashtag_access,
            '$target_hashtag_access' => $target_hashtag_access,

            'title' => $title,
            'flash_message' => $flash_message,
        );

        if (!$ui) {
            //Prep view:
            $app_handler = ($memory_detected ? strtolower($handles___6287[$app_handleid]['m__handle']) : 'memory');
            $raw_app = $this->load->view($app_handler, $view_input, true);
            $ui .= $raw_app;
        }


        if ($new_cache) {
            $cache_x = $this->Chains->create(array(
                'chainhandledomain' => website_setting(0),
                'chainhandletype' => 44176, //Handle View
                'chainhandleinput' => 14599, //Cache App
                'chainhandleoutput' => $app_handleid,

                'chainhandlecreator' => $chainhandlecreator,
                'chainvalue' => $ui,
                'chainhashtaginput' => $chainhashtaginput,
                'chainhashtagoutput' => $chainhashtagoutput,
            ));
        }


        //App title?
        if ($memory_detected && in_array($app_handleid, $this->config->item('handleids___42928'))) {
            $ui = '<h1><span style="font-size:2em !important;">' . $handles___6287[$app_handleid]['m__cover'] . '</span> ' . $handles___6287[$app_handleid]['m__title'] . '</h1>' . $ui;
        }


        //Check to ensure they have started:
        if ($app_handleid == 30795 && $target_i && $focus_i && $handle_session && $target_i['hashtagterm'] == $focus_i['hashtagterm']) {

            //Starting point, make sure all good:
            if (!hashtag_is_startable($target_i)) {

                //Not a valid starting point:
                return get_redirected(home_url(), '<div class="alert alert-warning" role="alert">#' . $target_i['hashtagterm'] . ' is not an active starting point.</div>');

            } elseif (!count($this->Chains->read(array(
                'LOWER(hashtagterm)' => strtolower($target_i['hashtagterm']),
                'chainhandlecreator' => $handle_session['handleid'],
                'chainhandletype IN (' . join(',', $this->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
            ), array('chainhashtaginput')))) {

                //Not yet started, add to their starting point:
                $completion_status = $this->Chains->hashtag_discovered(4235, $handle_session['handleid'], 0, $target_i);

                //Now return next hashtag:
                $next__url = $this->Chains->next_hashtags($handle_session['handleid'], $target_i['hashtagterm'], $target_i);

                if ($next__url) {
                    //Go Next:
                    return get_redirected(view_memory(42903, 30795) . $target_i['hashtagterm'] . '/' . $next__url);
                }

            }

        }


        //Delivery App
        if (!$memory_detected) {

            echo $ui;

        } else {

            if (in_array($app_handleid, $this->config->item('handleids___12741'))) {

                //Raw UI:
                echo $raw_app;

            } else {

                //Regular UI:
                //Load App:
                echo $this->load->view('websiteheader', $view_input, true);
                echo $ui;
                echo $cache_chaintime;
                echo $this->load->view('websitefooter', array(), true);

            }
        }
    }


    /*
     * 
     * AJAX FUNCTION CALLS:
     * 
     * */


    function chain_popover()
    {

        if (isset($_POST['handle_string']) && strlen($_POST['handle_string']) > 1 && in_array(substr($_POST['handle_string'], 0, 1), array('#', '@'))) {
            if (substr($_POST['handle_string'], 0, 1) == '#') {
                foreach ($this->Hashtags->read(array(
                    'LOWER(hashtagterm)' => strtolower(substr($_POST['handle_string'], 1)),
                )) as $i) {
                    echo hashtag_view(31777, $i);
                    return true;
                }
            } elseif (substr($_POST['handle_string'], 0, 1) == '@') {
                foreach ($this->Handles->read(array(
                    'LOWER(handleterm)' => strtolower(substr($_POST['handle_string'], 1)),
                )) as $e) {
                    echo handle_view(42287, $e);
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






    function add_media()
    {

        $handle_session = handle_session(null, 0, $this->handle_session);
        if (!$handle_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['hashtagid']) || !isset($_POST['chainid'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core IDs',
            ));
        }

        //$dd = add_media($uploaded_media);

        $hashtagid = 0; //New hashtag
        $created_hashtagid = 0;

        if (!$_POST['hashtagid']) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Hashtag Media ID!',
            ));
        }

        $is = $this->Hashtags->read(array(
            'hashtagid' => $_POST['hashtagid'],
        ));
        if (!count($is)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Hashtag is no longer active',
            ));
        } elseif (!hashtag_access($is[0]['hashtagterm'], 0, $is[0])) {
            return view_json(array(
                'status' => 0,
                'message' => 'You are missing permission to edit this hashtag',
            ));
        }


        $hashtagid = intval($is[0]['hashtagid']);

        //Fetch dynamic data based on hashtag type:
        $return_inputs = array();
        $handles___42179 = $this->config->item('handles___42179'); //Dynamic Input Fields
        $handles___11035 = $this->config->item('handles___11035'); //Encyclopedia

        foreach ($this->Chains->read(array(
            'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
            'chainhashtagoutput' => $is[0]['hashtagid'],
            'chainhandleinput IN (' . join(',', $this->config->item('handleids___4737')) . ')' => null, //Hashtag Types
        )) as $hashtag_type) {

            foreach (array_intersect($this->config->item('handleids___' . $hashtag_type['chainhandleinput']), $this->config->item('handleids___42179')) as $dynamic_handleid) {

                $superpowers_required = array_intersect($this->config->item('handleids___10957'), $handles___42179[$dynamic_handleid]['m__following']);
                if (count($superpowers_required) && !handle_session(end($superpowers_required), 0, $this->handle_session)) {
                    continue;
                }

                //Let's first determine the data type:
                $data_types = array_intersect($handles___42179[$dynamic_handleid]['m__following'], $this->config->item('handleids___4592'));

                if (count($data_types) != 1) {
                    //This is strange, we are expecting 1 match only report this:
                    log_error('Found ' . count($data_types) . ' Data Types (Expecting exactly 1) for @' . $dynamic_handleid . ': Check @4592 to see what is wrong', array(
                        'chainhandlecreator' => $handle_session['handleid'],
                        'chainhandleoutput' => $dynamic_handleid,
                        'chainhashtagoutput' => $hashtagid,
                    ));
                    continue; //Go to the next dynamic data type
                }

                //We found 1 match as expected:
                foreach ($data_types as $data_type_this) {
                    $data_type = $data_type_this;
                    break;
                }

                if (in_array($data_type, $this->config->item('handleids___42188'))) {

                    //Single or Multiple Choice:
                    array_push($return_inputs, array(
                        'd__id' => $dynamic_handleid,
                        'd__is_radio' => 1,
                        'd_chainid' => 0,
                        'd__html' => view_instant_select($dynamic_handleid, 0, $hashtagid),
                        'd__value' => ($hashtagid > 0 ? $hashtagid : ''),
                        'd__type_name' => '',
                        'd__placeholder' => '',
                        'd__profile_header' => '',
                    ));

                } else {

                    $this_data_type = $this->config->item('handles___' . $data_type);
                    $handles___4592 = $this->config->item('handles___4592'); //Data types
                    $handles___42179 = $this->config->item('handles___42179'); //Dynamic Input Field
                    $handles___11035 = $this->config->item('handles___11035'); //Encyclopedia

                    //Fetch the current value:
                    $counted = 0;
                    $unique_values = array();
                    if ($hashtagid > 0) { //Must have an original ID to possibly have a value...
                        foreach ($this->Chains->read(array(
                            'chainhandletype IN (' . join(',', $this->config->item('handleids___42252')) . ')' => null, //Plain Chain
                            'chainhashtagoutput' => $hashtagid,
                            'chainhandleinput' => $dynamic_handleid,
                        ), array('chainhandleinput')) as $selected_e) {
                            if (strlen($selected_e['chainvalue']) && !in_array($selected_e['chainvalue'], $unique_values)) {
                                $counted++;
                                array_push($unique_values, $selected_e['chainvalue']);
                                array_push($return_inputs, array(
                                    'd__id' => $dynamic_handleid,
                                    'd__is_radio' => 0,
                                    'd_chainid' => $selected_e['chainid'],
                                    'd__html' => view_dynamic_headline($dynamic_handleid, $handles___42179[$dynamic_handleid], $selected_e),
                                    'd__value' => $selected_e['chainvalue'],
                                    'd__type_name' => html_input_type($data_type),
                                    'd__placeholder' => (strlen($this_data_type[$dynamic_handleid]['m__message']) ? $this_data_type[$dynamic_handleid]['m__message'] : $handles___4592[$data_type]['m__title'] . '...'),
                                    'd__profile_header' => '',
                                ));
                            }
                        }
                    }


                    if (!$counted) {
                        foreach ($this->Handles->read(array(
                            'handleid' => $dynamic_handleid,
                        )) as $selected_e) {
                            array_push($return_inputs, array(
                                'd__id' => $dynamic_handleid,
                                'd__is_radio' => 0,
                                'd_chainid' => 0,
                                'd__html' => view_dynamic_headline($dynamic_handleid, $handles___42179[$dynamic_handleid], $selected_e),
                                'd__value' => '',
                                'd__type_name' => html_input_type($data_type),
                                'd__placeholder' => (strlen($this_data_type[$dynamic_handleid]['m__message']) ? $this_data_type[$dynamic_handleid]['m__message'] : $handles___4592[$data_type]['m__title'] . '...'),
                                'd__profile_header' => '',
                            ));
                        }
                    }
                }
            }
        }

        $return_array = array(
            'status' => 1,
            'return_inputs' => $return_inputs,
            'created_hashtagid' => $created_hashtagid,
        );

        //Return everything we found:
        return view_json($return_array);

    }

    function hashtag_editor()
    {

        $handle_session = handle_session(null, 0, $this->handle_session);
        if (!$handle_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['hashtagid']) || !isset($_POST['chainid'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core IDs',
            ));
        }


        $hashtagid = 0; //New hashtag
        $created_hashtagid = 0;

        if (!$_POST['hashtagid']) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Hashtag ID!',
            ));
        }

        $is = $this->Hashtags->read(array(
            'hashtagid' => $_POST['hashtagid'],
        ));
        if (!count($is)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Hashtag is no longer active',
            ));
        } elseif (!hashtag_access($is[0]['hashtagterm'], 0, $is[0])) {
            return view_json(array(
                'status' => 0,
                'message' => 'You are missing permission to edit this hashtag',
            ));
        }

        $hashtagid = intval($is[0]['hashtagid']);

        //Fetch dynamic data based on hashtag type:
        $return_inputs = array();
        $handles___42179 = $this->config->item('handles___42179'); //Dynamic Input Fields
        $handles___11035 = $this->config->item('handles___11035'); //Encyclopedia


        foreach ($this->Chains->read(array(
            'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
            'chainhashtagoutput' => $is[0]['hashtagid'],
            'chainhandleinput IN (' . join(',', $this->config->item('handleids___4737')) . ')' => null, //Hashtag Types
        )) as $hashtag_type) {
            foreach (array_intersect($this->config->item('handleids___' . $hashtag_type['chainhandleinput']), $this->config->item('handleids___42179')) as $dynamic_handleid) {

                $superpowers_required = array_intersect($this->config->item('handleids___10957'), $handles___42179[$dynamic_handleid]['m__following']);
                if (count($superpowers_required) && !handle_session(end($superpowers_required), 0, $this->handle_session)) {
                    continue;
                }

                //Let's first determine the data type:
                $data_types = array_intersect($handles___42179[$dynamic_handleid]['m__following'], $this->config->item('handleids___4592'));

                if (count($data_types) != 1) {
                    //This is strange, we are expecting 1 match only report this:
                    log_error('Found ' . count($data_types) . ' Data Types (Expecting exactly 1) for @' . $dynamic_handleid . ': Check @4592 to see what is wrong', array(
                        'chainhandlecreator' => $handle_session['handleid'],
                        'chainhandleoutput' => $dynamic_handleid,
                        'chainhashtagoutput' => $hashtagid,
                    ));
                    continue; //Go to the next dynamic data type
                }

                //We found 1 match as expected:
                foreach ($data_types as $data_type_this) {
                    $data_type = $data_type_this;
                    break;
                }

                if (in_array($data_type, $this->config->item('handleids___42188'))) {

                    //Single or Multiple Choice:
                    array_push($return_inputs, array(
                        'd__id' => $dynamic_handleid,
                        'd__is_radio' => 1,
                        'd_chainid' => 0,
                        'd__html' => view_instant_select($dynamic_handleid, 0, $hashtagid),
                        'd__value' => ($hashtagid > 0 ? $hashtagid : ''),
                        'd__type_name' => '',
                        'd__placeholder' => '',
                        'd__profile_header' => '',
                    ));

                } else {

                    $this_data_type = $this->config->item('handles___' . $data_type);
                    $handles___4592 = $this->config->item('handles___4592'); //Data types
                    $handles___42179 = $this->config->item('handles___42179'); //Dynamic Input Field
                    $handles___11035 = $this->config->item('handles___11035'); //Encyclopedia

                    //Fetch the current value:
                    $counted = 0;
                    $unique_values = array();
                    if ($hashtagid > 0) { //Must have an original ID to possibly have a value...
                        foreach ($this->Chains->read(array(
                            'chainhandletype IN (' . join(',', $this->config->item('handleids___42252')) . ')' => null, //Plain Chain
                            'chainhashtagoutput' => $hashtagid,
                            'chainhandleinput' => $dynamic_handleid,
                        ), array('chainhandleinput')) as $selected_e) {
                            if (strlen($selected_e['chainvalue']) && !in_array($selected_e['chainvalue'], $unique_values)) {
                                $counted++;
                                array_push($unique_values, $selected_e['chainvalue']);
                                array_push($return_inputs, array(
                                    'd__id' => $dynamic_handleid,
                                    'd__is_radio' => 0,
                                    'd_chainid' => $selected_e['chainid'],
                                    'd__html' => view_dynamic_headline($dynamic_handleid, $handles___42179[$dynamic_handleid], $selected_e),
                                    'd__value' => $selected_e['chainvalue'],
                                    'd__type_name' => html_input_type($data_type),
                                    'd__placeholder' => (strlen($this_data_type[$dynamic_handleid]['m__message']) ? $this_data_type[$dynamic_handleid]['m__message'] : $handles___4592[$data_type]['m__title'] . '...'),
                                    'd__profile_header' => '',
                                ));
                            }
                        }
                    }


                    if (!$counted) {
                        foreach ($this->Handles->read(array(
                            'handleid' => $dynamic_handleid,
                        )) as $selected_e) {
                            array_push($return_inputs, array(
                                'd__id' => $dynamic_handleid,
                                'd__is_radio' => 0,
                                'd_chainid' => 0,
                                'd__html' => view_dynamic_headline($dynamic_handleid, $handles___42179[$dynamic_handleid], $selected_e),
                                'd__value' => '',
                                'd__type_name' => html_input_type($data_type),
                                'd__placeholder' => (strlen($this_data_type[$dynamic_handleid]['m__message']) ? $this_data_type[$dynamic_handleid]['m__message'] : $handles___4592[$data_type]['m__title'] . '...'),
                                'd__profile_header' => '',
                            ));
                        }
                    }
                }
            }
        }

        $return_array = array(
            'status' => 1,
            'return_inputs' => $return_inputs,
            'created_hashtagid' => $created_hashtagid,
        );

        //Return everything we found:
        return view_json($return_array);

    }


    function hashtag_delete()
    {

        $handle_session = handle_session(null, 0, $this->handle_session);
        $migrateid = 0;

        if (!$handle_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['hashtagid']) || !isset($_POST['focus__id']) || !isset($_POST['migratehandle'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core IDs',
            ));
        } elseif (hashtag_access(null, $_POST['hashtagid']) < 3) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Access to delete this hashtag',
            ));
        } elseif (strlen($_POST['migratehandle']) > 1) {
            $valid_handle = $this->Hashtags->read(array(
                'hashtagid !=' => $_POST['hashtagid'],
                'LOWER(hashtagterm)' => strtolower(str_replace('#', '', $_POST['migratehandle'])),
            ));
            if (!count($valid_handle)) {
                return view_json(array(
                    'status' => 0,
                    'message' => $_POST['migratehandle'] . ' is not an active hashtag',
                ));
            }
            $migrateid = $valid_handle[0]['hashtagid'];
        }

        $delete_redirect = '';
        $delete_element = '';
        //Determine what to do after deleted:
        if ($_POST['hashtagid'] == $_POST['focus__id']) {

            //Find Published Followings:
            foreach ($this->Chains->read(array(
                'chainhandletype IN (' . join(',', $this->config->item('handleids___42345')) . ')' => null, //Active Sequence
                'chainhashtagoutput' => $_POST['hashtagid'],
            ), array('chainhashtaginput'), 1) as $previous_i) {
                $delete_redirect = view_memory(42903, 33286) . $previous_i['hashtagterm'];
            }

            //If not found, find active followings:
            if (!$delete_redirect) {
                foreach ($this->Chains->read(array(
                    'chainhandletype IN (' . join(',', $this->config->item('handleids___42345')) . ')' => null, //Active Sequence
                    'chainhashtagoutput' => $_POST['hashtagid'],
                ), array('chainhashtaginput'), 1) as $previous_i) {
                    $delete_redirect = view_memory(42903, 33286) . $previous_i['hashtagterm'];
                }
            }

            //If still not found, go to main page if no followings found:
            if (!$delete_redirect) {
                foreach ($this->Hashtags->read(array(
                    'hashtagid' => $_POST['hashtagid'],
                )) as $i) {
                    $delete_redirect = view_memory(42903, 33286) . $i['hashtagterm'];
                }
            }

        } else {

            //Just delete from UI using JS:
            $delete_element = '.s__12273_' . $_POST['hashtagid'];

        }

        //Delete all Chains:
        $chains_removed = $this->Hashtags->delete($_POST['hashtagid'], $handle_session['handleid'], $migrateid);

        return view_json(array(
            'status' => ($chains_removed > 0 ? 1 : 0),
            'message' => 'Hashtag successfully removed',
            'delete_redirect' => $delete_redirect,
            'delete_element' => $delete_element,
        ));

    }

    function handle_delete()
    {

        $handle_session = handle_session(null, 0, $this->handle_session);
        $migrateid = 0;

        if (!$handle_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['handleid']) || !isset($_POST['focus__id']) || !isset($_POST['migratehandle'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core IDs',
            ));
        } elseif (handle_access(null, $_POST['handleid']) < 3) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Access to delete this hashtag',
            ));
        } elseif (strlen($_POST['migratehandle']) > 1) {
            $valid_handle = $this->Handles->read(array(
                'handleid !=' => $_POST['handleid'],
                'LOWER(handleterm)' => strtolower(str_replace('@', '', $_POST['migratehandle'])),
            ));
            if (!count($valid_handle)) {
                return view_json(array(
                    'status' => 0,
                    'message' => $_POST['migratehandle'] . ' is not an active handle',
                ));
            }
            $migrateid = $valid_handle[0]['handleid'];
            if (!count($this->Handles->read(array('handleid' => $migrateid)))) {
                return array(
                    'status' => 0,
                    'message' => $_POST['migratehandle'] . ' is not a valid Handle',
                );
            }
        } elseif (in_array($_POST['handleid'], $this->config->item('handleids___14870'))) {
            return array(
                'status' => 0,
                'message' => 'Cannot Delete an active @chainhandledomain - Unchain, update @memory and try again',
            );
        } elseif (!count($this->Handles->read(array('handleid' => $_POST['handleid'])))) {
            return array(
                'status' => 0,
                'message' => $_POST['handleid'] . ' is not a valid ID',
            );
        }


        //Determine what to do after deleted:
        $delete_redirect = '';
        $delete_element = '';

        if ($_POST['handleid'] == $_POST['focus__id']) {

            //Find Published Followings:
            foreach ($this->Chains->read(array(
                'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                'chainhandleoutput' => $_POST['handleid'],
            ), array('chainhandleinput'), 1, 0, array('handlename' => 'DESC')) as $up_e) {
                $delete_redirect = view_memory(42903, 42902) . $up_e['handleterm'];
            }

            //If still not found, go to main page if no followings found:
            if (!$delete_redirect) {
                foreach ($this->Handles->read(array('handleid' => $_POST['handleid'])) as $e2) {
                    $delete_redirect = view_memory(42903, 42902) . e2['handleterm'];
                }
            }
        } else {

            //Just delete from UI using JS:
            $delete_element = '.s__12274_' . $_POST['handleid'];

        }

        //Delete all Chains:
        $chains_removed = $this->Handles->delete($_POST['handleid'], $handle_session['handleid'], $migrateid);

        if(!$chains_removed['status']){
            return view_json(array(
                'status' => 1,
                'message' => 'Handle successfully removed',
                'delete_redirect' => $delete_redirect,
                'delete_element' => $delete_element,
            ));
        }

        return view_json(array(
            'status' => 1,
            'message' => 'Handle successfully removed',
            'delete_redirect' => $delete_redirect,
            'delete_element' => $delete_element,
        ));

    }

    function hashtag_update()
    {

        $handle_session = handle_session(null, 0, $this->handle_session);
        if (!$handle_session) {

            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));

        } elseif (!isset($_POST['save_hashtagtext'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Hashtag',
            ));

        } elseif (!isset($_POST['focus__node']) || !isset($_POST['focus__id'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing focus Card/ID',
            ));

        } elseif (!isset($_POST['save_hashtagterm'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing hashtag',
            ));

        } elseif (!isset($_POST['save_hashtagid'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Hashtag ID',
            ));

        } elseif (!isset($_POST['next_hashtagid'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Next/Previous ID',
            ));

        } elseif (!isset($_POST['save_chainid']) || !isset($_POST['save_chainvalue'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Chain Data',
            ));

        } elseif (strlen($_POST['save_hashtagtext']) > view_memory(6404, 4736)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Hashtag message must be less than ' . view_memory(6404, 4736) . ' characters.',
            ));
        }


        if($_POST['save_hashtagid'] > 0){

            $focus__node = ($_POST['focus__node'] == 12273 && $_POST['focus__id'] == $_POST['save_hashtagid']);
            $is = $this->Hashtags->read(array(
                'hashtagid' => $_POST['save_hashtagid'],
            ));
            if (!count($is)) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Hashtag Not Valid',
                ));
            }

            $update_array = array();

            if (strtolower($is[0]['hashtagterm']) !== strtolower(trim($_POST['save_hashtagterm']))) {

                $validate_update_handle = validate_update_handle($_POST['save_hashtagterm'], $is[0]['hashtagid'], null);
                if (!$validate_update_handle['status']) {
                    return view_json(array(
                        'status' => 0,
                        'message' => $validate_update_handle['message'],
                    ));
                }
                $update_array['hashtagterm'] = $_POST['save_hashtagterm'];
            }

            if ($is[0]['hashtagtext'] !== trim($_POST['save_hashtagtext'])) {
                if (!strlen(trim($_POST['save_hashtagtext']))) {
                    //Since we do not have media, we must have a message:
                    return view_json(array(
                        'status' => 0,
                        'message' => 'Write something to save.',
                    ));
                }
                $update_array['hashtagtext'] = $_POST['save_hashtagtext'];
            }

            //Update new hashtag fields:
            if(count($update_array)){
                $this->Hashtags->update($is[0]['hashtagid'], $update_array, $handle_session['handleid']);
            }


            if (isset($update_array['hashtagterm'])) {

                //Now Handles everywhere they are referenced:
                foreach ($this->Chains->read(array(
                    'chainhashtagoutput' => $is[0]['hashtagid'],
                    'chainhandletype IN (' . join(',', $this->config->item('handleids___4486')) . ')' => null, //Ideas
                ), array('chainhashtaginput')) as $ref) {

                    //Redo their cache:
                    $hashtag_cache = hashtag_cache($ref['hashtagid'], $ref['hashtagtext'], $handle_session['handleid'], $is[0]['hashtagterm'], $update_array['hashtagterm']);

                    $update_columns = array();

                    if($update_columns['hashtagtext']!=$hashtag_cache['hashtagtext']){
                        $update_columns['hashtagtext'] = $hashtag_cache['hashtagtext'];
                    }
                    if($update_columns['hashtagdiscover']!=$hashtag_cache['hashtagdiscover']){
                        $update_columns['hashtagdiscover'] = $hashtag_cache['hashtagdiscover'];
                    }
                    if($update_columns['hashtagedit']!=$hashtag_cache['hashtagedit']){
                        $update_columns['hashtagedit'] = $hashtag_cache['hashtagedit'];
                    }

                    if(count($update_columns)){
                        //We should update:
                        $this->db->where('hashtagid', $ref['hashtagid']);
                        $this->db->update('ideachainhashtags', $update_columns);
                    }
                }

            }

        } else {

            $focus__node = false;

            //Create new hashtag
            $hashtag_new = $this->Hashtags->create(array(
                'hashtagterm' => $_POST['save_hashtagterm'],
                'hashtagtext' => $_POST['save_hashtagtext'],
            ), $handle_session['handleid']);

            $_POST['save_hashtagid'] = $hashtag_new['hashtag_create']['hashtagid'];

        }

        foreach ($this->Hashtags->read(array(
            'hashtagid' => $_POST['save_hashtagid'],
        )) as $new_i) {

            //Update Search Index:
            update_algolia(12273, $new_i['hashtagid']);

            $discovery_mode = ( isset($_POST['save_discoverymode']) && intval($_POST['save_discoverymode']) );

            return view_json(array(
                'status' => 1,
                'return_hashtagdiscover_chains' => view_hashtag_value($new_i, $handle_session['handleid'], $focus__node, $discovery_mode, $discovery_mode),
                'return_hashtagdiscover_full' => hashtag_view($_POST['focus_group'], $new_i),
                'save_hashtagid' => $new_i['hashtagid'],
                'save_hashtagtext' => trim($_POST['save_hashtagtext']),
                'redirect_hashtag' => ( $focus__node ? : ( isset($new_i['hashtagterm']) ? view_memory(42903, 33286) . $new_i['hashtagterm'] : null) ),
                'message' => 'Success',
            ));

        }

    }



    function hashtag_cover()
    {

        if (!isset($_POST['hashtagid']) || !isset($_POST['chainhandletype']) || !isset($_POST['first_segment']) || !isset($_POST['counter'])) {
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Missing core variables</div>';
        } else {

            $discover_chainhandletype = discover_chainhandletype();

            $ui = '';
            $listed_items = 0;
            if ($_POST['chainhandletype']==13550 || $_POST['chainhandletype']==31777) {

                //HANDLES
                $handles___4593 = $this->config->item('handles___4593'); //Chain Types
                $current_handleterm = view_valid_handle_handle($_POST['first_segment']);
                foreach (hashtags_query($_POST['chainhandletype'], $_POST['hashtagid'], 1, false) as $handle_session) {
                    if (isset($handle_session['handleid'])) {
                        $ui .= view_card(view_memory(42903, 42902) . $handle_session['handleterm'], $current_handleterm && $handle_session['handleterm'] == $current_handleterm, $handle_session['chainhandletype'], view_cover($handle_session['handlecover'], true), $handle_session['handlename'], $handle_session['chainvalue']);
                        $listed_items++;
                    }
                }

            } elseif (in_array($_POST['chainhandletype'], $this->config->item('handleids___11020'))) {

                //HASHTAGS
                $handles___4593 = $this->config->item('handles___4593'); //Chain Types
                $current_hashtagterm = (substr($_POST['first_segment'], 0, 1) == '~' ? substr($_POST['first_segment'], 1) : false);

                foreach (hashtags_query($_POST['chainhandletype'], $_POST['hashtagid'], 1, false) as $next_i) {
                    if (isset($next_i['hashtagid'])) {
                        $ui .= view_card($discover_chainhandletype . view_memory(42903, 33286) . $next_i['hashtagterm'], $next_i['hashtagterm'] == $current_hashtagterm, $next_i['chainhandletype'], '', view_hashtag_title($next_i, true), $next_i['chainvalue']);
                        $listed_items++;
                    }
                }

            }

            if ($listed_items < $_POST['counter']) {
                //We have more to show:
                foreach ($this->Hashtags->read(array(
                    'hashtagid' => $_POST['hashtagid'],
                )) as $i) {
                    $ui .= view_more($discover_chainhandletype . view_memory(42903, 33286) . $i['hashtagterm'], false, '&nbsp;', '&nbsp;', 'View All');
                }
            }

            echo $ui;

        }
    }

    function hashtag_sort_load()
    {

        /*
         *
         * Saves the order of read hashtags based on
         * member preferences.
         *
         * */

        $handle_session = handle_session(null, 0, $this->handle_session);

        if (!$handle_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['new_x_order']) || !is_array($_POST['new_x_order']) || count($_POST['new_x_order']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing sorting hashtags',
            ));
        } elseif (!isset($_POST['chainhandletype']) || !in_array($_POST['chainhandletype'], $this->config->item('handleids___4603'))) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Chain Type',
            ));
        }

        //Update the order of their discoveries:
        $updated = 0;
        foreach ($_POST['new_x_order'] as $chainkey => $chainid) {
            if (intval($chainid) > 0 && intval($chainkey) > 0) {
                //Update order of this Chain:
                if ($this->Chains->update(intval($chainid), array(
                    'chainkey' => $chainkey,
                    'chainhandlecreator' => $handle_session['handleid'],
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

    function hashtag_list()
    {
        //Authenticate Member:
        if (!isset($_POST['hashtagid']) || intval($_POST['hashtagid']) < 1 || !isset($_POST['counter']) || !isset($_POST['chainhandletype']) || intval($_POST['chainhandletype']) < 1) {
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Missing core variables</div>';
        } else {

            $hashtags_query = hashtags_query($_POST['chainhandletype'], $_POST['hashtagid'], 1);
            $ui = '';
            $is = $this->Hashtags->read(array(
                'hashtagid' => $_POST['hashtagid'],
            ));
            if (!count($is) || !$hashtags_query) {
                return false;
            }

            if ($_POST['chainhandletype']==11019) {

                //HASHTAG Chain Groups Previous
                $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-' . $_POST['chainhandletype'] . '">';
                foreach ($hashtags_query as $previous_i) {
                    $ui .= hashtag_view(11019, $previous_i);
                }
                $ui .= '</div>';

            } elseif ($_POST['chainhandletype']==12840) {

                //HASHTAG Chain Groups Next
                $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-' . $_POST['chainhandletype'] . '">';
                foreach ($hashtags_query as $next_i) {
                    $ui .= hashtag_view($_POST['chainhandletype'], $next_i, $is[0]);
                }
                $ui .= '</div>';

            } elseif ($_POST['chainhandletype']==31777) {

                $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-' . $_POST['chainhandletype'] . '">';
                foreach ($hashtags_query as $item) {
                    $ui .= handle_view(31777, $item);
                }
                $ui .= '</div>';

            } elseif ($_POST['chainhandletype']==13550) {

                //Handles
                $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-' . $_POST['chainhandletype'] . '">';
                foreach ($hashtags_query as $handle_ref) {
                    $ui .= handle_view($_POST['chainhandletype'], $handle_ref, null);
                }
                $ui .= '</div>';

            }

            echo $ui;

        }
    }


    function handle_list()
    {

        //Authenticate Member:
        if (!isset($_POST['handleid']) || intval($_POST['handleid']) < 1 || !isset($_POST['chainhandletype']) || intval($_POST['chainhandletype']) < 1) {
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Missing core variables</div>';
            return false;
        }

        $limit = view_memory(6404, 11064);
        $handle_session = handle_session();
        $handles_query = handles_query($_POST['chainhandletype'], $_POST['handleid'], 1);
        $es = $this->Handles->read(array(
            'handleid' => $_POST['handleid'],
        ));
        if (!count($es)) {
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-lock"></i></span>Invalid Handle ID</div>';
            return false;
        }
        if (!$handles_query) {
            return false;
        }

        $focus_handleid = ($_POST['handleid'] > 0 ? $_POST['handleid'] : ($handle_session ? $handle_session['handleid'] : 0));
        $ui = '';

        if ($_POST['chainhandletype']==13550 || $_POST['chainhandletype']==12273) {

            //Hashtag/Handle Link Groups
            //Hashtags:
            $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-' . $_POST['chainhandletype'] . '">';
            foreach ($handles_query as $i) {
                $ui .= hashtag_view($_POST['chainhandletype'], $i, null, null, $focus_handleid);
            }
            $ui .= '</div>';

        } elseif ($_POST['chainhandletype']==32292 || in_array($_POST['chainhandletype'], $this->config->item('handleids___11028'))) {

            //Handles:
            $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-' . $_POST['chainhandletype'] . '">';
            foreach ($handles_query as $e) {
                $ui .= handle_view($_POST['chainhandletype'], $e, null);
            }
            $ui .= '</div>';

        } elseif (in_array($_POST['chainhandletype'], $this->config->item('handleids___12144'))) {

            //Discoveries:
            $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-' . $_POST['chainhandletype'] . '">';
            foreach ($handles_query as $i) {
                $ui .= hashtag_view($_POST['chainhandletype'], $i, null, null, $focus_handleid);
            }
            $ui .= '</div>';

        }

        echo $ui;

    }

    function handle_cover()
    {

        if (!isset($_POST['handleid']) || !isset($_POST['chainhandletype']) || !isset($_POST['first_segment']) || !isset($_POST['counter'])) {

            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Missing core variables</div>';

        } else {

            $ui = '';
            $listed_items = 0;
            $is_cache = in_array($_POST['chainhandletype'], $this->config->item('handleids___14599'));

            if (in_array($_POST['chainhandletype'], $this->config->item('handleids___11028'))) {

                //HANDLES
                $current_handleterm = view_valid_handle_handle($_POST['first_segment']);
                $handles___4593 = $this->config->item('handles___4593'); //Chain Types

                foreach (handles_query($_POST['chainhandletype'], $_POST['handleid'], 1, false) as $handle_session) {
                    if (isset($handle_session['handleid'])) {
                        $ui .= view_card(view_memory(42903, 42902) . $handle_session['handleterm'], $handle_session['handleterm'] == $current_handleterm, $handle_session['chainhandletype'], view_cover($handle_session['handlecover'], true), $handle_session['handlename'], (!$is_cache ? $handle_session['chainvalue'] : null));
                        $listed_items++;
                    }
                }

            } elseif ($_POST['chainhandletype']==13550 || $_POST['chainhandletype']==31777 || $_POST['chainhandletype']==12273) {

                //HASHTAGS
                $current_hashtagterm = (substr($_POST['first_segment'], 0, 1) == '~' ? substr($_POST['first_segment'], 1) : false);
                $handles___4593 = $this->config->item('handles___4593'); //Chain Types
                $discover_chainhandletype = discover_chainhandletype();

                foreach (handles_query($_POST['chainhandletype'], $_POST['handleid'], 1, false) as $next_i) {
                    if (isset($next_i['hashtagid'])) {
                        $ui .= view_card($discover_chainhandletype . view_memory(42903, 33286) . $next_i['hashtagterm'], $next_i['hashtagterm'] == $current_hashtagterm, $next_i['chainhandletype'], '', view_hashtag_title($next_i, true), (!$is_cache ? $next_i['chainvalue'] : null));
                        $listed_items++;
                    }
                }

            }

            if ($listed_items < $_POST['counter']) {
                //We have more to show:
                foreach ($this->Handles->read(array(
                    'handleid' => $_POST['handleid'],
                )) as $handle_this) {
                    $ui .= view_more(view_memory(42903, 42902) . $handle_this['handleterm'], false, '&nbsp;', '&nbsp;', 'View All');
                }
            }

            echo $ui;

        }
    }

    function handle_sort_save()
    {

        //Authenticate Member:
        $handle_session = handle_session(10939, 0, $this->handle_session);
        if (!$handle_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(10939),
            ));
        } elseif (!isset($_POST['handleid']) || intval($_POST['handleid']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid handleid',
            ));
        } elseif (!isset($_POST['new_chainkey']) || !is_array($_POST['new_chainkey']) || count($_POST['new_chainkey']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Nothing passed for sorting',
            ));
        } else {

            //Validate Handle:
            $es = $this->Handles->read(array(
                'handleid' => $_POST['handleid'],
            ));

            //Count followers:
            $listhandle_count = $this->Chains->read(array(
                'chainhandleinput' => $_POST['handleid'],
                'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
            ), array('chainhandleoutput'), 0, 0, array(), 'COUNT(handleid) as totals');

            if (count($es) < 1) {

                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid handleid',
                ));

            } elseif ($listhandle_count[0]['totals'] > view_memory(6404, 11064)) {

                return view_json(array(
                    'status' => 0,
                    'message' => 'Cannot sort Handles if greater than ' . view_memory(6404, 11064),
                ));

            } else {

                //Update them all:
                $updated = 0;
                foreach ($_POST['new_chainkey'] as $rank => $chainid) {
                    if ($chainid > 0) {
                        $updated += $this->Chains->update($chainid, array(
                            'chainkey' => intval($rank),
                        ));
                    }
                }

                //Display message:
                return view_json(array(
                    'status' => 1,
                    'message' => $updated . ' Chains updated',
                ));

            }
        }
    }


    function hashtag_copy()
    {

        //Auth member and check required variables:
        $handle_session = handle_session(10939, 0, $this->handle_session);

        if (!$handle_session) {
            return view__json(array(
                'status' => 0,
                'messagCloe' => view__unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['hashtagid']) || intval($_POST['hashtagid']) < 1) {
            return view__json(array(
                'status' => 0,
                'message' => 'Invalid Following Handle',
            ));
        } elseif (!isset($_POST['do_recursive'])) {
            return view__json(array(
                'status' => 0,
                'message' => 'Missing template parameter',
            ));
        }

        return view_json($this->Hashtags->copy(intval($_POST['hashtagid']), intval($_POST['do_recursive']), $handle_session['handleid']));

    }


    function handle_copy()
    {

        //Auth member and check required variables:
        $handle_session = handle_session(10939, 0, $this->handle_session);

        if (!$handle_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(10939),
            ));
        } elseif (intval($_POST['handleid']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Handle',
            ));
        } elseif (!strlen($_POST['copy_handle_title'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Handle Title',
            ));
        }

        $copy_children = true;
        if(substr($_POST['copy_handle_title'], 0, 1)=='-'){
            $copy_children = false;
            $_POST['copy_handle_title'] = substr($_POST['copy_handle_title'], 1);
        }

        //Validate Handle:
        $fetch_o = $this->Handles->read(array(
            'handleid' => $_POST['handleid'],
        ));
        if (count($fetch_o) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid followings Handle ID',
            ));
        }


        //Create:
        $added_e = $this->Handles->create(array(
            'handlename' => $_POST['copy_handle_title'],
            'handlecover' => $fetch_o[0]['handlecover'],
        ), $handle_session['handleid']);
        if (!$added_e['status']) {
            //We had an error, return it:
            return view_json($added_e);
        } else {
            //Assign new Handle:
            $focus_e = $added_e['handle_create'];
        }


        //Followings:
        foreach ($this->Chains->read(array(
            'chainhandleoutput' => $_POST['handleid'],
            'chainhandletype IN (' . join(',', $this->config->item('handleids___41303')) . ')' => null, //Clone Handle Chains
        ), array(), 0) as $x) {
            if (!count($this->Chains->read(array(
                'chainhandletype' => $x['chainhandletype'],
                'chainhandleinput' => $x['chainhandleinput'],
                'chainhandleoutput' => $focus_e['handleid'],
                'chainvalue' => $x['chainvalue'],
            )))) {
                $this->Chains->create(array(
                    'chainhandlecreator' => $handle_session['handleid'],
                    'chainkey' => $x['chainkey'],
                    'chainhandletype' => $x['chainhandletype'],
                    'chainhandleinput' => $x['chainhandleinput'],
                    'chainhandleoutput' => $focus_e['handleid'],
                    'chainvalue' => $x['chainvalue'],
                ));
            }
        }

        if($copy_children){

            //Followers:
            foreach ($this->Chains->read(array(
                'chainhandleinput' => $_POST['handleid'],
                'chainhandletype IN (' . join(',', $this->config->item('handleids___41303')) . ')' => null, //Clone Handle Chains
            ), array(), 0) as $x) {

                //Make sure none existent in new Handle:
                if (!count($this->Chains->read(array(
                    'chainhandletype' => $x['chainhandletype'],
                    'chainhandleinput' => $focus_e['handleid'],
                    'chainhandleoutput' => $x['chainhandleoutput'],
                    'chainvalue' => $x['chainvalue'],
                )))) {
                    $this->Chains->create(array(
                        'chainhandlecreator' => $handle_session['handleid'],
                        'chainkey' => $x['chainkey'],
                        'chainhandletype' => $x['chainhandletype'],
                        'chainhandleinput' => $focus_e['handleid'],
                        'chainhandleoutput' => $x['chainhandleoutput'],
                        'chainvalue' => $x['chainvalue'],
                    ));
                }
            }
        }


        return view_json(array(
            'status' => 1,
            'handle_createhandle' => $focus_e['handleterm'],
        ));


    }

    function hashtag_create()
    {

        /*
         *
         * Either creates a HASHTAG Chain between focus_id & chain_hashtagid
         * OR will create a new hashtag with outcome hashtagtext and then Chain it
         * to focus_id (In this case chain_hashtagid=0)
         *
         * */

        //Authenticate Member:
        $member_e = handle_session(10939, 0, $this->handle_session);
        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(10939),
            ));
        } elseif (!isset($_POST['chainhandletype']) || !isset($_POST['focus_id']) || !isset($_POST['focus_card'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core Variables',
            ));
        } elseif (!isset($_POST['hashtag_createtext']) || !isset($_POST['chain_hashtagid'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing either Hashtag Outcome OR Follower Hashtag ID',
            ));
        }

        $validate_hashtagtext = validate_hashtagtext($_POST['hashtag_createtext']);
        if (!$validate_hashtagtext['status']) {
            //We had an error, return it:
            return view_json($validate_hashtagtext);
        }


        if (!$_POST['chain_hashtagid'] && view_valid_handle_hashtag($_POST['hashtag_createtext'])) {
            foreach ($this->Hashtags->read(array(
                'LOWER(hashtagterm)' => strtolower(view_valid_handle_hashtag($_POST['hashtag_createtext'])),
            )) as $i) {
                $_POST['chain_hashtagid'] = $i['hashtagid'];
            }
        }

        $x_i = array();

        if ($_POST['chain_hashtagid'] > 0) {
            //Fetch Chain hashtag to determine hashtag type:
            $x_i = $this->Hashtags->read(array(
                'hashtagid' => intval($_POST['chain_hashtagid']),
            ));
            if (count($x_i) == 0) {
                //validate Hashtag:
                return view_json(array(
                    'status' => 0,
                    'message' => 'Hashtag #' . $_POST['chain_hashtagid'] . ' is not active.',
                ));
            }
        }

        //All seems good, go ahead and try to create/chain the Hashtag:
        return view_json($this->Hashtags->create_or_chain($_POST['focus_card'], $_POST['chainhandletype'], trim($_POST['hashtag_createtext']), $member_e['handleid'], $_POST['focus_id'], $_POST['chain_hashtagid']));

    }


    function handle_create()
    {

        //Auth member and check required variables:
        $handle_session = handle_session(10939, 0, $this->handle_session);

        if (!$handle_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(10939),
            ));
        } elseif (intval($_POST['focus__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Following Handle',
            ));
        } elseif (!isset($_POST['chainhandletype'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Handle Creation Type',
            ));
        } elseif (!isset($_POST['handle_current_id']) || !isset($_POST['handle_new_string']) || (intval($_POST['handle_current_id']) < 1 && strlen($_POST['handle_new_string']) < 1)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Either New Handle ID or Handle Name',
            ));
        }

        $adding_to_i = ($_POST['focus__node'] == 12273);


        if ($adding_to_i) {

            //Validate Hashtag:
            $fetch_o = $this->Hashtags->read(array(
                'hashtagid' => $_POST['focus__id'],
            ));
            if (count($fetch_o) < 1) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid followings Handle ID',
                ));
            }

        } else {

            //Validate Handle:
            $fetch_o = $this->Handles->read(array(
                'handleid' => $_POST['focus__id'],
            ));
            if (count($fetch_o) < 1) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid followings Handle ID',
                ));
            }

        }


        //Set some variables:
        $_POST['handle_new_string'] = trim($_POST['handle_new_string']);
        $_POST['chainhandletype'] = intval($_POST['chainhandletype']);
        $is_upwards = in_array($_POST['chainhandletype'], $this->config->item('handleids___14686'));

        if (!intval($_POST['handle_current_id']) && view_valid_handle_handle($_POST['handle_new_string'])) {
            foreach ($this->Handles->read(array(
                'LOWER(handleterm)' => strtolower(substr($_POST['handle_new_string'], 1)),
            )) as $e) {
                $_POST['handle_current_id'] = $e['handleid'];
            }
        }
        $adding_to_existing = (intval($_POST['handle_current_id']) > 0);

        //Are we adding an existing Handle?
        if ($adding_to_existing) {

            //Validate this existing Handle:
            $es = $this->Handles->read(array(
                'handleid' => $_POST['handle_current_id'],
            ));

            if (count($es) < 1) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Handle @' . $_POST['handle_current_id'] . ' is not active',
                ));
            }

            //All good, assign:
            $focus_e = $es[0];

        } else {

            //We are creating a new Handle:
            $added_e = $this->Handles->create(array(
                'handlename' => $_POST['handle_new_string'],
            ), $handle_session['handleid']);
            if (!$added_e['status']) {
                //We had an error, return it:
                return view_json($added_e);
            } else {
                //Assign new Handle:
                $focus_e = $added_e['handle_create'];
            }

        }

        //We need to check to ensure this is not a duplicate Chain if adding an existing Handle:
        $ur2 = array();

        if ($adding_to_i) {

            //Add Author

        } else {

            //Add Up/Down Handle:

            //Add Chains only if not previously added by the URL function:
            if ($is_upwards) {

                //Following
                $chainhandleoutput = $fetch_o[0]['handleid'];
                $chainhandleinput = $focus_e['handleid'];
                $chainkey = 0; //Never sort following, only sort followers

            } else {

                //Followers
                $chainhandleinput = $fetch_o[0]['handleid'];
                $chainhandleoutput = $focus_e['handleid'];
                $chainkey = 0;

            }


            $chainvalue = null;

            //Create Chain:
            $ur2 = $this->Chains->create(array(
                'chainhandlecreator' => $handle_session['handleid'],
                'chainhandletype' => 4230,
                'chainvalue' => $chainvalue,
                'chainhandleoutput' => $chainhandleoutput,
                'chainhandleinput' => $chainhandleinput,
                'chainkey' => $chainkey,
            ));
        }

        //Return Handle:
        return view_json(array(
            'status' => 1,
            'handle_new_echo' => handle_view($_POST['chainhandletype'], array_merge($focus_e, $ur2), null),
        ));

    }

    function handle_editor()
    {

        $handle_session = handle_session(null, 0, $this->handle_session);
        $handles___11035 = $this->config->item('handles___11035');
        $handles___42776 = $this->config->item('handles___42776');
        $handles___4592 = $this->config->item('handles___4592'); //Data types
        if (!$handle_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['handleid']) || !isset($_POST['chainid'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core IDs',
            ));
        }

        $es = $this->Handles->read(array(
            'handleid' => $_POST['handleid'],
        ));
        if (!count($es)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Handle is no longer active',
            ));
        } elseif (!handle_access($es[0]['handleterm'], 0, $es[0])) {
            return view_json(array(
                'status' => 0,
                'message' => 'You are missing permission to edit this Handle',
            ));
        }


        //Fetch dynamic data based on hashtag type:
        $order_42145 = sort_by(42145);
        $scanned_handles = array();
        $return_inputs = array();
        $input_pointer = 0;
        $profile_header = '';

        //Fetch Handle Templates, if any:
        foreach ($this->Chains->read(array(
            'chainhandleinput IN (' . join(',', $this->config->item('handleids___42178')) . ')' => null, //Dynamic Handles
            'chainhandleoutput' => $es[0]['handleid'],
            'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
        ), array('chainhandleinput'), 0, 0, sort_by(42178)) as $handle_group) {

            if (in_array($handle_group['handleid'], $scanned_handles)) {
                continue;
            }
            array_push($scanned_handles, $handle_group['handleid']);

            foreach ($this->Chains->read(array(
                'chainhandleoutput' => $handle_group['handleid'],
                'chainhandleinput IN (' . join(',', $this->config->item('handleids___42145')) . ')' => null, //Dynamic Input Templates
                'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
            ), array('chainhandleinput'), 0, 0, $order_42145) as $handle_template) {

                $profile_header = '<div class="profile_header main__title"><span class="icon-block-sm">' . view_cover($handle_template['handlecover']) . '</span>' . $handle_template['handlename'] . '<a href="' . view_memory(42903, 42902) . $handle_group['handleterm'] . '" target="_blank" data-toggle="tooltip" data-placement="top" title="Because you follow ' . $handle_group['handlename'] . '... Click to Open in a New Window"><span class="icon-block-sm">' . view_cover($handle_group['handlecover']) . '</span></a></div>';


                //Load template:
                if (!is_array($this->config->item('handles___' . $handle_template['handleid']))) {
                    //Report Error:
                    log_error('handle_sessionditor_load() ERROR: @' . $handle_template['handleid'] . ' is NOT in memory cache', array(
                        'chainhandleoutput' => $handle_template['handleid'],
                    ));
                    continue;
                } elseif (in_array($handle_template['handleid'], $scanned_handles)) {
                    continue;
                }
                array_push($scanned_handles, $handle_template['handleid']);


                foreach ($this->config->item('handles___' . $handle_template['handleid']) as $dynamic_handleid => $m) {

                    //Make sure it's a dynamic input field:
                    if (!in_array($dynamic_handleid, $this->config->item('handleids___42179'))) {
                        continue;
                    } elseif (in_array($dynamic_handleid, $scanned_handles)) {
                        continue;
                    }
                    array_push($scanned_handles, $dynamic_handleid);

                    //Let's first determine the data type:
                    $data_types = array_intersect($m['m__following'], $this->config->item('handleids___4592'));

                    if (count($data_types) != 1) {

                        //This is strange, we are expecting 1 match only report this:
                        log_error('Found ' . count($data_types) . ' Data Types (@' . $es[0]['handleid'] . ') (Expecting exactly 1) for @' . $dynamic_handleid . ': Check @4592 to see what is wrong', array(
                            'chainhandleoutput' => $dynamic_handleid,
                            'chainhandlecreator' => $handle_session['handleid'],
                        ));
                        continue; //Go to the next dynamic data type

                    } elseif ($input_pointer >= view_memory(6404, 42206)) {
                        //Monitor if we ever reach the maximum:
                        log_error('Dynamic Fields Reach their maximum limit of ' . view_memory(6404, 42206) . '  which may require field expansion', array(
                            'chainhandleoutput' => $dynamic_handleid,
                            'chainhandlecreator' => $handle_session['handleid'],
                            'chainhashtagoutput' => $_POST['handleid'],
                        ));
                    }

                    //We found 1 match as expected:
                    $input_pointer++;
                    foreach ($data_types as $data_type_this) {
                        $data_type = $data_type_this;
                        break;
                    }

                    if (in_array($data_type, $this->config->item('handleids___42188'))) {

                        //Single or Multiple Choice:
                        array_push($return_inputs, array(
                            'd__id' => $dynamic_handleid,
                            'd__is_radio' => 1,
                            'd_chainid' => 0,
                            'd__html' => view_instant_select($dynamic_handleid, $es[0]['handleid'], 0),
                            'd__value' => ($es[0]['handleid'] > 0 ? $es[0]['handleid'] : ''),
                            'd__type_name' => '',
                            'd__placeholder' => '',
                            'd__profile_header' => $profile_header,
                        ));

                    } else {

                        $this_data_type = $this->config->item('handles___' . $data_type);
                        $handles___42179 = $this->config->item('handles___42179'); //Dynamic Input Field
                        $handles___11035 = $this->config->item('handles___11035'); //Encyclopedia

                        //Fetch the current value(s):
                        $counted = 0;
                        $unique_values = array();
                        foreach ($this->Chains->read(array(
                            'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                            'chainhandleoutput' => $es[0]['handleid'],
                            'chainhandleinput' => $dynamic_handleid,
                        ), array('chainhandleinput')) as $selected_e) {
                            if (strlen($selected_e['chainvalue']) && !in_array($selected_e['chainvalue'], $unique_values)) {
                                array_push($unique_values, $selected_e['chainvalue']);
                                $counted++;
                                array_push($return_inputs, array(
                                    'd__id' => $dynamic_handleid,
                                    'd__is_radio' => 0,
                                    'd_chainid' => $selected_e['chainid'],
                                    'd__html' => view_dynamic_headline($dynamic_handleid, $m, $selected_e),
                                    'd__value' => $selected_e['chainvalue'],
                                    'd__type_name' => html_input_type($data_type),
                                    'd__placeholder' => (strlen($this_data_type[$dynamic_handleid]['m__message']) ? $this_data_type[$dynamic_handleid]['m__message'] : $handles___4592[$data_type]['m__title'] . '...'),
                                    'd__profile_header' => $profile_header,
                                ));
                            }
                        }

                        if (!$counted) {
                            foreach ($this->Handles->read(array(
                                'handleid' => $dynamic_handleid,
                            )) as $selected_e) {
                                array_push($return_inputs, array(
                                    'd__id' => $dynamic_handleid,
                                    'd__is_radio' => 0,
                                    'd_chainid' => 0,
                                    'd__html' => view_dynamic_headline($dynamic_handleid, $m, $selected_e),
                                    'd__value' => '',
                                    'd__type_name' => html_input_type($data_type),
                                    'd__placeholder' => (strlen($this_data_type[$dynamic_handleid]['m__message']) ? $this_data_type[$dynamic_handleid]['m__message'] : $handles___4592[$data_type]['m__title'] . '...'),
                                    'd__profile_header' => $profile_header,
                                ));
                            }
                        }
                    }
                }
            }
        }


        //Add universal inputs only if missing bio profiles:
        if (!array_intersect($scanned_handles, $this->config->item('handleids___42885'))) {
            foreach ($this->Handles->read(array(
                'handleid IN (' . join(',', $this->config->item('handleids___42776')) . ')' => null, //Universal Dynamic Inputs
            )) as $selected_e) {
                foreach (array_intersect($handles___42776[$selected_e['handleid']]['m__following'], $this->config->item('handleids___4592')) as $data_type) {
                    //Any value?
                    $values = $this->Chains->read(array(
                        'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                        'chainhandleoutput' => $es[0]['handleid'],
                        'chainhandleinput' => $selected_e['handleid'],
                    ));
                    array_push($return_inputs, array(
                        'd__id' => $selected_e['handleid'],
                        'd__is_radio' => 0,
                        'd_chainid' => 0,
                        'd__html' => view_dynamic_headline($selected_e['handleid'], $handles___42776[$selected_e['handleid']], $selected_e),
                        'd__value' => (isset($values[0]['chainvalue']) && strlen($values[0]['chainvalue']) > 0 ? $values[0]['chainvalue'] : ''),
                        'd__type_name' => html_input_type($data_type),
                        'd__placeholder' => (strlen($handles___42776[$selected_e['handleid']]['m__message']) ? $handles___42776[$selected_e['handleid']]['m__message'] : $handles___4592[$data_type]['m__title'] . '...'),
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

    function handle_save_edit()
    {

        $handle_session = handle_session(null, 0, $this->handle_session);
        if (!$handle_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['save_handleid'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Coin ID',
            ));
        } elseif (!isset($_POST['save_handlename'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Handle Title',
            ));
        } elseif (!isset($_POST['save_handleterm'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Handle Handle',
            ));
        } elseif (!isset($_POST['save_handlecover'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Handle Cover',
            ));
        } elseif (!isset($_POST['save_chainid']) || !isset($_POST['save_chainvalue'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Chain Data',
            ));
        }


        $es = $this->Handles->read(array(
            'handleid' => $_POST['save_handleid'],
        ));
        if (!count($es)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Handle Not Active',
            ));
        }


        //Validate Dynamic Inputs:
        $handles___42179 = $this->config->item('handles___42179'); //Dynamic Input Fields

        //Process dynamic inputs if any:
        for ($p = 1; $p <= view_memory(6404, 42206); $p++) {

            if (!isset($_POST['save_dynamic_' . $p])) {
                break; //Nothing more to process
            }

            $input_parts = explode('____', $_POST['save_dynamic_' . $p], 3);
            if (!isset($input_parts[0]) || !isset($input_parts[1])) {
                continue;
            }
            $d_chainid = $input_parts[0];
            $dynamic_handleid = $input_parts[1];
            $dynamic_value = trim($input_parts[2]);


            //Required fields must have an input:
            if (in_array($dynamic_handleid, $this->config->item('handleids___28239')) && !strlen($dynamic_value) && !in_array($dynamic_handleid, $this->config->item('handleids___33331')) && !in_array($dynamic_handleid, $this->config->item('handleids___33332'))) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Missing Required Field: ' . $handles___42179[$dynamic_handleid]['m__title'],
                ));
            }

            //Validate input based on its data type, if provided:
            if (strlen($dynamic_value)) {
                foreach (array_intersect($handles___42179[$dynamic_handleid]['m__following'], $this->config->item('handleids___4592')) as $data_type_this) {
                    $data_type_validate = data_type_validate($data_type_this, $dynamic_value, $handles___42179[$dynamic_handleid]['m__title']);
                    if (!$data_type_validate['status']) {
                        //We had an error:
                        return view_json($data_type_validate);
                    }
                }
            }


            //Fetch the current value:
            if ($d_chainid > 0) {
                $values = $this->Chains->read(array(
                    'chainid' => $d_chainid,
                ));
            }

            if (!$d_chainid || !count($values)) {
                $values = $this->Chains->read(array(
                    'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                    'chainhandleinput' => $dynamic_handleid,
                    'chainhandleoutput' => $es[0]['handleid'],
                ));
            }


            //Update if needed:
            if (!strlen($dynamic_value)) {

                //Remove Chain if we have one:
                //HACK: Summary are key chains that should not be removed
                /*
                if (count($values) && $dynamic_handleid != 11035) {
                    $this->Chains->delete($values[0]['chainid'], $handle_session['handleid']);
                }
                */

            } elseif (!count($values)) {

                //Create Chain:
                $this->Chains->create(array(
                    'chainhandlecreator' => $handle_session['handleid'],
                    'chainhandletype' => 4230,
                    'chainhandleinput' => $dynamic_handleid,
                    'chainhandleoutput' => $es[0]['handleid'],
                    'chainvalue' => $dynamic_value,
                    'chainkey' => number_chainkey($dynamic_value),
                ));

            } elseif ($values[0]['chainvalue'] != $dynamic_value) {

                //Update Chain:
                $this->Chains->update($values[0]['chainid'], array(
                    'chainvalue' => $dynamic_value,
                    'chainhandlecreator' => $handle_session['handleid'],
                ));

            }
        }


        //Validate Handle Handle & save if needed:
        if ($es[0]['handleterm'] !== trim($_POST['save_handleterm'])) {
            $validate_update_handle = validate_update_handle(trim($_POST['save_handleterm']), null, $es[0]['handleid']);
            if (!$validate_update_handle['status']) {
                return view_json(array(
                    'status' => 0,
                    'message' => $validate_update_handle['message'],
                ));
            }
        }

        //Validate Handle Title & save if needed:
        $validate_handlename = validate_handlename($_POST['save_handlename']);
        if ($es[0]['handlename'] != trim($_POST['save_handlename'])) {
            if (!$validate_handlename['status']) {
                return view_json(array(
                    'status' => 0,
                    'message' => $validate_handlename['message'],
                ));
            }
            $es[0]['handlename'] = $validate_handlename['handlename_clean'];
        }

        //Save Handle Cover if needed:
        if ($es[0]['handlecover'] != trim($_POST['save_handlecover'])) {
            //TODO validate handlecover?
            $es[0]['handlecover'] = trim($_POST['save_handlecover']);
        }

        //Update:
        $this->Handles->update($es[0]['handleid'], array(
            'handlename' => $validate_handlename['handlename_clean'],
            'handlecover' => trim($_POST['save_handlecover']),
            'handleterm' => trim($_POST['save_handleterm']),
        ), $handle_session['handleid']);


        //Sync handle reference:
        $new_handle_string = trim($_POST['save_handleterm']);
        if ($es[0]['handleterm'] != $new_handle_string) {
            //Update Handles everywhere they are referenced:
            foreach ($this->Chains->read(array(
                'chainhandleinput' => $es[0]['handleid'],
                'chainhandletype' => 31835, //Handle Mention
            ), array('chainhashtagoutput')) as $ref) {
                $this->Hashtags->update($ref['hashtagid'], array(
                    'hashtagtext' => str_replace('@' . $es[0]['handleterm'], '@' . $new_handle_string, $ref['hashtagtext']),
                ), $handle_session['handleid']);
            }
            $es[0]['handleterm'] = $new_handle_string;
        }


        //Do we have a chain reference message that need to be saved?
        if ($_POST['save_chainid'] > 0 && $_POST['save_chainvalue'] != 'IGNORE_INPUT') {

            //Fetch Chain:
            foreach ($this->Chains->read(array(
                'chainid' => $_POST['save_chainid'],
            )) as $this_x) {

                $es[0] = array_merge($es[0], $this_x);

                if ($this_x['chainvalue'] != trim($_POST['save_chainvalue'])) {
                    $this->Chains->update($this_x['chainid'], array(
                        'chainvalue' => trim($_POST['save_chainvalue']),
                        'chainhandlecreator' => $handle_session['handleid'],
                    ));
                }
            }
        }


        //Reset member session data if this data belongs to the logged-in member:
        if ($_POST['save_handleid'] == $handle_session['handleid']) {
            $this->Handles->activate($es[0], true);
        }


        return view_json(array(
            'status' => 1,
            'message' => 'Updated ',
        ));


    }

    function handle_select_apply()
    {
        /*
         *
         * Saves the radio selection of some account fields
         *
         * */

        $handle_session = handle_session(null, 0, $this->handle_session);
        if (!$handle_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['focus__id']) || intval($_POST['focus__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing followings Handle',
            ));
        } elseif (!isset($_POST['selected_handleid']) || intval($_POST['selected_handleid']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing selected Handle',
            ));
        } elseif (!isset($_POST['down_handleid']) || !isset($_POST['right_hashtagid'])) {
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


        if ($_POST['down_handleid'] > 0) {

            //Dispatch Any Emails Necessary:
            if (isset($_POST['selected_handleid']) && intval($_POST['selected_handleid']) > 0) {
                foreach ($this->Chains->read(array(
                    'chainhandletype' => 31835, //Mention
                    'chainhandleinput' => $_POST['selected_handleid'],
                ), array('chainhashtagoutput'), 0) as $i) {
                    if (count($this->Chains->read(array(
                        'chainhandletype' => 31835, //Mention
                        'chainhandleinput' => 31065, //Choice Update Email Templates
                        'chainhashtagoutput' => $i['hashtagid'], //Is this the template?
                    )))) {
                        //Found the email template to send:
                        $total_sent = $this->Chains->broadcast(array($handle_session), $i, website_setting(0), false);
                        break; //Just the first template match
                    }
                }
            }
        }

        $is_required = in_array($_POST['focus__id'], $this->config->item('handleids___28239')); //Required Settings

        if (!$_POST['enable_mulitiselect'] || $_POST['was_previously_selected']) {

            //Since this is not a multi-select we want to delete all existing options

            //Fetch all possible answers based on followings Handle:
            $query_filters = array(
                'chainhandleinput' => $_POST['focus__id'],
                'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
            );

            if ((!$is_required || $_POST['enable_mulitiselect']) && $_POST['was_previously_selected']) {
                //Just delete this single item, not the other ones:
                $query_filters['chainhandleoutput'] = $_POST['selected_handleid'];
            }

            //List all possible answers:
            $possible_answers = array();
            foreach ($this->Chains->read($query_filters, array('chainhandleoutput'), 0, 0) as $answer_e) {
                $stats['total']++;
                array_push($possible_answers, $answer_e['handleid']);
            }

            //Delete previously selected options:
            if ($_POST['down_handleid']) {
                $delete_query = $this->Chains->read(array(
                    'chainhandleinput IN (' . join(',', $possible_answers) . ')' => null,
                    'chainhandleoutput' => $_POST['down_handleid'],
                    'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                ));
            } elseif ($_POST['right_hashtagid']) {
                $delete_query = $this->Chains->read(array(
                    'chainhandleinput IN (' . join(',', $possible_answers) . ')' => null,
                    'chainhashtagoutput' => $_POST['right_hashtagid'],
                    'chainhandletype IN (' . join(',', $this->config->item('handleids___33602')) . ')' => null, //Hashtag/Handle Chains Active
                ));
            }

            foreach ($delete_query as $delete) {
                $stats['deleted']++;
                //Should usually delete a single option:
                $this->Chains->delete($delete['chainid'], $handle_session['handleid']);
            }

        }

        //Add new option if not previously there:
        if ((!$_POST['enable_mulitiselect'] && $is_required) || !$_POST['was_previously_selected']) {
            if ($_POST['down_handleid']) {
                $stats['added']++;
                $this->Chains->create(array(
                    'chainhandlecreator' => $handle_session['handleid'],
                    'chainhandleinput' => $_POST['selected_handleid'],
                    'chainhandletype' => 4230,
                    'chainhandleoutput' => $_POST['down_handleid'],
                ));
            } elseif ($_POST['right_hashtagid']) {



            }
        }


        //Update Session:
        if ($_POST['down_handleid'] && $handle_session) {
            $this->Handles->activate($handle_session, true);
        }


        //All good:
        return view_json(array(
            'status' => 1,
            'message' => 'Updated: ' . print_r($stats, true),
        ));
    }

    function handle_authenticate()
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
        } elseif (!isset($_POST['sign_hashtagid'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing hashtag referrer',
            ));
        }

        $_POST['account_email_phone'] = trim(strtolower($_POST['account_email_phone']));

        //Validate member ID
        if ($_POST['account_id'] > 0) {

            $es = $this->Handles->read(array(
                'handleid' => $_POST['account_id'],
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
        foreach ($this->Chains->read(array(
            'chainhandletype' => 44176, //Handle View
            'chainhandleinput' => 32078, //Sign In Key
            'LOWER(chainvalue) LIKE \'' . strtolower($_POST['account_email_phone']) . '%\'' => null,
        ), array(), 1, 0, array('chaintime' => 'DESC')) as $sent_key) {
            if (strtotime($sent_key['chaintime']) <= (time() - 86400)) {
                //Expired
                $this->Chains->delete($sent_key['chainid'], $_POST['account_id']); //Code Verified
                break;
            }
            $session_key = $this->session->userdata('session_key');
            $key_parts = explode('/', $sent_key['chainvalue'], 2);
            if (strlen($session_key) && $key_parts[1] == md5($session_key . $_POST['input_code'])) {
                //Void access code:
                $is_authenticated = $this->Chains->delete($sent_key['chainid'], $_POST['account_id']); //Code Verified
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

            //Assign session & log Chain:
            $this->Handles->activate($es[0]);

        } else {

            //Add new account
            $_POST['account_email_phone'] = trim(strtolower($_POST['account_email_phone']));
            $is_email = filter_var($_POST['account_email_phone'], FILTER_VALIDATE_EMAIL);

            //Prep inputs & validate further:
            $acc_email = ($is_email ? $_POST['account_email_phone'] : $_POST['new_account_email']);
            $handle_result = $this->Handles->join(strstr($acc_email, '@', true), $acc_email, (!$is_email ? $_POST['account_email_phone'] : ''));
            if (!$handle_result['status']) {
                return view_json($handle_result);
            }

            $es[0] = $handle_result['e'];

        }


        //Set default sign in URL:
        $sign_url = view_memory(42903, 42902) . $es[0]['handleterm'];

        //See if we can find a better one:
        if (intval($_POST['sign_hashtagid']) > 0) {
            foreach ($this->Hashtags->read(array(
                'hashtagid' => $_POST['sign_hashtagid'],
            )) as $i) {
                $sign_url = $i['hashtagterm'] . '/' . view_memory(6404, 4235);
            }
        } elseif (isset($_POST['referrer_url']) && strlen(urldecode($_POST['referrer_url'])) > 1) {
            $sign_url = urldecode($_POST['referrer_url']);
        }

        return view_json(array(
            'status' => 1,
            'sign_url' => $sign_url,
        ));

    }

    function handle_toggle_follow()
    {

        $handle_session = handle_session(10939, 0, $this->handle_session);
        if (!$handle_session) {

            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(10939),
            ));

        } elseif (!isset($_POST['chainhandlecreator']) || !isset($_POST['handleid']) || !isset($_POST['hashtagid']) || !isset($_POST['chainid'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core Variable',
            ));

        } else {

            $_POST['require_writing'] = intval($_POST['require_writing']);

            $already_added = $this->Chains->read(array(
                'chainhandleinput' => $_POST['handleid'],
                'chainhandleoutput' => $_POST['chainhandlecreator'],
                'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
            ), array('chainhandleinput'));

            if (count($already_added)) {

                if (intval($_POST['require_writing'])) {

                    //Updating current value if changed:
                    if (strlen($_POST['written_answer']) && trim($_POST['written_answer']) != $already_added[0]['chainvalue']) {
                        $this->Chains->update($already_added[0]['chainid'], array(
                            'chainvalue' => $_POST['written_answer'],
                            'chainhandlecreator' => $handle_session['handleid'],
                        ));
                    } elseif (!strlen($_POST['written_answer'])) {
                        $this->Chains->delete($already_added[0]['chainid'], $handle_session['handleid']);
                    }

                    return view_json(array(
                        'status' => 1,
                        'message' => $_POST['written_answer'],
                    ));

                } else {

                    //Already exists, let's remove:
                    $this->Chains->delete($already_added[0]['chainid'], $handle_session['handleid']);

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

                    foreach ($this->Handles->read(array(
                        'handleid' => $_POST['handleid'],
                    )) as $e) {

                        //Does not exist, Add:
                        $this->Chains->create(array(
                            'chainhandleinput' => $_POST['handleid'],
                            'chainhandleoutput' => $_POST['chainhandlecreator'],
                            'chainhandlecreator' => $handle_session['handleid'],
                            'chainvalue' => $_POST['written_answer'],
                            'chainhandletype' => 4230,
                        ));

                        return view_json(array(
                            'status' => 1,
                            'message' => (intval($_POST['require_writing']) ? $_POST['written_answer'] : view_cover($e['handlecover'], true)),
                        ));

                    }
                }
            }
        }
    }

    function handle_verify()
    {

        if (!isset($_POST['account_email_phone'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'missing account details',
            ));
        }

        //Cleanup input email:
        $handles___11035 = $this->config->item('handles___11035'); //Encyclopedia
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
        } elseif (!isset($_POST['sign_hashtagid'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing data ID',
            ));
        }


        if (intval($_POST['sign_hashtagid']) > 0) {
            //Fetch the hashtag:
            $referrer_i = $this->Hashtags->read(array(
                'hashtagid' => $_POST['sign_hashtagid'],
            ));
        } else {
            $referrer_i = array();
        }


        //Search for email/phone to see if it exists
        $chainhandlecreator = 0;
        foreach ($this->Chains->read(array(
            'LOWER(chainvalue)' => strtolower($_POST['account_email_phone']),
            'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
            'chainhandleinput' => (filter_var($_POST['account_email_phone'], FILTER_VALIDATE_EMAIL) ? 3288 : 4783), //Email / Phone
        ), array('chainhandleoutput'), 1, 0, array('chainid' => 'ASC')) as $map_e) {
            $u = $map_e;
            $chainhandlecreator = $map_e['handleid'];
        }

        //Send Sign In Key
        $passcode = rand(1000, 9999);
        $session_key = random_string(55);

        //Append to session:
        $session_data = $this->session->all_userdata();
        $session_data['session_key'] = $session_key;
        $this->session->set_userdata($session_data);

        $html_message = $passcode . ' is your ' . $handles___11035[32078]['m__title'] . ' for your ' . get_domain('m__title') . ' account.';

        if ($valid_email) {

            //Email:
            dispatch_email(array($_POST['account_email_phone']), $html_message, '<div class="line">' . $html_message . '</div>', $chainhandlecreator, array(), 0, 0, false);


        } elseif ($possible_phone) {

            //SMS:
            dispatch_sms($_POST['account_email_phone'], $html_message, 0, array(), 0, 0, false);

        }

        //Log new key:
        $this->Chains->create(array(
            'chainhandletype' => 44176, //Handle View
            'chainhandleinput' => 32078, //Sign In Key
            'chainhandleoutput' => $chainhandlecreator, //Member making request
            'chainhandlecreator' => $chainhandlecreator, //Member making request
            'chainhashtaginput' => intval($_POST['sign_hashtagid']),
            'chainvalue' => $_POST['account_email_phone'] . '/' . md5($session_key . $passcode),
        ));

        return view_json(array(
            'status' => 1,
            'account_id' => $chainhandlecreator,
            'valid_email' => ($valid_email ? 1 : 0),
            'account_preview' => ($chainhandlecreator ? '<span class="icon-block">' . view_cover($u['handlecover'], true) . '</span>' . $u['handlename'] : ''),
            'clean_contact' => $_POST['account_email_phone'],
        ));

    }

    function handle_text_update()
    {

        //Authenticate Member:
        $handle_session = handle_session(null, 0, $this->handle_session);
        $handles___12112 = $this->config->item('handles___12112');

        if (!$handle_session) {

            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
                'original_val' => '',
            ));

        } elseif (!isset($_POST['handleid']) || !isset($_POST['cache_handleid']) || !isset($_POST['hashtag_createtext'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing core variables',
                'original_val' => '',
            ));

        } elseif ($_POST['cache_handleid'] == 6197 /* HANDLE FULL NAME */) {

            $es = $this->Handles->read(array(
                'handleid' => $_POST['handleid'],
            ));
            if (!count($es)) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Handle ID #3',
                    'original_val' => '',
                ));
            }


            $validate_handlename = validate_handlename($_POST['hashtag_createtext']);
            if (!$validate_handlename['status']) {
                return view_json(array_merge($validate_handlename, array(
                    'original_val' => $es[0]['handlename'],
                )));
            }

            //All good, go ahead and update:
            $this->Handles->update($es[0]['handleid'], array(
                'handlename' => $validate_handlename['handlename_clean'],
            ), $handle_session['handleid']);

            //Reset member session data if this data belongs to the logged-in member:
            if ($es[0]['handleid'] == $handle_session['handleid']) {
                //set Session with new data:
                $es[0]['handlename'] = $validate_handlename['handlename_clean'];
                $this->Handles->activate($es[0], true);
            }

            return view_json(array(
                'status' => 1,
            ));

        } else {

            return view_json(array(
                'status' => 0,
                'message' => 'Unknown Update Type [' . $_POST['cache_handleid'] . ']',
                'original_val' => '',
            ));

        }
    }

    function chain_preview()
    {

        if (!isset($_POST['apply_id']) || !isset($_POST['s__id'])) {
            die('Missing core data');
        }

        //Log Modal View
        $handle_session = handle_session(null, 0, $this->handle_session);

        if (!isset($_POST['apply_id']) || !isset($_POST['s__id'])) {
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Missing Core Data</div>';
        } else {
            if ($_POST['apply_id'] == 4997) {

                //Handle list:
                $counter = handles_query(42373, $_POST['s__id'], 0, false);
                if (!$counter) {
                    echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>No Handles yet</div>';
                } else {
                    echo '<div class="alert" role="alert"><span class="icon-block"><i class="far fa-list"></i></span>Will apply to ' . $counter . ' Handle' . search($counter) . ':</div>';
                    echo '<div class="row justify-content">';
                    $ids = array();
                    foreach (handles_query(42373, $_POST['s__id'], 1, true) as $e) {
                        array_push($ids, $e['handleid']);
                        echo handle_view(42287, $e);
                    }
                    echo '</div>';
                    echo '<div class="dotransparent" title="Total of ' . count($ids) . '">' . join(', ', $ids) . '</div>';
                }

            } elseif ($_POST['apply_id'] == 12589) {

                //hashtag list:
                $is_next = $this->Chains->read(array(
                    'chainhandletype IN (' . join(',', $this->config->item('handleids___42345')) . ')' => null, //Active Sequence
                    'chainhashtaginput' => $_POST['s__id'],
                ), array('chainhashtagoutput'), 0, 0, array('chainkey' => 'ASC'));
                $counter = count($is_next);

                if (!$counter) {
                    echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>No Hashtags yet</div>';
                } else {
                    echo '<div class="alert" role="alert"><span class="icon-block"><i class="far fa-list"></i></span>Will apply to ' . $counter . ' hashtag' . search($counter) . ':</div>';
                    echo '<div class="row justify-content">';
                    $ids = array();
                    foreach ($is_next as $i) {
                        array_push($ids, $i['hashtagid']);
                        echo hashtag_view(42288, $i);
                    }
                    echo '</div>';
                    echo '<div class="dotransparent">' . join(',', $ids) . '</div>';
                }

            } else {
                echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Unknown Apply ID</div>';
            }
        }
    }

    function chain_page_load()
    {

        $focus_e = array();
        $previous_i = array();

        if (!isset($_POST['focus__node'])) {
            die('Missing input. Refresh and try again.');
        }
        $success = false;

        if ($_POST['focus__node'] == 12274) {

            //HANDLE
            $focus_es = $this->Handles->read(array(
                'handleid' => $_POST['focus__id'],
            ));
            $focus_e = $focus_es[0];

            foreach (handles_query($_POST['chainhandletype'], $_POST['focus__id'], $_POST['current_page']) as $s) {
                if (in_array($_POST['chainhandletype'], $this->config->item('handleids___11028'))) {
                    echo handle_view($_POST['chainhandletype'], $s);
                    $success = true;
                } else if ($_POST['chainhandletype']==31777 || $_POST['chainhandletype']==13550 || in_array($_POST['chainhandletype'], $this->config->item('handleids___11020'))) {
                    echo hashtag_view($_POST['chainhandletype'], $s, $previous_i, null, $focus_e['handleid']);
                    $success = true;
                }
            }

        } elseif ($_POST['focus__node'] == 12273) {

            //HASHTAG
            $previous_is = $this->Hashtags->read(array(
                'hashtagid' => $_POST['focus__id'],
            ));
            $previous_i = $previous_is[0];

            foreach (hashtags_query($_POST['chainhandletype'], $_POST['focus__id'], $_POST['current_page']) as $s) {
                if (in_array($_POST['chainhandletype'], $this->config->item('handleids___11020'))) {
                    echo hashtag_view($_POST['chainhandletype'], $s, $previous_i);
                    $success = true;
                } else if ($_POST['chainhandletype']==31777 || $_POST['chainhandletype']==13550 || in_array($_POST['chainhandletype'], $this->config->item('handleids___11028'))) {
                    echo handle_view($_POST['chainhandletype'], $s);
                    $success = true;
                }
            }
        }

        if (!$success) {
            die('Nothing more to load :)');
        }

    }

    function chain_sort_reset()
    {

        //Authenticate Member:
        $handle_session = handle_session(10939, 0, $this->handle_session);

        if (!$handle_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(10939),
            ));
        } elseif (!isset($_POST['focus__node']) || !in_array($_POST['focus__node'], $this->config->item('handleids___28956'))) {
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
            //Hashtags order based on alphabetical order
            $order = 0;
            foreach ($this->Chains->read(array(
                'chainhandletype IN (' . join(',', $this->config->item('handleids___42345')) . ')' => null, //Active Sequence
                'chainhashtaginput' => $_POST['focus__id'],
            ), array('chainhashtagoutput'), 0, 0, array('hashtagtext' => 'ASC')) as $x) {
                $order++;
                $this->Chains->update($x['chainid'], array(
                    'chainkey' => $order,
                ));
            }
        } elseif ($_POST['focus__node'] == 12274) {
            //Handles reset order
            foreach ($this->Chains->read(array(
                'chainhandleinput' => $_POST['focus__id'],
                'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
            ), array('chainhandleoutput'), 0, 0) as $x) {
                $this->Chains->update($x['chainid'], array(
                    'chainkey' => 0,
                ));
            }
        }

        //Display message:
        view_json(array(
            'status' => 1,
        ));
    }

    function hashtag_discovered()
    {


        $handle_session = handle_session(null, 0, $this->handle_session);
        if (!$handle_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['target_hashtagterm']) || !isset($_POST['target_hashtagid']) || !isset($_POST['handle_submitted_data']) || !isset($_POST['do_skip'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core Data',
            ));
        }

        if (!isset($_POST['selection_hashtagid'])) {
            $_POST['selection_hashtagid'] = array();
        }
        if (!isset($_POST['handle_submitted_data']['hashtag_createtext'])) {
            $_POST['handle_submitted_data']['hashtag_createtext'] = null;
        }
        if (!isset($_POST['next_hashtag_data'])) {
            $_POST['next_hashtag_data'] = array();
        }

        //Discover Focus Hashtag:
        $primary_hashtagid = null;
        foreach ($this->Hashtags->read(array(
            'hashtagid' => $_POST['handle_submitted_data']['hashtagid'],
        )) as $focus_i) {

            $input__selection = count($this->Chains->read(array(
                'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
                'chainhashtagoutput' => $focus_i['hashtagid'],
                'chainhandleinput IN (' . join(',', $this->config->item('handleids___7712')) . ')' => null,
            )));
            $input__upload = count($this->Chains->read(array(
                'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
                'chainhashtagoutput' => $focus_i['hashtagid'],
                'chainhandleinput IN (' . join(',', $this->config->item('handleids___43004')) . ')' => null,
            )));
            $skipping_not_allowed = count($this->Chains->read(array(
                'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
                'chainhashtagoutput' => $focus_i['hashtagid'],
                'chainhandleinput IN (' . join(',', $this->config->item('handleids___43009')) . ')' => null,
            )));
            $input__text = count($this->Chains->read(array(
                'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
                'chainhashtagoutput' => $focus_i['hashtagid'],
                'chainhandleinput IN (' . join(',', array_merge($this->config->item('handleids___43002'), $this->config->item('handleids___43003'))) . ')' => null,
            )));
            $total_selected = count($_POST['selection_hashtagid']);
            $trying_to_skip = !$skipping_not_allowed &&
                (
                    intval($_POST['do_skip'])
                    || ($input__selection && !$total_selected)
                    || ($input__upload && !strlen($_POST['handle_submitted_data']['hashtag_createtext'])) //TODO Check Media
                    || (!$input__selection && !$input__upload && !strlen($_POST['handle_submitted_data']['hashtag_createtext']))
                );
            $hashtag_required = hashtag_required($focus_i);

            if (!$primary_hashtagid) {
                $primary_hashtagid = ($total_selected ? end($_POST['selection_hashtagid']) : $focus_i['hashtagid']);
            }

            //If skipping, make sure they can:
            if ($hashtag_required && $trying_to_skip) {
                return view_json(array(
                    'status' => 0,
                    'message' => ($input__selection ? 'Make a selection to continue...' : 'Respond to continue...'),
                ));
            }

            //Now complete relevant next hashtags, if any:
            if ($input__selection) {

                $is_single_selection = count($this->Chains->read(array(
                    'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
                    'chainhashtagoutput' => $focus_i['hashtagid'],
                    'chainhandleinput IN (' . join(',', $this->config->item('handleids___33331')) . ')' => null,
                )));


                if (!$is_single_selection) {

                    //How about the min selection?
                    if ($hashtag_required) {
                        foreach ($this->Chains->read(array(
                            'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
                            'chainhashtagoutput' => $focus_i['hashtagid'],
                            'chainhandleinput' => 40834, //Min Selection
                        ), array(), 1) as $limit) {
                            if (intval($limit['chainvalue']) > 0 && $total_selected < intval($limit['chainvalue'])) {
                                return view_json(array(
                                    'status' => 0,
                                    'message' => 'Select ' . $limit['chainvalue'] . ' or more hashtags to go next.',
                                ));
                            }
                        }
                    }

                    //How about max selection?
                    foreach ($this->Chains->read(array(
                        'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
                        'chainhashtagoutput' => $focus_i['hashtagid'],
                        'chainhandleinput' => 40833, //Max Selection
                    ), array(), 1) as $limit) {
                        if (intval($limit['chainvalue']) > 0 && $total_selected > intval($limit['chainvalue'])) {
                            return view_json(array(
                                'status' => 0,
                                'message' => 'You cannot select more than ' . $limit['chainvalue'] . ' items.',
                            ));
                        }
                    }

                }


                //Delete ALL previous answers that are not currently selected, if any:
                $already_answered = array();
                foreach ($this->Chains->read(array(
                    'chainhandletype' => 7712, //Input Choice
                    'chainhandlecreator' => $handle_session['handleid'],
                    'chainhashtaginput' => $focus_i['hashtagid'],
                ), array('chainhashtagoutput')) as $x_selection) {

                    if (in_array($x_selection['hashtagid'], $_POST['selection_hashtagid'])) {
                        //Current selection is already in the database from before:
                        array_push($already_answered, $x_selection['hashtagid']);
                        continue; //Nothing we need to do here...
                    }

                    $this->Chains->delete($x_selection['chainid'], $handle_session['handleid']);

                    //Remove discovery if we can:
                    if (!count($this->Chains->read(array(
                        'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
                        'chainhashtagoutput' => $x_selection['hashtagid'],
                        'chainhandleinput IN (' . join(',', $this->config->item('handleids___42905')) . ')' => null,
                    )))) {
                        foreach ($this->Chains->read(array(
                            'chainhandletype IN (' . join(',', $this->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
                            'chainhashtaginput' => $x_selection['hashtagid'],
                            'chainhandlecreator' => $handle_session['handleid'],
                        ), array(), 0) as $x_discovery) {
                            $this->Chains->delete($x_discovery['chainid'], $handle_session['handleid']);
                        }
                    }
                }

                //Save New Answers if not already:
                foreach ($_POST['selection_hashtagid'] as $answer_hashtagid) {
                    if (!in_array($answer_hashtagid, $already_answered)) {
                        $this->Chains->create(array(
                            'chainhandletype' => 7712, //Input Choice
                            'chainhandlecreator' => $handle_session['handleid'],
                            'chainhandleinput' => $handle_session['handleid'],
                            'chainhashtaginput' => $focus_i['hashtagid'],
                            'chainhashtagoutput' => $answer_hashtagid,
                        ));
                    }
                }

            }

            //Save Skip if no answer was selected:
            if($trying_to_skip){
                $completion_status = $this->Chains->hashtag_discovered(31022, $handle_session['handleid'], $_POST['target_hashtagid'], $focus_i, $_POST['handle_submitted_data'], array(
                    'chainkey' => $_POST['handle_submitted_data']['hashtagweight'],
                ));
                if (!$completion_status['status']) {
                    //We had an error with data within target_hashtagid:
                    return view_json($completion_status);
                }
            }


            //Look through ALL next hashtags and see which ones we can complete, if any:
            foreach ($_POST['next_hashtag_data'] as $index => $next_hashtag_data) {

                if ($input__selection && !in_array($next_hashtag_data['hashtagid'], $_POST['selection_hashtagid'])) {
                    //Not selected, move on:
                    continue;
                }

                foreach ($this->Hashtags->read(array(
                    'hashtagid' => $next_hashtag_data['hashtagid'],
                )) as $hashtag_next) {

                    //Analyze input:
                    $input__required = count($this->Chains->read(array(
                        'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
                        'chainhashtagoutput' => $hashtag_next['hashtagid'],
                        'chainhandleinput IN (' . join(',', $this->config->item('handleids___43039')) . ')' => null,
                    )));
                    if ($input__required) {
                        continue;
                    }
                    $input__text = count($this->Chains->read(array(
                        'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
                        'chainhashtagoutput' => $hashtag_next['hashtagid'],
                        'chainhandleinput IN (' . join(',', array_merge($this->config->item('handleids___43002'), $this->config->item('handleids___43003'))) . ')' => null,
                    )));
                    $input__upload = count($this->Chains->read(array(
                        'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
                        'chainhashtagoutput' => $hashtag_next['hashtagid'],
                        'chainhandleinput IN (' . join(',', $this->config->item('handleids___43004')) . ')' => null,
                    )));
                    $skipping_not_allowed = count($this->Chains->read(array(
                        'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
                        'chainhashtagoutput' => $hashtag_next['hashtagid'],
                        'chainhandleinput IN (' . join(',', $this->config->item('handleids___43009')) . ')' => null,
                    )));


                    //Cleanup phone number:
                    if($input__text && strlen($next_hashtag_data['hashtag_createtext']) && !is_numeric($next_hashtag_data['hashtag_createtext']) && count($this->Chains->read(array(
                            'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
                            'chainhashtagoutput' => $hashtag_next['hashtagid'],
                            'chainhandleinput' => 42181, //Phone
                        )))){
                        $next_hashtag_data['hashtag_createtext'] = preg_replace("/[^0-9]+/", "", $next_hashtag_data['hashtag_createtext']);
                        if(strlen($next_hashtag_data['hashtag_createtext'])<10){
                            return view_json(array(
                                'status' => 0,
                                'message' => 'Phone numbers cannot be less than 10 digits',
                            ));
                        }
                    }

                    $trying_to_skip = (
                        !strlen($next_hashtag_data['hashtag_createtext']) ||
                        ($input__upload && !strlen($next_hashtag_data['hashtag_createtext'])) //TODO Check Media
                    );
                    $hashtag_required = !$skipping_not_allowed && hashtag_required($hashtag_next);

                    if ($hashtag_required && $trying_to_skip) {
                        return view_json(array(
                            'status' => 0,
                            'message' => 'Enter a valid response to '.view_hashtag_title($hashtag_next, true).' instead of "'.$next_hashtag_data['hashtag_createtext'].'"',
                        ));
                    }

                    //Try to complete:
                    $completion_status = $this->Chains->hashtag_discovered(( $trying_to_skip ? 31022 : 4559 ), $handle_session['handleid'], $_POST['target_hashtagid'], $hashtag_next, $next_hashtag_data, array(
                        'chainkey' => $next_hashtag_data['hashtagweight'],
                    ));
                    if ($hashtag_required && !$completion_status['status']) {
                        //We had an error with data within target_hashtagid:
                        //return view_json($completion_status);
                    }
                }
            }

            //Find Next:
            $hashtag_redirect_url = false;
            foreach ($this->Hashtags->read(array(
                'hashtagid' => $primary_hashtagid,
            )) as $primary_i) {
                $hashtag_redirect_url = hashtag_redirect_url($primary_i);
            }
            if (!$hashtag_redirect_url) {
                $hashtag_next = $this->Chains->next_hashtags($handle_session['handleid'], $_POST['target_hashtagterm']);
            }

            //All good:
            return view_json(array(
                'status' => 1,
                'message' => 'Saved & Next',
                'next__url' => ($hashtag_redirect_url ? $hashtag_redirect_url : ($hashtag_next ? $hashtag_next : 'start')),
            ));

        }

        //All good:
        return view_json(array(
            'status' => 0,
            'message' => 'Invalid Hashtag',
        ));

    }

    function handle_select()
    {

        if (!isset($_POST['focus__id']) || !isset($_POST['o__id']) || !isset($_POST['element_id']) || !isset($_POST['handle_createid']) || !isset($_POST['migratehandle']) || !isset($_POST['chainid'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing core data',
            ));
        }

        //Validate migration handles if any:
        $_POST['migratehandle'] = trim($_POST['migratehandle']);
        $first_letter = substr($_POST['migratehandle'], 0, 1);
        if ($first_letter == '@' && strlen($_POST['migratehandle']) > 1) {
            if (!count($this->Handles->read(array(
                'LOWER(handleterm)' => strtolower(substr($_POST['migratehandle'], 1)),
            )))) {
                return view_json(array(
                    'status' => 0,
                    'message' => $_POST['migratehandle'] . ' is an invalid Handle Handle. Try again if you want to migrate this Handle chains or leave the field blank.',
                ));
            }
        } elseif ($first_letter == '#' && strlen($_POST['migratehandle']) > 1) {
            if (!count($this->Hashtags->read(array(
                'LOWER(hashtagterm)' => strtolower(substr($_POST['migratehandle'], 1)),
            )))) {
                return view_json(array(
                    'status' => 0,
                    'message' => $_POST['migratehandle'] . ' is an invalid Hashtag Hashtag. Try again if you want to migrate this hashtag chains or leave the field blank.',
                ));
            }
        } else {
            $_POST['migratehandle'] = '';
        }

        if (is_array($_POST['o__id'])) {
            $mass_result = array();
            foreach ($_POST['o__id'] as $o__id) {
                array_push($mass_result, $this->Chains->select($_POST['focus__id'], $o__id, $_POST['element_id'], $_POST['handle_createid'], $_POST['migratehandle'], $_POST['chainid']));
            }
            return view_json($mass_result);
        } else {
            return view_json($this->Chains->select($_POST['focus__id'], $_POST['o__id'], $_POST['element_id'], $_POST['handle_createid'], $_POST['migratehandle'], $_POST['chainid']));
        }

    }


    function chain_delete()
    {

        /*
         *
         * When members indicate they want to stop
         * a HASHTAG this function saves the changes
         * necessary and delete the hashtag from their
         * discoveries.
         *
         * */

        $handle_session = handle_session(null, 0, $this->handle_session);

        if (!$handle_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['chainid']) || intval($_POST['chainid']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Chain ID',
            ));
        }

        //Remove Hashtag
        $this->Chains->delete($_POST['chainid'], $handle_session['handleid']);

        return view_json(array(
            'status' => 1,
        ));
    }

    function chain_load()
    {

        /*
         * Loads the list of Chains based on the
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
        $handle_session = handle_session(null, 0, $this->handle_session);

        $message = '';
        $overall_stats = '';

        //Fetch Chains and total Chain counts:
        $x = $this->Chains->read($query_filters, $joined_by, view_memory(6404, 11064), $query_offset);
        $x_count = $this->Chains->read($query_filters, $joined_by, 0, 0, array(), 'COUNT(chainid) as total_count');
        $total_items_loaded = ($query_offset + count($x));
        $has_more_chains = ($x_count[0]['total_count'] > 0 && $total_items_loaded < $x_count[0]['total_count']);


        //Display filter:
        if ($total_items_loaded > 0) {
            //Subsequent messages:
            $overall_stats = '<tr class="main__title x-info grey"><td colspan="100%">' . ($x_count[0]['total_count'] > $total_items_loaded ? ($total_items_loaded >= ($query_offset + 1) ? $total_items_loaded . ' OF ' : '') : '') . number_format($x_count[0]['total_count'], 0) . ' CHAINS:</td></tr>';
        }


        if (count($x) > 0) {

            foreach ($x as $x) {
                $message .= chain_view($x);
            }

            //Do we have more to show?
            if (!$has_more_chains) {
                $message .= '<tr class="main__title x-info grey"><td colspan="100%"><div class="alert alert-success" role="alert"><span class="icon-block"><i class="far fa-check-circle"></i></span>All ' . $x_count[0]['total_count'] . ' Chains have been loaded</div></td></tr>';


            }

        } else {

            //Show no Chain warning:
            $message .= '<tr class="main__title x-info grey"><td colspan="100%"><div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>No Chains found with the selected filters. Modify filters and try again.</div></td></tr>';

        }

        return view_json(array(
            'status' => 1,
            'message' => $message,
            'has_more_chains' => $has_more_chains,
            'overall_stats' => $overall_stats,

        ));


    }

    function chain_stats()
    {

        //See if we have any hashtag or Handle targets to limit our stats:
        $has_handle = isset($_POST['handleterm']) && strlen($_POST['handleterm']) && $_POST['handleterm'];
        $has_hashtag = isset($_POST['hashtagterm']) && strlen($_POST['hashtagterm']) && $_POST['hashtagterm'];

        if ($has_handle) {

            //See stats for this Handle:
            $es = $this->Handles->read(array(
                'LOWER(handleterm)' => strtolower($_POST['handleterm']),
            ));
            if (!count($es)) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Handle',
                ));
            }

        } elseif ($has_hashtag) {

            //See stats for this hashtag:
            $is = $this->Hashtags->read(array(
                'LOWER(hashtagterm)' => strtolower($_POST['hashtagterm']),
            ));
            if (!count($is)) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Hashtag',
                ));
            }

            $copy = $this->Hashtags->ids($is[0], 'ALL');
        }


        //Count Chains:
        $return_array = array();
        foreach ($this->config->item('handles___33292') as $chainhandletype1 => $m1) { //Stats

            $level1_total = 0;

            if($chainhandletype1==1309754){

                //Voided
                if ($has_handle) {
                    $void_filter['(chainvoid >0 AND ( chainhandleoutput = ' . $es[0]['handleid'] . ' OR chainhandleinput = ' . $es[0]['handleid'] . ' OR chainhandlecreator = ' . $es[0]['handleid'] . ' ))'] = null;
                } elseif ($has_hashtag) {
                    $void_filter['(chainvoid >0 AND ( chainhashtaginput = ' . $is[0]['hashtagid'] . ' OR chainhashtagoutput = ' . $is[0]['hashtagid'] . ' ))'] = null;
                } else {
                    //Void Chains
                    $void_filter = array(
                        'chainvoid >' => 0, //Chains that have been voided
                    );
                }
                $sub_counter = $this->Chains->read($void_filter, array(), 0, 0, array(), 'COUNT(chainid) as totals');
                $return_array[$chainhandletype1] = intval($sub_counter[0]['totals']);
                continue;
            }

            foreach ($this->config->item('handles___' . $chainhandletype1) as $chainhandletype2 => $m2) {

                //Nodes/Chains
                $level2_total = 0;
                if ($chainhandletype2 == 12273) {

                    if ($has_handle) {

                        $sub_counter = $this->Chains->read(array(
                            'chainhandletype IN (' . join(',', $this->config->item('handleids___33602')) . ')' => null, //Hashtag/Handle Chains Active
                            'chainhandleinput' => $es[0]['handleid'],
                        ), array('chainhashtagoutput'), 0, 0, array(), 'COUNT(chainid) as totals');

                    } elseif ($has_hashtag && count($copy['recursive_hashtag_ids'])) {

                        //See stats for this hashtag:
                        $sub_counter = $this->Hashtags->read(array(
                            'hashtagid IN (' . join(',', $copy['recursive_hashtag_ids']) . ')' => null,
                        ), 0, 0, array(), 'COUNT(hashtagid) as totals');

                    } else {

                        $sub_counter = $this->Hashtags->read(array(), 0, 0, array(), 'COUNT(hashtagid) as totals');

                    }

                    $level2_total += $sub_counter[0]['totals'];
                    $return_array[$chainhandletype2] = intval($sub_counter[0]['totals']);

                } elseif ($chainhandletype2 == 12274) {

                    if ($has_handle) {

                        $sub_counter = $this->Chains->read(array(
                            'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                            'chainhandleinput' => $es[0]['handleid'],
                        ), array('chainhandleoutput'), 0, 0, array(), 'COUNT(chainid) as totals');

                    } elseif ($has_hashtag && count($copy['recursive_hashtag_ids'])) {

                        //See stats for this hashtag:
                        $sub_counter = $this->Chains->read(array(
                            'chainhandletype IN (' . join(',', $this->config->item('handleids___33602')) . ')' => null, //Hashtag/Handle Chains Active
                            'chainhashtagoutput IN (' . join(',', $copy['recursive_hashtag_ids']) . ')' => null,
                        ), array('chainhandleinput'), 0, 0, array(), 'COUNT(chainid) as totals');

                    } else {

                        $sub_counter = $this->Handles->read(array(), 0, 0, array(), 'COUNT(handleid) as totals');

                    }

                    $level2_total += $sub_counter[0]['totals'];
                    $return_array[$chainhandletype2] = intval($sub_counter[0]['totals']);

                } else {

                    foreach ($this->config->item('handles___' . $chainhandletype2) as $chainhandletype3 => $m3) {

                        if ($has_handle) {

                            $sub_counter = $this->Chains->read(array(
                                'chainhandletype' => $chainhandletype3,
                                '( chainhandleoutput = ' . $es[0]['handleid'] . ' OR chainhandleinput = ' . $es[0]['handleid'] . ' OR chainhandlecreator = ' . $es[0]['handleid'] . ' )' => null,
                            ), array(), 0, 0, array(), 'COUNT(chainid) as totals');

                        } elseif ($has_hashtag && count($copy['recursive_hashtag_ids'])) {

                            $sub_counter = $this->Chains->read(array(
                                'chainhandletype' => $chainhandletype3,
                                '( chainhashtaginput IN (' . join(',', $copy['recursive_hashtag_ids']) . ') OR chainhashtagoutput IN (' . join(',', $copy['recursive_hashtag_ids']) . '))' => null,
                            ), array(), 0, 0, array(), 'COUNT(chainid) as totals');

                        } else {

                            $sub_counter = $this->Chains->read(array(
                                'chainhandletype' => $chainhandletype3,
                            ), array(), 0, 0, array(), 'COUNT(chainid) as totals');

                        }

                        $level2_total += $sub_counter[0]['totals'];
                        $return_array[$chainhandletype3] = intval($sub_counter[0]['totals']);

                    }

                }

                $return_array[4341] += $level2_total;
                $level1_total += $level2_total;
                $return_array[$chainhandletype2] = intval($level2_total);

            }

            $return_array[4341] += $level1_total;
            $return_array[$chainhandletype1] = intval($level1_total);

        }
        return view_json(array(
            'status' => 1,
            'return_array' => $return_array,
        ));
    }

}