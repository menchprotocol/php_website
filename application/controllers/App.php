<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        auto_login();
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
        $warning_alerts = '';
        $focus_e = null; //Sourcing
        $focus_i = null; //Ideation/Discovery
        $target_i = null; //Discovery

        if($focus_handle && strlen($focus_handle) && !isset($_GET['e__handle'])){
            $_GET['e__handle'] = $focus_handle;
        }
        if($focus_hashtag && strlen($focus_hashtag) && !isset($_GET['i__hashtag'])){
            $_GET['i__hashtag'] = $focus_hashtag;
        }

        if($target_hashtag && strlen($target_hashtag)){
            //Verify:
            foreach($this->I_model->fetch(array(
                'LOWER(i__hashtag)' => strtolower($target_hashtag),
            )) as $i_found){
                $target_i = $i_found;
            }
            if(!$focus_i){
                $warning_alerts .=  '<div class="alert alert-danger" role="alert">#'.$_GET['i__hashtag'].' is not a valid hashtag 🤔</div>';
            }
        }

        if(isset($_GET['i__hashtag']) && strlen($_GET['i__hashtag'])){
            foreach($this->I_model->fetch(array(
                'LOWER(i__hashtag)' => strtolower($_GET['i__hashtag']),
            )) as $i_found){
                $focus_i = $i_found;
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

                if(!$focus_i){
                    $warning_alerts .=  '<div class="alert alert-danger" role="alert">#'.$_GET['i__hashtag'].' is not a valid hashtag 🤔</div>';
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

                if(!$focus_e){
                    $warning_alerts .=  '<div class="alert alert-danger" role="alert">@'.$_GET['e__handle'].' is not a valid handle 🤔</div>';
                }
            }
            if($app_e__id==42902 && $focus_e && $focus_e['e__handle']!==$_GET['e__handle']){
                //Adjust URL Case Sensitive:
                return redirect_message(view_memory(42903,42902).$focus_e['e__handle']);
            }
        }



        //Validate App
        if($memory_detected && !in_array($app_e__id, $this->config->item('n___6287'))){
            foreach($this->E_model->fetch(array('e__id' => $app_e__id)) as $this_e){
                return redirect_message(view_memory(42903,42902).$this_e['e__handle'], '<div class="alert alert-danger" role="alert">@'.$this_e['e__handle'].' Is not an APP, yet 🤔</div>');
            }
        }


        //Access Levels
        if ( $focus_i && !in_array($focus_i['i__privacy'], $this->config->item('n___31871')) && !write_privacy_i($focus_i['i__hashtag'])){
            return redirect_message( ( $target_i ? view_memory(42903,33286).$target_i['i__hashtag'] : home_url() ), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Idea #' . $focus_hashtag . ' is not public and you are missing permission to access.</div>');
        }




        //Run App
        $flash_message = false;
        $player_e = false;
        $is_u_request = isset($_SERVER['SERVER_NAME']);
        $e___6287 = $this->config->item('e___6287'); //APP

       // if(in_array($app_e__id, $this->config->item('n___42920'))){
            //boost_power();
       // }

        if($memory_detected && $is_u_request){

            //Needs superpowers?
            $player_e = superpower_unlocked();

            //Auto Login?
            if(isset($_GET['e__hash']) && isset($_GET['e__time']) && $focus_e){

                //Validate Hash:
                if($_GET['e__hash'] == view__hash($_GET['e__time'].$focus_e['e__handle'])){

                    $this->X_model->x_read_only_complete($focus_e['e__id'], ( $target_i ? $target_i['i__id'] : 0 ), $focus_i);
                    $this->X_model->mark_complete(29393, $focus_e['e__id'], ( $target_i ? $target_i['i__id'] : 0 ), $focus_i);

                    //Inform user of changes:
                    $flash_message = '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>Idea has been discovered</div>';

                    //If not logged in, log them in:
                    if(!$player_e){
                        $session_data = $this->E_model->activate_session($player_e, true);
                    }

                } else {

                    $flash_message = '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>Invalid Hash: Idea could not be discovered at this time.</div>';

                }
            }



            $superpowers_required = array_intersect($this->config->item('n___10957'), $e___6287[$app_e__id]['m__following']);
            if($is_u_request && count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
                if(!$player_e){
                    //No user, maybe they can login to get it:
                    return redirect_message(view_app_link(4269).'?url='.urlencode($_SERVER['REQUEST_URI']), '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-lock-open"></i></span>Login to gain access.</div>');
                } else {
                    die(view_unauthorized_message(end($superpowers_required)));
                }
            }




        }

        $x__player = ( $is_u_request ? ( $player_e ? $player_e['e__id'] : 14068 /* GUEST */ ) : 7274 /* CRON JOB */ );


        //MEMBER REDIRECT?
        if($player_e && in_array($app_e__id, $this->config->item('n___14639'))){
            //Should redirect them:
            return redirect_message(view_memory(42903,42902).$player_e['e__handle']);
        } elseif(!$player_e && in_array($app_e__id, $this->config->item('n___14740'))){
            //Should redirect them:
            return redirect_message(view_app_link(4269).'?url='.urlencode($_SERVER['REQUEST_URI']), '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-lock-open"></i></span>Login to <b>'.$e___6287[$app_e__id]['m__title'].'</b></div>');
        }



        $x_completes = array();
        if($player_e && $app_e__id==30795) {

            //Fetch discovery
            $x_completes = $this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                'x__player' => $player_e['e__id'],
                'x__previous' => $focus_i['i__id'],
                'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            ), array('x__next'));

            //Missing focus Idea?
            if(!$target_i) {

                //Do we have a direct discovery?
                $this_discovery = null;
                foreach($x_completes as $x){
                    $this_discovery = $x['i__hashtag'];
                    break;
                }

                if($this_discovery){
                    //We have a discovery here, make sure its not the same as the starting point:
                    if($this_discovery!=$focus_hashtag){
                        return redirect_message(view_memory(42903,30795).$this_discovery.'/'.$focus_hashtag);
                    }
                } else {
                    //No discovery here, let's see if we can find any above:
                    $target_x_i__hashtag = $this->X_model->find_previous_discovered($focus_i['i__id'], $player_e['e__id']);
                    if($target_x_i__hashtag){
                        return redirect_message(view_memory(42903,30795).$target_x_i__hashtag.'/'.$focus_hashtag);
                    }
                }
            }
        }

        //Validate Focus Idea:
        if(0 && $focus_hashtag==view_memory(6404,4235)){

            if($player_e){
                //See if they have already started or need to start?
                if(!$this->X_model->i_has_started($player_e['e__id'], $target_i['i__hashtag'])) {
                    //Go to start:
                    return redirect_message('/ajax/x_start/'.$target_i['i__hashtag']);
                }
            } else {
                //Force login?

            }

            //This is the starting point:
            $focus_hashtag = $target_hashtag;

        }



        //Cache App?
        $x__previous = ( $focus_i ? $focus_i['i__id'] : 0 );
        $x__follower = ( $focus_e ? $focus_e['e__id'] : 0 );
        $title = null;
        $ui = null;
        $new_cache = false;
        $cache_x__id = 0;
        $cache_x__time = null;
        if($memory_detected){
            
            $title = $e___6287[$app_e__id]['m__title'];

            if(in_array($app_e__id, $this->config->item('n___14599')) && !in_array($app_e__id, $this->config->item('n___12741'))){

                if(!isset($_GET['reset_cache'])){
                    //Fetch Most Recent Cache:
                    foreach($this->X_model->fetch(array(
                        'x__website' => website_setting(0),
                        'x__type' => 14599, //Cache App
                        'x__following' => $app_e__id,
                        'x__previous' => $x__previous,
                        'x__follower' => $x__follower,
                        'x__time >' => date("Y-m-d H:i:s", (time() - view_memory(6404,14599))),
                        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    ), array(), 1, 0, array('x__time' => 'DESC')) as $latest_cache){
                        $ui = $latest_cache['x__message'];
                        $cache_x__id = $latest_cache['x__id'];
                        $cache_x__time = '<div class="texttransparent center main__title">Updated ' . view_time_difference($latest_cache['x__time']) . ' Ago</div>';
                    }
                }

                if(!$ui){
                    //No recent cache found, create a new one:
                    $new_cache = true;
                }
            }
        }



        if(!$ui){

            //Prep view:
            $raw_app = $this->load->view('app/'.$app_e__id, array(
                'app_e__id' => $app_e__id,
                'x__player' => $x__player,
                'player_e' => $player_e,
                'is_u_request' => $is_u_request,
                'memory_detected' => $memory_detected,
                'focus_e' => $focus_e,
                'focus_i' => $focus_i,
                'target_i' => $target_i,
                'x_completes' => $x_completes,
            ), true);

            $ui .= $raw_app;
        }

        if($new_cache){
            $cache_x = $this->X_model->create(array(
                'x__player' => $x__player,
                'x__type' => 14599, //Cache App
                'x__following' => $app_e__id,
                'x__message' => $ui,
                'x__previous' => $x__previous,
                'x__follower' => $x__follower,
            ));
            $cache_x__id = $cache_x['x__id'];
        }
        

        //Log App Load:
        $interaction = array(
            'x__player' => $x__player,
            'x__type' => 14067, //APP LOADED $app_e__id
            'x__following' => $app_e__id,
            'x__reference' => $cache_x__id,
            'x__metadata' => array(
                '$_GET' => $_GET,
                '$_POST' => $_POST,
                'REQUEST' => $_REQUEST,
            ),
        );

        //Called when the paypal payment is complete:
        $this->X_model->create(array(
            'x__type' => 27901,
            'x__metadata' => array(
                'POST' => $_POST,
                'GET' => $_GET,
            ),
        ));

        //Append additional info for members:
        if($is_u_request){

            $interaction['x__message'] = current_link();

            //Any more data to append?
            if(isset($_GET['e__handle']) && strlen($_GET['e__handle'])){
                foreach($this->E_model->fetch(array(
                    'LOWER(e__handle)' => strtolower($_GET['e__handle']),
                )) as $e){
                    $interaction['x__follower'] = $e['e__id'];
                    $title = $e['e__title'].' | '.$title;
                }
            }

            if(isset($_GET['i__hashtag']) && strlen($_GET['i__hashtag'])){
                foreach($this->I_model->fetch(array(
                    'LOWER(i__hashtag)' => strtolower($_GET['i__hashtag']),
                )) as $i_this){
                    $interaction['x__previous'] = $i_this['i__id'];
                    $title = view_i_title($i_this, true).' | '.$title;
                }
            }
        }

        //Log Interaction:
        $this->X_model->create($interaction);

        //Delivery App
        if(!$memory_detected){

            echo $ui;

        } else {

            if(in_array($app_e__id, $this->config->item('n___12741'))){

                //Raw UI:
                echo $raw_app;

            } else {

                $basic_header = intval(in_array($app_e__id, $this->config->item('n___14562')));

                //Regular UI:
                //Load App:
                echo $this->load->view('header', array(
                    'title' => $title,
                    'basic_header_footer' => $basic_header,
                    'app_e__id' => $app_e__id,
                    'flash_message' => $flash_message,
                ), true);
                echo $ui;
                echo $cache_x__time;
                echo $this->load->view('footer', array(
                    'basic_header_footer' => $basic_header,
                ), true);

            }
        }
    }
}