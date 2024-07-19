<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        auto_login_player(false);
    }

    function index(){
        //Home:
        $this->load(14565);
    }

    function load($app_e__id = 14563 /* Error if none provided */, $focus_handle = 0, $focus_hashtag = 0, $target_hashtag = 0){


        $memory_detected = is_array($this->config->item('n___6287')) && count($this->config->item('n___6287'));
        if(!$memory_detected){
            //Since we don't have the memory created we must load the app that does so:
            $app_e__id = 4527;
        }


        //Any ideas passed?
        $e___6287 = $this->config->item('e___6287'); //APP
        $flash_message = false;
        $focus_e = null; //Sourcing
        $focus_i = null; //Ideation/Discovery
        $target_i = null; //Discovery


        if(isset($_GET['e__handle']) && $_GET['e__handle']=='SuccessfulWhale'){
            $_GET['e__handle'] = '';
            $focus_handle = '';
        } elseif($focus_handle && strlen($focus_handle) && !isset($_GET['e__handle'])){
            $_GET['e__handle'] = $focus_handle;
        }
        if($focus_hashtag && strlen($focus_hashtag) && !isset($_GET['i__hashtag'])){
            $_GET['i__hashtag'] = $focus_hashtag;
        }
        if(!isset($_GET['e__handle'])){
            $_GET['e__handle'] = 0;
        }
        if(!isset($_GET['i__hashtag'])){
            $_GET['i__hashtag'] = 0;
        }


        if($target_hashtag && strlen($target_hashtag)){
            //Verify:
            foreach($this->I_model->fetch(array(
                'LOWER(i__hashtag)' => strtolower($target_hashtag),
            )) as $i_found){
                $target_i = $i_found;
            }
        }


        if(strlen($_GET['i__hashtag'])){

            //Validate Focus Idea:
            if($target_i && $_GET['i__hashtag']==view_memory(6404,4235)){

                //This is the starting point:
                $_GET['i__hashtag'] = $target_hashtag;
                $focus_i = $target_i;

            } else {

                foreach($this->I_model->fetch(array(
                    'LOWER(i__hashtag)' => strtolower($_GET['i__hashtag']),
                )) as $i_found){
                    $focus_i = $i_found;
                }

            }

            if(!$focus_i){
                //See if we can find via ID?
                if(is_numeric($_GET['i__hashtag'])){
                    foreach($this->I_model->fetch(array(
                        'i__id' => $_GET['i__hashtag'],
                    )) as $i_found){
                        $focus_i = $i_found;
                    }
                }
            }

            if($app_e__id==33286 && $focus_i && $focus_i['i__hashtag']!==$_GET['i__hashtag']){
                //Adjust URL Case Sensitive:
                return redirect_message(view_memory(42903,33286).$focus_i['i__hashtag']);
            }
        }

        
        if(isset($_GET['e__handle']) && strlen($_GET['e__handle'])){
            foreach($this->E_model->fetch(array(
                'LOWER(e__handle)' => strtolower($_GET['e__handle']),
            )) as $e_found){
                $focus_e = $e_found;
            }
            if(!$focus_e){
                //See if we need to lookup the ID:
                if(is_numeric($_GET['e__handle'])){
                    //Maybe its an ID?
                    foreach ($this->E_model->fetch(array(
                        'e__id' => $_GET['e__handle'],
                    )) as $e_found){
                        $focus_e = $e_found;
                    }
                }
            }
            if($app_e__id==42902 && $focus_e && $focus_e['e__handle']!==$_GET['e__handle']){
                //Adjust URL Case Sensitive:
                return redirect_message(view_memory(42903,42902).$focus_e['e__handle']);
            }
        }







        if($memory_detected && !in_array($app_e__id, $this->config->item('n___6287'))){
            //Invalid App:
            return redirect_message(view_memory(42903,42902).$e___6287[$app_e__id]['m__handle'], '<div class="alert alert-danger" role="alert">@'.$e___6287[$app_e__id]['m__handle'].' Is not an APP, yet 🤔</div>');
        } elseif($memory_detected && !in_array($app_e__id, $this->config->item('n___42922'))){
            //Validate Required App input:
            if(in_array($app_e__id, $this->config->item('n___42905')) && !$focus_e){
                return redirect_message( home_url(), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Error: @'.$_GET['e__handle'].' is not a valid source handle.</div>');
            } elseif(in_array($app_e__id, $this->config->item('n___42923')) && (!$focus_i || !$target_i)){
                return redirect_message( home_url(), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Error: Both #'.$_GET['i__hashtag'].' & #'.$target_hashtag.' must be valid hashtags.</div>');
            } elseif(in_array($app_e__id, $this->config->item('n___42911')) && !$focus_i){
                return redirect_message( home_url(), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Error: #'.$_GET['i__hashtag'].' is not a valid idea hashtag.</div>');
            }
        }



        $x__follower = ( $focus_e ? $focus_e['e__id'] : 0 );
        $x__next = ( $focus_i ? $focus_i['i__id'] : 0 );
        $x__previous = ($target_i ? $target_i['i__id'] : 0 );

        //Run App
        $player_e = false;
        $player_http_request = ( isset($_SERVER['SERVER_NAME']) ? 1 : 0 );

        if($memory_detected && in_array($app_e__id, $this->config->item('n___42920'))){
            boost_power();
        }

        if($memory_detected && $player_http_request){

            //Needs superpowers?
            $player_e = superpower_unlocked();

            //Auto Login?
            if(isset($_GET['e__hash']) && isset($_GET['e__time']) && $focus_e){

                //Validate Hash:
                if($_GET['e__hash'] == view__hash($_GET['e__time'].$focus_e['e__handle'])){

                    if($focus_i){
                        if(i_startable($focus_i)){
                            $flash_message = '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="far fa-play"></i></span>You have started discovering this idea. Scroll to the bottom & go next to continue.</div>';
                        } else {
                            $this->X_model->mark_complete(i__discovery_link($focus_i), $focus_e['e__id'], ( $target_i ? $target_i['i__id'] : 0 ), $focus_i);
                            $this->X_model->mark_complete(29393, $focus_e['e__id'], ( $target_i ? $target_i['i__id'] : 0 ), $focus_i);

                            //Inform user of changes:
                            $flash_message = '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="far fa-check-circle"></i></span>Idea has been discovered</div>';
                        }
                    }

                    //If not logged in, log them in:
                    if(!$player_e){
                        $session_data = $this->E_model->activate_session($player_e, true);
                    }

                }
            }
        }


        //Cache App?
        $x__metadata = array(
            'current_link' => 'https://' .get_server('SERVER_NAME') . get_server('REQUEST_URI'),
            '$_GET' => $_GET,
            '$_POST' => $_POST,
            '$_REQUEST' => $_REQUEST,
            'php_input' => json_decode(file_get_contents('php://input')),
        );
        $ui = null;
        $new_cache = false;
        $cache_x__time = null;
        $x__player = ( $player_http_request ? ( $player_e ? $player_e['e__id'] : 14068 /* GUEST */ ) : 7274 /* CRON JOB */ );
        $access_level_e = access_level_e(null, $focus_e['e__id'], $focus_e);
        $access_level_i = access_level_i(null, $focus_i['i__id'], $focus_i);
        $target_access_level_i = access_level_i(null, $target_i['i__id'], $target_i);

        //MEMBER REDIRECT?
        if($player_http_request){

            //Missing App, Source or Idea Access?
            $missing_access = false; //Assume they have access
            $superpowers_required = array_intersect($this->config->item('n___10957'), $e___6287[$app_e__id]['m__following']);
            if($player_e && in_array($app_e__id, $this->config->item('n___14639'))){
                //Should redirect them:
                return redirect_message(view_memory(42903,42902).$player_e['e__handle']);
            } elseif(!$player_e && in_array($app_e__id, $this->config->item('n___14740'))){
                //Should redirect them:
                $missing_access = 'Login or register a free account to continue.';
            } elseif(count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
                $e___10957 = $this->config->item('e___10957');
                $missing_access = 'Error: You Cannot Access '.$e___6287[$app_e__id]['m__title'].' as it requires the superpower of '.$e___10957[end($superpowers_required)]['m__title'].'.';
            } elseif($focus_e && !$access_level_e){
                $missing_access = 'Error: You Cannot Access @'.$focus_e['e__handle'].' due to Privacy Settings.';
            } elseif($focus_i && !$access_level_i){
                $missing_access = 'Error: You Cannot Access Focus #'.$focus_i['i__hashtag'].' due to Privacy Settings.';
            } elseif($target_i && !$target_access_level_i){
                $missing_access = 'Error: You Cannot Access Target #'.$target_i['i__hashtag'].' due to Privacy Settings.';
            }


            if($missing_access){
                //Redirect:
                return redirect_message((!$player_e ? view_app_link(4269).'?url='.urlencode($_SERVER['REQUEST_URI']) : home_url() ), '<div class="alert alert-warning" role="alert">'.$missing_access.'</div>');
            }
        }




        if($memory_detected){

            if(in_array($app_e__id, $this->config->item('n___14599')) && !in_array($app_e__id, $this->config->item('n___12741'))){

                if(!isset($_GET['reset_cache'])){
                    //Fetch Most Recent Cache:
                    foreach($this->X_model->fetch(array(
                        'x__website' => website_setting(0),
                        'x__type' => 14599, //Cache App
                        'x__following' => $app_e__id,
                        'x__follower' => $x__follower,
                        'x__previous' => $x__previous,
                        'x__next' => $x__next,
                        'x__time >' => date("Y-m-d H:i:s", (time() - view_memory(6404,14599))),
                        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    ), array(), 1, 0, array('x__time' => 'DESC')) as $latest_cache){
                        $ui = $latest_cache['x__message'];
                        $cache_x__time = '<div class="texttransparent center main__title">Updated ' . view_time_difference($latest_cache['x__time']) . ' Ago</div>';
                    }
                }

                if(!$ui){
                    //No recent cache found, create a new one:
                    $new_cache = true;
                }
            }
        }


        $title = null;
        if($focus_i){
            $title .= view_i_title($focus_i, true).' | ';
        }
        if($target_i){
            $title .= view_i_title($target_i, true).' | ';
        }
        if($focus_e){
            $title .= $focus_e['e__title'].' @'.$focus_e['e__handle'].' | ';
        }
        if(!$title){
            //Append app name since no title:
            $title .= $e___6287[$app_e__id]['m__title'].' | ';
        }
        //Always Append Website at the end:
        $title .= get_domain('m__title');



        $view_input = array(
            'app_e__id' => $app_e__id,
            'x__player' => $x__player,
            'player_e' => $player_e,
            'player_http_request' => $player_http_request,
            'memory_detected' => $memory_detected,

            'focus_e' => $focus_e,
            'focus_i' => $focus_i,
            'target_i' => $target_i,

            '$access_level_e' => $access_level_e,
            '$access_level_i' => $access_level_i,
            '$target_access_level_i' => $target_access_level_i,

            'title' => $title,
            'flash_message' => $flash_message,
        );

        if(!$ui){
            //Prep view:
            $raw_app = $this->load->view('app/'.strtolower($e___6287[$app_e__id]['m__handle']), $view_input, true);
            $ui .= $raw_app;
        }


        if($new_cache){
            $cache_x = $this->X_model->create(array(
                'x__player' => $x__player,
                'x__type' => 14599, //Cache App
                'x__following' => $app_e__id,
                'x__message' => $ui,

                'x__follower' => $x__follower,
                'x__previous' => $x__previous,
                'x__next' => $x__next,
            ));
        }


        //Log Interaction:
        $this->X_model->create(array(
            'x__player' => $x__player,
            'x__type' => $app_e__id,

            'x__metadata' => $x__metadata,
            'x__follower' => $x__follower,
            'x__previous' => $x__previous,
            'x__next' => $x__next,
        ));


        //App title?
        if(in_array($app_e__id, $this->config->item('n___42928'))){
            $ui = '<h1>'.$e___6287[$app_e__id]['m__title'].'</h1>'.$ui;
        }


        //Check to ensure they have started:
        if($app_e__id==30795 && $target_i && $focus_i && $player_e && $target_i['i__hashtag']==$focus_i['i__hashtag']){

            //Starting point, make sure all good:
            if(!i_startable($target_i)){

                //Not a valid starting point:
                return redirect_message(home_url(), '<div class="alert alert-warning" role="alert">#'.$target_i['i__hashtag'].' is not an active starting point.</div>');

            } elseif(!$this->X_model->i_has_started($player_e['e__id'], $target_i['i__hashtag'])){

                //Not yet started, add to their starting point:
                $this->X_model->create(array(
                    'x__player' => $player_e['e__id'],
                    'x__type' => 4235, //Get started
                    'x__next' => $target_i['i__id'],
                    'x__previous' => $target_i['i__id'],
                ));

                //Mark as complete:
                $this->X_model->mark_complete(i__discovery_link($target_i), $player_e['e__id'], $target_i['i__id'], $target_i);

                //Now return next idea:
                $next_i__hashtag = $this->X_model->find_next($player_e['e__id'], $target_i['i__hashtag'], $target_i);
                if($next_i__hashtag){
                    //Go Next:
                    return redirect_message(view_memory(42903,30795).$target_i['i__hashtag'].'/'.$next_i__hashtag );
                }

            }

        }




        //Delivery App
        if(!$memory_detected){

            echo $ui;

        } else {

            if(in_array($app_e__id, $this->config->item('n___12741'))){

                //Raw UI:
                echo $raw_app;

            } else {

                //Regular UI:
                //Load App:
                echo $this->load->view('includes/header', $view_input, true);
                echo $ui;
                echo $cache_x__time;
                echo $this->load->view('includes/footer', array(), true);

            }
        }
    }
}