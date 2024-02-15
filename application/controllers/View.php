<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class View extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        auto_login();

    }

    function index(){
        //Default Home Page:
        //TODO Can load a customized home page app per website...
        $this->app_load(14565);
    }

    function app_load($app_e__id = 14563 /* Error if none provided */){

        $memory_detected = is_array($this->config->item('n___6287')) && count($this->config->item('n___6287'));
        if(!$memory_detected){
            //Since we don't have the memory created we must load the app that does so:
            $app_e__id = 4527;
        }

        //Any ideas passed?
        $warning_alerts = '';
        $i = null;
        $e = null;

        if(isset($_GET['i__hashtag'])){
            foreach($this->I_model->fetch(array(
                'LOWER(i__hashtag)' => strtolower($_GET['i__hashtag']),
            )) as $i_found){
                $i = $i_found;
            }
            if(!$i){
                $warning_alerts .=  '<div class="alert alert-danger" role="alert">#'.$_GET['i__hashtag'].' is not a valid hashtag 🤫</div>';
            }
        }
        
        if(isset($_GET['e__handle'])){
            foreach($this->E_model->fetch(array(
                'LOWER(e__handle)' => strtolower($_GET['e__handle']),
            )) as $e_found){
                $e = $e_found;
            }
            if(!$e){
                $warning_alerts .=  '<div class="alert alert-danger" role="alert">@'.$_GET['e__handle'].' is not a valid handle 🤫</div>';
            }
        }


        //Validate App
        if($memory_detected && !in_array($app_e__id, $this->config->item('n___6287'))){
            foreach($this->E_model->fetch(array('e__id' => $app_e__id)) as $e){
                return redirect_message('/@'.$e['e__handle'], '<div class="alert alert-danger" role="alert">@'.$e['e__handle'].' Is not an APP, yet 🤫</div>');
            }
        }


        //Run App
        boost_power();
        $member_e = false;
        $is_u_request = isset($_SERVER['SERVER_NAME']);
        $e___6287 = $this->config->item('e___6287'); //APP
        $e___11035 = $this->config->item('e___11035'); //Summary

        if($memory_detected && $is_u_request){
            //Needs superpowers?
            $member_e = superpower_unlocked();
            $superpowers_required = array_intersect($this->config->item('n___10957'), $e___6287[$app_e__id]['m__following']);
            if($is_u_request && count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
                if(!$member_e){
                    //No user, maybe they can login to get it:
                    return redirect_message(view_app_link(4269).'?url='.urlencode($_SERVER['REQUEST_URI']), '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-lock-open"></i></span>Login to gain access.</div>');
                } else {
                    die(view_unauthorized_message(end($superpowers_required)));
                }
            }
        }

        $x__creator = ( $is_u_request ? ( $member_e ? $member_e['e__id'] : 14068 /* GUEST */ ) : 7274 /* CRON JOB */ );


        //MEMBER REDIRECT?
        if($member_e && in_array($app_e__id, $this->config->item('n___14639'))){
            //Should redirect them:
            return redirect_message('/@'.$member_e['e__handle']);
        } elseif(!$member_e && in_array($app_e__id, $this->config->item('n___14740'))){
            //Should redirect them:
            return redirect_message(view_app_link(4269).'?url='.urlencode($_SERVER['REQUEST_URI']), '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-lock-open"></i></span>Login to <b>'.$e___6287[$app_e__id]['m__title'].'</b></div>');
        }


        //Cache App?
        $x__previous = ( $i ? $i['i__id'] : 0 );
        $x__follower = ( $e ? $e['e__id'] : 0 );
        $title = null;
        $ui = null;
        $new_cache = false;
        $cache_x__id = 0;
        $cache_x__time = null;
        if($memory_detected){

            $es = $this->E_model->fetch(array(
                'e__id' => $app_e__id,
            ));
            $e___6287 = $this->config->item('e___6287'); //APP
            $title = $e___6287[$app_e__id]['m__title'].( public_app($es[0]) ? ' | '.$e___11035[6287]['m__title'] : '' );

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
                'x__creator' => $x__creator,
                'member_e' => $member_e,
                'is_u_request' => $is_u_request,
                'memory_detected' => $memory_detected,
            ), true);

            if($memory_detected && !in_array($app_e__id, $this->config->item('n___14597'))){
                $ui .= '<h1>' . $e___6287[$app_e__id]['m__title'] . '</h1>';
            }
            $ui .= $raw_app;
        }

        if($new_cache){
            $cache_x = $this->X_model->create(array(
                'x__creator' => $x__creator,
                'x__type' => 14599, //Cache App
                'x__following' => $app_e__id,
                'x__message' => $ui,
                'x__previous' => $x__previous,
                'x__follower' => $x__follower,
            ));
            $cache_x__id = $cache_x['x__id'];
        }



        //Log App Load:
        $log_data = array(
            'x__creator' => $x__creator,
            'x__type' => 14067, //APP LOADED
            'x__following' => $app_e__id,
            'x__reference' => $cache_x__id,
            'x__metadata' => array(
                '$_GET' => $_GET,
                '$_POST' => $_POST,
            ),
        );

        //Append additional info for members:
        if($is_u_request){

            $log_data['x__message'] = current_link();

            //Any more data to append?
            if(isset($_GET['e__handle']) && strlen($_GET['e__handle'])){
                foreach($this->E_model->fetch(array(
                    'LOWER(e__handle)' => strtolower($_GET['e__handle']),
                )) as $e){
                    $log_data['x__follower'] = $e['e__id'];
                    $title = $e['e__title'].' | '.$title;
                }
            }

            if(isset($_GET['i__hashtag']) && strlen($_GET['i__hashtag'])){
                foreach($this->I_model->fetch(array(
                    'LOWER(i__hashtag)' => strtolower($_GET['i__hashtag']),
                )) as $i){
                    $log_data['x__previous'] = $i['i__id'];
                    $title = view_i_title($i, true).' | '.$title;
                }
            }
        }

        $x = $this->X_model->create($log_data);



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
                ), true);
                echo $ui;
                echo $cache_x__time;
                echo $this->load->view('footer', array(
                    'basic_header_footer' => $basic_header,
                ), true);

            }
        }
    }

    function e_layout($e__handle)
    {

        //Validate source ID and fetch data:
        $es = $this->E_model->fetch(array(
            'LOWER(e__handle)' => strtolower($e__handle),
        ));
        if (count($es) < 1) {

            //See if we need to lookup the ID:
            if(is_numeric($e__handle)){
                //Maybe its an ID?
                foreach ($this->E_model->fetch(array(
                    'e__id' => $e__handle,
                )) as $e_redirect){
                    return redirect_message('/@'.$e_redirect['e__handle']);
                }
            }

            return redirect_message(home_url());
        } elseif($es[0]['e__handle']!==$e__handle){
            //Adjust URL:
            return redirect_message('/@'.$es[0]['e__handle']);
        }

        $member_e = superpower_unlocked();
        //Make sure not a private source:
        if(!in_array($es[0]['e__privacy'], $this->config->item('n___33240') /* PUBLIC/GUEST Access */) && !write_privacy_e($es[0]['e__handle'])){
            $member_e = superpower_unlocked(13422, true);
        }

        $e___14874 = $this->config->item('e___14874'); //Mench Cards

        //Load views:
        $this->load->view('header', array(
            'title' => $es[0]['e__title'].' @'.$es[0]['e__handle'].' | '.$e___14874[12274]['m__title'],
        ));
        $this->load->view('e_layout', array(
            'e' => $es[0],
            'member_e' => $member_e,
        ));
        $this->load->view('footer');

    }

    function i_layout($i__hashtag){

        //Validate/fetch Idea:
        $is = $this->I_model->fetch(array(
            'LOWER(i__hashtag)' => strtolower($i__hashtag),
        ));
        if ( count($is) < 1) {

            //See if we can find via ID?
            if(is_numeric($i__hashtag)){
                foreach($this->I_model->fetch(array(
                    'i__id' => $i__hashtag,
                )) as $go){
                    return redirect_message('/'.$go['i__hashtag']);
                }
            }

            return redirect_message(home_url(), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>IDEA #' . $i__hashtag . ' Not Found</div>');

        }

        $member_e = superpower_unlocked(); //Idea Pen?
        if(!$member_e){
            if(in_array($is[0]['i__privacy'], $this->config->item('n___31871'))){
                return redirect_message('/'.$i__hashtag);
            } else {
                return redirect_message(home_url(), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>IDEA #' . $i__hashtag . ' is not published yet.</div>');
            }
        }

        //Import Discoveries?
        $flash_message = '';
        if(isset($_GET['e__handle'])){
            foreach($this->E_model->fetch(array(
                'LOWER(e__handle)' => strtolower($_GET['e__handle']),
            )) as $e_append){
                $completed = 0;
                foreach($this->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                    'x__previous' => $is[0]['i__id'],
                ), array(), 0) as $x){
                    if(!count($this->X_model->fetch(array(
                        'x__following' => $e_append['e__id'],
                        'x__follower' => $x['x__creator'],
                        'x__message' => $x['x__message'],
                        'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    )))){
                        //Add source link:
                        $completed++;
                        $this->X_model->create(array(
                            'x__creator' => ($member_e ? $member_e['e__id'] : $x['x__creator']),
                            'x__following' => $e_append['e__id'],
                            'x__follower' => $x['x__creator'],
                            'x__message' => $x['x__message'],
                            'x__type' => 4230,
                        ));
                    }
                }

                $flash_message = '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span> '.$completed.' sources who played this idea added to @'.$e_append['e__handle'].'</div>';
            }
        }

        $e___14874 = $this->config->item('e___14874'); //Mench Cards

        //Load views:
        $this->load->view('header', array(
            'title' => view_i_title($is[0], true).' | '.$e___14874[12273]['m__title'],
            'flash_message' => $flash_message,
        ));
        $this->load->view('i_layout', array(
            'focus_i' => $is[0],
            'member_e' => $member_e,
        ));
        $this->load->view('footer');

    }

    function x_layout($top_i__hashtag=null, $focus_i__hashtag)
    {

        /*
         *
         * Enables a Member to DISCOVER an IDEA
         * on the public web
         *
         * */

        $flash_message = null;
        $member_e = superpower_unlocked();
        $focus_es = array();

        if($top_i__hashtag && $top_i__hashtag==$focus_i__hashtag){
            //Cleaner URL:
            return redirect_message('/'.$focus_i__hashtag);
        }

        if(isset($_GET['e__handle'])){
            $focus_es = $this->E_model->fetch(array(
                'LOWER(e__handle)' => strtolower($_GET['e__handle']),
            ));
            if(!count($focus_es)){
                return redirect_message( home_url(), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Invalid User Handler</div>');
            }
        } elseif($member_e){
            $focus_es[0] = $member_e;
        }

        //Validate Top Idea:
        $top_is = array();
        if($top_i__hashtag && count($focus_es)){
            $top_is = $this->I_model->fetch(array(
                'LOWER(i__hashtag)' => strtolower($top_i__hashtag),
            ));
            if ( !count($top_is) ) {
                return redirect_message(home_url(), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Top Idea #' . $top_i__hashtag . ' not found</div>');
            }
        }


        //Validate Focus Idea:
        $focus_is = $this->I_model->fetch(array(
            'LOWER(i__hashtag)' => strtolower($focus_i__hashtag),
        ));
        if ( !count($focus_is) ) {
            return redirect_message( ( $top_i__hashtag ? '/'.$top_i__hashtag : home_url() ), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Invalid Handle #' . $focus_i__hashtag . '</div>');
        } elseif(!in_array($focus_is[0]['i__privacy'], $this->config->item('n___31871')) && !write_privacy_i($focus_is[0]['i__hashtag'])){
            return redirect_message( ( $top_i__hashtag ? '/'.$top_i__hashtag : home_url() ), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Idea #' . $focus_i__hashtag . ' is not public and you are missing permission to access.</div>');
        }


        //Log Link Click discovery if authenticated:
        if(
            isset($_GET['e__hash']) && isset($_GET['e__time'])
            && count($focus_es) //We have a user
        ){

            //Validate Hash:
            if($_GET['e__hash'] == view__hash($_GET['e__time'].$focus_es[0]['e__handle'])){

                $this->X_model->x_read_only_complete($focus_es[0]['e__id'], ( count($top_is) ? $top_is[0]['i__id'] : 0 ), $focus_is[0]);
                $this->X_model->mark_complete(29393, $focus_es[0]['e__id'], ( count($top_is) ? $top_is[0]['i__id'] : 0 ), $focus_is[0]);

                //Inform user of changes:
                $flash_message = '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>Idea has been discovered</div>';

                //If not logged in, log them in:
                if(!$member_e){
                    $this->E_model->activate_session($focus_es[0], true);
                }

            } else {

                $flash_message = '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>Invalid Hash: Idea could not be discovered at this time.</div>';

            }
        }


        $x_completes = array();
        if(count($focus_es)) {

            //Fetch discovery
            $x_completes = $this->X_model->fetch(array(
                'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                'x__creator' => $focus_es[0]['e__id'],
                'x__previous' => $focus_is[0]['i__id'],
                'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            ), array('x__next'));

            //Missing focus Idea?
            if(!$top_i__hashtag) {

                //Do we have a direct discovery?
                $this_discovery = null;
                foreach($x_completes as $x){
                    $this_discovery = $x['i__hashtag'];
                    break;
                }

                if($this_discovery){
                    //We have a discovery here, make sure its not the same as the starting point:
                    if($this_discovery!=$focus_i__hashtag){
                        return redirect_message('/'.$this_discovery.'/'.$focus_i__hashtag);
                    }
                } else {
                    //No discovery here, let's see if we can find any above:
                    $top_x_i__hashtag = $this->X_model->find_previous_discovered($focus_is[0]['i__id'], $focus_es[0]['e__id']);
                    if($top_x_i__hashtag){
                        return redirect_message('/'.$top_x_i__hashtag.'/'.$focus_i__hashtag);
                    }
                }
            }
        }


        //VIEW DISCOVERY
        $this->X_model->create(array(
            'x__creator' => ( count($focus_es) ? $focus_es[0]['e__id'] : 14068 ), //Guest Member
            'x__type' => 7610, //MEMBER VIEWED DISCOVERY
            'x__previous' => ( count($top_is) ? $top_is[0]['i__id'] : 0 ),
            'x__next' => $focus_is[0]['i__id'],
        ));

        $this->load->view('header', array(
            'title' => view_i_title($focus_is[0], true).( count($top_is) ? ' > '.view_i_title($top_is[0],  true) : '' ),
            'flash_message' => $flash_message,
        ));


        $this->load->view('x_layout', array(
            'focus_i' => $focus_is[0],
            'top_i' => ( count($top_is) ? $top_is[0] : array() ),
            'member_e' => ( count($focus_es) ? $focus_es[0] : array() ),
            'x_completes' => $x_completes,
        ));

        $this->load->view('footer');

    }

}