<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Controller extends CI_Controller
{

    public $source_session;

    function __construct()
    {

        parent::__construct();

        $this->output->enable_profiler(FALSE);

        $this->source_session = source_session();


        date_default_timezone_set('America/Los_Angeles');

        @session_start();

        //AUTO Login source if has cookie?
        $is_ajax = false;
        $source_user = false;
        $first_segment = ($is_ajax && isset($_POST['js_request_uri']) ? $_POST['js_request_uri'] : $this->uri->segment(1));
        $_SERVER['REQUEST_URI'] = (isset($_POST['js_request_uri']) ? $_POST['js_request_uri'] : @$_SERVER['REQUEST_URI']);
        $_SERVER['REQUEST_URI'] = (strlen($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : view_app_chain(4269));
        $source_session = source_session();
        $is_login_verified = isset($_GET['sourcehandle']) && $_GET['sourcehandle'] != 'SuccessfulWhale' && isset($_GET['hash']) && isset($_GET['time']) && ($_GET['time'] + 604800) > time() && strlen($_GET['sourcehandle']) && view_hash($_GET['time'] . $_GET['sourcehandle']) == $_GET['hash'];

        if (
            !$source_session
            && !array_key_exists(strtolower($first_segment), $this->config->item('handlsources___14582'))
            && (isset($_COOKIE['auth_cookie']) || $is_login_verified) //We can auto login with either method:
        ) {

            if ($is_login_verified) {

                foreach ($this->Sources->read(array(
                    'LOWER(sourcehandle)' => strtolower($_GET['sourcehandle']),
                )) as $source_session) {

                    //Login:
                    $this->Sources->activate($source_session, true);

                    //Log them in:
                    if (!$is_ajax) {
                        header("Location: " . $_SERVER['REQUEST_URI'], true, 307);
                        exit;
                    }

                }

            } elseif (isset($_COOKIE['auth_cookie'])) {

                $source_session = verify_cookie();
                if ($source_session) {
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

    function load($app_sourceid = 14563 /* Error if none provided */, $focus_handle = 0, $focus_hashtag = 0, $target_hashtag = 0)
    {

        $memory_detected = is_array($this->config->item('sourceids___6287')) && count($this->config->item('sourceids___6287'));
        if (!$memory_detected) {
            //Since we don't have the memory created we must load the app that does so:
            $app_sourceid = 4527;
        }

        //Any ideas passed?
        $sources___6287 = $this->config->item('sources___6287'); //APP
        $flash_message = false;
        $focus_e = null; //Sourcing
        $focus_i = null; //Ideation/Discovery
        $target_i = null; //Discovery


        if (isset($_GET['sourcehandle']) && $_GET['sourcehandle'] == 'SuccessfulWhale') {
            $_GET['sourcehandle'] = '';
            $focus_handle = '';
        } elseif ($focus_handle && strlen($focus_handle) && !isset($_GET['sourcehandle'])) {
            $_GET['sourcehandle'] = $focus_handle;
        }
        if ($focus_hashtag && strlen($focus_hashtag) && !isset($_GET['ideahashtag'])) {
            $_GET['ideahashtag'] = $focus_hashtag;
        }
        if (!isset($_GET['sourcehandle'])) {
            $_GET['sourcehandle'] = 0;
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

            if ($app_sourceid == 33286 && $focus_i && $focus_i['ideahashtag'] !== $_GET['ideahashtag']) {
                //Adjust URL Case Sensitive:
                return get_redirected(view_memory(42903, 33286) . $focus_i['ideahashtag']);
            }
        }


        if (isset($_GET['sourcehandle']) && strlen($_GET['sourcehandle'])) {
            foreach ($this->Sources->read(array(
                'LOWER(sourcehandle)' => strtolower($_GET['sourcehandle']),
            )) as $source_found) {
                $focus_e = $source_found;
            }
            if (!$focus_e) {
                //See if we need to lookup the ID:
                if (is_numeric($_GET['sourcehandle'])) {
                    //Maybe its an ID?
                    foreach ($this->Sources->read(array(
                        'sourceid' => $_GET['sourcehandle'],
                    )) as $source_found) {
                        $focus_e = $source_found;
                    }
                }
            }
            if ($app_sourceid == 42902 && $focus_e && $focus_e['sourcehandle'] !== $_GET['sourcehandle']) {
                //Adjust URL Case Sensitive:
                return get_redirected(view_memory(42903, 42902) . $focus_e['sourcehandle']);
            }
        }


        if ($memory_detected && !in_array($app_sourceid, $this->config->item('sourceids___6287'))) {
            //Invalid App:
            return get_redirected(view_memory(42903, 42902) . $sources___6287[$app_sourceid]['m__handle'], '<div class="alert alert-danger" role="alert">@' . $sources___6287[$app_sourceid]['m__handle'] . ' Is not an APP, yet 🤔</div>');
        } elseif ($memory_detected && !in_array($app_sourceid, $this->config->item('sourceids___42922'))) {
            //Validate Required App input:
            if (in_array($app_sourceid, $this->config->item('sourceids___42905')) && !$focus_e) {
                return get_redirected(home_url(), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Error: @' . $_GET['sourcehandle'] . ' is not a valid Source handle.</div>');
            } elseif (in_array($app_sourceid, $this->config->item('sourceids___44329')) && (!$focus_i || !$target_i)) {
                return get_redirected(home_url(), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Error: Both #' . $_GET['ideahashtag'] . ' & #' . $target_hashtag . ' must be valid hashtags.</div>');
            } elseif (in_array($app_sourceid, $this->config->item('sourceids___42911')) && !$focus_i) {
                return get_redirected(home_url(), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Error: #' . $_GET['ideahashtag'] . ' is not a valid idea hashtag.</div>');
            }
        }


        $chainsourcedown = ($focus_e ? $focus_e['sourceid'] : 0);
        $chainidearight = ($focus_i ? $focus_i['ideaid'] : 0);
        $chainidealeft = ($target_i ? $target_i['ideaid'] : 0);

        //Run App
        $source_session = false;
        $source_http_request = (isset($_SERVER['SERVER_NAME']) ? 1 : 0);

        if ($memory_detected && in_array($app_sourceid, $this->config->item('sourceids___42920'))) {
            boost_power();
        }

        if ($memory_detected && $source_http_request) {

            //Needs superpowers?
            $source_session = source_session();

            if ($source_session && !isset($source_session['sourceid']) && $app_sourceid!=7291) {
                //Old source, must log out:
                header("Location: /logout", true, 301);
                return false;
            }

            //Auto Login?
            if (isset($_GET['hash']) && isset($_GET['time']) && $focus_e) {

                //Validate Hash:
                if ($_GET['hash'] == view_hash($_GET['time'] . $focus_e['sourcehandle'])) {

                    if ($focus_i) {
                        if (idea_is_startable($focus_i)) {
                            $flash_message = '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="far fa-play"></i></span>You have started discovering this idea. Scroll to the bottom & go next to continue.</div>';
                        } else {
                            $this->Chains->idea_discovered(idea_type_discovery($focus_i), $focus_e['sourceid'], ($target_i ? $target_i['ideaid'] : 0), $focus_i);
                            $this->Chains->idea_discovered(29393, $focus_e['sourceid'], ($target_i ? $target_i['ideaid'] : 0), $focus_i);

                            //Inform user of changes:
                            $flash_message = '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="far fa-check-circle"></i></span>Ideas has been idea_discovered</div>';
                        }
                    }

                    //If not logged in, log them in:
                    if (!$source_session) {
                        $session_data = $this->Sources->activate($source_session, true);
                    }

                }
            }
        }


        //Cache App?
        $ui = null;
        $new_cache = false;
        $cache_chaintime = null;
        $chainsourcecreator = ($source_http_request ? ($source_session ? $source_session['sourceid'] : 14068 /* GUEST */) : 7274 /* CRON JOB */);
        $skip_idea_privacy_check = !$memory_detected || in_array($app_sourceid, $this->config->item('sourceids___43388'));
        $source_access = source_access(null, $focus_e['sourceid'], $focus_e);
        $idea_access = idea_access(null, $focus_i['ideaid'], $focus_i);
        $target_idea_access = idea_access(null, $target_i['ideaid'], $target_i);

        //MEMBER REDIRECT?
        if ($source_http_request && $memory_detected) {

            //Missing App, Source or Idea Access?
            $missing_access = false; //Assume they have access
            $superpowers_required = array_intersect($this->config->item('sourceids___10957'), $sources___6287[$app_sourceid]['m__following']);
            if ($source_session && in_array($app_sourceid, $this->config->item('sourceids___14639'))) {
                //Should redirect them:
                return get_redirected(view_memory(42903, 42902) . $source_session['sourcehandle']);
            } elseif (!$source_session && in_array($app_sourceid, $this->config->item('sourceids___14740'))) {
                //Should redirect them:
                $missing_access = 'Login or register a free account to continue.';
            } elseif (count($superpowers_required) && !source_session(end($superpowers_required))) {
                $sources___10957 = $this->config->item('sources___10957');
                $missing_access = 'Error: You Cannot Access ' . $sources___6287[$app_sourceid]['m__title'] . ' as it requires the superpower of ' . $sources___10957[end($superpowers_required)]['m__title'] . '.';
            } elseif ($focus_e && !$source_access) {
                $missing_access = 'Error: You Cannot Access @' . $focus_e['sourcehandle'] . ' due to Privacy Settings.';
            } elseif (!$skip_idea_privacy_check && $focus_i && !$idea_access) {
                $missing_access = 'Error: You Cannot Access Focus #' . $focus_i['ideahashtag'] . ' due to Privacy Settings.';
            } elseif (!$skip_idea_privacy_check && $target_i && !$target_idea_access) {
                $missing_access = 'Error: You Cannot Access Target #' . $target_i['ideahashtag'] . ' due to Privacy Settings.';
            }

            if ($missing_access) {
                //Redirect:
                return get_redirected((!$source_session ? view_app_chain(4269) . '?url=' . urlencode($_SERVER['REQUEST_URI']) : home_url()), '<div class="alert alert-warning" role="alert">' . $missing_access . '</div>');
            }
        }


        if ($memory_detected) {

            if (in_array($app_sourceid, $this->config->item('sourceids___14599')) && !in_array($app_sourceid, $this->config->item('sourceids___12741'))) {

                if (!isset($_GET['reset_cache'])) {
                    //Fetch Most Recent Cache:
                    foreach ($this->Chains->read(array(
                        'chainsourcedomain' => website_setting(0),
                        'chainsourcetype' => 44179, //Triggered
                        'chainsourceup' => 14599, //Cache App
                        'chainsourcedown' => $app_sourceid,
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
            $title .= view_idea_title($focus_i, true) . ' | ';
        }
        if ($target_i) {
            $title .= view_idea_title($target_i, true) . ' | ';
        }
        if ($focus_e) {
            $title .= $focus_e['sourcevalue'] . ' @' . $focus_e['sourcehandle'] . ' | ';
        }
        if (!$title) {
            //Append app name since no title:
            $title .= $sources___6287[$app_sourceid]['m__title'] . ' | ';
        }
        //Always Append Website at the end:
        $title .= ($memory_detected ? get_domain('m__title') : 'Loading Memory');


        $view_input = array(
            'app_sourceid' => $app_sourceid,
            'chainsourcecreator' => $chainsourcecreator,
            'source_session' => $source_session,
            'source_http_request' => $source_http_request,
            'memory_detected' => $memory_detected,

            'focus_e' => $focus_e,
            'focus_i' => $focus_i,
            'target_i' => $target_i,

            '$source_access' => $source_access,
            '$idea_access' => $idea_access,
            '$target_idea_access' => $target_idea_access,

            'title' => $title,
            'flash_message' => $flash_message,
        );

        if (!$ui) {
            //Prep view:
            $app_handler = ($memory_detected ? strtolower($sources___6287[$app_sourceid]['m__handle']) : 'memory');
            $raw_app = $this->load->view($app_handler, $view_input, true);
            $ui .= $raw_app;
        }


        if ($new_cache) {
            $cache_x = $this->Chains->create(array(
                'chainsourcedomain' => website_setting(0),
                'chainsourcetype' => 44179, //Triggered
                'chainsourceup' => 14599, //Cache App
                'chainsourcedown' => $app_sourceid,

                'chainsourcecreator' => $chainsourcecreator,
                'chainvalue' => $ui,
                'chainidealeft' => $chainidealeft,
                'chainidearight' => $chainidearight,
            ));
        }


        //App title?
        if ($memory_detected && in_array($app_sourceid, $this->config->item('sourceids___42928'))) {
            $ui = '<h1><span style="font-size:2em !important;">' . $sources___6287[$app_sourceid]['m__cover'] . '</span> ' . $sources___6287[$app_sourceid]['m__title'] . '</h1>' . $ui;
        }


        //Check to ensure they have started:
        if ($app_sourceid == 30795 && $target_i && $focus_i && $source_session && $target_i['ideahashtag'] == $focus_i['ideahashtag']) {

            //Starting point, make sure all good:
            if (!idea_is_startable($target_i)) {

                //Not a valid starting point:
                return get_redirected(home_url(), '<div class="alert alert-warning" role="alert">#' . $target_i['ideahashtag'] . ' is not an active starting point.</div>');

            } elseif (!count($this->Chains->read(array(
                'LOWER(ideahashtag)' => strtolower($target_i['ideahashtag']),
                'chainsourcecreator' => $source_session['sourceid'],
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___31777')) . ')' => null, //DISCOVERIES
            ), array('chainidealeft')))) {

                //Not yet started, add to their starting point:
                $completion_status = $this->Chains->idea_discovered(4235, $source_session['sourceid'], 0, $target_i);

                //Now return next idea:
                $next__url = $this->Chains->next_ideas($source_session['sourceid'], $target_i['ideahashtag'], $target_i);

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

            if (in_array($app_sourceid, $this->config->item('sourceids___12741'))) {

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
                foreach ($this->Ideas->read(array(
                    'LOWER(ideahashtag)' => strtolower(substr($_POST['handle_string'], 1)),
                )) as $i) {
                    echo idea_view(31777, $i);
                    return true;
                }
            } elseif (substr($_POST['handle_string'], 0, 1) == '@') {
                foreach ($this->Sources->read(array(
                    'LOWER(sourcehandle)' => strtolower(substr($_POST['handle_string'], 1)),
                )) as $e) {
                    echo source_view(12274, $e);
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

        $source_session = source_session(null, 0, $this->source_session);
        if (!$source_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['ideaid']) || !isset($_POST['chainid']) || !isset($_POST['current_ideatype'])) {
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
                'ideavalue' => null,
                'ideatype' => $_POST['current_ideatype'],
            ), $source_session['sourceid']);

            $ideaid = $idea_new['idea_create']['ideaid'];
            $created_ideaid = $ideaid;

        }


        //Fetch dynamic data based on idea type:
        $return_inputs = array();
        $sources___4737 = $this->config->item('sources___4737'); // Idea Status
        $sources___42179 = $this->config->item('sources___42179'); //Dynamic Input Fields
        $sources___11035 = $this->config->item('sources___11035'); //Encyclopedia

        foreach (array_intersect($this->config->item('sourceids___' . $ideatype), $this->config->item('sourceids___42179')) as $dynamic_sourceid) {

            $superpowers_required = array_intersect($this->config->item('sourceids___10957'), $sources___42179[$dynamic_sourceid]['m__following']);
            if (count($superpowers_required) && !source_session(end($superpowers_required), 0, $this->source_session)) {
                continue;
            }

            //Let's first determine the data type:
            $data_types = array_intersect($sources___42179[$dynamic_sourceid]['m__following'], $this->config->item('sourceids___4592'));

            if (count($data_types) != 1) {
                //This is strange, we are expecting 1 match only report this:
                log_error('Found ' . count($data_types) . ' Data Types (Expecting exactly 1) for @' . $dynamic_sourceid . ': Check @4592 to see what is wrong', array(
                    'chainsourcecreator' => $source_session['sourceid'],
                    'chainsourcedown' => $dynamic_sourceid,
                    'chainidearight' => $ideaid,
                ));
                continue; //Go to the next dynamic data type
            }

            //We found 1 match as expected:
            foreach ($data_types as $data_type_this) {
                $data_type = $data_type_this;
                break;
            }

            if (in_array($data_type, $this->config->item('sourceids___42188'))) {

                //Single or Multiple Choice:
                array_push($return_inputs, array(
                    'd__id' => $dynamic_sourceid,
                    'd__is_radio' => 1,
                    'd_chainid' => 0,
                    'd__html' => view_instant_select($dynamic_sourceid, 0, $ideaid),
                    'd__value' => ($ideaid > 0 ? $ideaid : ''),
                    'd__type_name' => '',
                    'd__placeholder' => '',
                    'd__profile_header' => '',
                ));

            } else {

                $this_data_type = $this->config->item('sources___' . $data_type);
                $sources___4592 = $this->config->item('sources___4592'); //Data types
                $sources___42179 = $this->config->item('sources___42179'); //Dynamic Input Field
                $sources___11035 = $this->config->item('sources___11035'); //Encyclopedia

                //Fetch the current value:
                $counted = 0;
                $unique_values = array();
                if ($ideaid > 0) { //Must have an original ID to possibly have a value...
                    foreach ($this->Chains->read(array(
                        'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42252')) . ')' => null, //Plain Chain
                        'chainidearight' => $ideaid,
                        'chainsourceup' => $dynamic_sourceid,
                    ), array('chainsourceup')) as $selected_e) {
                        if (strlen($selected_e['chainvalue']) && !in_array($selected_e['chainvalue'], $unique_values)) {
                            $counted++;
                            array_push($unique_values, $selected_e['chainvalue']);
                            array_push($return_inputs, array(
                                'd__id' => $dynamic_sourceid,
                                'd__is_radio' => 0,
                                'd_chainid' => $selected_e['chainid'],
                                'd__html' => view_dynamic_headline($dynamic_sourceid, $sources___42179[$dynamic_sourceid], $selected_e),
                                'd__value' => $selected_e['chainvalue'],
                                'd__type_name' => html_input_type($data_type),
                                'd__placeholder' => (strlen($this_data_type[$dynamic_sourceid]['m__message']) ? $this_data_type[$dynamic_sourceid]['m__message'] : $sources___4592[$data_type]['m__title'] . '...'),
                                'd__profile_header' => '',
                            ));
                        }
                    }
                }


                if (!$counted) {
                    foreach ($this->Sources->read(array(
                        'sourceid' => $dynamic_sourceid,
                    )) as $selected_e) {
                        array_push($return_inputs, array(
                            'd__id' => $dynamic_sourceid,
                            'd__is_radio' => 0,
                            'd_chainid' => 0,
                            'd__html' => view_dynamic_headline($dynamic_sourceid, $sources___42179[$dynamic_sourceid], $selected_e),
                            'd__value' => '',
                            'd__type_name' => html_input_type($data_type),
                            'd__placeholder' => (strlen($this_data_type[$dynamic_sourceid]['m__message']) ? $this_data_type[$dynamic_sourceid]['m__message'] : $sources___4592[$data_type]['m__title'] . '...'),
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

        $source_session = source_session(null, 0, $this->source_session);
        $migrateid = 0;

        if (!$source_session) {
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
            foreach ($this->Chains->read(array(
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42345')) . ')' => null, //Active Sequence
                'chainidearight' => $_POST['ideaid'],
            ), array('chainidealeft'), 1) as $previous_i) {
                $delete_redirect = view_memory(42903, 33286) . $previous_i['ideahashtag'];
            }

            //If not found, find active followings:
            if (!$delete_redirect) {
                foreach ($this->Chains->read(array(
                    'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42345')) . ')' => null, //Active Sequence
                    'chainidearight' => $_POST['ideaid'],
                ), array('chainidealeft'), 1) as $previous_i) {
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

        //Delete all Chains:
        $chains_removed = $this->Ideas->delete($_POST['ideaid'], $source_session['sourceid'], $migrateid);

        return view_json(array(
            'status' => ($chains_removed > 0 ? 1 : 0),
            'message' => 'Idea successfully removed',
            'delete_redirect' => $delete_redirect,
            'delete_element' => $delete_element,
        ));

    }

    function source_delete()
    {

        $source_session = source_session(null, 0, $this->source_session);
        $migrateid = 0;

        if (!$source_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['sourceid']) || !isset($_POST['focus__id']) || !isset($_POST['migratehandle'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core IDs',
            ));
        } elseif (source_access(null, $_POST['sourceid']) < 3) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Access to delete this idea',
            ));
        } elseif (strlen($_POST['migratehandle']) > 1) {
            $valid_handle = $this->Sources->read(array(
                'sourceid !=' => $_POST['sourceid'],
                'LOWER(sourcehandle)' => strtolower(str_replace('@', '', $_POST['migratehandle'])),
            ));
            if (!count($valid_handle)) {
                return view_json(array(
                    'status' => 0,
                    'message' => $_POST['migratehandle'] . ' is not an active handle',
                ));
            }
            $migrateid = $valid_handle[0]['sourceid'];
            if (!count($this->Sources->read(array('sourceid' => $migrateid)))) {
                return array(
                    'status' => 0,
                    'message' => $_POST['migratehandle'] . ' is not a valid Handle',
                );
            }
        } elseif (in_array($_POST['sourceid'], $this->config->item('sourceids___14870'))) {
            return array(
                'status' => 0,
                'message' => 'Cannot Delete an active @chainsourcedomain - Unchain, update @memory and try again',
            );
        } elseif (!count($this->Sources->read(array('sourceid' => $_POST['sourceid'])))) {
            return array(
                'status' => 0,
                'message' => $_POST['sourceid'] . ' is not a valid ID',
            );
        }


        //Determine what to do after deleted:
        $delete_redirect = '';
        $delete_element = '';

        if ($_POST['sourceid'] == $_POST['focus__id']) {

            //Find Published Followings:
            foreach ($this->Chains->read(array(
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
                'chainsourcedown' => $_POST['sourceid'],
            ), array('chainsourceup'), 1, 0, array('sourcevalue' => 'DESC')) as $up_e) {
                $delete_redirect = view_memory(42903, 42902) . $up_e['sourcehandle'];
            }

            //If still not found, go to main page if no followings found:
            if (!$delete_redirect) {
                foreach ($this->Sources->read(array('sourceid' => $_POST['sourceid'])) as $e2) {
                    $delete_redirect = view_memory(42903, 42902) . e2['sourcehandle'];
                }
            }
        } else {

            //Just delete from UI using JS:
            $delete_element = '.s__12274_' . $_POST['sourceid'];

        }

        //Delete all Chains:
        $chains_removed = $this->Sources->delete($_POST['sourceid'], $source_session['sourceid'], $migrateid);

        if(!$chains_removed['status']){
            return view_json(array(
                'status' => 1,
                'message' => 'Source successfully removed',
                'delete_redirect' => $delete_redirect,
                'delete_element' => $delete_element,
            ));
        }

        return view_json(array(
            'status' => 1,
            'message' => 'Source successfully removed',
            'delete_redirect' => $delete_redirect,
            'delete_element' => $delete_element,
        ));

    }

    function idea_update()
    {

        $source_session = source_session(null, 0, $this->source_session);
        if (!$source_session) {

            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));

        } elseif (!isset($_POST['save_ideavalue'])) {

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

        } elseif (!isset($_POST['next_ideaid'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Next/Previous ID',
            ));

        } elseif (!isset($_POST['save_chainid']) || !isset($_POST['save_chainvalue'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Chain Data',
            ));

        } elseif (!isset($_POST['save_ideatype']) || !in_array($_POST['save_ideatype'], $this->config->item('sourceids___4737'))) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid idea Type',
            ));
        } elseif (strlen($_POST['save_ideavalue']) > view_memory(6404, 4736)) {
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

        //Might be new if pre-drafting:
        if (!strlen($is[0]['ideavalue'])) {

            //See if references only:
            if (strlen($_POST['save_ideavalue']) && !substr_count($_POST['save_ideavalue'], "\n") && (intval($_POST['next_ideaid']))) {

                $all_hashtags = true;
                $idea_references = array();
                foreach (explode(' ', trim($_POST['save_ideavalue'])) as $word) {
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
                        if (!$valid_hashtag && source_session(10939, 0, $this->source_session)) {
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

                if ($all_hashtags && count($idea_references)) {

                    //Return success:
                    foreach ($this->Ideas->read(array(
                        'ideaid' => intval($_POST['next_ideaid']),
                    )) as $focus_i) {

                        //Append all of these hashtags:
                        foreach ($idea_references as $reference_i) {
                            if (intval($_POST['next_ideaid']) > 0) {
                                $status = $this->Ideas->chain($focus_i, 4228, $reference_i, $source_session['sourceid']);
                            }
                            if (!$status['status']) {
                                return view_json($status);
                            }
                        }

                        //What to focus on depends on how many total ideas added:
                        $return_i = (count($idea_references) >= 2 ? $focus_i : $reference_i);

                        return view_json(array(
                            'status' => 1,
                            'return_ideacache_chains' => '',
                            'return_ideacache_full' => idea_view($_POST['focus_group'], $return_i),
                            'redirect_idea' => view_memory(42903, 33286) . $return_i['ideahashtag'],
                            'message' => count($idea_references) . ' ideas chained',
                        ));
                    }
                }
            }

            //Update new idea fields:
            $this->Ideas->update($is[0]['ideaid'], array(
                'ideatype' => $_POST['save_ideatype'],
            ), $source_session['sourceid']);
            $is[0]['ideatype'] = trim($_POST['save_ideatype']);

        }

        //Validate Idea Message:
        if (!strlen(trim($_POST['save_ideavalue']))) {
            //Since we do not have media, we must have a message:
            return view_json(array(
                'status' => 0,
                'message' => 'Write or Upload something to save.',
            ));
        }


        //Process dynamic inputs if any:
        $sources___42179 = $this->config->item('sources___42179'); //Dynamic Input Fields
        if ($_POST['save_ideaid'] > 0) {
            for ($p = 1; $p <= view_memory(6404, 42206); $p++) {

                if (!isset($_POST['save_dynamic_' . $p])) {
                    break; //Nothing more to process
                }

                $input_parts = explode('____', $_POST['save_dynamic_' . $p], 3);
                if (!isset($input_parts[0]) || !isset($input_parts[1])) {
                    continue;
                }
                $d_chainid = $input_parts[0];
                $dynamic_sourceid = $input_parts[1];
                $dynamic_value = trim($input_parts[2]);

                //Required fields must have an input:
                if (in_array($dynamic_sourceid, $this->config->item('sourceids___28239')) && !strlen($dynamic_value) && !in_array($dynamic_sourceid, $this->config->item('sourceids___33331')) && !in_array($dynamic_sourceid, $this->config->item('sourceids___33332'))) {
                    return view_json(array(
                        'status' => 0,
                        'message' => 'Missing Required Field: ' . $sources___42179[$dynamic_sourceid]['m__title'],
                    ));
                }

                //Validate input based on its data type, if provided:
                if (strlen($dynamic_value)) {
                    foreach (array_intersect($sources___42179[$dynamic_sourceid]['m__following'], $this->config->item('sourceids___4592')) as $data_type_this) {
                        $data_type_validate = data_type_validate($data_type_this, $dynamic_value, $sources___42179[$dynamic_sourceid]['m__title']);
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
                        'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42252')) . ')' => null, //Plain Chain
                        'chainidearight' => $is[0]['ideaid'],
                        'chainsourceup' => $dynamic_sourceid,
                    ));
                }


                //Update if needed:
                if (!strlen($dynamic_value)) {

                    //Remove Chain if we have one:
                    if (count($values) && $dynamic_sourceid != 11035 /* HACK: Summary are key chains that should not be removed */) {
                        $this->Chains->delete($values[0]['chainid'], $source_session['sourceid']);
                    }

                } elseif (!count($values)) {

                    //Create New Chain:
                    $this->Chains->create(array(
                        'chainsourcecreator' => $source_session['sourceid'],
                        'chainsourcetype' => 4983, //Co-Author
                        'chainsourceup' => $dynamic_sourceid,
                        'chainidearight' => $is[0]['ideaid'],
                        'chainvalue' => $dynamic_value,
                        'chainkey' => number_chainkey($dynamic_value),
                    ));

                } elseif ($values[0]['chainvalue'] != $dynamic_value) {

                    //Update Chain:
                    $this->Chains->update($values[0]['chainid'], array(
                        'chainvalue' => $dynamic_value,
                        'chainsourcecreator' => $source_session['sourceid'],
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
            ), $source_session['sourceid']);

            //Now Handles everywhere they are referenced:
            foreach ($this->Chains->read(array(
                'chainidealeft' => $is[0]['ideaid'],
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42341')) . ')' => null, //Idea References
            ), array('chainidearight')) as $ref) {

                $this->Ideas->update($ref['ideaid'], array(
                    'ideavalue' => str_replace('#' . $is[0]['ideahashtag'], '#' . trim($_POST['save_ideahashtag']), $ref['ideavalue']),
                ), $source_session['sourceid']);

            }

            //Assign new value:
            $is[0]['ideahashtag'] = trim($_POST['save_ideahashtag']);

        }


        //Also have to add as a comment to another idea?
        if (intval($_POST['next_ideaid']) > 0) {
            $this->Chains->create(array(
                'chainsourcecreator' => $source_session['sourceid'],
                'chainidearight' => $_POST['next_ideaid'],
                'chainidealeft' => $is[0]['ideaid'],
                'chainsourcetype' => 4228,
            ));
        }


        //Do we have a chain reference message that need to be saved?
        if ($_POST['save_chainid'] > 0 && $_POST['save_chainvalue'] != 'IGNORE_INPUT') {
            //Fetch Chain:
            foreach ($this->Chains->read(array(
                'chainid' => $_POST['save_chainid'],
            )) as $this_x) {

                $is[0] = array_merge($is[0], $this_x);

                if ($this_x['chainvalue'] != trim($_POST['save_chainvalue'])) {
                    $this->Chains->update($this_x['chainid'], array(
                        'chainvalue' => trim($_POST['save_chainvalue']),
                        'chainsourcecreator' => $source_session['sourceid'],
                    ));
                }
            }
        }

        //Update Text:
        $text_updated = $this->Ideas->update($is[0]['ideaid'], array(
            'ideavalue' => trim($_POST['save_ideavalue']),
        ), $source_session['sourceid']);


        foreach ($this->Ideas->read(array(
            'ideaid' => $is[0]['ideaid'],
        )) as $new_i) {
            //Update Search Index:
            update_algolia(12273, $new_i['ideaid']);

            return view_json(array(
                'status' => 1,
                'return_ideacache_chains' => view_idea_value($new_i, $source_session['sourceid'], $focus__node, $focus__node),
                'return_ideacache_full' => idea_view($_POST['focus_group'], $new_i),
                'save_ideaid' => $is[0]['ideaid'],
                'save_ideavalue' => trim($_POST['save_ideavalue']),
                'text_updated' => $text_updated,
                'redirect_idea' => (isset($new_i['ideahashtag']) ? view_memory(42903, 33286) . $new_i['ideahashtag'] : null),
                'message' => 'Success',
            ));
        }

    }



    function idea_cover()
    {

        if (!isset($_POST['ideaid']) || !isset($_POST['chainsourcetype']) || !isset($_POST['first_segment']) || !isset($_POST['counter'])) {
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Missing core variables</div>';
        } else {

            $discover_chainsourcetype = discover_chainsourcetype();

            $ui = '';
            $listed_items = 0;
            if ($_POST['chainsourcetype']==13550 || $_POST['chainsourcetype']==31777) {

                //SOURCES
                $sources___4593 = $this->config->item('sources___4593'); //Chain Types
                $current_sourcehandle = view_valid_handle_source($_POST['first_segment']);
                foreach (ideas_query($_POST['chainsourcetype'], $_POST['ideaid'], 1, false) as $source_session) {
                    if (isset($source_session['sourceid'])) {
                        $ui .= view_card(view_memory(42903, 42902) . $source_session['sourcehandle'], $current_sourcehandle && $source_session['sourcehandle'] == $current_sourcehandle, $source_session['chainsourcetype'], view_cover($source_session['sourcecover'], true), $source_session['sourcevalue'], $source_session['chainvalue']);
                        $listed_items++;
                    }
                }

            } elseif (in_array($_POST['chainsourcetype'], $this->config->item('sourceids___11020'))) {

                //IDEAS
                $sources___4737 = $this->config->item('sources___4737'); //Idea Types
                $sources___4593 = $this->config->item('sources___4593'); //Chain Types
                $current_ideahashtag = (substr($_POST['first_segment'], 0, 1) == '~' ? substr($_POST['first_segment'], 1) : false);

                foreach (ideas_query($_POST['chainsourcetype'], $_POST['ideaid'], 1, false) as $next_i) {
                    if (isset($next_i['ideaid'])) {
                        $ui .= view_card($discover_chainsourcetype . view_memory(42903, 33286) . $next_i['ideahashtag'], $next_i['ideahashtag'] == $current_ideahashtag, $next_i['chainsourcetype'], (in_array($next_i['ideatype'], $this->config->item('sourceids___32172')) ? $sources___4737[$next_i['ideatype']]['m__cover'] : ''), view_idea_title($next_i, true), $next_i['chainvalue']);
                        $listed_items++;
                    }
                }

            }

            if ($listed_items < $_POST['counter']) {
                //We have more to show:
                foreach ($this->Ideas->read(array(
                    'ideaid' => $_POST['ideaid'],
                )) as $i) {
                    $ui .= view_more($discover_chainsourcetype . view_memory(42903, 33286) . $i['ideahashtag'], false, '&nbsp;', '&nbsp;', 'View All');
                }
            }

            echo $ui;

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

        $source_session = source_session(null, 0, $this->source_session);

        if (!$source_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['new_x_order']) || !is_array($_POST['new_x_order']) || count($_POST['new_x_order']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing sorting ideas',
            ));
        } elseif (!isset($_POST['chainsourcetype']) || !in_array($_POST['chainsourcetype'], $this->config->item('sourceids___4603'))) {
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
                    'chainsourcecreator' => $source_session['sourceid'],
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
        if (!isset($_POST['ideaid']) || intval($_POST['ideaid']) < 1 || !isset($_POST['counter']) || !isset($_POST['chainsourcetype']) || intval($_POST['chainsourcetype']) < 1) {
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Missing core variables</div>';
        } else {

            $ideas_query = ideas_query($_POST['chainsourcetype'], $_POST['ideaid'], 1);
            $ui = '';
            $is = $this->Ideas->read(array(
                'ideaid' => $_POST['ideaid'],
            ));
            if (!count($is) || !$ideas_query) {
                return false;
            }

            if ($_POST['chainsourcetype']==11019) {

                //IDEA Chain Groups Previous
                $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-' . $_POST['chainsourcetype'] . '">';
                foreach ($ideas_query as $previous_i) {
                    $ui .= idea_view(11019, $previous_i);
                }
                $ui .= '</div>';

            } elseif ($_POST['chainsourcetype']==12840) {

                //IDEA Chain Groups Next
                $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-' . $_POST['chainsourcetype'] . '">';
                foreach ($ideas_query as $next_i) {
                    $ui .= idea_view($_POST['chainsourcetype'], $next_i, $is[0]);
                }
                $ui .= '</div>';

            } elseif ($_POST['chainsourcetype']==31777) {

                $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-' . $_POST['chainsourcetype'] . '">';
                foreach ($ideas_query as $item) {
                    $ui .= source_view(31777, $item);
                }
                $ui .= '</div>';

            } elseif ($_POST['chainsourcetype']==13550) {

                //Sources
                $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-' . $_POST['chainsourcetype'] . '">';
                foreach ($ideas_query as $source_ref) {
                    $ui .= source_view($_POST['chainsourcetype'], $source_ref, null);
                }
                $ui .= '</div>';

            }

            echo $ui;

        }
    }


    function source_list()
    {

        //Authenticate Member:
        if (!isset($_POST['sourceid']) || intval($_POST['sourceid']) < 1 || !isset($_POST['chainsourcetype']) || intval($_POST['chainsourcetype']) < 1) {
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Missing core variables</div>';
            return false;
        }

        $limit = view_memory(6404, 11064);
        $source_session = source_session();
        $sources_query = sources_query($_POST['chainsourcetype'], $_POST['sourceid'], 1);
        $es = $this->Sources->read(array(
            'sourceid' => $_POST['sourceid'],
        ));
        if (!count($es)) {
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-lock"></i></span>Invalid Source ID</div>';
            return false;
        }
        if (!$sources_query) {
            return false;
        }

        $focus_sourceid = ($_POST['sourceid'] > 0 ? $_POST['sourceid'] : ($source_session ? $source_session['sourceid'] : 0));
        $ui = '';

        if ($_POST['chainsourcetype']==13550) {

            //Idea/Source Link Groups
            //Ideas:
            $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-' . $_POST['chainsourcetype'] . '">';
            foreach ($sources_query as $i) {
                $ui .= idea_view($_POST['chainsourcetype'], $i, null, null, $focus_sourceid);
            }
            $ui .= '</div>';

        } elseif (in_array($_POST['chainsourcetype'], $this->config->item('sourceids___11028'))) {

            //Sources:
            $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-' . $_POST['chainsourcetype'] . '">';
            foreach ($sources_query as $e) {
                $ui .= source_view($_POST['chainsourcetype'], $e, null);
            }
            $ui .= '</div>';

        } elseif (in_array($_POST['chainsourcetype'], $this->config->item('sourceids___12144'))) {

            //Discoveries:
            $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-' . $_POST['chainsourcetype'] . '">';
            foreach ($sources_query as $i) {
                $ui .= idea_view($_POST['chainsourcetype'], $i, null, null, $focus_sourceid);
            }
            $ui .= '</div>';

        }

        echo $ui;

    }

    function source_cover()
    {

        if (!isset($_POST['sourceid']) || !isset($_POST['chainsourcetype']) || !isset($_POST['first_segment']) || !isset($_POST['counter'])) {

            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Missing core variables</div>';

        } else {

            $ui = '';
            $listed_items = 0;
            $is_cache = in_array($_POST['chainsourcetype'], $this->config->item('sourceids___14599'));

            if (in_array($_POST['chainsourcetype'], $this->config->item('sourceids___11028')) || $_POST['chainsourcetype']==12274) {

                //SOURCES
                $current_sourcehandle = view_valid_handle_source($_POST['first_segment']);
                $sources___4593 = $this->config->item('sources___4593'); //Chain Types

                foreach (sources_query($_POST['chainsourcetype'], $_POST['sourceid'], 1, false) as $source_session) {
                    if (isset($source_session['sourceid'])) {
                        $ui .= view_card(view_memory(42903, 42902) . $source_session['sourcehandle'], $source_session['sourcehandle'] == $current_sourcehandle, $source_session['chainsourcetype'], view_cover($source_session['sourcecover'], true), $source_session['sourcevalue'], (!$is_cache ? $source_session['chainvalue'] : null));
                        $listed_items++;
                    }
                }

            } elseif ($_POST['chainsourcetype']==13550 || $_POST['chainsourcetype']==31777 || $_POST['chainsourcetype']==12273) {

                //IDEAS
                $current_ideahashtag = (substr($_POST['first_segment'], 0, 1) == '~' ? substr($_POST['first_segment'], 1) : false);
                $sources___4737 = $this->config->item('sources___4737'); //Idea Types
                $sources___4593 = $this->config->item('sources___4593'); //Chain Types
                $discover_chainsourcetype = discover_chainsourcetype();

                foreach (sources_query($_POST['chainsourcetype'], $_POST['sourceid'], 1, false) as $next_i) {
                    if (isset($next_i['ideaid'])) {
                        $ui .= view_card($discover_chainsourcetype . view_memory(42903, 33286) . $next_i['ideahashtag'], $next_i['ideahashtag'] == $current_ideahashtag, $next_i['chainsourcetype'], (in_array($next_i['ideatype'], $this->config->item('sourceids___32172')) ? $sources___4737[$next_i['ideatype']]['m__cover'] : ''), view_idea_title($next_i, true), (!$is_cache ? $next_i['chainvalue'] : null));
                        $listed_items++;
                    }
                }

            }

            if ($listed_items < $_POST['counter']) {
                //We have more to show:
                foreach ($this->Sources->read(array(
                    'sourceid' => $_POST['sourceid'],
                )) as $source_this) {
                    $ui .= view_more(view_memory(42903, 42902) . $source_this['sourcehandle'], false, '&nbsp;', '&nbsp;', 'View All');
                }
            }

            echo $ui;

        }
    }

    function source_sort_save()
    {

        //Authenticate Member:
        $source_session = source_session(10939, 0, $this->source_session);
        if (!$source_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(10939),
            ));
        } elseif (!isset($_POST['sourceid']) || intval($_POST['sourceid']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid sourceid',
            ));
        } elseif (!isset($_POST['new_chainkey']) || !is_array($_POST['new_chainkey']) || count($_POST['new_chainkey']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Nothing passed for sorting',
            ));
        } else {

            //Validate Source:
            $es = $this->Sources->read(array(
                'sourceid' => $_POST['sourceid'],
            ));

            //Count followers:
            $listsource_count = $this->Chains->read(array(
                'chainsourceup' => $_POST['sourceid'],
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
            ), array('chainsourcedown'), 0, 0, array(), 'COUNT(sourceid) as totals');

            if (count($es) < 1) {

                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid sourceid',
                ));

            } elseif ($listsource_count[0]['totals'] > view_memory(6404, 11064)) {

                return view_json(array(
                    'status' => 0,
                    'message' => 'Cannot sort Sources if greater than ' . view_memory(6404, 11064),
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


    function idea_copy()
    {

        //Auth member and check required variables:
        $source_session = source_session(10939, 0, $this->source_session);

        if (!$source_session) {
            return view__json(array(
                'status' => 0,
                'messagCloe' => view__unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['ideaid']) || intval($_POST['ideaid']) < 1) {
            return view__json(array(
                'status' => 0,
                'message' => 'Invalid Following Source',
            ));
        } elseif (!isset($_POST['do_recursive'])) {
            return view__json(array(
                'status' => 0,
                'message' => 'Missing template parameter',
            ));
        }

        return view_json($this->Ideas->copy(intval($_POST['ideaid']), intval($_POST['do_recursive']), $source_session['sourceid']));

    }


    function source_copy()
    {

        //Auth member and check required variables:
        $source_session = source_session(10939, 0, $this->source_session);

        if (!$source_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(10939),
            ));
        } elseif (intval($_POST['sourceid']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Source',
            ));
        } elseif (!strlen($_POST['copy_source_title'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Source Title',
            ));
        }

        $copy_children = true;
        if(substr($_POST['copy_source_title'], 0, 1)=='-'){
            $copy_children = false;
            $_POST['copy_source_title'] = substr($_POST['copy_source_title'], 1);
        }

        //Validate Source:
        $fetch_o = $this->Sources->read(array(
            'sourceid' => $_POST['sourceid'],
        ));
        if (count($fetch_o) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid followings Source ID',
            ));
        }


        //Create:
        $added_e = $this->Sources->create(array(
            'sourcevalue' => $_POST['copy_source_title'],
            'sourcecover' => $fetch_o[0]['sourcecover'],
        ), $source_session['sourceid']);
        if (!$added_e['status']) {
            //We had an error, return it:
            return view_json($added_e);
        } else {
            //Assign new Source:
            $focus_e = $added_e['source_create'];
        }


        //Followings:
        foreach ($this->Chains->read(array(
            'chainsourcedown' => $_POST['sourceid'],
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___41303')) . ')' => null, //Clone Source Chains
        ), array(), 0) as $x) {
            if (!count($this->Chains->read(array(
                'chainsourcetype' => $x['chainsourcetype'],
                'chainsourceup' => $x['chainsourceup'],
                'chainsourcedown' => $focus_e['sourceid'],
                'chainvalue' => $x['chainvalue'],
            )))) {
                $this->Chains->create(array(
                    'chainsourcecreator' => $source_session['sourceid'],
                    'chainkey' => $x['chainkey'],
                    'chainsourcetype' => $x['chainsourcetype'],
                    'chainsourceup' => $x['chainsourceup'],
                    'chainsourcedown' => $focus_e['sourceid'],
                    'chainvalue' => $x['chainvalue'],
                ));
            }
        }

        if($copy_children){
            //Followers:
            foreach ($this->Chains->read(array(
                'chainsourceup' => $_POST['sourceid'],
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___41303')) . ')' => null, //Clone Source Chains
            ), array(), 0) as $x) {

                //Make sure none existent in new Source:
                if (!count($this->Chains->read(array(
                    'chainsourcetype' => $x['chainsourcetype'],
                    'chainsourceup' => $focus_e['sourceid'],
                    'chainsourcedown' => $x['chainsourcedown'],
                    'chainvalue' => $x['chainvalue'],
                )))) {
                    $this->Chains->create(array(
                        'chainsourcecreator' => $source_session['sourceid'],
                        'chainkey' => $x['chainkey'],
                        'chainsourcetype' => $x['chainsourcetype'],
                        'chainsourceup' => $focus_e['sourceid'],
                        'chainsourcedown' => $x['chainsourcedown'],
                        'chainvalue' => $x['chainvalue'],
                    ));
                }
            }
        }

        //Ideas:
        foreach ($this->Chains->read(array(
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___41302')) . ')' => null, //Clone Idea Source Chains
            'chainsourceup' => $_POST['sourceid'],
        ), array(), 0) as $x) {
            if (!count($this->Chains->read(array(
                'chainsourcetype' => $x['chainsourcetype'],
                'chainsourceup' => $focus_e['sourceid'],
                'chainsourcedown' => $x['chainsourcedown'],
                'chainidealeft' => $x['chainidealeft'],
                'chainidearight' => $x['chainidearight'],
                'chainvalue' => $x['chainvalue'],
            )))) {
                $this->Chains->create(array(
                    'chainsourcecreator' => $source_session['sourceid'],
                    'chainkey' => $x['chainkey'],
                    'chainsourcetype' => $x['chainsourcetype'],
                    'chainsourceup' => $focus_e['sourceid'],
                    'chainsourcedown' => $x['chainsourcedown'],
                    'chainidealeft' => $x['chainidealeft'],
                    'chainidearight' => $x['chainidearight'],
                    'chainvalue' => $x['chainvalue'],
                ));
            }
        }

        return view_json(array(
            'status' => 1,
            'source_createhandle' => $focus_e['sourcehandle'],
        ));


    }

    function idea_create()
    {

        /*
         *
         * Either creates a IDEA Chain between focus_id & chain_ideaid
         * OR will create a new idea with outcome ideavalue and then Chain it
         * to focus_id (In this case chain_ideaid=0)
         *
         * */

        //Authenticate Member:
        $member_e = source_session(10939, 0, $this->source_session);
        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(10939),
            ));
        } elseif (!isset($_POST['chainsourcetype']) || !isset($_POST['focus_id']) || !isset($_POST['focus_card'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core Variables',
            ));
        } elseif (!isset($_POST['idea_createtext']) || !isset($_POST['chain_ideaid'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing either Idea Outcome OR Follower Idea ID',
            ));
        }

        $validate_ideavalue = validate_ideavalue($_POST['idea_createtext']);
        if (!$validate_ideavalue['status']) {
            //We had an error, return it:
            return view_json($validate_ideavalue);
        }


        if (!$_POST['chain_ideaid'] && view_valid_handle_idea($_POST['idea_createtext'])) {
            foreach ($this->Ideas->read(array(
                'LOWER(ideahashtag)' => strtolower(view_valid_handle_idea($_POST['idea_createtext'])),
            )) as $i) {
                $_POST['chain_ideaid'] = $i['ideaid'];
            }
        }

        $x_i = array();

        if ($_POST['chain_ideaid'] > 0) {
            //Fetch Chain idea to determine idea type:
            $x_i = $this->Ideas->read(array(
                'ideaid' => intval($_POST['chain_ideaid']),
            ));
            if (count($x_i) == 0) {
                //validate Idea:
                return view_json(array(
                    'status' => 0,
                    'message' => 'Idea #' . $_POST['chain_ideaid'] . ' is not active.',
                ));
            }
        }

        //All seems good, go ahead and try to create/chain the Idea:
        return view_json($this->Ideas->create_or_chain($_POST['focus_card'], $_POST['chainsourcetype'], trim($_POST['idea_createtext']), $member_e['sourceid'], $_POST['focus_id'], $_POST['chain_ideaid']));

    }


    function source_create()
    {

        //Auth member and check required variables:
        $source_session = source_session(10939, 0, $this->source_session);

        if (!$source_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(10939),
            ));
        } elseif (intval($_POST['focus__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Following Source',
            ));
        } elseif (!isset($_POST['chainsourcetype'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Source Creation Type',
            ));
        } elseif (!isset($_POST['source_current_id']) || !isset($_POST['source_new_string']) || (intval($_POST['source_current_id']) < 1 && strlen($_POST['source_new_string']) < 1)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Either New Source ID or Source Name',
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
                    'message' => 'Invalid followings Source ID',
                ));
            }

        } else {

            //Validate Source:
            $fetch_o = $this->Sources->read(array(
                'sourceid' => $_POST['focus__id'],
            ));
            if (count($fetch_o) < 1) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid followings Source ID',
                ));
            }

        }


        //Set some variables:
        $_POST['source_new_string'] = trim($_POST['source_new_string']);
        $_POST['chainsourcetype'] = intval($_POST['chainsourcetype']);
        $is_upwards = in_array($_POST['chainsourcetype'], $this->config->item('sourceids___14686'));

        if (!intval($_POST['source_current_id']) && view_valid_handle_source($_POST['source_new_string'])) {
            foreach ($this->Sources->read(array(
                'LOWER(sourcehandle)' => strtolower(substr($_POST['source_new_string'], 1)),
            )) as $e) {
                $_POST['source_current_id'] = $e['sourceid'];
            }
        }
        $adding_to_existing = (intval($_POST['source_current_id']) > 0);

        //Are we adding an existing Source?
        if ($adding_to_existing) {

            //Validate this existing Source:
            $es = $this->Sources->read(array(
                'sourceid' => $_POST['source_current_id'],
            ));

            if (count($es) < 1) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Source @' . $_POST['source_current_id'] . ' is not active',
                ));
            }

            //All good, assign:
            $focus_e = $es[0];

        } else {

            //We are creating a new Source:
            $added_e = $this->Sources->create(array(
                'sourcevalue' => $_POST['source_new_string'],
            ), $source_session['sourceid']);
            if (!$added_e['status']) {
                //We had an error, return it:
                return view_json($added_e);
            } else {
                //Assign new Source:
                $focus_e = $added_e['source_create'];
            }

        }

        //We need to check to ensure this is not a duplicate Chain if adding an existing Source:
        $ur2 = array();

        if ($adding_to_i) {

            //Add Author:
            $ur2 = $this->Chains->create(array(
                'chainsourcecreator' => $source_session['sourceid'],
                'chainsourcetype' => 4983, //Co-Author
                'chainsourceup' => $focus_e['sourceid'],
                'chainidearight' => $fetch_o[0]['ideaid'],
            ));

        } else {

            //Add Up/Down Source:

            //Add Chains only if not previously added by the URL function:
            if ($is_upwards) {

                //Following
                $chainsourcedown = $fetch_o[0]['sourceid'];
                $chainsourceup = $focus_e['sourceid'];
                $chainkey = 0; //Never sort following, only sort followers

            } else {

                //Followers
                $chainsourceup = $fetch_o[0]['sourceid'];
                $chainsourcedown = $focus_e['sourceid'];
                $chainkey = 0;

            }


            $chainvalue = null;

            //Create Chain:
            $ur2 = $this->Chains->create(array(
                'chainsourcecreator' => $source_session['sourceid'],
                'chainsourcetype' => 4230,
                'chainvalue' => $chainvalue,
                'chainsourcedown' => $chainsourcedown,
                'chainsourceup' => $chainsourceup,
                'chainkey' => $chainkey,
            ));
        }

        //Return Source:
        return view_json(array(
            'status' => 1,
            'source_new_echo' => source_view($_POST['chainsourcetype'], array_merge($focus_e, $ur2), null),
        ));

    }

    function source_editor()
    {

        $source_session = source_session(null, 0, $this->source_session);
        $sources___11035 = $this->config->item('sources___11035');
        $sources___42776 = $this->config->item('sources___42776');
        $sources___4592 = $this->config->item('sources___4592'); //Data types
        if (!$source_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['sourceid']) || !isset($_POST['chainid'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core IDs',
            ));
        }

        $es = $this->Sources->read(array(
            'sourceid' => $_POST['sourceid'],
        ));
        if (!count($es)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Source is no longer active',
            ));
        } elseif (!source_access($es[0]['sourcehandle'], 0, $es[0])) {
            return view_json(array(
                'status' => 0,
                'message' => 'You are missing permission to edit this Source',
            ));
        }


        //Fetch dynamic data based on idea type:
        $order_42145 = sort_by(42145);
        $scanned_sources = array();
        $return_inputs = array();
        $input_pointer = 0;
        $profile_header = '';

        //Fetch Source Templates, if any:
        foreach ($this->Chains->read(array(
            'chainsourceup IN (' . join(',', $this->config->item('sourceids___42178')) . ')' => null, //Dynamic Sources
            'chainsourcedown' => $es[0]['sourceid'],
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
        ), array('chainsourceup'), 0, 0, sort_by(42178)) as $source_group) {

            if (in_array($source_group['sourceid'], $scanned_sources)) {
                continue;
            }
            array_push($scanned_sources, $source_group['sourceid']);

            foreach ($this->Chains->read(array(
                'chainsourcedown' => $source_group['sourceid'],
                'chainsourceup IN (' . join(',', $this->config->item('sourceids___42145')) . ')' => null, //Dynamic Input Templates
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
            ), array('chainsourceup'), 0, 0, $order_42145) as $source_template) {

                $profile_header = '<div class="profile_header main__title"><span class="icon-block-sm">' . view_cover($source_template['sourcecover']) . '</span>' . $source_template['sourcevalue'] . '<a href="' . view_memory(42903, 42902) . $source_group['sourcehandle'] . '" target="_blank" data-toggle="tooltip" data-placement="top" title="Because you follow ' . $source_group['sourcevalue'] . '... Click to Open in a New Window"><span class="icon-block-sm">' . view_cover($source_group['sourcecover']) . '</span></a></div>';


                //Load template:
                if (!is_array($this->config->item('sources___' . $source_template['sourceid']))) {
                    //Report Error:
                    log_error('source_sessionditor_load() ERROR: @' . $source_template['sourceid'] . ' is NOT in memory cache', array(
                        'chainsourcedown' => $source_template['sourceid'],
                    ));
                    continue;
                } elseif (in_array($source_template['sourceid'], $scanned_sources)) {
                    continue;
                }
                array_push($scanned_sources, $source_template['sourceid']);


                foreach ($this->config->item('sources___' . $source_template['sourceid']) as $dynamic_sourceid => $m) {

                    //Make sure it's a dynamic input field:
                    if (!in_array($dynamic_sourceid, $this->config->item('sourceids___42179'))) {
                        continue;
                    } elseif (in_array($dynamic_sourceid, $scanned_sources)) {
                        continue;
                    }
                    array_push($scanned_sources, $dynamic_sourceid);

                    //Let's first determine the data type:
                    $data_types = array_intersect($m['m__following'], $this->config->item('sourceids___4592'));

                    if (count($data_types) != 1) {

                        //This is strange, we are expecting 1 match only report this:
                        log_error('Found ' . count($data_types) . ' Data Types (@' . $es[0]['sourceid'] . ') (Expecting exactly 1) for @' . $dynamic_sourceid . ': Check @4592 to see what is wrong', array(
                            'chainsourcedown' => $dynamic_sourceid,
                            'chainsourcecreator' => $source_session['sourceid'],
                        ));
                        continue; //Go to the next dynamic data type

                    } elseif ($input_pointer >= view_memory(6404, 42206)) {
                        //Monitor if we ever reach the maximum:
                        log_error('Dynamic Fields Reach their maximum limit of ' . view_memory(6404, 42206) . '  which may require field expansion', array(
                            'chainsourcedown' => $dynamic_sourceid,
                            'chainsourcecreator' => $source_session['sourceid'],
                            'chainidearight' => $_POST['sourceid'],
                        ));
                    }

                    //We found 1 match as expected:
                    $input_pointer++;
                    foreach ($data_types as $data_type_this) {
                        $data_type = $data_type_this;
                        break;
                    }

                    if (in_array($data_type, $this->config->item('sourceids___42188'))) {

                        //Single or Multiple Choice:
                        array_push($return_inputs, array(
                            'd__id' => $dynamic_sourceid,
                            'd__is_radio' => 1,
                            'd_chainid' => 0,
                            'd__html' => view_instant_select($dynamic_sourceid, $es[0]['sourceid'], 0),
                            'd__value' => ($es[0]['sourceid'] > 0 ? $es[0]['sourceid'] : ''),
                            'd__type_name' => '',
                            'd__placeholder' => '',
                            'd__profile_header' => $profile_header,
                        ));

                    } else {

                        $this_data_type = $this->config->item('sources___' . $data_type);
                        $sources___42179 = $this->config->item('sources___42179'); //Dynamic Input Field
                        $sources___11035 = $this->config->item('sources___11035'); //Encyclopedia

                        //Fetch the current value(s):
                        $counted = 0;
                        $unique_values = array();
                        foreach ($this->Chains->read(array(
                            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
                            'chainsourcedown' => $es[0]['sourceid'],
                            'chainsourceup' => $dynamic_sourceid,
                        ), array('chainsourceup')) as $selected_e) {
                            if (strlen($selected_e['chainvalue']) && !in_array($selected_e['chainvalue'], $unique_values)) {
                                array_push($unique_values, $selected_e['chainvalue']);
                                $counted++;
                                array_push($return_inputs, array(
                                    'd__id' => $dynamic_sourceid,
                                    'd__is_radio' => 0,
                                    'd_chainid' => $selected_e['chainid'],
                                    'd__html' => view_dynamic_headline($dynamic_sourceid, $m, $selected_e),
                                    'd__value' => $selected_e['chainvalue'],
                                    'd__type_name' => html_input_type($data_type),
                                    'd__placeholder' => (strlen($this_data_type[$dynamic_sourceid]['m__message']) ? $this_data_type[$dynamic_sourceid]['m__message'] : $sources___4592[$data_type]['m__title'] . '...'),
                                    'd__profile_header' => $profile_header,
                                ));
                            }
                        }

                        if (!$counted) {
                            foreach ($this->Sources->read(array(
                                'sourceid' => $dynamic_sourceid,
                            )) as $selected_e) {
                                array_push($return_inputs, array(
                                    'd__id' => $dynamic_sourceid,
                                    'd__is_radio' => 0,
                                    'd_chainid' => 0,
                                    'd__html' => view_dynamic_headline($dynamic_sourceid, $m, $selected_e),
                                    'd__value' => '',
                                    'd__type_name' => html_input_type($data_type),
                                    'd__placeholder' => (strlen($this_data_type[$dynamic_sourceid]['m__message']) ? $this_data_type[$dynamic_sourceid]['m__message'] : $sources___4592[$data_type]['m__title'] . '...'),
                                    'd__profile_header' => $profile_header,
                                ));
                            }
                        }
                    }
                }
            }
        }


        //Add universal inputs only if missing bio profiles:
        if (!array_intersect($scanned_sources, $this->config->item('sourceids___42885'))) {
            foreach ($this->Sources->read(array(
                'sourceid IN (' . join(',', $this->config->item('sourceids___42776')) . ')' => null, //Universal Dynamic Inputs
            )) as $selected_e) {
                foreach (array_intersect($sources___42776[$selected_e['sourceid']]['m__following'], $this->config->item('sourceids___4592')) as $data_type) {
                    //Any value?
                    $values = $this->Chains->read(array(
                        'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
                        'chainsourcedown' => $es[0]['sourceid'],
                        'chainsourceup' => $selected_e['sourceid'],
                    ));
                    array_push($return_inputs, array(
                        'd__id' => $selected_e['sourceid'],
                        'd__is_radio' => 0,
                        'd_chainid' => 0,
                        'd__html' => view_dynamic_headline($selected_e['sourceid'], $sources___42776[$selected_e['sourceid']], $selected_e),
                        'd__value' => (isset($values[0]['chainvalue']) && strlen($values[0]['chainvalue']) > 0 ? $values[0]['chainvalue'] : ''),
                        'd__type_name' => html_input_type($data_type),
                        'd__placeholder' => (strlen($sources___42776[$selected_e['sourceid']]['m__message']) ? $sources___42776[$selected_e['sourceid']]['m__message'] : $sources___4592[$data_type]['m__title'] . '...'),
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

    function source_save_edit()
    {

        $source_session = source_session(null, 0, $this->source_session);
        if (!$source_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['save_sourceid'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Coin ID',
            ));
        } elseif (!isset($_POST['save_sourcevalue'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Source Title',
            ));
        } elseif (!isset($_POST['save_sourcehandle'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Source Handle',
            ));
        } elseif (!isset($_POST['save_sourcecover'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Source Cover',
            ));
        } elseif (!isset($_POST['save_chainid']) || !isset($_POST['save_chainvalue'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Chain Data',
            ));
        }


        $es = $this->Sources->read(array(
            'sourceid' => $_POST['save_sourceid'],
        ));
        if (!count($es)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Source Not Active',
            ));
        }


        //Validate Dynamic Inputs:
        $sources___42179 = $this->config->item('sources___42179'); //Dynamic Input Fields

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
            $dynamic_sourceid = $input_parts[1];
            $dynamic_value = trim($input_parts[2]);


            //Required fields must have an input:
            if (in_array($dynamic_sourceid, $this->config->item('sourceids___28239')) && !strlen($dynamic_value) && !in_array($dynamic_sourceid, $this->config->item('sourceids___33331')) && !in_array($dynamic_sourceid, $this->config->item('sourceids___33332'))) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Missing Required Field: ' . $sources___42179[$dynamic_sourceid]['m__title'],
                ));
            }

            //Validate input based on its data type, if provided:
            if (strlen($dynamic_value)) {
                foreach (array_intersect($sources___42179[$dynamic_sourceid]['m__following'], $this->config->item('sourceids___4592')) as $data_type_this) {
                    $data_type_validate = data_type_validate($data_type_this, $dynamic_value, $sources___42179[$dynamic_sourceid]['m__title']);
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
                    'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
                    'chainsourceup' => $dynamic_sourceid,
                    'chainsourcedown' => $es[0]['sourceid'],
                ));
            }


            //Update if needed:
            if (!strlen($dynamic_value)) {

                //Remove Chain if we have one:
                if (count($values) && $dynamic_sourceid != 11035 /* HACK: Summary are key chains that should not be removed */) {
                    $this->Chains->delete($values[0]['chainid'], $source_session['sourceid']);
                }

            } elseif (!count($values)) {

                //Create Chain:
                $this->Chains->create(array(
                    'chainsourcecreator' => $source_session['sourceid'],
                    'chainsourcetype' => 4230,
                    'chainsourceup' => $dynamic_sourceid,
                    'chainsourcedown' => $es[0]['sourceid'],
                    'chainvalue' => $dynamic_value,
                    'chainkey' => number_chainkey($dynamic_value),
                ));

            } elseif ($values[0]['chainvalue'] != $dynamic_value) {

                //Update Chain:
                $this->Chains->update($values[0]['chainid'], array(
                    'chainvalue' => $dynamic_value,
                    'chainsourcecreator' => $source_session['sourceid'],
                ));

            }
        }


        //Validate Source Handle & save if needed:
        if ($es[0]['sourcehandle'] !== trim($_POST['save_sourcehandle'])) {
            $validate_update_handle = validate_update_handle(trim($_POST['save_sourcehandle']), null, $es[0]['sourceid']);
            if (!$validate_update_handle['status']) {
                return view_json(array(
                    'status' => 0,
                    'message' => $validate_update_handle['message'],
                ));
            }
        }

        //Validate Source Title & save if needed:
        $validate_sourcevalue = validate_sourcevalue($_POST['save_sourcevalue']);
        if ($es[0]['sourcevalue'] != trim($_POST['save_sourcevalue'])) {
            if (!$validate_sourcevalue['status']) {
                return view_json(array(
                    'status' => 0,
                    'message' => $validate_sourcevalue['message'],
                ));
            }
            $es[0]['sourcevalue'] = $validate_sourcevalue['sourcevalue_clean'];
        }

        //Save Source Cover if needed:
        if ($es[0]['sourcecover'] != trim($_POST['save_sourcecover'])) {
            //TODO validate sourcecover?
            $es[0]['sourcecover'] = trim($_POST['save_sourcecover']);
        }

        //Update:
        $this->Sources->update($es[0]['sourceid'], array(
            'sourcevalue' => $validate_sourcevalue['sourcevalue_clean'],
            'sourcecover' => trim($_POST['save_sourcecover']),
            'sourcehandle' => trim($_POST['save_sourcehandle']),
        ), $source_session['sourceid']);


        //Sync handle reference:
        $new_handle_string = trim($_POST['save_sourcehandle']);
        if ($es[0]['sourcehandle'] != $new_handle_string) {
            //Update Handles everywhere they are referenced:
            foreach ($this->Chains->read(array(
                'chainsourceup' => $es[0]['sourceid'],
                'chainsourcetype' => 31835, //Source Mention
            ), array('chainidearight')) as $ref) {
                $this->Ideas->update($ref['ideaid'], array(
                    'ideavalue' => str_replace('@' . $es[0]['sourcehandle'], '@' . $new_handle_string, $ref['ideavalue']),
                ), $source_session['sourceid']);
            }
            $es[0]['sourcehandle'] = $new_handle_string;
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
                        'chainsourcecreator' => $source_session['sourceid'],
                    ));
                }
            }
        }


        //Reset member session data if this data belongs to the logged-in member:
        if ($_POST['save_sourceid'] == $source_session['sourceid']) {
            $this->Sources->activate($es[0], true);
        }


        return view_json(array(
            'status' => 1,
            'message' => 'Updated ',
        ));


    }

    function source_select_apply()
    {
        /*
         *
         * Saves the radio selection of some account fields
         *
         * */

        $source_session = source_session(null, 0, $this->source_session);
        if (!$source_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['focus__id']) || intval($_POST['focus__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing followings Source',
            ));
        } elseif (!isset($_POST['selected_sourceid']) || intval($_POST['selected_sourceid']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing selected Source',
            ));
        } elseif (!isset($_POST['down_sourceid']) || !isset($_POST['right_ideaid'])) {
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


        if ($_POST['down_sourceid'] > 0) {

            //Dispatch Any Emails Necessary:
            if (isset($_POST['selected_sourceid']) && intval($_POST['selected_sourceid']) > 0) {
                foreach ($this->Chains->read(array(
                    'chainsourcetype' => 33600, //Draft
                    'chainsourceup' => $_POST['selected_sourceid'],
                ), array('chainidearight'), 0) as $i) {
                    if (count($this->Chains->read(array(
                        'chainsourcetype' => 33600, //Draft
                        'chainsourceup' => 31065, //Choice Update Email Templates
                        'chainidearight' => $i['ideaid'], //Is this the template?
                    )))) {
                        //Found the email template to send:
                        $total_sent = $this->Chains->broadcast(array($source_session), $i, website_setting(0), false);
                        break; //Just the first template match
                    }
                }
            }
        }

        $is_required = in_array($_POST['focus__id'], $this->config->item('sourceids___28239')); //Required Settings

        if (!$_POST['enable_mulitiselect'] || $_POST['was_previously_selected']) {

            //Since this is not a multi-select we want to delete all existing options

            //Fetch all possible answers based on followings Source:
            $query_filters = array(
                'chainsourceup' => $_POST['focus__id'],
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
            );

            if ((!$is_required || $_POST['enable_mulitiselect']) && $_POST['was_previously_selected']) {
                //Just delete this single item, not the other ones:
                $query_filters['chainsourcedown'] = $_POST['selected_sourceid'];
            }

            //List all possible answers:
            $possible_answers = array();
            foreach ($this->Chains->read($query_filters, array('chainsourcedown'), 0, 0) as $answer_e) {
                $stats['total']++;
                array_push($possible_answers, $answer_e['sourceid']);
            }

            //Delete previously selected options:
            if ($_POST['down_sourceid']) {
                $delete_query = $this->Chains->read(array(
                    'chainsourceup IN (' . join(',', $possible_answers) . ')' => null,
                    'chainsourcedown' => $_POST['down_sourceid'],
                    'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
                ));
            } elseif ($_POST['right_ideaid']) {
                $delete_query = $this->Chains->read(array(
                    'chainsourceup IN (' . join(',', $possible_answers) . ')' => null,
                    'chainidearight' => $_POST['right_ideaid'],
                    'chainsourcetype IN (' . join(',', $this->config->item('sourceids___33602')) . ')' => null, //Idea/Source Chains Active
                ));
            }

            foreach ($delete_query as $delete) {
                $stats['deleted']++;
                //Should usually delete a single option:
                $this->Chains->delete($delete['chainid'], $source_session['sourceid']);
            }

        }

        //Add new option if not previously there:
        if ((!$_POST['enable_mulitiselect'] && $is_required) || !$_POST['was_previously_selected']) {
            if ($_POST['down_sourceid']) {
                $stats['added']++;
                $this->Chains->create(array(
                    'chainsourcecreator' => $source_session['sourceid'],
                    'chainsourceup' => $_POST['selected_sourceid'],
                    'chainsourcetype' => 4230,
                    'chainsourcedown' => $_POST['down_sourceid'],
                ));
            } elseif ($_POST['right_ideaid']) {

                if (!count($this->Chains->read(array(
                    'chainsourcetype IN (' . join(',', $this->config->item('sourceids___31919')) . ')' => null, //IDEA AUTHOR
                    'chainsourceup' => $_POST['selected_sourceid'],
                    'chainidearight' => $_POST['right_ideaid'],
                )))) {
                    $stats['added']++;
                    $this->Chains->create(array(
                        'chainsourcecreator' => $source_session['sourceid'],
                        'chainsourcetype' => 4983, //Co-Author
                        'chainsourceup' => $_POST['selected_sourceid'],
                        'chainidearight' => $_POST['right_ideaid'],
                    ));
                }

            }
        }


        //Update Session:
        if ($_POST['down_sourceid'] && $source_session) {
            $this->Sources->activate($source_session, true);
        }


        //All good:
        return view_json(array(
            'status' => 1,
            'message' => 'Updated: ' . print_r($stats, true),
        ));
    }

    function source_authenticate()
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

            $es = $this->Sources->read(array(
                'sourceid' => $_POST['account_id'],
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
            'chainsourcetype' => 44179, //Triggered
            'chainsourceup' => 32078, //Sign In Key
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
            $this->Sources->activate($es[0]);

        } else {

            //Add new account
            $_POST['account_email_phone'] = trim(strtolower($_POST['account_email_phone']));
            $is_email = filter_var($_POST['account_email_phone'], FILTER_VALIDATE_EMAIL);

            //Prep inputs & validate further:
            $acc_email = ($is_email ? $_POST['account_email_phone'] : $_POST['new_account_email']);
            $source_result = $this->Sources->join(strstr($acc_email, '@', true), $acc_email, (!$is_email ? $_POST['account_email_phone'] : ''));
            if (!$source_result['status']) {
                return view_json($source_result);
            }

            $es[0] = $source_result['e'];

        }


        //Set default sign in URL:
        $sign_url = view_memory(42903, 42902) . $es[0]['sourcehandle'];

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

    function source_toggle_follow()
    {

        $source_session = source_session(10939, 0, $this->source_session);
        if (!$source_session) {

            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(10939),
            ));

        } elseif (!isset($_POST['chainsourcecreator']) || !isset($_POST['sourceid']) || !isset($_POST['ideaid']) || !isset($_POST['chainid'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core Variable',
            ));

        } else {

            $_POST['require_writing'] = intval($_POST['require_writing']);

            $already_added = $this->Chains->read(array(
                'chainsourceup' => $_POST['sourceid'],
                'chainsourcedown' => $_POST['chainsourcecreator'],
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
            ), array('chainsourceup'));

            if (count($already_added)) {

                if (intval($_POST['require_writing'])) {

                    //Updating current value if changed:
                    if (strlen($_POST['written_answer']) && trim($_POST['written_answer']) != $already_added[0]['chainvalue']) {
                        $this->Chains->update($already_added[0]['chainid'], array(
                            'chainvalue' => $_POST['written_answer'],
                            'chainsourcecreator' => $source_session['sourceid'],
                        ));
                    } elseif (!strlen($_POST['written_answer'])) {
                        $this->Chains->delete($already_added[0]['chainid'], $source_session['sourceid']);
                    }

                    return view_json(array(
                        'status' => 1,
                        'message' => $_POST['written_answer'],
                    ));

                } else {

                    //Already exists, let's remove:
                    $this->Chains->delete($already_added[0]['chainid'], $source_session['sourceid']);

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

                    foreach ($this->Sources->read(array(
                        'sourceid' => $_POST['sourceid'],
                    )) as $e) {

                        //Does not exist, Add:
                        $this->Chains->create(array(
                            'chainsourceup' => $_POST['sourceid'],
                            'chainsourcedown' => $_POST['chainsourcecreator'],
                            'chainsourcecreator' => $source_session['sourceid'],
                            'chainvalue' => $_POST['written_answer'],
                            'chainsourcetype' => 4230,
                        ));

                        return view_json(array(
                            'status' => 1,
                            'message' => (intval($_POST['require_writing']) ? $_POST['written_answer'] : view_cover($e['sourcecover'], true)),
                        ));

                    }
                }
            }
        }
    }

    function source_verify()
    {

        if (!isset($_POST['account_email_phone'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'missing account details',
            ));
        }

        //Cleanup input email:
        $sources___11035 = $this->config->item('sources___11035'); //Encyclopedia
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
        $chainsourcecreator = 0;
        foreach ($this->Chains->read(array(
            'LOWER(chainvalue)' => strtolower($_POST['account_email_phone']),
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
            'chainsourceup' => (filter_var($_POST['account_email_phone'], FILTER_VALIDATE_EMAIL) ? 3288 : 4783), //Email / Phone
        ), array('chainsourcedown'), 1, 0, array('chainid' => 'ASC')) as $map_e) {
            $u = $map_e;
            $chainsourcecreator = $map_e['sourceid'];
        }

        //Send Sign In Key
        $passcode = rand(1000, 9999);
        $session_key = random_string(55);

        //Append to session:
        $session_data = $this->session->all_userdata();
        $session_data['session_key'] = $session_key;
        $this->session->set_userdata($session_data);

        $html_message = $passcode . ' is your ' . $sources___11035[32078]['m__title'] . ' for your ' . get_domain('m__title') . ' account.';

        if ($valid_email) {

            //Email:
            dispatch_email(array($_POST['account_email_phone']), $html_message, '<div class="line">' . $html_message . '</div>', $chainsourcecreator, array(), 0, 0, false);


        } elseif ($possible_phone) {

            //SMS:
            dispatch_sms($_POST['account_email_phone'], $html_message, 0, array(), 0, 0, false);

        }

        //Log new key:
        $this->Chains->create(array(
            'chainsourcetype' => 44179, //Triggered
            'chainsourceup' => 32078, //Sign In Key
            'chainsourcedown' => $chainsourcecreator, //Member making request
            'chainsourcecreator' => $chainsourcecreator, //Member making request
            'chainidealeft' => intval($_POST['sign_ideaid']),
            'chainvalue' => $_POST['account_email_phone'] . '/' . md5($session_key . $passcode),
        ));

        return view_json(array(
            'status' => 1,
            'account_id' => $chainsourcecreator,
            'valid_email' => ($valid_email ? 1 : 0),
            'account_preview' => ($chainsourcecreator ? '<span class="icon-block">' . view_cover($u['sourcecover'], true) . '</span>' . $u['sourcevalue'] : ''),
            'clean_contact' => $_POST['account_email_phone'],
        ));

    }

    function source_text_update()
    {

        //Authenticate Member:
        $source_session = source_session(null, 0, $this->source_session);
        $sources___12112 = $this->config->item('sources___12112');

        if (!$source_session) {

            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
                'original_val' => '',
            ));

        } elseif (!isset($_POST['sourceid']) || !isset($_POST['cache_sourceid']) || !isset($_POST['idea_createtext'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing core variables',
                'original_val' => '',
            ));

        } elseif ($_POST['cache_sourceid'] == 6197 /* SOURCE FULL NAME */) {

            $es = $this->Sources->read(array(
                'sourceid' => $_POST['sourceid'],
            ));
            if (!count($es)) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Source ID #3',
                    'original_val' => '',
                ));
            }


            $validate_sourcevalue = validate_sourcevalue($_POST['idea_createtext']);
            if (!$validate_sourcevalue['status']) {
                return view_json(array_merge($validate_sourcevalue, array(
                    'original_val' => $es[0]['sourcevalue'],
                )));
            }

            //All good, go ahead and update:
            $this->Sources->update($es[0]['sourceid'], array(
                'sourcevalue' => $validate_sourcevalue['sourcevalue_clean'],
            ), $source_session['sourceid']);

            //Reset member session data if this data belongs to the logged-in member:
            if ($es[0]['sourceid'] == $source_session['sourceid']) {
                //set Session with new data:
                $es[0]['sourcevalue'] = $validate_sourcevalue['sourcevalue_clean'];
                $this->Sources->activate($es[0], true);
            }

            return view_json(array(
                'status' => 1,
            ));

        } else {

            return view_json(array(
                'status' => 0,
                'message' => 'Unknown Update Type [' . $_POST['cache_sourceid'] . ']',
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
        $source_session = source_session(null, 0, $this->source_session);

        if (!isset($_POST['apply_id']) || !isset($_POST['s__id'])) {
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Missing Core Data</div>';
        } else {
            if ($_POST['apply_id'] == 4997) {

                //Source list:
                $counter = sources_query(42373, $_POST['s__id'], 0, false);
                if (!$counter) {
                    echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>No Sources yet</div>';
                } else {
                    echo '<div class="alert" role="alert"><span class="icon-block"><i class="far fa-list"></i></span>Will apply to ' . $counter . ' Source' . search($counter) . ':</div>';
                    echo '<div class="row justify-content">';
                    $ids = array();
                    foreach (sources_query(42373, $_POST['s__id'], 1, true) as $e) {
                        array_push($ids, $e['sourceid']);
                        echo source_view(12274, $e);
                    }
                    echo '</div>';
                    echo '<div class="dotransparent" title="Total of ' . count($ids) . '">' . join(', ', $ids) . '</div>';
                }

            } elseif ($_POST['apply_id'] == 12589) {

                //idea list:
                $is_next = $this->Chains->read(array(
                    'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42345')) . ')' => null, //Active Sequence
                    'chainidealeft' => $_POST['s__id'],
                ), array('chainidearight'), 0, 0, array('chainkey' => 'ASC'));
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

    function chain_page_load()
    {

        $focus_e = array();
        $previous_i = array();

        if (!isset($_POST['focus__node'])) {
            die('Missing input. Refresh and try again.');
        }
        $success = false;

        if ($_POST['focus__node'] == 12274) {

            //SOURCE
            $focus_es = $this->Sources->read(array(
                'sourceid' => $_POST['focus__id'],
            ));
            $focus_e = $focus_es[0];

            foreach (sources_query($_POST['chainsourcetype'], $_POST['focus__id'], $_POST['current_page']) as $s) {
                if (in_array($_POST['chainsourcetype'], $this->config->item('sourceids___11028'))) {
                    echo source_view($_POST['chainsourcetype'], $s);
                    $success = true;
                } else if ($_POST['chainsourcetype']==31777 || $_POST['chainsourcetype']==13550 || in_array($_POST['chainsourcetype'], $this->config->item('sourceids___11020'))) {
                    echo idea_view($_POST['chainsourcetype'], $s, $previous_i, null, $focus_e['sourceid']);
                    $success = true;
                }
            }

        } elseif ($_POST['focus__node'] == 12273) {

            //IDEA
            $previous_is = $this->Ideas->read(array(
                'ideaid' => $_POST['focus__id'],
            ));
            $previous_i = $previous_is[0];

            foreach (ideas_query($_POST['chainsourcetype'], $_POST['focus__id'], $_POST['current_page']) as $s) {
                if (in_array($_POST['chainsourcetype'], $this->config->item('sourceids___11020'))) {
                    echo idea_view($_POST['chainsourcetype'], $s, $previous_i);
                    $success = true;
                } else if ($_POST['chainsourcetype']==31777 || $_POST['chainsourcetype']==13550 || in_array($_POST['chainsourcetype'], $this->config->item('sourceids___11028'))) {
                    echo source_view($_POST['chainsourcetype'], $s);
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
        $source_session = source_session(10939, 0, $this->source_session);

        if (!$source_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(10939),
            ));
        } elseif (!isset($_POST['focus__node']) || !in_array($_POST['focus__node'], $this->config->item('sourceids___28956'))) {
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
            foreach ($this->Chains->read(array(
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42345')) . ')' => null, //Active Sequence
                'chainidealeft' => $_POST['focus__id'],
            ), array('chainidearight'), 0, 0, array('ideavalue' => 'ASC')) as $x) {
                $order++;
                $this->Chains->update($x['chainid'], array(
                    'chainkey' => $order,
                ));
            }
        } elseif ($_POST['focus__node'] == 12274) {
            //Sources reset order
            foreach ($this->Chains->read(array(
                'chainsourceup' => $_POST['focus__id'],
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
            ), array('chainsourcedown'), 0, 0) as $x) {
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

    function idea_discovered()
    {


        $source_session = source_session(null, 0, $this->source_session);
        if (!$source_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['target_ideahashtag']) || !isset($_POST['target_ideaid']) || !isset($_POST['source_submitted_data']) || !isset($_POST['do_skip'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core Data',
            ));
        }

        if (!isset($_POST['selection_ideaid'])) {
            $_POST['selection_ideaid'] = array();
        }
        if (!isset($_POST['source_submitted_data']['idea_createtext'])) {
            $_POST['source_submitted_data']['idea_createtext'] = null;
        }
        if (!isset($_POST['next_idea_data'])) {
            $_POST['next_idea_data'] = array();
        }

        //Discover Focus Idea:
        $primary_ideaid = null;
        foreach ($this->Ideas->read(array(
            'ideaid' => $_POST['source_submitted_data']['ideaid'],
        )) as $focus_i) {

            $input__selection = in_array($focus_i['ideatype'], $this->config->item('sourceids___7712'));
            $input__upload = in_array($focus_i['ideatype'], $this->config->item('sourceids___43004'));
            $skipping_not_allowed = in_array($focus_i['ideatype'], $this->config->item('sourceids___43009'));
            $input__text = in_array($focus_i['ideatype'], $this->config->item('sourceids___43002')) || in_array($focus_i['ideatype'], $this->config->item('sourceids___43003'));
            $total_selected = count($_POST['selection_ideaid']);
            $trying_to_skip = !$skipping_not_allowed &&
                (
                    intval($_POST['do_skip'])
                    || ($input__selection && !$total_selected)
                    || ($input__upload && !strlen($_POST['source_submitted_data']['idea_createtext'])) //TODO Check Media
                    || !strlen($_POST['source_submitted_data']['idea_createtext'])
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

                $is_single_selection = in_array($focus_i['ideatype'], $this->config->item('sourceids___33331'));


                if (!$is_single_selection) {

                    //How about the min selection?
                    if ($idea_required) {
                        foreach ($this->Chains->read(array(
                            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42991')) . ')' => null, //Active Writes
                            'chainidearight' => $focus_i['ideaid'],
                            'chainsourceup' => 40834, //Min Selection
                        ), array(), 1) as $limit) {
                            if (intval($limit['chainvalue']) > 0 && $total_selected < intval($limit['chainvalue'])) {
                                return view_json(array(
                                    'status' => 0,
                                    'message' => 'Select ' . $limit['chainvalue'] . ' or more ideas to go next.',
                                ));
                            }
                        }
                    }

                    //How about max selection?
                    foreach ($this->Chains->read(array(
                        'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42991')) . ')' => null, //Active Writes
                        'chainidearight' => $focus_i['ideaid'],
                        'chainsourceup' => 40833, //Max Selection
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
                    'chainsourcetype' => 7712, //Input Choice
                    'chainsourcecreator' => $source_session['sourceid'],
                    'chainidealeft' => $focus_i['ideaid'],
                ), array('chainidearight')) as $x_selection) {

                    if (in_array($x_selection['ideaid'], $_POST['selection_ideaid'])) {
                        //Current selection is already in the database from before:
                        array_push($already_answered, $x_selection['ideaid']);
                        continue; //Nothing we need to do here...
                    }

                    $this->Chains->delete($x_selection['chainid'], $source_session['sourceid']);

                    //Remove discovery if we can:
                    if (!in_array($x_selection['ideatype'], $this->config->item('sourceids___42905'))) {
                        foreach ($this->Chains->read(array(
                            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___31777')) . ')' => null, //DISCOVERIES
                            'chainidealeft' => $x_selection['ideaid'],
                            'chainsourcecreator' => $source_session['sourceid'],
                        ), array(), 0) as $x_discovery) {
                            $this->Chains->delete($x_discovery['chainid'], $source_session['sourceid']);
                        }
                    }
                }

                //Save New Answers if not already:
                foreach ($_POST['selection_ideaid'] as $answer_ideaid) {
                    if (!in_array($answer_ideaid, $already_answered)) {
                        $this->Chains->create(array(
                            'chainsourcetype' => 7712, //Input Choice
                            'chainsourcecreator' => $source_session['sourceid'],
                            'chainsourceup' => $source_session['sourceid'],
                            'chainidealeft' => $focus_i['ideaid'],
                            'chainidearight' => $answer_ideaid,
                        ));
                    }
                }

            }

            //Issue DISCOVERY/IDEA COIN:
            $completion_status = $this->Chains->idea_discovered(idea_type_discovery($focus_i, $trying_to_skip), $source_session['sourceid'], $_POST['target_ideaid'], $focus_i, $_POST['source_submitted_data'], array(
                'chainkey' => $_POST['source_submitted_data']['ideakey'],
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

                foreach ($this->Ideas->read(array(
                    'ideaid' => $next_idea_data['ideaid'],
                )) as $idea_next) {

                    //Analyze input:
                    $input__required = in_array($idea_next['ideatype'], $this->config->item('sourceids___43039'));
                    if ($input__required) {
                        continue;
                    }
                    $input__text = in_array($idea_next['ideatype'], $this->config->item('sourceids___43002')) || in_array($idea_next['ideatype'], $this->config->item('sourceids___43003'));
                    $input__upload = in_array($idea_next['ideatype'], $this->config->item('sourceids___43004'));
                    $skipping_not_allowed = in_array($idea_next['ideatype'], $this->config->item('sourceids___43009'));


                    //Cleanup phone number:
                    if($input__text && strlen($next_idea_data['idea_createtext']) && !is_numeric($next_idea_data['idea_createtext']) && count($this->Chains->read(array(
                            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42991')) . ')' => null, //Active Writes
                            'chainidearight' => $idea_next['ideaid'],
                            'chainsourceup' => 42181, //Phone
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
                        !strlen($next_idea_data['idea_createtext']) ||
                        ($input__upload && !strlen($next_idea_data['idea_createtext'])) //TODO Check Media
                    );
                    $idea_required = !$skipping_not_allowed && idea_required($idea_next);

                    if ($idea_required && $trying_to_skip) {
                        return view_json(array(
                            'status' => 0,
                            'message' => 'Enter a valid response to '.view_idea_title($idea_next, true).' instead of "'.$next_idea_data['idea_createtext'].'"',
                        ));
                    }

                    //Try to complete:
                    $completion_status = $this->Chains->idea_discovered(idea_type_discovery($idea_next, $trying_to_skip), $source_session['sourceid'], $_POST['target_ideaid'], $idea_next, $next_idea_data, array(
                        'chainkey' => $next_idea_data['ideakey'],
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
                $idea_next = $this->Chains->next_ideas($source_session['sourceid'], $_POST['target_ideahashtag'], $focus_i);
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

    function source_select()
    {

        if (!isset($_POST['focus__id']) || !isset($_POST['o__id']) || !isset($_POST['element_id']) || !isset($_POST['source_createid']) || !isset($_POST['migratehandle']) || !isset($_POST['chainid'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing core data',
            ));
        }

        //Validate migration handles if any:
        $_POST['migratehandle'] = trim($_POST['migratehandle']);
        $first_letter = substr($_POST['migratehandle'], 0, 1);
        if ($first_letter == '@' && strlen($_POST['migratehandle']) > 1) {
            if (!count($this->Sources->read(array(
                'LOWER(sourcehandle)' => strtolower(substr($_POST['migratehandle'], 1)),
            )))) {
                return view_json(array(
                    'status' => 0,
                    'message' => $_POST['migratehandle'] . ' is an invalid Source Handle. Try again if you want to migrate this Source chains or leave the field blank.',
                ));
            }
        } elseif ($first_letter == '#' && strlen($_POST['migratehandle']) > 1) {
            if (!count($this->Ideas->read(array(
                'LOWER(ideahashtag)' => strtolower(substr($_POST['migratehandle'], 1)),
            )))) {
                return view_json(array(
                    'status' => 0,
                    'message' => $_POST['migratehandle'] . ' is an invalid Idea Hashtag. Try again if you want to migrate this idea chains or leave the field blank.',
                ));
            }
        } else {
            $_POST['migratehandle'] = '';
        }

        if (is_array($_POST['o__id'])) {
            $mass_result = array();
            foreach ($_POST['o__id'] as $o__id) {
                array_push($mass_result, $this->Chains->select($_POST['focus__id'], $o__id, $_POST['element_id'], $_POST['source_createid'], $_POST['migratehandle'], $_POST['chainid']));
            }
            return view_json($mass_result);
        } else {
            return view_json($this->Chains->select($_POST['focus__id'], $_POST['o__id'], $_POST['element_id'], $_POST['source_createid'], $_POST['migratehandle'], $_POST['chainid']));
        }

    }


    function chain_delete()
    {

        /*
         *
         * When members indicate they want to stop
         * a IDEA this function saves the changes
         * necessary and delete the idea from their
         * discoveries.
         *
         * */

        $source_session = source_session(null, 0, $this->source_session);

        if (!$source_session) {
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

        //Remove Idea
        $this->Chains->delete($_POST['chainid'], $source_session['sourceid']);

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
        $source_session = source_session(null, 0, $this->source_session);

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

    function chain_graph()
    {

        //See if we have any idea or Source targets to limit our stats:
        $has_handle = isset($_POST['sourcehandle']) && strlen($_POST['sourcehandle']) && $_POST['sourcehandle'];
        $has_hashtag = isset($_POST['ideahashtag']) && strlen($_POST['ideahashtag']) && $_POST['ideahashtag'];

        if ($has_handle) {

            //See stats for this Source:
            $es = $this->Sources->read(array(
                'LOWER(sourcehandle)' => strtolower($_POST['sourcehandle']),
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


        //Count Chains:
        $return_array = array();
        foreach ($this->config->item('sources___33292') as $chainsourcetype1 => $m1) { //Gameplay

            $level1_total = 0;

            if($chainsourcetype1==1309754){

                if ($has_handle) {
                    $void_filter['(chainvoid >0 AND ( chainsourcedown = ' . $es[0]['sourceid'] . ' OR chainsourceup = ' . $es[0]['sourceid'] . ' OR chainsourcecreator = ' . $es[0]['sourceid'] . ' ))'] = null;
                } elseif ($has_hashtag) {
                    $void_filter['(chainvoid >0 AND ( chainidealeft = ' . $is[0]['ideaid'] . ' OR chainidearight = ' . $is[0]['ideaid'] . ' ))'] = null;
                } else {
                    //Void Chains
                    $void_filter = array(
                        'chainvoid >' => 0, //Chains that have been voided
                    );
                }
                $sub_counter = $this->Chains->read($void_filter, array(), 0, 0, array(), 'COUNT(chainid) as totals');
                $return_array[$chainsourcetype1] = intval($sub_counter[0]['totals']);
                continue;
            }

            foreach ($this->config->item('sources___' . $chainsourcetype1) as $chainsourcetype2 => $m2) { //Nodes/Chains

                $source_pinned = source_pinned($chainsourcetype2, true);
                $level2_total = 0;
                if (!is_array($this->config->item('sources___' . $source_pinned)) || !count($this->config->item('sources___' . $source_pinned))) {
                    continue;
                }
                foreach ($this->config->item('sources___' . $source_pinned) as $chainsourcetype3 => $m3) { //Source/Idea/Discovery

                    if ($chainsourcetype2 == 12273) {

                        if ($has_handle) {

                            $sub_counter = $this->Chains->read(array(
                                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___33602')) . ')' => null, //Idea/Source Chains Active
                                'chainsourceup' => $es[0]['sourceid'],
                            ), array('chainidearight'), 0, 0, array(), 'COUNT(chainid) as totals');

                        } elseif ($has_hashtag && count($copy['recursive_idea_ids'])) {

                            //See stats for this idea:
                            $sub_counter = $this->Ideas->read(array(
                                'ideaid IN (' . join(',', $copy['recursive_idea_ids']) . ')' => null,
                            ), 0, 0, array(), 'COUNT(ideaid) as totals');

                        } else {

                            $sub_counter = $this->Ideas->read(array(), 0, 0, array(), 'COUNT(ideaid) as totals');

                        }

                    } elseif ($chainsourcetype2 == 12274) {

                        if ($has_handle) {

                            $sub_counter = $this->Chains->read(array(
                                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
                                'chainsourceup' => $es[0]['sourceid'],
                            ), array('chainsourcedown'), 0, 0, array(), 'COUNT(chainid) as totals');

                        } elseif ($has_hashtag && count($copy['recursive_idea_ids'])) {

                            //See stats for this idea:
                            $sub_counter = $this->Chains->read(array(
                                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___33602')) . ')' => null, //Idea/Source Chains Active
                                'chainidearight IN (' . join(',', $copy['recursive_idea_ids']) . ')' => null,
                            ), array('chainsourceup'), 0, 0, array(), 'COUNT(chainid) as totals');

                        } else {

                            $sub_counter = $this->Sources->read(array(), 0, 0, array(), 'COUNT(sourceid) as totals');

                        }

                    } else {

                        if ($has_handle) {

                            $sub_counter = $this->Chains->read(array(
                                'chainsourcetype' => $chainsourcetype3,
                                '( chainsourcedown = ' . $es[0]['sourceid'] . ' OR chainsourceup = ' . $es[0]['sourceid'] . ' OR chainsourcecreator = ' . $es[0]['sourceid'] . ' )' => null,
                            ), array(), 0, 0, array(), 'COUNT(chainid) as totals');

                        } elseif ($has_hashtag && count($copy['recursive_idea_ids'])) {

                            $sub_counter = $this->Chains->read(array(
                                'chainsourcetype' => $chainsourcetype3,
                                '( chainidealeft IN (' . join(',', $copy['recursive_idea_ids']) . ') OR chainidearight IN (' . join(',', $copy['recursive_idea_ids']) . '))' => null,
                            ), array(), 0, 0, array(), 'COUNT(chainid) as totals');

                        } else {

                            $sub_counter = $this->Chains->read(array(
                                'chainsourcetype' => $chainsourcetype3,
                            ), array(), 0, 0, array(), 'COUNT(chainid) as totals');

                        }

                    }

                    $level2_total += $sub_counter[0]['totals'];
                    $return_array[$chainsourcetype3] = intval($sub_counter[0]['totals']);

                    if ($chainsourcetype2 == 12273 || $chainsourcetype2 == 12274) {
                        break;
                    }

                }

                $level1_total += $level2_total;
                $return_array[$chainsourcetype2] = intval($level2_total);

            }

            $return_array[$chainsourcetype1] = intval($level1_total);

        }
        return view_json(array(
            'status' => 1,
            'return_array' => $return_array,
        ));
    }

}