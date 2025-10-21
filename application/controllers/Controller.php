<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Controller extends CI_Controller
{

    public $user_session;

    function __construct()
    {

        parent::__construct();

        $this->output->enable_profiler(FALSE);

        $this->user_session = user_session();


        date_default_timezone_set('America/Los_Angeles');

        @session_start();

        //AUTO Login user if has cookie?
        $is_ajax = false;
        $user_user = false;
        $memory_detected = is_array($this->config->item('userids___6287')) && count($this->config->item('userids___6287'));
        $first_segment = ($is_ajax && isset($_POST['js_request_uri']) ? $_POST['js_request_uri'] : $this->uri->segment(1));
        $_SERVER['REQUEST_URI'] = (isset($_POST['js_request_uri']) ? $_POST['js_request_uri'] : @$_SERVER['REQUEST_URI']);
        $_SERVER['REQUEST_URI'] = (strlen($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : view_app_chain(4269));
        $user_session = user_session();
        $is_login_verified = isset($_GET['userlogin']) && isset($_GET['hash']) && isset($_GET['time']) && ($_GET['time'] + 604800) > time() && strlen($_GET['userlogin']) && view_hash($_GET['time'] . $_GET['userlogin']) == $_GET['hash'];

        if (
            $memory_detected &&
            !$user_session
            && !array_key_exists(strtolower($first_segment), $this->config->item('handlusers___14582'))
            && (isset($_COOKIE['auth_cookie']) || $is_login_verified) //We can auto login with either method:
        ) {

            if ($is_login_verified) {

                foreach ($this->Users->read(array(
                    'LOWER(userhandle)' => strtolower($_GET['userlogin']),
                )) as $user_session) {

                    //Login:
                    $this->Users->activate($user_session, true);

                    //Log them in:
                    if (!$is_ajax) {
                        header("Location: " . $_SERVER['REQUEST_URI'], true, 307);
                        exit;
                    }

                }

            } elseif (isset($_COOKIE['auth_cookie'])) {

                $user_session = verify_cookie();
                if ($user_session) {
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




    function post_list()
    {
        //Authenticate Member:
        if (!isset($_POST['postid']) || intval($_POST['postid']) < 1 || !isset($_POST['chainusertype']) || intval($_POST['chainusertype']) < 1) {
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Missing core variables</div>';
            return false;
        }



        //Load menu:
        $is = $this->Posts->read(array(
            'postid' => $_POST['postid'],
        ));
        if (!count($is)) {
            return false;
        }


        //Loading App?
        if(in_array(intval($_POST['chainusertype']), $this->config->item('userids___6287'))){
            //yes load the app:
            $_GET['posthashtag'] = $is[0]['posthashtag'];
            echo $this->load(intval($_POST['chainusertype']), 0, $is[0]['postid'], 0, false);
            return false;
        }


        $posts_query = posts_query($_POST['chainusertype'], $_POST['postid'], 1);
        if (!$posts_query) {
            return false;
        }

        $ui = '';
        if ($_POST['chainusertype']==11019) {

            //POST Chain Groups Previous
            $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-' . $_POST['chainusertype'] . '">';
            foreach ($posts_query as $previous_i) {
                $ui .= post_view(11019, $previous_i);
            }
            $ui .= '</div>';

        } elseif ($_POST['chainusertype']==12840) {

            //POST Chain Groups Next
            $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-' . $_POST['chainusertype'] . '">';
            foreach ($posts_query as $next_i) {
                $ui .= post_view($_POST['chainusertype'], $next_i, $is[0]);
            }
            $ui .= '</div>';

        } elseif ($_POST['chainusertype']==31777) {

            $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-' . $_POST['chainusertype'] . '">';
            foreach ($posts_query as $item) {
                $ui .= user_view(31777, $item);
            }
            $ui .= '</div>';

        } elseif ($_POST['chainusertype']==13550) {

            //Users
            $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-' . $_POST['chainusertype'] . '">';
            foreach ($posts_query as $user_ref) {
                $ui .= user_view($_POST['chainusertype'], $user_ref, null);
            }
            $ui .= '</div>';

        }

        echo $ui;

    }


    function load($app_userid = 14563 /* Error if none provided */, $focus_user = 0, $focus_post = 0, $target_post = 0, $standalone = true)
    {

        $memory_detected = is_array($this->config->item('userids___6287')) && count($this->config->item('userids___6287'));
        if (!$memory_detected) {
            //Since we don't have the memory created we must load the app that does so:
            $app_userid = 4527;
        }

        //Any posts passed?
        $users___6287 = $this->config->item('users___6287'); //APP
        $flash_message = false;
        $focus_e = null; //Users
        $focus_i = null; //Posts
        $target_i = null; //Discovery
        $user_http_request = (isset($_SERVER['SERVER_NAME']) ? 1 : 0);


        if ($focus_user && strlen($focus_user) && !isset($_GET['userhandle'])) {
            $_GET['userhandle'] = $focus_user;
        }
        if ($focus_post && strlen($focus_post) && !isset($_GET['posthashtag'])) {
            $_GET['posthashtag'] = $focus_post;
        }
        if (!isset($_GET['userhandle'])) {
            $_GET['userhandle'] = 0;
        }
        if (!isset($_GET['posthashtag'])) {
            $_GET['posthashtag'] = 0;
        }


        if ($target_post && strlen($target_post)) {
            //Verify:
            foreach ($this->Posts->read(array(
                'LOWER(posthashtag)' => strtolower($target_post),
            )) as $post_found) {
                $target_i = $post_found;
            }
        }


        if (strlen($_GET['posthashtag'])) {

            //Validate Focus Post:
            if ($target_i && $_GET['posthashtag'] == view_memory(6404, 4235)) {

                //This is the starting point:
                $_GET['posthashtag'] = $target_post;
                $focus_i = $target_i;

            } else {

                foreach ($this->Posts->read(array(
                    'LOWER(posthashtag)' => strtolower($_GET['posthashtag']),
                )) as $post_found) {
                    $focus_i = $post_found;
                }

            }

            if (!$focus_i) {
                //See if we can find via ID?
                if (is_numeric($_GET['posthashtag'])) {
                    foreach ($this->Posts->read(array(
                        'postid' => $_GET['posthashtag'],
                    )) as $post_found) {
                        $focus_i = $post_found;
                    }
                }
            }

            if ($standalone && $app_userid == 33286 && $focus_i && $focus_i['posthashtag'] !== $_GET['posthashtag']) {
                //Adjust URL Case Sensitive:
                return get_redirected(view_memory(42903, 33286) . $focus_i['posthashtag']);
            }
        }


        if (isset($_GET['userhandle']) && strlen($_GET['userhandle'])) {
            foreach ($this->Users->read(array(
                'LOWER(userhandle)' => strtolower($_GET['userhandle']),
            )) as $user_found) {
                $focus_e = $user_found;
            }
            if (!$focus_e) {
                //See if we need to lookup the ID:
                if (is_numeric($_GET['userhandle'])) {
                    //Maybe its an ID?
                    foreach ($this->Users->read(array(
                        'userid' => $_GET['userhandle'],
                    )) as $user_found) {
                        $focus_e = $user_found;
                    }
                }
            }
            if ($standalone && $app_userid == 42902 && $focus_e && $focus_e['userhandle'] !== $_GET['userhandle']) {
                //Adjust URL Case Sensitive:
                return get_redirected(view_memory(42903, 42902) . $focus_e['userhandle']);
            }
        }


        if ($memory_detected && !in_array($app_userid, $this->config->item('userids___6287'))) {
            //Invalid App:
            return get_redirected(view_memory(42903, 42902) . $users___6287[$app_userid]['m__handle'], '<div class="alert alert-danger" role="alert">@' . $users___6287[$app_userid]['m__handle'] . ' Is not an APP, yet 🤔</div>', false, $standalone);
        } elseif ($memory_detected && !in_array($app_userid, $this->config->item('userids___42922'))) {
            //Validate Required App input:
            if (in_array($app_userid, $this->config->item('userids___42905')) && !$focus_e) {
                return get_redirected(home_url(), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Error: @' . $_GET['userhandle'] . ' is not a valid User user.</div>', false, $standalone);
            } elseif (in_array($app_userid, $this->config->item('userids___44329')) && (!$focus_i || !$target_i)) {
                return get_redirected(home_url(), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Error: Both #' . $_GET['posthashtag'] . ' & #' . $target_post . ' must be valid posts.</div>', false, $standalone);
            } elseif (in_array($app_userid, $this->config->item('userids___42911')) && !$focus_i) {
                return get_redirected(home_url(), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Error: #' . $_GET['posthashtag'] . ' is not a valid post post.</div>', false, $standalone);
            }
        }


        $chainuseroutput = ($focus_e ? $focus_e['userid'] : 0);
        $chainpostoutput = ($focus_i ? $focus_i['postid'] : 0);
        $chainpostinput = ($target_i ? $target_i['postid'] : 0);

        //Run App
        $user_session = false;

        if ($memory_detected && in_array($app_userid, $this->config->item('userids___42920'))) {
            boost_power();
        }

        if ($memory_detected && $user_http_request) {

            //Needs superpowers?
            $user_session = user_session();

            if ($user_session && !isset($user_session['userid']) && $app_userid!=7291) {
                //Old user, must log out:
                header("Location: /logout", true, 301);
                return false;
            }

            //Auto Login?
            if (isset($_GET['hash']) && isset($_GET['time']) && $focus_e) {

                //Validate Hash:
                if ($_GET['hash'] == view_hash($_GET['time'] . $focus_e['userhandle'])) {

                    if ($focus_i) {
                        if (post_is_startable($focus_i)) {
                            $flash_message = '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="far fa-play"></i></span>You have started discovering this post. Scroll to the bottom & go next to continue.</div>';
                        } else {
                            $this->Chains->post_discovered(4559, $focus_e['userid'], ($target_i ? $target_i['postid'] : 0), $focus_i);

                            //Inform user of changes:
                            $flash_message = '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="far fa-check-circle"></i></span>Posts has been discovered</div>';
                        }
                    }

                    //If not logged in, log them in:
                    if (!$user_session) {
                        $session_data = $this->Users->activate($user_session, true);
                    }

                }
            }
        }


        //Cache App?
        $ui = null;
        $new_cache = false;
        $cache_chaintime = null;
        $chainusercreator = ($user_http_request ? ($user_session ? $user_session['userid'] : 14068 /* GUEST */) : 7274 /* CRON JOB */);
        $user_access = user_access(null, $focus_e['userid'], $focus_e);
        $post_access = post_access(null, $focus_i['postid'], $focus_i);
        $target_post_access = post_access(null, $target_i['postid'], $target_i);

        //MEMBER REDIRECT?
        if ($user_http_request && $memory_detected) {

            //Missing App, User or Post Access?
            $missing_access = false; //Assume they have access
            $superpowers_required = array_intersect($this->config->item('userids___10957'), $users___6287[$app_userid]['m__following']);
            if ($standalone && $user_session && in_array($app_userid, $this->config->item('userids___14639'))) {
                //Should redirect them:
                return get_redirected(view_memory(42903, 42902) . $user_session['userhandle']);
            } elseif (!$user_session && in_array($app_userid, $this->config->item('userids___14740'))) {
                //Should redirect them:
                $missing_access = 'Login or register a free account to continue.';
            } elseif (count($superpowers_required) && !user_session(end($superpowers_required))) {
                $users___10957 = $this->config->item('users___10957');
                $missing_access = 'Error: You Cannot Access ' . $users___6287[$app_userid]['m__name'] . ' as it requires the superpower of ' . $users___10957[end($superpowers_required)]['m__name'] . '.';
            } elseif ($focus_e && !$user_access) {
                $missing_access = 'Error: You Cannot Access @' . $focus_e['userhandle'] . ' due to Privacy Settings.';
            } elseif ($focus_i && !$post_access) {
                $missing_access = 'Error: You Cannot Access Focus #' . $focus_i['posthashtag'] . ' due to Privacy Settings.';
            } elseif ($target_i && !$target_post_access) {
                $missing_access = 'Error: You Cannot Access Target #' . $target_i['posthashtag'] . ' due to Privacy Settings.';
            }

            if ($missing_access) {
                //Redirect:
                return get_redirected((!$user_session ? view_app_chain(4269) . '?url=' . urlencode($_SERVER['REQUEST_URI']) : home_url()), '<div class="alert alert-warning" role="alert">' . $missing_access . '</div>', false, $standalone);
            }
        }


        if ($memory_detected) {

            if (in_array($app_userid, $this->config->item('userids___14599')) && !in_array($app_userid, $this->config->item('userids___12741'))) {

                if (!isset($_GET['reset_cache'])) {
                    //Fetch Most Recent Cache:
                    foreach ($this->Chains->read(array(
                        'chainuserdomain' => website_setting(0),
                        'chainusertype' => 44176, //User View
                        'chainuserinput' => 14599, //Cache App
                        'chainuseroutput' => $app_userid,
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
            $title .= view_post_title($focus_i, true) . ' | ';
        }
        if ($target_i) {
            $title .= view_post_title($target_i, true) . ' | ';
        }
        if ($focus_e) {
            $title .= $focus_e['username'] . ' @' . $focus_e['userhandle'] . ' | ';
        }
        if (!$title) {
            //Append app name since no title:
            $title .= $users___6287[$app_userid]['m__name'] . ' | ';
        }
        //Always Append Website at the end:
        $title .= ($memory_detected ? get_domain('m__name') : 'Loading Memory');


        $view_input = array(
            'app_userid' => $app_userid,
            'chainusercreator' => $chainusercreator,
            'user_session' => $user_session,
            'user_http_request' => $user_http_request,
            'memory_detected' => $memory_detected,
            'standalone' => ( $standalone ? 1 : 0 ),

            'focus_e' => $focus_e,
            'focus_i' => $focus_i,
            'target_i' => $target_i,

            '$user_access' => $user_access,
            '$post_access' => $post_access,
            '$target_post_access' => $target_post_access,

            'title' => $title,
            'flash_message' => $flash_message,
        );

        if (!$ui) {
            //Prep view:
            $app_userr = ($memory_detected ? strtolower($users___6287[$app_userid]['m__handle']) : 'memory');
            $raw_app = $this->load->view($app_userr, $view_input, true);
            $ui .= $raw_app;
        }


        if ($new_cache) {
            $cache_x = $this->Chains->create(array(
                'chainuserdomain' => website_setting(0),
                'chainusertype' => 44176, //User View
                'chainuserinput' => 14599, //Cache App
                'chainuseroutput' => $app_userid,
                'chainusercreator' => $chainusercreator,
                'chainvalue' => $ui,
                'chainpostinput' => $chainpostinput,
                'chainpostoutput' => $chainpostoutput,
            ));
        }


        //App title?
        if ($standalone && $memory_detected && in_array($app_userid, $this->config->item('userids___42928'))) {
            $ui = '<h1><span style="font-size:2em !important;">' . $users___6287[$app_userid]['m__cover'] . '</span> ' . $users___6287[$app_userid]['m__name'] . '</h1>' . $ui;
        }


        //Check to ensure they have started:
        if ($standalone && $app_userid == 30795 && $target_i && $focus_i && $user_session && $target_i['posthashtag'] == $focus_i['posthashtag']) {

            //Starting point, make sure all good:
            if (!post_is_startable($target_i)) {

                //Not a valid starting point:
                return get_redirected(home_url(), '<div class="alert alert-warning" role="alert">#' . $target_i['posthashtag'] . ' is not an active starting point.</div>');

            } elseif (!count($this->Chains->read(array(
                'LOWER(posthashtag)' => strtolower($target_i['posthashtag']),
                'chainusercreator' => $user_session['userid'],
                'chainusertype IN (' . join(',', $this->config->item('userids___31777')) . ')' => null, //DISCOVERIES
            ), array('chainpostinput')))) {

                //Not yet started, add to their starting point:
                $completion_status = $this->Chains->post_discovered(4235, $user_session['userid'], 0, $target_i);

                //Now return next post:
                $next__url = $this->Chains->next_posts($user_session['userid'], $target_i['posthashtag'], $target_i);

                if ($next__url) {
                    //Go Next:
                    return get_redirected(view_memory(42903, 30795) . $target_i['posthashtag'] . '/' . $next__url);
                }

            }

        }


        //Delivery App
        if (!$memory_detected) {

            echo $ui;

        } else {

            if (in_array($app_userid, $this->config->item('userids___12741'))) {

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

        if (isset($_POST['user_string']) && strlen($_POST['user_string']) > 1 && in_array(substr($_POST['user_string'], 0, 1), array('#', '@'))) {
            if (substr($_POST['user_string'], 0, 1) == '#') {
                foreach ($this->Posts->read(array(
                    'LOWER(posthashtag)' => strtolower(substr($_POST['user_string'], 1)),
                )) as $i) {
                    echo post_view(31777, $i);
                    return true;
                }
            } elseif (substr($_POST['user_string'], 0, 1) == '@') {
                foreach ($this->Users->read(array(
                    'LOWER(userhandle)' => strtolower(substr($_POST['user_string'], 1)),
                )) as $e) {
                    echo user_view(42287, $e);
                    return true;
                }
            }

            //Did not find, had error:
            echo '<div class="alert alert-danger" role="alert">Could not find ' . $_POST['user_string'] . '</div>';
            return false;
        }

        //Did not find, had error:
        echo '<div class="alert alert-danger" role="alert">Missing user_string variable</div>';
        return false;

    }






    function add_media()
    {

        $user_session = user_session(null, 0, $this->user_session);
        if (!$user_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['postid']) || !isset($_POST['chainid'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core IDs',
            ));
        }

        //$dd = add_media($uploaded_media);

        $postid = 0; //New post
        $created_postid = 0;

        if (!$_POST['postid']) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Post Media ID!',
            ));
        }

        $is = $this->Posts->read(array(
            'postid' => $_POST['postid'],
        ));
        if (!count($is)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Post is no longer active',
            ));
        } elseif (!post_access($is[0]['posthashtag'], 0, $is[0])) {
            return view_json(array(
                'status' => 0,
                'message' => 'You are missing permission to edit this post',
            ));
        }


        $postid = intval($is[0]['postid']);

        //Fetch dynamic data based on post type:
        $return_inputs = array();
        $users___42179 = $this->config->item('users___42179'); //Dynamic Input Fields
        $users___11035 = $this->config->item('users___11035'); //Encyclopedia

        foreach ($this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
            'chainpostoutput' => $is[0]['postid'],
            'chainuserinput IN (' . join(',', $this->config->item('userids___4737')) . ')' => null, //Post Types
        )) as $post_type) {

            foreach (array_intersect($this->config->item('userids___' . $post_type['chainuserinput']), $this->config->item('userids___42179')) as $dynamic_userid) {

                $superpowers_required = array_intersect($this->config->item('userids___10957'), $users___42179[$dynamic_userid]['m__following']);
                if (count($superpowers_required) && !user_session(end($superpowers_required), 0, $this->user_session)) {
                    continue;
                }

                //Let's first determine the data type:
                $data_types = array_intersect($users___42179[$dynamic_userid]['m__following'], $this->config->item('userids___4592'));

                if (count($data_types) != 1) {
                    //This is strange, we are expecting 1 match only report this:
                    log_error('Found ' . count($data_types) . ' Data Types (Expecting exactly 1) for @' . $dynamic_userid . ': Check @4592 to see what is wrong', array(
                        'chainusercreator' => $user_session['userid'],
                        'chainuseroutput' => $dynamic_userid,
                        'chainpostoutput' => $postid,
                    ));
                    continue; //Go to the next dynamic data type
                }

                //We found 1 match as expected:
                foreach ($data_types as $data_type_this) {
                    $data_type = $data_type_this;
                    break;
                }

                if (in_array($data_type, $this->config->item('userids___42188'))) {

                    //Single or Multiple Choice:
                    array_push($return_inputs, array(
                        'd__id' => $dynamic_userid,
                        'd__is_radio' => 1,
                        'd_chainid' => 0,
                        'd__html' => view_instant_select($dynamic_userid, 0, $postid),
                        'd__value' => ($postid > 0 ? $postid : ''),
                        'd__type_name' => '',
                        'd__placeholder' => '',
                        'd__profile_header' => '',
                    ));

                } else {

                    $this_data_type = $this->config->item('users___' . $data_type);
                    $users___4592 = $this->config->item('users___4592'); //Data types
                    $users___42179 = $this->config->item('users___42179'); //Dynamic Input Field
                    $users___11035 = $this->config->item('users___11035'); //Encyclopedia

                    //Fetch the current value:
                    $counted = 0;
                    $unique_values = array();
                    if ($postid > 0) { //Must have an original ID to possibly have a value
                        foreach ($this->Chains->read(array(
                            'chainusertype IN (' . join(',', $this->config->item('userids___42252')) . ')' => null, //Plain Chain
                            'chainpostoutput' => $postid,
                            'chainuserinput' => $dynamic_userid,
                        ), array('chainuserinput')) as $selected_e) {
                            if (strlen($selected_e['chainvalue']) && !in_array($selected_e['chainvalue'], $unique_values)) {
                                $counted++;
                                array_push($unique_values, $selected_e['chainvalue']);
                                array_push($return_inputs, array(
                                    'd__id' => $dynamic_userid,
                                    'd__is_radio' => 0,
                                    'd_chainid' => $selected_e['chainid'],
                                    'd__html' => view_dynamic_headline($dynamic_userid, $users___42179[$dynamic_userid], $selected_e),
                                    'd__value' => $selected_e['chainvalue'],
                                    'd__type_name' => html_input_type($data_type),
                                    'd__placeholder' => (strlen($this_data_type[$dynamic_userid]['m__message']) ? $this_data_type[$dynamic_userid]['m__message'] : $users___4592[$data_type]['m__name'] ),
                                    'd__profile_header' => '',
                                ));
                            }
                        }
                    }


                    if (!$counted) {
                        foreach ($this->Users->read(array(
                            'userid' => $dynamic_userid,
                        )) as $selected_e) {
                            array_push($return_inputs, array(
                                'd__id' => $dynamic_userid,
                                'd__is_radio' => 0,
                                'd_chainid' => 0,
                                'd__html' => view_dynamic_headline($dynamic_userid, $users___42179[$dynamic_userid], $selected_e),
                                'd__value' => '',
                                'd__type_name' => html_input_type($data_type),
                                'd__placeholder' => (strlen($this_data_type[$dynamic_userid]['m__message']) ? $this_data_type[$dynamic_userid]['m__message'] : $users___4592[$data_type]['m__name'] ),
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
            'created_postid' => $created_postid,
        );

        //Return everything we found:
        return view_json($return_array);

    }

    function post_edit()
    {

        $user_session = user_session(null, 0, $this->user_session);
        if (!$user_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['postid']) || !isset($_POST['chainid'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core IDs',
            ));
        }

        $created_postid = 0;

        if (!$_POST['postid']) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Post ID!',
            ));
        }

        $is = $this->Posts->read(array(
            'postid' => $_POST['postid'],
        ));
        if (!count($is)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Post is no longer active',
            ));
        } elseif (!post_access($is[0]['posthashtag'], 0, $is[0])) {
            return view_json(array(
                'status' => 0,
                'message' => 'You are missing permission to edit this post',
            ));
        }

        $postid = intval($is[0]['postid']);

        //Fetch dynamic data based on post type:
        $return_inputs = array();
        $users___42179 = $this->config->item('users___42179'); //Dynamic Input Fields
        $users___11035 = $this->config->item('users___11035'); //Encyclopedia


        foreach ($this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
            'chainpostoutput' => $is[0]['postid'],
            'chainuserinput IN (' . join(',', $this->config->item('userids___4737')) . ')' => null, //Post Types
        )) as $post_type) {
            foreach (array_intersect($this->config->item('userids___' . $post_type['chainuserinput']), $this->config->item('userids___42179')) as $dynamic_userid) {

                $superpowers_required = array_intersect($this->config->item('userids___10957'), $users___42179[$dynamic_userid]['m__following']);
                if (count($superpowers_required) && !user_session(end($superpowers_required), 0, $this->user_session)) {
                    continue;
                }

                //Let's first determine the data type:
                $data_types = array_intersect($users___42179[$dynamic_userid]['m__following'], $this->config->item('userids___4592'));

                if (count($data_types) != 1) {
                    //This is strange, we are expecting 1 match only report this:
                    log_error('Found ' . count($data_types) . ' Data Types (Expecting exactly 1) for @' . $dynamic_userid . ': Check @4592 to see what is wrong', array(
                        'chainusercreator' => $user_session['userid'],
                        'chainuseroutput' => $dynamic_userid,
                        'chainpostoutput' => $postid,
                    ));
                    continue; //Go to the next dynamic data type
                }

                //We found 1 match as expected:
                foreach ($data_types as $data_type_this) {
                    $data_type = $data_type_this;
                    break;
                }

                if (in_array($data_type, $this->config->item('userids___42188'))) {

                    //Single or Multiple Choice:
                    array_push($return_inputs, array(
                        'd__id' => $dynamic_userid,
                        'd__is_radio' => 1,
                        'd_chainid' => 0,
                        'd__html' => view_instant_select($dynamic_userid, 0, $postid),
                        'd__value' => ($postid > 0 ? $postid : ''),
                        'd__type_name' => '',
                        'd__placeholder' => '',
                        'd__profile_header' => '',
                    ));

                } else {

                    $this_data_type = $this->config->item('users___' . $data_type);
                    $users___4592 = $this->config->item('users___4592'); //Data types
                    $users___42179 = $this->config->item('users___42179'); //Dynamic Input Field
                    $users___11035 = $this->config->item('users___11035'); //Encyclopedia

                    //Fetch the current value:
                    $counted = 0;
                    $unique_values = array();
                    if ($postid > 0) { //Must have an original ID to possibly have a value.
                        foreach ($this->Chains->read(array(
                            'chainusertype IN (' . join(',', $this->config->item('userids___42252')) . ')' => null, //Plain Chain
                            'chainpostoutput' => $postid,
                            'chainuserinput' => $dynamic_userid,
                        ), array('chainuserinput')) as $selected_e) {
                            if (strlen($selected_e['chainvalue']) && !in_array($selected_e['chainvalue'], $unique_values)) {
                                $counted++;
                                array_push($unique_values, $selected_e['chainvalue']);
                                array_push($return_inputs, array(
                                    'd__id' => $dynamic_userid,
                                    'd__is_radio' => 0,
                                    'd_chainid' => $selected_e['chainid'],
                                    'd__html' => view_dynamic_headline($dynamic_userid, $users___42179[$dynamic_userid], $selected_e),
                                    'd__value' => $selected_e['chainvalue'],
                                    'd__type_name' => html_input_type($data_type),
                                    'd__placeholder' => (strlen($this_data_type[$dynamic_userid]['m__message']) ? $this_data_type[$dynamic_userid]['m__message'] : $users___4592[$data_type]['m__name'] ),
                                    'd__profile_header' => '',
                                ));
                            }
                        }
                    }


                    if (!$counted) {
                        foreach ($this->Users->read(array(
                            'userid' => $dynamic_userid,
                        )) as $selected_e) {
                            array_push($return_inputs, array(
                                'd__id' => $dynamic_userid,
                                'd__is_radio' => 0,
                                'd_chainid' => 0,
                                'd__html' => view_dynamic_headline($dynamic_userid, $users___42179[$dynamic_userid], $selected_e),
                                'd__value' => '',
                                'd__type_name' => html_input_type($data_type),
                                'd__placeholder' => (strlen($this_data_type[$dynamic_userid]['m__message']) ? $this_data_type[$dynamic_userid]['m__message'] : $users___4592[$data_type]['m__name'] ),
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
            'created_postid' => $created_postid,
        );

        //Return everything we found:
        return view_json($return_array);

    }


    function post_delete()
    {

        $user_session = user_session(null, 0, $this->user_session);
        $migrationid = 0;

        if (!$user_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['postid']) || !isset($_POST['focus__id']) || !isset($_POST['migrateuser'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core IDs',
            ));
        } elseif (post_access(null, $_POST['postid']) < 3) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Access to delete this post',
            ));
        } elseif (strlen($_POST['migrateuser']) > 1) {
            $valid_user = $this->Posts->read(array(
                'postid !=' => $_POST['postid'],
                'LOWER(posthashtag)' => strtolower(str_replace('#', '', $_POST['migrateuser'])),
            ));
            if (!count($valid_user)) {
                return view_json(array(
                    'status' => 0,
                    'message' => $_POST['migrateuser'] . ' is not an active post',
                ));
            }
            $migrationid = $valid_user[0]['postid'];
        }

        $delete_redirect = '';
        $delete_element = '';
        //Determine what to do after deleted:
        if ($_POST['postid'] == $_POST['focus__id']) {

            //Find Published Followings:
            foreach ($this->Chains->read(array(
                'chainusertype IN (' . join(',', $this->config->item('userids___42345')) . ')' => null, //Active Sequence
                'chainpostoutput' => $_POST['postid'],
            ), array('chainpostinput'), 1) as $previous_i) {
                $delete_redirect = view_memory(42903, 33286) . $previous_i['posthashtag'];
            }

            //If not found, find active followings:
            if (!$delete_redirect) {
                foreach ($this->Chains->read(array(
                    'chainusertype IN (' . join(',', $this->config->item('userids___42345')) . ')' => null, //Active Sequence
                    'chainpostoutput' => $_POST['postid'],
                ), array('chainpostinput'), 1) as $previous_i) {
                    $delete_redirect = view_memory(42903, 33286) . $previous_i['posthashtag'];
                }
            }

            //If still not found, go to main page if no followings found:
            if (!$delete_redirect) {
                foreach ($this->Posts->read(array(
                    'postid' => $_POST['postid'],
                )) as $i) {
                    $delete_redirect = view_memory(42903, 33286) . $i['posthashtag'];
                }
            }

        } else {

            //Just delete from UI using JS:
            $delete_element = '.s__12273_' . $_POST['postid'];

        }

        //Delete all Chains:
        $chains_removed = $this->Posts->delete($_POST['postid'], $user_session['userid'], $migrationid);

        return view_json(array(
            'status' => ($chains_removed > 0 ? 1 : 0),
            'message' => ($chains_removed > 0 ? 'Post successfully removed' : 'Error in removing the post'),
            'delete_redirect' => $delete_redirect,
            'delete_element' => $delete_element,
        ));

    }

    function user_delete()
    {

        $user_session = user_session(null, 0, $this->user_session);
        $migrationid = 0;

        if (!$user_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['userid']) || !isset($_POST['focus__id']) || !isset($_POST['migrateuser'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core IDs',
            ));
        } elseif (user_access(null, $_POST['userid']) < 3) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Access to delete this post',
            ));
        } elseif (strlen($_POST['migrateuser']) > 1) {
            $valid_user = $this->Users->read(array(
                'userid !=' => $_POST['userid'],
                'LOWER(userhandle)' => strtolower(str_replace('@', '', $_POST['migrateuser'])),
            ));
            if (!count($valid_user)) {
                return view_json(array(
                    'status' => 0,
                    'message' => $_POST['migrateuser'] . ' is not an active user',
                ));
            }
            $migrationid = $valid_user[0]['userid'];
            if (!count($this->Users->read(array('userid' => $migrationid)))) {
                return array(
                    'status' => 0,
                    'message' => $_POST['migrateuser'] . ' is not a valid User',
                );
            }
        } elseif (in_array($_POST['userid'], $this->config->item('userids___14870'))) {
            return array(
                'status' => 0,
                'message' => 'Cannot Delete an active @chainuserdomain - Unchain, update @memory and try again',
            );
        } elseif (!count($this->Users->read(array('userid' => $_POST['userid'])))) {
            return array(
                'status' => 0,
                'message' => $_POST['userid'] . ' is not a valid ID',
            );
        }


        //Determine what to do after deleted:
        $delete_redirect = '';
        $delete_element = '';

        if ($_POST['userid'] == $_POST['focus__id']) {

            //Find Published Followings:
            foreach ($this->Chains->read(array(
                'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                'chainuseroutput' => $_POST['userid'],
            ), array('chainuserinput'), 1, 0, array('username' => 'DESC')) as $up_e) {
                $delete_redirect = view_memory(42903, 42902) . $up_e['userhandle'];
            }

            //If still not found, go to main page if no followings found:
            if (!$delete_redirect) {
                foreach ($this->Users->read(array('userid' => $_POST['userid'])) as $e2) {
                    $delete_redirect = view_memory(42903, 42902) . e2['userhandle'];
                }
            }
        } else {

            //Just delete from UI using JS:
            $delete_element = '.s__12274_' . $_POST['userid'];

        }

        //Delete all Chains:
        $chains_removed = $this->Users->delete($_POST['userid'], $user_session['userid'], $migrationid);

        if(!$chains_removed['status']){
            return view_json(array(
                'status' => 1,
                'message' => 'User successfully removed',
                'delete_redirect' => $delete_redirect,
                'delete_element' => $delete_element,
            ));
        }

        return view_json(array(
            'status' => 1,
            'message' => 'User successfully removed',
            'delete_redirect' => $delete_redirect,
            'delete_element' => $delete_element,
        ));

    }

    function post_update()
    {

        $user_session = user_session(null, 0, $this->user_session);
        if (!$user_session) {

            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));

        } elseif (!isset($_POST['save_postmessage'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Post',
            ));

        } elseif (!isset($_POST['focus__node']) || !isset($_POST['focus__id'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing focus Card/ID',
            ));

        } elseif (!isset($_POST['save_posthashtag'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing post',
            ));

        } elseif (!isset($_POST['save_postid'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Post ID',
            ));

        } elseif (!isset($_POST['next_postid'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Next/Previous ID',
            ));

        } elseif (!isset($_POST['save_chainid']) || !isset($_POST['save_chainvalue'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Chain Data',
            ));

        } elseif (strlen($_POST['save_postmessage']) > view_memory(6404, 4736)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Post message must be less than ' . view_memory(6404, 4736) . ' characters.',
            ));
        }


        if($_POST['save_postid'] > 0){

            $focus__node = ($_POST['focus__node'] == 12273 && $_POST['focus__id'] == $_POST['save_postid']);
            $is = $this->Posts->read(array(
                'postid' => $_POST['save_postid'],
            ));
            if (!count($is)) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Post Not Valid',
                ));
            }

            $update_array = array();

            if (strtolower($is[0]['posthashtag']) !== strtolower(trim($_POST['save_posthashtag']))) {

                $validate_update_user = validate_update_user($_POST['save_posthashtag'], $is[0]['postid'], null);
                if (!$validate_update_user['status']) {
                    return view_json(array(
                        'status' => 0,
                        'message' => $validate_update_user['message'],
                    ));
                }
                $update_array['posthashtag'] = $_POST['save_posthashtag'];
            }

            if ($is[0]['postmessage'] !== trim($_POST['save_postmessage'])) {
                if (!strlen(trim($_POST['save_postmessage']))) {
                    //Since we do not have media, we must have a message:
                    return view_json(array(
                        'status' => 0,
                        'message' => 'Write something to save.',
                    ));
                }
                $update_array['postmessage'] = $_POST['save_postmessage'];
            }

            //Update new post fields:
            $statusupdate = -1;
            if(count($update_array)){
                $statusupdate = $this->Posts->update($is[0]['postid'], $update_array, $user_session['userid']);
            }


            if (isset($update_array['posthashtag'])) {

                //Now Users everywhere they are referenced:
                foreach ($this->Chains->read(array(
                    'chainpostoutput' => $is[0]['postid'],
                    'chainusertype IN (' . join(',', $this->config->item('userids___4486')) . ')' => null, //Ideas
                ), array('chainpostinput')) as $ref) {

                    //Redo their cache:
                    $post_index = post_index($ref['postmessage'], $ref['postid'], $user_session['userid'], $is[0]['posthashtag'], $update_array['posthashtag']);

                    $update_columns = array();

                    if($update_columns['postmessage']!=$post_index['postmessage']){
                        $update_columns['postmessage'] = $post_index['postmessage'];
                    }
                    if($update_columns['postdiscover']!=$post_index['postdiscover']){
                        $update_columns['postdiscover'] = $post_index['postdiscover'];
                    }
                    if($update_columns['postedit']!=$post_index['postedit']){
                        $update_columns['postedit'] = $post_index['postedit'];
                    }

                    if(count($update_columns)){
                        //We should update:
                        $this->db->where('postid', $ref['postid']);
                        $this->db->update('posts', $update_columns);
                    }
                }

            }

        } else {

            $focus__node = false;

            //Create new post
            $post_new = $this->Posts->create(array(
                'posthashtag' => $_POST['save_posthashtag'],
                'postmessage' => $_POST['save_postmessage'],
            ), $user_session['userid']);

            $_POST['save_postid'] = intval($post_new['post_create']['postid']);

        }

        foreach ($this->Posts->read(array(
            'postid' => $_POST['save_postid'],
        )) as $new_i) {

            //Update Search Index:
            update_search(12273, $new_i['postid']);

            $discovery_mode = ( isset($_POST['save_discoverymode']) && intval($_POST['save_discoverymode']) );

            return view_json(array(
                'status' => 1,
                'return_postdiscover_chains' => view_post_value($new_i, $user_session['userid'], $focus__node, $discovery_mode, $discovery_mode),
                'return_postdiscover_full' => post_view($_POST['focus_group'], $new_i),
                'save_postid' => $new_i['postid'],
                'save_postmessage' => trim($_POST['save_postmessage']),
                'redirect_post' => ( $focus__node ? : ( isset($new_i['posthashtag']) ? view_memory(42903, 33286) . $new_i['posthashtag'] : null) ),
                'message' => 'Success',
                'statusupdate' => $statusupdate,
                'update_array' => $update_array,
                'post_index' => post_index($_POST['save_postmessage'], $new_i['postid'], 1, $_POST['save_posthashtag']),
        ));

        }

    }



    function post_cover()
    {

        if (!isset($_POST['postid']) || !isset($_POST['chainusertype']) || !isset($_POST['first_segment']) || !isset($_POST['counter'])) {
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Missing core variables</div>';
        } else {

            $discover_chainusertype = discover_chainusertype();

            $ui = '';
            $listed_items = 0;
            if ($_POST['chainusertype']==13550 || $_POST['chainusertype']==31777) {

                //USERS
                $users___4593 = $this->config->item('users___4593'); //Chain Types
                $current_userhandle = view_valid_user_user($_POST['first_segment']);
                foreach (posts_query($_POST['chainusertype'], $_POST['postid'], 1, false) as $user_session) {
                    if (isset($user_session['userid'])) {
                        $ui .= view_card(view_memory(42903, 42902) . $user_session['userhandle'], $current_userhandle && $user_session['userhandle'] == $current_userhandle, $user_session['chainusertype'], view_cover($user_session['usercover'], true), $user_session['username'], $user_session['chainvalue']);
                        $listed_items++;
                    }
                }

            } elseif (in_array($_POST['chainusertype'], $this->config->item('userids___11020'))) {

                //POSTS
                $users___4593 = $this->config->item('users___4593'); //Chain Types
                $current_posthashtag = (substr($_POST['first_segment'], 0, 1) == '~' ? substr($_POST['first_segment'], 1) : false);

                foreach (posts_query($_POST['chainusertype'], $_POST['postid'], 1, false) as $next_i) {
                    if (isset($next_i['postid'])) {
                        $ui .= view_card($discover_chainusertype . view_memory(42903, 33286) . $next_i['posthashtag'], $next_i['posthashtag'] == $current_posthashtag, $next_i['chainusertype'], '', view_post_title($next_i, true), $next_i['chainvalue']);
                        $listed_items++;
                    }
                }

            }

            if ($listed_items < $_POST['counter']) {
                //We have more to show:
                foreach ($this->Posts->read(array(
                    'postid' => $_POST['postid'],
                )) as $i) {
                    $ui .= view_more($discover_chainusertype . view_memory(42903, 33286) . $i['posthashtag'], false, '&nbsp;', '&nbsp;', 'View All');
                }
            }

            echo $ui;

        }
    }


    function user_list()
    {

        //Authenticate Member:
        if (!isset($_POST['userid']) || intval($_POST['userid']) < 1 || !isset($_POST['chainusertype']) || intval($_POST['chainusertype']) < 1) {
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Missing core variables</div>';
            return false;
        }

        $limit = view_memory(6404, 11064);
        $user_session = user_session();
        $users_query = users_query($_POST['chainusertype'], $_POST['userid'], 1);
        $es = $this->Users->read(array(
            'userid' => $_POST['userid'],
        ));
        if (!count($es)) {
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-lock"></i></span>Invalid User ID</div>';
            return false;
        }
        if (!$users_query) {
            return false;
        }

        $focus_userid = ($_POST['userid'] > 0 ? $_POST['userid'] : ($user_session ? $user_session['userid'] : 0));
        $ui = '';

        if ($_POST['chainusertype']==13550 || $_POST['chainusertype']==12273) {

            //Post/User Link Groups
            //Posts:
            $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-' . $_POST['chainusertype'] . '">';
            foreach ($users_query as $i) {
                $ui .= post_view($_POST['chainusertype'], $i, null, null, $focus_userid);
            }
            $ui .= '</div>';

        } elseif ($_POST['chainusertype']==32292 || in_array($_POST['chainusertype'], $this->config->item('userids___11028'))) {

            //Users:
            $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-' . $_POST['chainusertype'] . '">';
            foreach ($users_query as $e) {
                $ui .= user_view($_POST['chainusertype'], $e, null);
            }
            $ui .= '</div>';

        } elseif (in_array($_POST['chainusertype'], $this->config->item('userids___12144'))) {

            //Discoveries:
            $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-' . $_POST['chainusertype'] . '">';
            foreach ($users_query as $i) {
                $ui .= post_view($_POST['chainusertype'], $i, null, null, $focus_userid);
            }
            $ui .= '</div>';

        }

        echo $ui;

    }

    function user_cover()
    {

        if (!isset($_POST['userid']) || !isset($_POST['chainusertype']) || !isset($_POST['first_segment']) || !isset($_POST['counter'])) {

            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Missing core variables</div>';

        } else {

            $ui = '';
            $listed_items = 0;
            $is_cache = in_array($_POST['chainusertype'], $this->config->item('userids___14599'));

            if (in_array($_POST['chainusertype'], $this->config->item('userids___11028'))) {

                //USERS
                $current_userhandle = view_valid_user_user($_POST['first_segment']);
                $users___4593 = $this->config->item('users___4593'); //Chain Types

                foreach (users_query($_POST['chainusertype'], $_POST['userid'], 1, false) as $user_session) {
                    if (isset($user_session['userid'])) {
                        $ui .= view_card(view_memory(42903, 42902) . $user_session['userhandle'], $user_session['userhandle'] == $current_userhandle, $user_session['chainusertype'], view_cover($user_session['usercover'], true), $user_session['username'], (!$is_cache ? $user_session['chainvalue'] : null));
                        $listed_items++;
                    }
                }

            } elseif ($_POST['chainusertype']==13550 || $_POST['chainusertype']==31777 || $_POST['chainusertype']==12273) {

                //POSTS
                $current_posthashtag = (substr($_POST['first_segment'], 0, 1) == '~' ? substr($_POST['first_segment'], 1) : false);
                $users___4593 = $this->config->item('users___4593'); //Chain Types
                $discover_chainusertype = discover_chainusertype();

                foreach (users_query($_POST['chainusertype'], $_POST['userid'], 1, false) as $next_i) {
                    if (isset($next_i['postid'])) {
                        $ui .= view_card($discover_chainusertype . view_memory(42903, 33286) . $next_i['posthashtag'], $next_i['posthashtag'] == $current_posthashtag, $next_i['chainusertype'], '', view_post_title($next_i, true), (!$is_cache ? $next_i['chainvalue'] : null));
                        $listed_items++;
                    }
                }

            }

            if ($listed_items < $_POST['counter']) {
                //We have more to show:
                foreach ($this->Users->read(array(
                    'userid' => $_POST['userid'],
                )) as $user_this) {
                    $ui .= view_more(view_memory(42903, 42902) . $user_this['userhandle'], false, '&nbsp;', '&nbsp;', 'View All');
                }
            }

            echo $ui;

        }
    }

    function user_sort_save()
    {

        //Authenticate Member:
        $user_session = user_session(10939, 0, $this->user_session);
        if (!$user_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(10939),
            ));
        } elseif (!isset($_POST['userid']) || intval($_POST['userid']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid userid',
            ));
        } elseif (!isset($_POST['new_chainkey']) || !is_array($_POST['new_chainkey']) || count($_POST['new_chainkey']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Nothing passed for sorting',
            ));
        } else {

            //Validate User:
            $es = $this->Users->read(array(
                'userid' => $_POST['userid'],
            ));

            //Count followers:
            $listuser_count = $this->Chains->read(array(
                'chainuserinput' => $_POST['userid'],
                'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
            ), array('chainuseroutput'), 0, 0, array(), 'COUNT(userid) as totals');

            if (count($es) < 1) {

                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid userid',
                ));

            } elseif ($listuser_count[0]['totals'] > view_memory(6404, 11064)) {

                return view_json(array(
                    'status' => 0,
                    'message' => 'Cannot sort Users if greater than ' . view_memory(6404, 11064),
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


    function post_copy()
    {

        //Auth member and check required variables:
        $user_session = user_session(10939, 0, $this->user_session);

        if (!$user_session) {
            return view__json(array(
                'status' => 0,
                'messagCloe' => view__unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['postid']) || intval($_POST['postid']) < 1) {
            return view__json(array(
                'status' => 0,
                'message' => 'Invalid Following User',
            ));
        } elseif (!isset($_POST['do_recursive'])) {
            return view__json(array(
                'status' => 0,
                'message' => 'Missing template parameter',
            ));
        }

        return view_json($this->Posts->copy(intval($_POST['postid']), intval($_POST['do_recursive']), $user_session['userid']));

    }


    function user_copy()
    {

        //Auth member and check required variables:
        $user_session = user_session(10939, 0, $this->user_session);

        if (!$user_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(10939),
            ));
        } elseif (intval($_POST['userid']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid User',
            ));
        } elseif (!strlen($_POST['copy_user_title'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid User Title',
            ));
        }

        $copy_children = true;
        if(substr($_POST['copy_user_title'], 0, 1)=='-'){
            $copy_children = false;
            $_POST['copy_user_title'] = substr($_POST['copy_user_title'], 1);
        }

        //Validate User:
        $fetch_o = $this->Users->read(array(
            'userid' => $_POST['userid'],
        ));
        if (count($fetch_o) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid followings User ID',
            ));
        }


        //Create:
        $added_e = $this->Users->create(array(
            'username' => $_POST['copy_user_title'],
            'usercover' => $fetch_o[0]['usercover'],
            'userbio' => $fetch_o[0]['userbio'],
        ), $user_session['userid']);
        if (!$added_e['status']) {
            //We had an error, return it:
            return view_json($added_e);
        } else {
            //Assign new User:
            $focus_e = $added_e['user_create'];
        }


        //Followings:
        foreach ($this->Chains->read(array(
            'chainuseroutput' => $_POST['userid'],
            'chainusertype IN (' . join(',', $this->config->item('userids___41303')) . ')' => null, //Clone User Chains
        ), array(), 0) as $x) {
            if (!count($this->Chains->read(array(
                'chainusertype' => $x['chainusertype'],
                'chainuserinput' => $x['chainuserinput'],
                'chainuseroutput' => $focus_e['userid'],
                'chainvalue' => $x['chainvalue'],
            )))) {
                $this->Chains->create(array(
                    'chainusercreator' => $user_session['userid'],
                    'chainkey' => $x['chainkey'],
                    'chainusertype' => $x['chainusertype'],
                    'chainuserinput' => $x['chainuserinput'],
                    'chainuseroutput' => $focus_e['userid'],
                    'chainvalue' => $x['chainvalue'],
                ));
            }
        }

        if($copy_children){

            //Followers:
            foreach ($this->Chains->read(array(
                'chainuserinput' => $_POST['userid'],
                'chainusertype IN (' . join(',', $this->config->item('userids___41303')) . ')' => null, //Clone User Chains
            ), array(), 0) as $x) {

                //Make sure none existent in new User:
                if (!count($this->Chains->read(array(
                    'chainusertype' => $x['chainusertype'],
                    'chainuserinput' => $focus_e['userid'],
                    'chainuseroutput' => $x['chainuseroutput'],
                    'chainvalue' => $x['chainvalue'],
                )))) {
                    $this->Chains->create(array(
                        'chainusercreator' => $user_session['userid'],
                        'chainkey' => $x['chainkey'],
                        'chainusertype' => $x['chainusertype'],
                        'chainuserinput' => $focus_e['userid'],
                        'chainuseroutput' => $x['chainuseroutput'],
                        'chainvalue' => $x['chainvalue'],
                    ));
                }
            }
        }


        return view_json(array(
            'status' => 1,
            'user_createuser' => $focus_e['userhandle'],
        ));


    }

    function user_create()
    {

        //Auth member and check required variables:
        $user_session = user_session(10939, 0, $this->user_session);

        if (!$user_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(10939),
            ));
        } elseif (intval($_POST['focus__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Following User',
            ));
        } elseif (!isset($_POST['chainusertype'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid User Creation Type',
            ));
        } elseif (!isset($_POST['user_current_id']) || !isset($_POST['user_new_string']) || (intval($_POST['user_current_id']) < 1 && strlen($_POST['user_new_string']) < 1)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Either New User ID or User Name',
            ));
        }

        $adding_to_i = ($_POST['focus__node'] == 12273);


        if ($adding_to_i) {

            //Validate Post:
            $fetch_o = $this->Posts->read(array(
                'postid' => $_POST['focus__id'],
            ));
            if (count($fetch_o) < 1) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid followings User ID',
                ));
            }

        } else {

            //Validate User:
            $fetch_o = $this->Users->read(array(
                'userid' => $_POST['focus__id'],
            ));
            if (count($fetch_o) < 1) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid followings User ID',
                ));
            }

        }


        //Set some variables:
        $_POST['user_new_string'] = trim($_POST['user_new_string']);
        $_POST['chainusertype'] = intval($_POST['chainusertype']);
        $is_upwards = in_array($_POST['chainusertype'], $this->config->item('userids___14686'));

        if (!intval($_POST['user_current_id']) && view_valid_user_user($_POST['user_new_string'])) {
            foreach ($this->Users->read(array(
                'LOWER(userhandle)' => strtolower(substr($_POST['user_new_string'], 1)),
            )) as $e) {
                $_POST['user_current_id'] = $e['userid'];
            }
        }
        $adding_to_existing = (intval($_POST['user_current_id']) > 0);

        //Are we adding an existing User?
        if ($adding_to_existing) {

            //Validate this existing User:
            $es = $this->Users->read(array(
                'userid' => $_POST['user_current_id'],
            ));

            if (count($es) < 1) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'User @' . $_POST['user_current_id'] . ' is not active',
                ));
            }

            //All good, assign:
            $focus_e = $es[0];

        } else {

            //We are creating a new User:
            $added_e = $this->Users->create(array(
                'username' => $_POST['user_new_string'],
            ), $user_session['userid']);
            if (!$added_e['status']) {
                //We had an error, return it:
                return view_json($added_e);
            } else {
                //Assign new User:
                $focus_e = $added_e['user_create'];
            }

        }

        //We need to check to ensure this is not a duplicate Chain if adding an existing User:
        $ur2 = array();

        if ($adding_to_i) {

            //Add Author

        } else {

            //Add Up/Down User:

            //Add Chains only if not previously added by the URL function:
            if ($is_upwards) {

                //Following
                $chainuseroutput = $fetch_o[0]['userid'];
                $chainuserinput = $focus_e['userid'];
                $chainkey = 0; //Never sort following, only sort followers

            } else {

                //Followers
                $chainuserinput = $fetch_o[0]['userid'];
                $chainuseroutput = $focus_e['userid'];
                $chainkey = 0;

            }


            $chainvalue = null;

            //Create Chain:
            $ur2 = $this->Chains->create(array(
                'chainusercreator' => $user_session['userid'],
                'chainusertype' => 4230,
                'chainvalue' => $chainvalue,
                'chainuseroutput' => $chainuseroutput,
                'chainuserinput' => $chainuserinput,
                'chainkey' => $chainkey,
            ));
        }

        //Return User:
        return view_json(array(
            'status' => 1,
            'user_new_echo' => user_view($_POST['chainusertype'], array_merge($focus_e, $ur2), null),
        ));

    }

    function user_editor()
    {

        $user_session = user_session(null, 0, $this->user_session);
        $users___11035 = $this->config->item('users___11035');
        $users___42776 = $this->config->item('users___42776');
        $users___4592 = $this->config->item('users___4592'); //Data types
        if (!$user_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['userid']) || !isset($_POST['chainid'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core IDs',
            ));
        }

        $es = $this->Users->read(array(
            'userid' => $_POST['userid'],
        ));
        if (!count($es)) {
            return view_json(array(
                'status' => 0,
                'message' => 'User is no longer active',
            ));
        } elseif (!user_access($es[0]['userhandle'], 0, $es[0])) {
            return view_json(array(
                'status' => 0,
                'message' => 'You are missing permission to edit this User',
            ));
        }


        //Fetch dynamic data based on post type:
        $order_42145 = sort_by(42145);
        $scanned_users = array();
        $return_inputs = array();
        $input_pointer = 0;
        $profile_header = '';

        //Fetch User Templates, if any:
        foreach ($this->Chains->read(array(
            'chainuserinput IN (' . join(',', $this->config->item('userids___42178')) . ')' => null, //Dynamic Users
            'chainuseroutput' => $es[0]['userid'],
            'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
        ), array('chainuserinput'), 0, 0, sort_by(42178)) as $user_group) {

            if (in_array($user_group['userid'], $scanned_users)) {
                continue;
            }
            array_push($scanned_users, $user_group['userid']);

            foreach ($this->Chains->read(array(
                'chainuseroutput' => $user_group['userid'],
                'chainuserinput IN (' . join(',', $this->config->item('userids___42145')) . ')' => null, //Dynamic Input Templates
                'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
            ), array('chainuserinput'), 0, 0, $order_42145) as $user_template) {

                $profile_header = '<div class="profile_header main__title"><span class="icon-block-sm">' . view_cover($user_template['usercover']) . '</span>' . $user_template['username'] . '<a href="' . view_memory(42903, 42902) . $user_group['userhandle'] . '" target="_blank" data-toggle="tooltip" data-placement="top" title="Because you follow ' . $user_group['username'] . '. Click to Open in a New Window"><span class="icon-block-sm">' . view_cover($user_group['usercover']) . '</span></a></div>';


                //Load template:
                if (!is_array($this->config->item('users___' . $user_template['userid']))) {
                    //Report Error:
                    log_error('user_sessionditor_load() ERROR: @' . $user_template['userid'] . ' is NOT in memory cache', array(
                        'chainuseroutput' => $user_template['userid'],
                    ));
                    continue;
                } elseif (in_array($user_template['userid'], $scanned_users)) {
                    continue;
                }
                array_push($scanned_users, $user_template['userid']);


                foreach ($this->config->item('users___' . $user_template['userid']) as $dynamic_userid => $m) {

                    //Make sure it's a dynamic input field:
                    if (!in_array($dynamic_userid, $this->config->item('userids___42179'))) {
                        continue;
                    } elseif (in_array($dynamic_userid, $scanned_users)) {
                        continue;
                    }
                    array_push($scanned_users, $dynamic_userid);

                    //Let's first determine the data type:
                    $data_types = array_intersect($m['m__following'], $this->config->item('userids___4592'));

                    if (count($data_types) != 1) {

                        //This is strange, we are expecting 1 match only report this:
                        log_error('Found ' . count($data_types) . ' Data Types (@' . $es[0]['userid'] . ') (Expecting exactly 1) for @' . $dynamic_userid . ': Check @4592 to see what is wrong', array(
                            'chainuseroutput' => $dynamic_userid,
                            'chainusercreator' => $user_session['userid'],
                        ));
                        continue; //Go to the next dynamic data type

                    } elseif ($input_pointer >= view_memory(6404, 42206)) {
                        //Monitor if we ever reach the maximum:
                        log_error('Dynamic Fields Reach their maximum limit of ' . view_memory(6404, 42206) . '  which may require field expansion', array(
                            'chainuseroutput' => $dynamic_userid,
                            'chainusercreator' => $user_session['userid'],
                            'chainpostoutput' => $_POST['userid'],
                        ));
                    }

                    //We found 1 match as expected:
                    $input_pointer++;
                    foreach ($data_types as $data_type_this) {
                        $data_type = $data_type_this;
                        break;
                    }

                    if (in_array($data_type, $this->config->item('userids___42188'))) {

                        //Single or Multiple Choice:
                        array_push($return_inputs, array(
                            'd__id' => $dynamic_userid,
                            'd__is_radio' => 1,
                            'd_chainid' => 0,
                            'd__html' => view_instant_select($dynamic_userid, $es[0]['userid'], 0),
                            'd__value' => ($es[0]['userid'] > 0 ? $es[0]['userid'] : ''),
                            'd__type_name' => '',
                            'd__placeholder' => '',
                            'd__profile_header' => $profile_header,
                        ));

                    } else {

                        $this_data_type = $this->config->item('users___' . $data_type);
                        $users___42179 = $this->config->item('users___42179'); //Dynamic Input Field
                        $users___11035 = $this->config->item('users___11035'); //Encyclopedia

                        //Fetch the current value(s):
                        $counted = 0;
                        $unique_values = array();
                        foreach ($this->Chains->read(array(
                            'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                            'chainuseroutput' => $es[0]['userid'],
                            'chainuserinput' => $dynamic_userid,
                        ), array('chainuserinput')) as $selected_e) {
                            if (strlen($selected_e['chainvalue']) && !in_array($selected_e['chainvalue'], $unique_values)) {
                                array_push($unique_values, $selected_e['chainvalue']);
                                $counted++;
                                array_push($return_inputs, array(
                                    'd__id' => $dynamic_userid,
                                    'd__is_radio' => 0,
                                    'd_chainid' => $selected_e['chainid'],
                                    'd__html' => view_dynamic_headline($dynamic_userid, $m, $selected_e),
                                    'd__value' => $selected_e['chainvalue'],
                                    'd__type_name' => html_input_type($data_type),
                                    'd__placeholder' => (strlen($this_data_type[$dynamic_userid]['m__message']) ? $this_data_type[$dynamic_userid]['m__message'] : $users___4592[$data_type]['m__name'] ),
                                    'd__profile_header' => $profile_header,
                                ));
                            }
                        }

                        if (!$counted) {
                            foreach ($this->Users->read(array(
                                'userid' => $dynamic_userid,
                            )) as $selected_e) {
                                array_push($return_inputs, array(
                                    'd__id' => $dynamic_userid,
                                    'd__is_radio' => 0,
                                    'd_chainid' => 0,
                                    'd__html' => view_dynamic_headline($dynamic_userid, $m, $selected_e),
                                    'd__value' => '',
                                    'd__type_name' => html_input_type($data_type),
                                    'd__placeholder' => (strlen($this_data_type[$dynamic_userid]['m__message']) ? $this_data_type[$dynamic_userid]['m__message'] : $users___4592[$data_type]['m__name'] ),
                                    'd__profile_header' => $profile_header,
                                ));
                            }
                        }
                    }
                }
            }
        }


        //Add universal inputs only if missing bio profiles:
        if (!array_intersect($scanned_users, $this->config->item('userids___42885'))) {
            foreach ($this->Users->read(array(
                'userid IN (' . join(',', $this->config->item('userids___42776')) . ')' => null, //Universal Dynamic Inputs
            )) as $selected_e) {
                foreach (array_intersect($users___42776[$selected_e['userid']]['m__following'], $this->config->item('userids___4592')) as $data_type) {
                    //Any value?
                    $values = $this->Chains->read(array(
                        'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                        'chainuseroutput' => $es[0]['userid'],
                        'chainuserinput' => $selected_e['userid'],
                    ));
                    array_push($return_inputs, array(
                        'd__id' => $selected_e['userid'],
                        'd__is_radio' => 0,
                        'd_chainid' => 0,
                        'd__html' => view_dynamic_headline($selected_e['userid'], $users___42776[$selected_e['userid']], $selected_e),
                        'd__value' => (isset($values[0]['chainvalue']) && strlen($values[0]['chainvalue']) > 0 ? $values[0]['chainvalue'] : ''),
                        'd__type_name' => html_input_type($data_type),
                        'd__placeholder' => (strlen($users___42776[$selected_e['userid']]['m__message']) ? $users___42776[$selected_e['userid']]['m__message'] : $users___4592[$data_type]['m__name'] ),
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

    function user_save_edit()
    {

        $user_session = user_session(null, 0, $this->user_session);
        if (!$user_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['save_userid'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Coin ID',
            ));
        } elseif (!isset($_POST['save_username'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid User Title',
            ));
        } elseif (!isset($_POST['save_userbio'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid User Bio',
            ));
        } elseif (!isset($_POST['save_userhandle'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid User User',
            ));
        } elseif (!isset($_POST['save_usercover'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid User Cover',
            ));
        } elseif (!isset($_POST['save_chainid']) || !isset($_POST['save_chainvalue'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Chain Data',
            ));
        }


        $es = $this->Users->read(array(
            'userid' => $_POST['save_userid'],
        ));
        if (!count($es)) {
            return view_json(array(
                'status' => 0,
                'message' => 'User Not Active',
            ));
        }


        //Validate Dynamic Inputs:
        $users___42179 = $this->config->item('users___42179'); //Dynamic Input Fields

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
            $dynamic_userid = $input_parts[1];
            $dynamic_value = trim($input_parts[2]);


            //Required fields must have an input:
            if (in_array($dynamic_userid, $this->config->item('userids___28239')) && !strlen($dynamic_value) && !in_array($dynamic_userid, $this->config->item('userids___33331')) && !in_array($dynamic_userid, $this->config->item('userids___33332'))) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Missing Required Field: ' . $users___42179[$dynamic_userid]['m__name'],
                ));
            }

            //Validate input based on its data type, if provided:
            if (strlen($dynamic_value)) {
                foreach (array_intersect($users___42179[$dynamic_userid]['m__following'], $this->config->item('userids___4592')) as $data_type_this) {
                    $data_type_validate = data_type_validate($data_type_this, $dynamic_value, $users___42179[$dynamic_userid]['m__name']);
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
                    'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                    'chainuserinput' => $dynamic_userid,
                    'chainuseroutput' => $es[0]['userid'],
                ));
            }


            //Update if needed:
            if (!strlen($dynamic_value)) {

                //Remove Chain if we have one:
                //HACK: Summary are key chains that should not be removed
                /*
                if (count($values) && $dynamic_userid != 11035) {
                    $this->Chains->delete($values[0]['chainid'], $user_session['userid']);
                }
                */

            } elseif (!count($values)) {

                //Create Chain:
                $this->Chains->create(array(
                    'chainusercreator' => $user_session['userid'],
                    'chainusertype' => 4230,
                    'chainuserinput' => $dynamic_userid,
                    'chainuseroutput' => $es[0]['userid'],
                    'chainvalue' => $dynamic_value,
                    'chainkey' => number_chainkey($dynamic_value),
                ));

            } elseif ($values[0]['chainvalue'] != $dynamic_value) {

                //Update Chain:
                $this->Chains->update($values[0]['chainid'], array(
                    'chainvalue' => $dynamic_value,
                    'chainusercreator' => $user_session['userid'],
                ));

            }
        }


        //Validate User User & save if needed:
        if ($es[0]['userhandle'] !== trim($_POST['save_userhandle'])) {
            $validate_update_user = validate_update_user(trim($_POST['save_userhandle']), null, $es[0]['userid']);
            if (!$validate_update_user['status']) {
                return view_json(array(
                    'status' => 0,
                    'message' => $validate_update_user['message'],
                ));
            }
        }

        //Validate User Title & save if needed:
        $validate_username = validate_username($_POST['save_username']);
        if ($es[0]['username'] != trim($_POST['save_username'])) {
            if (!$validate_username['status']) {
                return view_json(array(
                    'status' => 0,
                    'message' => $validate_username['message'],
                ));
            } else {
                $es[0]['username'] = $validate_username['username_clean'];
            }
        }

        //Save User Cover if needed:
        if ($es[0]['usercover'] != trim($_POST['save_usercover'])) {
            //TODO validate usercover?
            $es[0]['usercover'] = trim($_POST['save_usercover']);
        }

        //Update Cache:
        $this->Users->update($es[0]['userid'], array(
            'username' => $validate_username['username_clean'],
            'usercover' => trim($_POST['save_usercover']),
            'userhandle' => trim($_POST['save_userhandle']),
            'userbio' => trim($_POST['save_userbio']),
        ), $user_session['userid']);


        //Sync user reference:
        $new_user_string = trim($_POST['save_userhandle']);
        if ($es[0]['userhandle'] != $new_user_string) {
            //Update Users everywhere they are referenced:
            foreach ($this->Chains->read(array(
                'chainuserinput' => $es[0]['userid'],
                'chainusertype' => 31835, //User Mention
            ), array('chainpostoutput')) as $ref) {
                $this->Posts->update($ref['postid'], array(
                    'postmessage' => str_replace('@' . $es[0]['userhandle'], '@' . $new_user_string, $ref['postmessage']),
                ), $user_session['userid']);
            }
            $es[0]['userhandle'] = $new_user_string;
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
                        'chainusercreator' => $user_session['userid'],
                    ));
                }
            }
        }


        //Reset member session data if this data belongs to the logged-in member:
        if ($_POST['save_userid'] == $user_session['userid']) {
            $this->Users->activate($es[0], true);
        }


        return view_json(array(
            'status' => 1,
            'message' => 'Updated',
        ));


    }




    function post_suggestions()
    {
        /*
         *
         * Generate Post Suggestions based on post message
         *
         * */

        $user_session = user_session(null, 0, $this->user_session);
        if (!$user_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['postid']) || !isset($_POST['save_postmessage']) || !isset($_POST['save_posthashtag']) || !isset($_POST['save_postfootnote'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing selected User',
            ));
        }

        $post_index = post_index(trim($_POST['save_postmessage']).( strlen($_POST['save_postfootnote']) ? "\n*\n".trim($_POST['save_postfootnote']) : '' ), 0, 0, $_POST['save_posthashtag']);


        $warning_message = null;
        $preview_media = null;
        $suggest_data = array();


        //Generate suggestions?
        $suggest_data[4737] = array();
        $suggest_data[6287] = array();
        $suggest_data[42179] = array();

        if(user_session(10939)){

            //Form Inputs, show them all if none of them are referenced:
            if(count(array_intersect($this->config->item('userids___4737'), $post_index['referenced_users']))){
                foreach(array_intersect($this->config->item('userids___4737'), $post_index['referenced_users']) as $first_form_input){
                    foreach(array_intersect($this->config->item('userids___'.$first_form_input), $this->config->item('userids___42179')) as $settingid){
                        if(!in_array($settingid, $post_index['referenced_users'])){
                            array_push($suggest_data[42179], $settingid);
                        }
                    }
                    break;
                }
            } else {
                foreach($this->config->item('users___4737') as $userid => $m){
                    array_push($suggest_data[4737], $userid);
                }
            }

            foreach($this->config->item('users___30841') as $userid => $m){
                if(!in_array($userid, $post_index['referenced_users'])){
                    array_push($suggest_data[6287], $userid);
                }
            }
            if((count($post_index['new_posts']) + count($post_index['referenced_posts']))>0){
                foreach($this->config->item('users___3450818') as $userid => $m){
                    if(!in_array($userid, $post_index['referenced_users'])){
                        array_push($suggest_data[6287], $userid);
                    }
                }
            }


        }

        //All good:
        return view_json(array(
            'status' => 1,
            'warning_message' => $warning_message,
            'preview_media' => $preview_media,
            'suggest_data' => $suggest_data,
            'post_index' => $post_index,
        ));
    }


    function user_select_apply()
    {
        /*
         *
         * Saves the radio selection of some account fields
         *
         * */

        $user_session = user_session(null, 0, $this->user_session);
        if (!$user_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['focus__id']) || intval($_POST['focus__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing followings User',
            ));
        } elseif (!isset($_POST['selected_userid']) || intval($_POST['selected_userid']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing selected User',
            ));
        } elseif (!isset($_POST['down_userid']) || !isset($_POST['right_postid'])) {
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


        if ($_POST['down_userid'] > 0) {

            //Dispatch Any Emails Necessary:
            if (isset($_POST['selected_userid']) && intval($_POST['selected_userid']) > 0) {
                foreach ($this->Chains->read(array(
                    'chainusertype' => 31835, //Mention
                    'chainuserinput' => $_POST['selected_userid'],
                ), array('chainpostoutput'), 0) as $i) {
                    if (count($this->Chains->read(array(
                        'chainusertype' => 31835, //Mention
                        'chainuserinput' => 31065, //Choice Update Email Templates
                        'chainpostoutput' => $i['postid'], //Is this the template?
                    )))) {
                        //Found the email template to send:
                        $total_sent = $this->Chains->broadcast(array($user_session), $i, website_setting(0), false);
                        break; //Just the first template match
                    }
                }
            }
        }

        $is_required = in_array($_POST['focus__id'], $this->config->item('userids___28239')); //Required Settings

        if (!$_POST['enable_mulitiselect'] || $_POST['was_previously_selected']) {

            //Since this is not a multi-select we want to delete all existing options

            //Fetch all possible answers based on followings User:
            $query_filters = array(
                'chainuserinput' => $_POST['focus__id'],
                'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
            );

            if ((!$is_required || $_POST['enable_mulitiselect']) && $_POST['was_previously_selected']) {
                //Just delete this single item, not the other ones:
                $query_filters['chainuseroutput'] = $_POST['selected_userid'];
            }

            //List all possible answers:
            $possible_answers = array();
            foreach ($this->Chains->read($query_filters, array('chainuseroutput'), 0, 0) as $answer_e) {
                $stats['total']++;
                array_push($possible_answers, $answer_e['userid']);
            }

            //Delete previously selected options:
            if ($_POST['down_userid']) {
                $delete_query = $this->Chains->read(array(
                    'chainuserinput IN (' . join(',', $possible_answers) . ')' => null,
                    'chainuseroutput' => $_POST['down_userid'],
                    'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                ));
            } elseif ($_POST['right_postid']) {
                $delete_query = $this->Chains->read(array(
                    'chainuserinput IN (' . join(',', $possible_answers) . ')' => null,
                    'chainpostoutput' => $_POST['right_postid'],
                    'chainusertype IN (' . join(',', $this->config->item('userids___33602')) . ')' => null, //Post/User Chains Active
                ));
            }

            foreach ($delete_query as $delete) {
                $stats['deleted']++;
                //Should usually delete a single option:
                $this->Chains->delete($delete['chainid'], $user_session['userid']);
            }

        }

        //Add new option if not previously there:
        if ((!$_POST['enable_mulitiselect'] && $is_required) || !$_POST['was_previously_selected']) {
            if ($_POST['down_userid']) {
                $stats['added']++;
                $this->Chains->create(array(
                    'chainusercreator' => $user_session['userid'],
                    'chainuserinput' => $_POST['selected_userid'],
                    'chainusertype' => 4230,
                    'chainuseroutput' => $_POST['down_userid'],
                ));
            } elseif ($_POST['right_postid']) {



            }
        }


        //Update Session:
        if ($_POST['down_userid'] && $user_session) {
            $this->Users->activate($user_session, true);
        }


        //All good:
        return view_json(array(
            'status' => 1,
            'message' => 'Updated: ' . print_r($stats, true),
        ));
    }

    function user_authenticate()
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
        } elseif (!isset($_POST['sign_postid'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing post referrer',
            ));
        }

        $_POST['account_email_phone'] = trim(strtolower($_POST['account_email_phone']));

        //Validate member ID
        if ($_POST['account_id'] > 0) {

            $es = $this->Users->read(array(
                'userid' => $_POST['account_id'],
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
            'chainusertype' => 44176, //User View
            'chainuserinput' => 32078, //Sign In Key
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
            $this->Users->activate($es[0]);

        } else {

            //Add new account
            $_POST['account_email_phone'] = trim(strtolower($_POST['account_email_phone']));
            $is_email = filter_var($_POST['account_email_phone'], FILTER_VALIDATE_EMAIL);

            //Prep inputs & validate further:
            $acc_email = ($is_email ? $_POST['account_email_phone'] : $_POST['new_account_email']);
            $user_result = $this->Users->join(strstr($acc_email, '@', true), $acc_email, (!$is_email ? $_POST['account_email_phone'] : ''));
            if (!$user_result['status']) {
                return view_json($user_result);
            }

            $es[0] = $user_result['e'];

        }


        //Set default sign in URL:
        $sign_url = view_memory(42903, 42902) . $es[0]['userhandle'];

        //See if we can find a better one:
        if (intval($_POST['sign_postid']) > 0) {
            foreach ($this->Posts->read(array(
                'postid' => $_POST['sign_postid'],
            )) as $i) {
                $sign_url = $i['posthashtag'] . '/' . view_memory(6404, 4235);
            }
        } elseif (isset($_POST['referrer_url']) && strlen(urldecode($_POST['referrer_url'])) > 1) {
            $sign_url = urldecode($_POST['referrer_url']);
        }

        return view_json(array(
            'status' => 1,
            'sign_url' => $sign_url,
        ));

    }

    function user_toggle_follow()
    {

        $user_session = user_session(10939, 0, $this->user_session);
        if (!$user_session) {

            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(10939),
            ));

        } elseif (!isset($_POST['chainusercreator']) || !isset($_POST['userid']) || !isset($_POST['postid']) || !isset($_POST['chainid'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core Variable',
            ));

        } else {

            $_POST['require_writing'] = intval($_POST['require_writing']);

            $already_added = $this->Chains->read(array(
                'chainuserinput' => $_POST['userid'],
                'chainuseroutput' => $_POST['chainusercreator'],
                'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
            ), array('chainuserinput'));

            if (count($already_added)) {

                if (intval($_POST['require_writing'])) {

                    //Updating current value if changed:
                    if (strlen($_POST['written_answer']) && trim($_POST['written_answer']) != $already_added[0]['chainvalue']) {
                        $this->Chains->update($already_added[0]['chainid'], array(
                            'chainvalue' => $_POST['written_answer'],
                            'chainusercreator' => $user_session['userid'],
                        ));
                    } elseif (!strlen($_POST['written_answer'])) {
                        $this->Chains->delete($already_added[0]['chainid'], $user_session['userid']);
                    }

                    return view_json(array(
                        'status' => 1,
                        'message' => $_POST['written_answer'],
                    ));

                } else {

                    //Already exists, let's remove:
                    $this->Chains->delete($already_added[0]['chainid'], $user_session['userid']);

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

                    foreach ($this->Users->read(array(
                        'userid' => $_POST['userid'],
                    )) as $e) {

                        //Does not exist, Add:
                        $this->Chains->create(array(
                            'chainuserinput' => $_POST['userid'],
                            'chainuseroutput' => $_POST['chainusercreator'],
                            'chainusercreator' => $user_session['userid'],
                            'chainvalue' => $_POST['written_answer'],
                            'chainusertype' => 4230,
                        ));

                        return view_json(array(
                            'status' => 1,
                            'message' => (intval($_POST['require_writing']) ? $_POST['written_answer'] : view_cover($e['usercover'], true)),
                        ));

                    }
                }
            }
        }
    }

    function user_verify()
    {

        if (!isset($_POST['account_email_phone'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'missing account details',
            ));
        }

        //Cleanup input email:
        $users___11035 = $this->config->item('users___11035'); //Encyclopedia
        $_POST['account_email_phone'] = trim(strtolower($_POST['account_email_phone']));
        $valid_email = filter_var($_POST['account_email_phone'], FILTER_VALIDATE_EMAIL);
        if (!$valid_email && strlen($_POST['account_email_phone']) >= 10) {
            $_POST['account_email_phone'] = preg_replace('/[^0-9]+/', '', $_POST['account_email_phone']);
        }
        $possible_phone = !$valid_email && strlen($_POST['account_email_phone']) >= 10;

        if (!$valid_email && !$possible_phone) {
            return view_json(array(
                'status' => 0,
                'message' => (strlen($_POST['account_email_phone']) ? '[' . $_POST['account_email_phone'] . '] is Invalid!' : 'Enter your email to continue:'),
            ));
        } elseif (!isset($_POST['sign_postid'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing data ID',
            ));
        }


        if (intval($_POST['sign_postid']) > 0) {
            //Fetch the post:
            $referrer_i = $this->Posts->read(array(
                'postid' => $_POST['sign_postid'],
            ));
        } else {
            $referrer_i = array();
        }


        //Search for email/phone to see if it exists
        $chainusercreator = 0;
        foreach ($this->Chains->read(array(
            'LOWER(chainvalue)' => strtolower($_POST['account_email_phone']),
            'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
            'chainuserinput' => (filter_var($_POST['account_email_phone'], FILTER_VALIDATE_EMAIL) ? 3288 : 4783), //Email / Phone
        ), array('chainuseroutput'), 1, 0, array('chainid' => 'ASC')) as $map_e) {
            $u = $map_e;
            $chainusercreator = $map_e['userid'];
        }

        //Send Sign In Key
        $passcode = rand(1000, 9999);
        $session_key = random_string(55);

        //Append to session:
        $session_data = $this->session->all_userdata();
        $session_data['session_key'] = $session_key;
        $this->session->set_userdata($session_data);

        $html_message = $passcode . ' is your ' . $users___11035[32078]['m__name'] . ' for your ' . get_domain('m__name') . ' account.';

        if ($valid_email) {

            //Email:
            dispatch_email(array($_POST['account_email_phone']), $html_message, '<div class="line">' . $html_message . '</div>', $chainusercreator, array(), 0, 0, false);


        } elseif ($possible_phone) {

            //SMS:
            dispatch_sms($_POST['account_email_phone'], $html_message, 0, array(), 0, 0, false);

        }

        //Log new key:
        $this->Chains->create(array(
            'chainusertype' => 44176, //User View
            'chainuserinput' => 32078, //Sign In Key
            'chainuseroutput' => $chainusercreator, //Member making request
            'chainusercreator' => $chainusercreator, //Member making request
            'chainpostinput' => intval($_POST['sign_postid']),
            'chainvalue' => $_POST['account_email_phone'] . '/' . md5($session_key . $passcode),
        ));

        return view_json(array(
            'status' => 1,
            'account_id' => $chainusercreator,
            'valid_email' => ($valid_email ? 1 : 0),
            'account_preview' => ($chainusercreator ? '<span class="icon-block">' . view_cover($u['usercover'], true) . '</span>' . $u['username'] : ''),
            'clean_contact' => $_POST['account_email_phone'],
        ));

    }

    function user_text_update()
    {

        //Authenticate Member:
        $user_session = user_session(null, 0, $this->user_session);
        $users___12112 = $this->config->item('users___12112');

        if (!$user_session) {

            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
                'original_val' => '',
            ));

        } elseif (!isset($_POST['userid']) || !isset($_POST['cache_userid']) || !isset($_POST['post_createtext'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing core variables',
                'original_val' => '',
            ));

        } elseif ($_POST['cache_userid'] == 6197 /* USERNAME */) {

            $es = $this->Users->read(array(
                'userid' => $_POST['userid'],
            ));
            if (!count($es)) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid User ID #3',
                    'original_val' => '',
                ));
            }

            $validate_username = validate_username($_POST['post_createtext']);
            if (!$validate_username['status']) {
                return view_json(array_merge($validate_username, array(
                    'original_val' => $es[0]['username'],
                )));
            }

            //All good, go ahead and update:
            $this->Users->update($_POST['userid'], array(
                'username' => $validate_username['username_clean'],
            ), $user_session['userid']);

            //Reset member session data if this data belongs to the logged-in member:
            if ($_POST['userid'] == $user_session['userid']) {
                //set Session with new data:
                $es[0]['username'] = $validate_username['username_clean'];
                $this->Users->activate($es[0], true);
            }

            return view_json(array(
                'status' => 1,
            ));

        } else {

            return view_json(array(
                'status' => 0,
                'message' => 'Unknown Update Type [' . $_POST['cache_userid'] . ']',
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
        $user_session = user_session(null, 0, $this->user_session);

        if (!isset($_POST['apply_id']) || !isset($_POST['s__id'])) {
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Missing Core Data</div>';
        } else {
            if ($_POST['apply_id'] == 4997) {

                //User list:
                $counter = users_query(42373, $_POST['s__id'], 0, false);
                if (!$counter) {
                    echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>No Users yet</div>';
                } else {
                    echo '<div class="alert" role="alert"><span class="icon-block"><i class="far fa-list"></i></span>Will apply to ' . $counter . ' User' . search($counter) . ':</div>';
                    echo '<div class="row justify-content">';
                    $ids = array();
                    foreach (users_query(42373, $_POST['s__id'], 1, true) as $e) {
                        array_push($ids, $e['userid']);
                        echo user_view(42287, $e);
                    }
                    echo '</div>';
                    echo '<div class="dotransparent" title="Total of ' . count($ids) . '">' . join(', ', $ids) . '</div>';
                }

            } elseif ($_POST['apply_id'] == 12589) {

                //post list:
                $is_next = $this->Chains->read(array(
                    'chainusertype IN (' . join(',', $this->config->item('userids___42345')) . ')' => null, //Active Sequence
                    'chainpostinput' => $_POST['s__id'],
                ), array('chainpostoutput'), 0, 0, array('chainkey' => 'ASC'));
                $counter = count($is_next);

                if (!$counter) {
                    echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>No Posts yet</div>';
                } else {
                    echo '<div class="alert" role="alert"><span class="icon-block"><i class="far fa-list"></i></span>Will apply to ' . $counter . ' post' . search($counter) . ':</div>';
                    echo '<div class="row justify-content">';
                    $ids = array();
                    foreach ($is_next as $i) {
                        array_push($ids, $i['postid']);
                        echo post_view(42288, $i);
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

            //USER
            $focus_es = $this->Users->read(array(
                'userid' => $_POST['focus__id'],
            ));
            $focus_e = $focus_es[0];

            foreach (users_query($_POST['chainusertype'], $_POST['focus__id'], $_POST['current_page']) as $s) {
                if (in_array($_POST['chainusertype'], $this->config->item('userids___11028'))) {
                    echo user_view($_POST['chainusertype'], $s);
                    $success = true;
                } else if ($_POST['chainusertype']==31777 || $_POST['chainusertype']==13550 || in_array($_POST['chainusertype'], $this->config->item('userids___11020'))) {
                    echo post_view($_POST['chainusertype'], $s, $previous_i, null, $focus_e['userid']);
                    $success = true;
                }
            }

        } elseif ($_POST['focus__node'] == 12273) {

            //POST
            $previous_is = $this->Posts->read(array(
                'postid' => $_POST['focus__id'],
            ));
            $previous_i = $previous_is[0];

            foreach (posts_query($_POST['chainusertype'], $_POST['focus__id'], $_POST['current_page']) as $s) {
                if (in_array($_POST['chainusertype'], $this->config->item('userids___11020'))) {
                    echo post_view($_POST['chainusertype'], $s, $previous_i);
                    $success = true;
                } else if ($_POST['chainusertype']==31777 || $_POST['chainusertype']==13550 || in_array($_POST['chainusertype'], $this->config->item('userids___11028'))) {
                    echo user_view($_POST['chainusertype'], $s);
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
        $user_session = user_session(10939, 0, $this->user_session);

        if (!$user_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(10939),
            ));
        } elseif (!isset($_POST['focus__node']) || !in_array($_POST['focus__node'], $this->config->item('userids___28956'))) {
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
            //Posts order based on alphabetical order
            $order = 0;
            foreach ($this->Chains->read(array(
                'chainusertype IN (' . join(',', $this->config->item('userids___42345')) . ')' => null, //Active Sequence
                'chainpostinput' => $_POST['focus__id'],
            ), array('chainpostoutput'), 0, 0, array('postmessage' => 'ASC')) as $x) {
                $order++;
                $this->Chains->update($x['chainid'], array(
                    'chainkey' => $order,
                ));
            }
        } elseif ($_POST['focus__node'] == 12274) {
            //Users reset order
            foreach ($this->Chains->read(array(
                'chainuserinput' => $_POST['focus__id'],
                'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
            ), array('chainuseroutput'), 0, 0) as $x) {
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

    function post_discovered()
    {


        $user_session = user_session(null, 0, $this->user_session);
        if (!$user_session) {
            return view_json(array(
                'status' => 0,
                'message' => blocked_reasoning(),
            ));
        } elseif (!isset($_POST['target_posthashtag']) || !isset($_POST['target_postid']) || !isset($_POST['user_submitted_data']) || !isset($_POST['do_skip'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core Data',
            ));
        }

        if (!isset($_POST['selection_postid'])) {
            $_POST['selection_postid'] = array();
        }
        if (!isset($_POST['user_submitted_data']['post_createtext'])) {
            $_POST['user_submitted_data']['post_createtext'] = null;
        }
        if (!isset($_POST['next_post_data'])) {
            $_POST['next_post_data'] = array();
        }

        //Discover Focus Post:
        $primary_postid = null;
        foreach ($this->Posts->read(array(
            'postid' => $_POST['user_submitted_data']['postid'],
        )) as $focus_i) {

            $input__selection = count($this->Chains->read(array(
                'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
                'chainpostoutput' => $focus_i['postid'],
                'chainuserinput IN (' . join(',', $this->config->item('userids___7712')) . ')' => null,
            )));
            $input__upload = count($this->Chains->read(array(
                'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
                'chainpostoutput' => $focus_i['postid'],
                'chainuserinput IN (' . join(',', $this->config->item('userids___43004')) . ')' => null,
            )));
            $skipping_not_allowed = count($this->Chains->read(array(
                'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
                'chainpostoutput' => $focus_i['postid'],
                'chainuserinput IN (' . join(',', $this->config->item('userids___43009')) . ')' => null,
            )));
            $input__text = count($this->Chains->read(array(
                'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
                'chainpostoutput' => $focus_i['postid'],
                'chainuserinput IN (' . join(',', array_merge($this->config->item('userids___43002'), $this->config->item('userids___43003'))) . ')' => null,
            )));
            $total_selected = count($_POST['selection_postid']);
            $trying_to_skip = !$skipping_not_allowed &&
                (
                    intval($_POST['do_skip'])
                    || ($input__selection && !$total_selected)
                    || ($input__upload && !strlen($_POST['user_submitted_data']['post_createtext'])) //TODO Check Media
                    || (!$input__selection && !$input__upload && !strlen($_POST['user_submitted_data']['post_createtext']))
                );
            $post_required = post_required($focus_i);

            if (!$primary_postid) {
                $primary_postid = ($total_selected ? end($_POST['selection_postid']) : $focus_i['postid']);
            }

            //If skipping, make sure they can:
            if ($post_required && $trying_to_skip) {
                return view_json(array(
                    'status' => 0,
                    'message' => ($input__selection ? 'Make a selection to continue:' : 'Respond to continue:'),
                ));
            }

            //Now complete relevant next posts, if any:
            if ($input__selection) {

                $is_single_selection = count($this->Chains->read(array(
                    'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
                    'chainpostoutput' => $focus_i['postid'],
                    'chainuserinput IN (' . join(',', $this->config->item('userids___33331')) . ')' => null,
                )));


                if (!$is_single_selection) {

                    //How about the min selection?
                    if ($post_required) {
                        foreach ($this->Chains->read(array(
                            'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
                            'chainpostoutput' => $focus_i['postid'],
                            'chainuserinput' => 40834, //Min Selection
                        ), array(), 1) as $limit) {
                            if (intval($limit['chainvalue']) > 0 && $total_selected < intval($limit['chainvalue'])) {
                                return view_json(array(
                                    'status' => 0,
                                    'message' => 'Select ' . $limit['chainvalue'] . ' or more posts to go next.',
                                ));
                            }
                        }
                    }

                    //How about max selection?
                    foreach ($this->Chains->read(array(
                        'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
                        'chainpostoutput' => $focus_i['postid'],
                        'chainuserinput' => 40833, //Max Selection
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
                    'chainusertype' => 7712, //Input Choice
                    'chainusercreator' => $user_session['userid'],
                    'chainpostinput' => $focus_i['postid'],
                ), array('chainpostoutput')) as $x_selection) {

                    if (in_array($x_selection['postid'], $_POST['selection_postid'])) {
                        //Current selection is already in the database from before:
                        array_push($already_answered, $x_selection['postid']);
                        continue; //Nothing we need to do here
                    }

                    $this->Chains->delete($x_selection['chainid'], $user_session['userid']);

                    //Remove discovery if we can:
                    if (!count($this->Chains->read(array(
                        'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
                        'chainpostoutput' => $x_selection['postid'],
                        'chainuserinput IN (' . join(',', $this->config->item('userids___42905')) . ')' => null,
                    )))) {
                        foreach ($this->Chains->read(array(
                            'chainusertype IN (' . join(',', $this->config->item('userids___31777')) . ')' => null, //DISCOVERIES
                            'chainpostinput' => $x_selection['postid'],
                            'chainusercreator' => $user_session['userid'],
                        ), array(), 0) as $x_discovery) {
                            $this->Chains->delete($x_discovery['chainid'], $user_session['userid']);
                        }
                    }
                }

                //Save New Answers if not already:
                foreach ($_POST['selection_postid'] as $answer_postid) {
                    if (!in_array($answer_postid, $already_answered)) {
                        $this->Chains->create(array(
                            'chainusertype' => 7712, //Input Choice
                            'chainusercreator' => $user_session['userid'],
                            'chainuserinput' => $user_session['userid'],
                            'chainpostinput' => $focus_i['postid'],
                            'chainpostoutput' => $answer_postid,
                        ));
                    }
                }

            }

            //Save Skip if no answer was selected:
            if($trying_to_skip){
                $completion_status = $this->Chains->post_discovered(31022, $user_session['userid'], $_POST['target_postid'], $focus_i, $_POST['user_submitted_data'], array(
                    'chainkey' => $_POST['user_submitted_data']['postweight'],
                ));
                if (!$completion_status['status']) {
                    //We had an error with data within target_postid:
                    return view_json($completion_status);
                }
            }


            //Look through ALL next posts and see which ones we can complete, if any:
            foreach ($_POST['next_post_data'] as $index => $next_post_data) {

                if ($input__selection && !in_array($next_post_data['postid'], $_POST['selection_postid'])) {
                    //Not selected, move on:
                    continue;
                }

                foreach ($this->Posts->read(array(
                    'postid' => $next_post_data['postid'],
                )) as $post_next) {

                    //Analyze input:
                    $input__required = count($this->Chains->read(array(
                        'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
                        'chainpostoutput' => $post_next['postid'],
                        'chainuserinput IN (' . join(',', $this->config->item('userids___43039')) . ')' => null,
                    )));
                    if ($input__required) {
                        continue;
                    }
                    $input__text = count($this->Chains->read(array(
                        'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
                        'chainpostoutput' => $post_next['postid'],
                        'chainuserinput IN (' . join(',', array_merge($this->config->item('userids___43002'), $this->config->item('userids___43003'))) . ')' => null,
                    )));
                    $input__upload = count($this->Chains->read(array(
                        'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
                        'chainpostoutput' => $post_next['postid'],
                        'chainuserinput IN (' . join(',', $this->config->item('userids___43004')) . ')' => null,
                    )));
                    $skipping_not_allowed = count($this->Chains->read(array(
                        'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
                        'chainpostoutput' => $post_next['postid'],
                        'chainuserinput IN (' . join(',', $this->config->item('userids___43009')) . ')' => null,
                    )));


                    //Cleanup phone number:
                    if($input__text && strlen($next_post_data['post_createtext']) && !is_numeric($next_post_data['post_createtext']) && count($this->Chains->read(array(
                            'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
                            'chainpostoutput' => $post_next['postid'],
                            'chainuserinput' => 42181, //Phone
                        )))){
                        $next_post_data['post_createtext'] = preg_replace("/[^0-9]+/", "", $next_post_data['post_createtext']);
                        if(strlen($next_post_data['post_createtext'])<10){
                            return view_json(array(
                                'status' => 0,
                                'message' => 'Phone numbers cannot be less than 10 digits',
                            ));
                        }
                    }

                    $trying_to_skip = !strlen($next_post_data['post_createtext']);
                    $post_required = !$skipping_not_allowed && post_required($post_next);

                    if ($post_required && $trying_to_skip) {
                        return view_json(array(
                            'status' => 0,
                            'message' => 'Enter a valid response to '.view_post_title($post_next, true).' instead of "'.$next_post_data['post_createtext'].'"',
                        ));
                    }

                    //Try to complete:
                    $completion_status = $this->Chains->post_discovered(( $trying_to_skip ? 31022 : 4559 ), $user_session['userid'], $_POST['target_postid'], $post_next, $next_post_data, array(
                        'chainkey' => $next_post_data['postweight'],
                    ));
                    if ($post_required && !$completion_status['status']) {
                        //We had an error with data within target_postid:
                        //return view_json($completion_status);
                    }
                }
            }

            //Find Next:
            $post_redirect_url = false;
            foreach ($this->Posts->read(array(
                'postid' => $primary_postid,
            )) as $primary_i) {
                $post_redirect_url = post_redirect_url($primary_i);
            }
            if (!$post_redirect_url) {
                $post_next = $this->Chains->next_posts($user_session['userid'], $_POST['target_posthashtag']);
            }

            //All good:
            return view_json(array(
                'status' => 1,
                'message' => 'Saved & Next',
                'next__url' => ($post_redirect_url ? $post_redirect_url : ($post_next ? $post_next : 'start')),
            ));

        }

        //All good:
        return view_json(array(
            'status' => 0,
            'message' => 'Invalid Post',
        ));

    }

    function user_select()
    {

        if (!isset($_POST['focus__id']) || !isset($_POST['o__id']) || !isset($_POST['element_id']) || !isset($_POST['user_createid']) || !isset($_POST['migrateuser']) || !isset($_POST['chainid'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing core data',
            ));
        }

        //Validate migration users if any:
        $_POST['migrateuser'] = trim($_POST['migrateuser']);
        $first_letter = substr($_POST['migrateuser'], 0, 1);
        if ($first_letter == '@' && strlen($_POST['migrateuser']) > 1) {
            if (!count($this->Users->read(array(
                'LOWER(userhandle)' => strtolower(substr($_POST['migrateuser'], 1)),
            )))) {
                return view_json(array(
                    'status' => 0,
                    'message' => $_POST['migrateuser'] . ' is an invalid User User. Try again if you want to migrate this User chains or leave the field blank.',
                ));
            }
        } elseif ($first_letter == '#' && strlen($_POST['migrateuser']) > 1) {
            if (!count($this->Posts->read(array(
                'LOWER(posthashtag)' => strtolower(substr($_POST['migrateuser'], 1)),
            )))) {
                return view_json(array(
                    'status' => 0,
                    'message' => $_POST['migrateuser'] . ' is an invalid Post Post. Try again if you want to migrate this post chains or leave the field blank.',
                ));
            }
        } else {
            $_POST['migrateuser'] = '';
        }

        if (is_array($_POST['o__id'])) {
            $mass_result = array();
            foreach ($_POST['o__id'] as $o__id) {
                array_push($mass_result, $this->Chains->select($_POST['focus__id'], $o__id, $_POST['element_id'], $_POST['user_createid'], $_POST['migrateuser'], $_POST['chainid']));
            }
            return view_json($mass_result);
        } else {
            return view_json($this->Chains->select($_POST['focus__id'], $_POST['o__id'], $_POST['element_id'], $_POST['user_createid'], $_POST['migrateuser'], $_POST['chainid']));
        }

    }


    function chain_delete()
    {

        /*
         *
         * When members indicate they want to stop
         * a POST this function saves the changes
         * necessary and delete the post from their
         * discoveries.
         *
         * */

        $user_session = user_session(null, 0, $this->user_session);

        if (!$user_session) {
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

        //Remove Post
        $this->Chains->delete($_POST['chainid'], $user_session['userid']);

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
        $user_session = user_session(null, 0, $this->user_session);

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

        //See if we have any post or User targets to limit our stats:
        $has_user = isset($_POST['userhandle']) && strlen($_POST['userhandle']) && $_POST['userhandle'];
        $has_post = isset($_POST['posthashtag']) && strlen($_POST['posthashtag']) && $_POST['posthashtag'];

        if ($has_user) {

            //See stats for this User:
            $es = $this->Users->read(array(
                'LOWER(userhandle)' => strtolower($_POST['userhandle']),
            ));
            if (!count($es)) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid User',
                ));
            }

        } elseif ($has_post) {

            //See stats for this post:
            $is = $this->Posts->read(array(
                'LOWER(posthashtag)' => strtolower($_POST['posthashtag']),
            ));
            if (!count($is)) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Post',
                ));
            }

            $copy = $this->Posts->ids($is[0], 'ALL');
        }


        //Count Chains:
        $return_array = array(
            4341 => 0,
        );
        foreach ($this->config->item('users___33292') as $chainusertype1 => $m1) { //Stats

            $level1_total = 0;

            if($chainusertype1==1309754){

                //Voided
                if ($has_user) {
                    $void_filter['(chainvoid >0 AND ( chainuseroutput = ' . $es[0]['userid'] . ' OR chainuserinput = ' . $es[0]['userid'] . ' OR chainusercreator = ' . $es[0]['userid'] . ' ))'] = null;
                } elseif ($has_post) {
                    $void_filter['(chainvoid >0 AND ( chainpostinput = ' . $is[0]['postid'] . ' OR chainpostoutput = ' . $is[0]['postid'] . ' ))'] = null;
                } else {
                    //Void Chains
                    $void_filter = array(
                        'chainvoid > 0' => null, //Chains that have been voided
                    );
                }
                $sub_counter = $this->Chains->read($void_filter, array(), 0, 0, array(), 'COUNT(chainid) as totals');
                $return_array[$chainusertype1] = intval($sub_counter[0]['totals']);
                continue;
            }

            foreach ($this->config->item('users___' . $chainusertype1) as $chainusertype2 => $m2) {

                //Nodes/Chains
                $level2_total = 0;
                if ($chainusertype2 == 12273) {

                    if ($has_user) {

                        $sub_counter = $this->Chains->read(array(
                            'chainusertype IN (' . join(',', $this->config->item('userids___33602')) . ')' => null, //Post/User Chains Active
                            'chainuserinput' => $es[0]['userid'],
                        ), array('chainpostoutput'), 0, 0, array(), 'COUNT(chainid) as totals');

                    } elseif ($has_post && count($copy['recursive_post_ids'])) {

                        //See stats for this post:
                        $sub_counter = $this->Posts->read(array(
                            'postid IN (' . join(',', $copy['recursive_post_ids']) . ')' => null,
                        ), 0, 0, array(), 'COUNT(postid) as totals');

                    } else {

                        $sub_counter = $this->Chains->read(array(
                            'chainusertype' => $chainusertype2,
                        ), array(), 0, 0, array(), 'COUNT(chainid) as totals');

                    }

                    $level2_total += $sub_counter[0]['totals'];
                    $return_array[$chainusertype2] = intval($sub_counter[0]['totals']);

                } elseif ($chainusertype2 == 12274) {

                    if ($has_user) {

                        $sub_counter = $this->Chains->read(array(
                            'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                            'chainuserinput' => $es[0]['userid'],
                        ), array('chainuseroutput'), 0, 0, array(), 'COUNT(chainid) as totals');

                    } elseif ($has_post && count($copy['recursive_post_ids'])) {

                        //See stats for this post:
                        $sub_counter = $this->Chains->read(array(
                            'chainusertype IN (' . join(',', $this->config->item('userids___33602')) . ')' => null, //Post/User Chains Active
                            'chainpostoutput IN (' . join(',', $copy['recursive_post_ids']) . ')' => null,
                        ), array('chainuserinput'), 0, 0, array(), 'COUNT(chainid) as totals');

                    } else {

                        $sub_counter = $this->Chains->read(array(
                            'chainusertype' => $chainusertype2,
                        ), array(), 0, 0, array(), 'COUNT(chainid) as totals');

                    }

                    $level2_total += $sub_counter[0]['totals'];
                    $return_array[$chainusertype2] = intval($sub_counter[0]['totals']);

                } else {

                    foreach ($this->config->item('users___' . $chainusertype2) as $chainusertype3 => $m3) {

                        if ($has_user) {

                            $sub_counter = $this->Chains->read(array(
                                'chainusertype' => $chainusertype3,
                                '( chainuseroutput = ' . $es[0]['userid'] . ' OR chainuserinput = ' . $es[0]['userid'] . ' OR chainusercreator = ' . $es[0]['userid'] . ' )' => null,
                            ), array(), 0, 0, array(), 'COUNT(chainid) as totals');

                        } elseif ($has_post && count($copy['recursive_post_ids'])) {

                            $sub_counter = $this->Chains->read(array(
                                'chainusertype' => $chainusertype3,
                                '( chainpostinput IN (' . join(',', $copy['recursive_post_ids']) . ') OR chainpostoutput IN (' . join(',', $copy['recursive_post_ids']) . '))' => null,
                            ), array(), 0, 0, array(), 'COUNT(chainid) as totals');

                        } else {

                            $sub_counter = $this->Chains->read(array(
                                'chainusertype' => $chainusertype3,
                            ), array(), 0, 0, array(), 'COUNT(chainid) as totals');

                        }

                        $level2_total += $sub_counter[0]['totals'];
                        $return_array[$chainusertype3] = intval($sub_counter[0]['totals']);

                    }

                }

                $level1_total += $level2_total;
                $return_array[$chainusertype2] = intval($level2_total);

            }

            $return_array[$chainusertype1] = intval($level1_total);
            $return_array[4341] += intval($level1_total);

        }
        return view_json(array(
            'status' => 1,
            'return_array' => $return_array,
        ));
    }

}