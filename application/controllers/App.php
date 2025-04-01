<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller
{

    public $player_e; // Declare the variable

    function __construct()
    {
        
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        $this->player_e = superpower_unlocked();

        auto_login_player(false);
    }

    function index(){
        //Home:
        $this->load(14565);
    }

    function load($app_playerid = 14563 /* Error if none provided */, $focus_handle = 0, $focus_hashtag = 0, $target_hashtag = 0){

        if($_SERVER['REMOTE_ADDR']!='73.15.62.97'){ die('We will be back up shortly... IP '.$_SERVER['REMOTE_ADDR']); }

        $memory_detected = is_array($this->config->item('n___6287')) && count($this->config->item('n___6287'));
        if(!$memory_detected){
            //Since we don't have the memory created we must load the app that does so:
            $app_playerid = 4527;
        }


        //Any ideas passed?
        $e___6287 = $this->config->item('e___6287'); //APP
        $flash_message = false;
        $focus_e = null; //Sourcing
        $focus_i = null; //Ideation/Discovery
        $target_i = null; //Discovery


        if(isset($_GET['playerhandle']) && $_GET['playerhandle']=='SuccessfulWhale'){
            $_GET['playerhandle'] = '';
            $focus_handle = '';
        } elseif($focus_handle && strlen($focus_handle) && !isset($_GET['playerhandle'])){
            $_GET['playerhandle'] = $focus_handle;
        }
        if($focus_hashtag && strlen($focus_hashtag) && !isset($_GET['ideahashtag'])){
            $_GET['ideahashtag'] = $focus_hashtag;
        }
        if(!isset($_GET['playerhandle'])){
            $_GET['playerhandle'] = 0;
        }
        if(!isset($_GET['ideahashtag'])){
            $_GET['ideahashtag'] = 0;
        }


        if($target_hashtag && strlen($target_hashtag)){
            //Verify:
            foreach($this->Idea_cache->fetch(array(
                'LOWER(ideahashtag)' => strtolower($target_hashtag),
            )) as $i_found){
                $target_i = $i_found;
            }
        }


        if(strlen($_GET['ideahashtag'])){

            //Validate Focus Idea:
            if($target_i && $_GET['ideahashtag']==view__memory(6404,4235)){

                //This is the starting point:
                $_GET['ideahashtag'] = $target_hashtag;
                $focus_i = $target_i;

            } else {

                foreach($this->Idea_cache->fetch(array(
                    'LOWER(ideahashtag)' => strtolower($_GET['ideahashtag']),
                )) as $i_found){
                    $focus_i = $i_found;
                }

            }

            if(!$focus_i){
                //See if we can find via ID?
                if(is_numeric($_GET['ideahashtag'])){
                    foreach($this->Idea_cache->fetch(array(
                        'ideaid' => $_GET['ideahashtag'],
                    )) as $i_found){
                        $focus_i = $i_found;
                    }
                }
            }

            if($app_playerid==33286 && $focus_i && $focus_i['ideahashtag']!==$_GET['ideahashtag']){
                //Adjust URL Case Sensitive:
                return redirect_message(view__memory(42903,33286).$focus_i['ideahashtag']);
            }
        }

        
        if(isset($_GET['playerhandle']) && strlen($_GET['playerhandle'])){
            foreach($this->Source_cache->fetch(array(
                'LOWER(playerhandle)' => strtolower($_GET['playerhandle']),
            )) as $e_found){
                $focus_e = $e_found;
            }
            if(!$focus_e){
                //See if we need to lookup the ID:
                if(is_numeric($_GET['playerhandle'])){
                    //Maybe its an ID?
                    foreach ($this->Source_cache->fetch(array(
                        'playerid' => $_GET['playerhandle'],
                    )) as $e_found){
                        $focus_e = $e_found;
                    }
                }
            }
            if($app_playerid==42902 && $focus_e && $focus_e['playerhandle']!==$_GET['playerhandle']){
                //Adjust URL Case Sensitive:
                return redirect_message(view__memory(42903,42902).$focus_e['playerhandle']);
            }
        }







        if($memory_detected && !in_array($app_playerid, $this->config->item('n___6287'))){
            //Invalid App:
            return redirect_message(view__memory(42903,42902).$e___6287[$app_playerid]['m__handle'], '<div class="alert alert-danger" role="alert">@'.$e___6287[$app_playerid]['m__handle'].' Is not an APP, yet 🤔</div>');
        } elseif($memory_detected && !in_array($app_playerid, $this->config->item('n___42922'))){
            //Validate Required App input:
            if(in_array($app_playerid, $this->config->item('n___42905')) && !$focus_e){
                return redirect_message( home_url(), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Error: @'.$_GET['playerhandle'].' is not a valid source handle.</div>');
            } elseif(in_array($app_playerid, $this->config->item('n___44329')) && (!$focus_i || !$target_i)){
                return redirect_message( home_url(), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Error: Both #'.$_GET['ideahashtag'].' & #'.$target_hashtag.' must be valid hashtags.</div>');
            } elseif(in_array($app_playerid, $this->config->item('n___42911')) && !$focus_i){
                return redirect_message( home_url(), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Error: #'.$_GET['ideahashtag'].' is not a valid idea hashtag.</div>');
            }
        }



        $linkdown = ( $focus_e ? $focus_e['playerid'] : 0 );
        $linkright = ( $focus_i ? $focus_i['ideaid'] : 0 );
        $linkleft = ($target_i ? $target_i['ideaid'] : 0 );

        //Run App
        $player_e = false;
        $player_http_request = ( isset($_SERVER['SERVER_NAME']) ? 1 : 0 );

        if($memory_detected && in_array($app_playerid, $this->config->item('n___42920'))){
            boost_power();
        }

        if($memory_detected && $player_http_request){

            //Needs superpowers?
            $player_e = superpower_unlocked();

            //Auto Login?
            if(isset($_GET['e__hash']) && isset($_GET['e__time']) && $focus_e){

                //Validate Hash:
                if($_GET['e__hash'] == view__hash($_GET['e__time'].$focus_e['playerhandle'])){

                    if($focus_i){
                        if(i_startable($focus_i)){
                            $flash_message = '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="far fa-play"></i></span>You have started discovering this idea. Scroll to the bottom & go next to continue.</div>';
                        } else {
                            $this->Mench_ledger->mark_complete(i__discovery_link($focus_i), $focus_e['playerid'], ( $target_i ? $target_i['ideaid'] : 0 ), $focus_i);
                            $this->Mench_ledger->mark_complete(29393, $focus_e['playerid'], ( $target_i ? $target_i['ideaid'] : 0 ), $focus_i);

                            //Inform user of changes:
                            $flash_message = '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="far fa-check-circle"></i></span>Idea has been discovered</div>';
                        }
                    }

                    //If not logged in, log them in:
                    if(!$player_e){
                        $session_data = $this->Source_cache->activate_session($player_e, true);
                    }

                }
            }
        }


        //Cache App?
        $ui = null;
        $new_cache = false;
        $cache_linktime = null;
        $linkplayer = ( $player_http_request ? ( $player_e ? $player_e['playerid'] : 14068 /* GUEST */ ) : 7274 /* CRON JOB */ );
        $skip_i_privacy_check = !$memory_detected || in_array($app_playerid, $this->config->item('n___43388'));
        $access_level_e = access_level_e(null, $focus_e['playerid'], $focus_e);
        $access_level_i = access_level_i(null, $focus_i['ideaid'], $focus_i);
        $target_access_level_i = access_level_i(null, $target_i['ideaid'], $target_i);

        //MEMBER REDIRECT?
        if($player_http_request && $memory_detected){

            //Missing App, Source or Idea Access?
            $missing_access = false; //Assume they have access
            $superpowers_required = array_intersect($this->config->item('n___10957'), $e___6287[$app_playerid]['m__following']);
            if($player_e && in_array($app_playerid, $this->config->item('n___14639'))){
                //Should redirect them:
                return redirect_message(view__memory(42903,42902).$player_e['playerhandle']);
            } elseif(!$player_e && in_array($app_playerid, $this->config->item('n___14740'))){
                //Should redirect them:
                $missing_access = 'Login or register a free account to continue.';
            } elseif(count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
                $e___10957 = $this->config->item('e___10957');
                $missing_access = 'Error: You Cannot Access '.$e___6287[$app_playerid]['m__title'].' as it requires the superpower of '.$e___10957[end($superpowers_required)]['m__title'].'.';
            } elseif($focus_e && !$access_level_e){
                $missing_access = 'Error: You Cannot Access @'.$focus_e['playerhandle'].' due to Privacy Settings.';
            } elseif(!$skip_i_privacy_check && $focus_i && !$access_level_i){
                $missing_access = 'Error: You Cannot Access Focus #'.$focus_i['ideahashtag'].' due to Privacy Settings.';
            } elseif(!$skip_i_privacy_check && $target_i && !$target_access_level_i){
                $missing_access = 'Error: You Cannot Access Target #'.$target_i['ideahashtag'].' due to Privacy Settings.';
            }


            if($missing_access){
                //Redirect:
                return redirect_message((!$player_e ? view__app_link(4269).'?url='.urlencode($_SERVER['REQUEST_URI']) : home_url() ), '<div class="alert alert-warning" role="alert">'.$missing_access.'</div>');
            }
        }




        if($memory_detected){

            if(in_array($app_playerid, $this->config->item('n___14599')) && !in_array($app_playerid, $this->config->item('n___12741'))){

                if(!isset($_GET['reset_cache'])){
                    //Fetch Most Recent Cache:
                    foreach($this->Mench_ledger->fetch(array(
                        'linkdomain' => website_setting(0),
                        'linktype' => 44179, //Triggered
                        'linkup' => 14599, //Cache App
                        'linkdown' => $app_playerid,
                        'linkvoid' => 0, //Not Void
                    ), array(), 1, 0, array('linktime' => 'DESC')) as $latest_cache){
                        if(strtotime($latest_cache['linktime']) <= (time() - view__memory(6404,14599))){
                            //Its expired, void it:
                            $this->Mench_ledger->update($latest_cache['linkid'], array());
                        } else {
                            $ui = $latest_cache['linktext'];
                            $cache_linktime = '<div class="texttransparent center main__title">Updated ' . view__time_difference($latest_cache['linktime']) . ' Ago</div>';
                        }
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
            $title .= view__i_title($focus_i, true).' | ';
        }
        if($target_i){
            $title .= view__i_title($target_i, true).' | ';
        }
        if($focus_e){
            $title .= $focus_e['playertext'].' @'.$focus_e['playerhandle'].' | ';
        }
        if(!$title){
            //Append app name since no title:
            $title .= $e___6287[$app_playerid]['m__title'].' | ';
        }
        //Always Append Website at the end:
        $title .= ( $memory_detected ? get_domain('m__title') : 'Loading Memory' ) ;



        $view_input = array(
            'app_playerid' => $app_playerid,
            'linkplayer' => $linkplayer,
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
            $app_handler = ( $memory_detected ? strtolower($e___6287[$app_playerid]['m__handle']) : 'memory' );
            $raw_app = $this->load->view($app_handler, $view_input, true);
            $ui .= $raw_app;
        }


        if($new_cache){
            $cache_x = $this->Mench_ledger->create(array(
                'linkdomain' => website_setting(0),
                'linktype' => 44179, //Triggered
                'linkup' => 14599, //Cache App
                'linkdown' => $app_playerid,

                'linkplayer' => $linkplayer,
                'linktext' => $ui,
                'linkleft' => $linkleft,
                'linkright' => $linkright,
            ));
        }




        //App title?
        if($memory_detected && in_array($app_playerid, $this->config->item('n___42928'))){
            $ui = '<h1>'.$e___6287[$app_playerid]['m__title'].'</h1>'.$ui;
        }


        //Check to ensure they have started:
        if($app_playerid==30795 && $target_i && $focus_i && $player_e && $target_i['ideahashtag']==$focus_i['ideahashtag']){

            //Starting point, make sure all good:
            if(!i_startable($target_i)){

                //Not a valid starting point:
                return redirect_message(home_url(), '<div class="alert alert-warning" role="alert">#'.$target_i['ideahashtag'].' is not an active starting point.</div>');

            } elseif(!$this->Mench_ledger->i_has_started($player_e['playerid'], $target_i['ideahashtag'])){

                //Not yet started, add to their starting point:
                $completion_status = $this->Mench_ledger->mark_complete(4235, $player_e['playerid'], 0, $target_i);

                //Now return next idea:
                $next__url = $this->Mench_ledger->find_next($player_e['playerid'], $target_i['ideahashtag'], $target_i);

                if($next__url){
                    //Go Next:
                    return redirect_message(view__memory(42903,30795).$target_i['ideahashtag'].'/'.$next__url );
                }

            }

        }




        //Delivery App
        if(!$memory_detected){

            echo $ui;

        } else {

            if(in_array($app_playerid, $this->config->item('n___12741'))){

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





    function load_popover(){
        if(isset($_POST['handle_string']) && strlen($_POST['handle_string'])>1 && in_array(substr($_POST['handle_string'], 0, 1), array('#','@')) ){
            if(substr($_POST['handle_string'], 0, 1)=='#'){
                foreach($this->Idea_cache->fetch(array(
                    'LOWER(ideahashtag)' => strtolower(substr($_POST['handle_string'], 1)),
                )) as $i){
                    echo view__card_i(6255, $i);
                    return true;
                }
            } elseif(substr($_POST['handle_string'], 0, 1)=='@'){
                foreach($this->Source_cache->fetch(array(
                    'LOWER(playerhandle)' => strtolower(substr($_POST['handle_string'], 1)),
                )) as $e){
                    echo view__card_e(12274, $e);
                    return true;
                }
            }

            //Did not find, had error:
            echo '<div class="alert alert-danger" role="alert">Could not find '.$_POST['handle_string'].'</div>';
            return false;
        }

        //Did not find, had error:
        echo '<div class="alert alert-danger" role="alert">Missing handle_string variable</div>';
        return false;

    }

    function i_editor_load()
    {

        $player_e = superpower_unlocked(null, 0, $this->player_e);
        if (!$player_e) {
            return view__json(array(
                'status' => 0,
                'message' => view__unauthorized_message(),
            ));
        } elseif (!isset($_POST['ideaid']) || !isset($_POST['linkid']) || !isset($_POST['current_i__type'])) {
            return view__json(array(
                'status' => 0,
                'message' => 'Missing Core IDs',
            ));
        }


        $ideaid = 0; //New idea
        $i__type = intval($_POST['current_i__type']);
        $created_ideaid = 0;

        if($_POST['ideaid'] > 0){

            $is = $this->Idea_cache->fetch(array(
                'ideaid' => $_POST['ideaid'],
            ));
            if (!count($is)) {
                return view__json(array(
                    'status' => 0,
                    'message' => 'Idea is no longer active',
                ));
            } elseif (!access_level_i($is[0]['ideahashtag'], 0, $is[0])) {
                return view__json(array(
                    'status' => 0,
                    'message' => 'You are missing permission to edit this idea',
                ));
            }


            $ideaid = intval($is[0]['ideaid']);
            if(!$i__type){
                $i__type = intval($is[0]['i__type']);
            }

        } else {

            //Create a new idea:
            $i_new = $this->Idea_cache->create(array(
                'ideatext' => null,
                'i__type' => $_POST['current_i__type'],
            ), $player_e['playerid']);

            $ideaid = $i_new['ideaid'];
            $created_ideaid = $ideaid;

        }


        //Fetch dynamic data based on idea type:
        $return_inputs = array();
        $e___4737 = $this->config->item('e___4737'); // Idea Status
        $e___42179 = $this->config->item('e___42179'); //Dynamic Input Fields
        $e___11035 = $this->config->item('e___11035'); //Encyclopedia

        foreach(array_intersect($this->config->item('n___'.$i__type), $this->config->item('n___42179')) as $dynamic_playerid){

            $superpowers_required = array_intersect($this->config->item('n___10957'), $e___42179[$dynamic_playerid]['m__following']);
            if(count($superpowers_required) && !superpower_unlocked(end($superpowers_required), 0, $this->player_e)){
                continue;
            }

            //Let's first determine the data type:
            $data_types = array_intersect($e___42179[$dynamic_playerid]['m__following'], $this->config->item('n___4592'));

            if(count($data_types)!=1) {
                //This is strange, we are expecting 1 match only report this:
                $this->Mench_ledger->create(array(
                    'linktype' => 44179, //Triggered
                    'linkup' => 4246, //Platform Bug Reports
                    'linkplayer' => $player_e['playerid'],
                    'linkdown' => $dynamic_playerid,
                    'linkright' => $ideaid,
                    'linktext' => 'Found ' . count($data_types) . ' Data Types (Expecting exactly 1) for @' . $dynamic_playerid . ': Check @4592 to see what is wrong',
                ));
                continue; //Go to the next dynamic data type
            }

            //We found 1 match as expected:
            foreach($data_types as $data_type_this){
                $data_type = $data_type_this;
                break;
            }

            if(in_array($data_type, $this->config->item('n___42188'))){

                //Single or Multiple Choice:
                array_push($return_inputs, array(
                    'd__id' => $dynamic_playerid,
                    'd__is_radio' => 1,
                    'd_linkid' => 0,
                    'd__html' => view__instant_select($dynamic_playerid, 0, $ideaid),
                    'd__value' => ( $ideaid>0 ? $ideaid : '' ),
                    'd__type_name' => '',
                    'd__placeholder' => '',
                    'd__profile_header' => '',
                ));

            } else {

                $this_data_type = $this->config->item('e___'.$data_type);
                $e___4592 = $this->config->item('e___4592'); //Data types
                $e___42179 = $this->config->item('e___42179'); //Dynamic Input Field
                $e___11035 = $this->config->item('e___11035'); //Encyclopedia

                //Fetch the current value:
                $counted = 0;
                $unique_values = array();
                if($ideaid > 0){ //Must have an original ID to possibly have a value...
                    foreach($this->Mench_ledger->fetch(array(
                        'linkvoid' => 0, //Not Void
                        'linktype IN (' . join(',', $this->config->item('n___42252')) . ')' => null, //Plain Link
                        'linkright' => $ideaid,
                        'linkup' => $dynamic_playerid,
                    ), array('linkup')) as $selected_e){
                        if(strlen($selected_e['linktext']) && !in_array($selected_e['linktext'], $unique_values)){
                            $counted++;
                            array_push($unique_values, $selected_e['linktext']);
                            array_push($return_inputs, array(
                                'd__id' => $dynamic_playerid,
                                'd__is_radio' => 0,
                                'd_linkid' => $selected_e['linkid'],
                                'd__html' => view__dynamic_headline($dynamic_playerid, $e___42179[$dynamic_playerid], $selected_e),
                                'd__value' => $selected_e['linktext'],
                                'd__type_name' => html_input_type($data_type),
                                'd__placeholder' => ( strlen($this_data_type[$dynamic_playerid]['m__message']) ? $this_data_type[$dynamic_playerid]['m__message'] : $e___4592[$data_type]['m__title'].'...' ),
                                'd__profile_header' => '',
                            ));
                        }
                    }
                }


                if(!$counted){
                    foreach($this->Source_cache->fetch(array(
                        'playerid' => $dynamic_playerid,
                    )) as $selected_e){
                        array_push($return_inputs, array(
                            'd__id' => $dynamic_playerid,
                            'd__is_radio' => 0,
                            'd_linkid' => 0,
                            'd__html' => view__dynamic_headline($dynamic_playerid, $e___42179[$dynamic_playerid], $selected_e),
                            'd__value' => '',
                            'd__type_name' => html_input_type($data_type),
                            'd__placeholder' => ( strlen($this_data_type[$dynamic_playerid]['m__message']) ? $this_data_type[$dynamic_playerid]['m__message'] : $e___4592[$data_type]['m__title'].'...' ),
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
        return view__json($return_array);

    }


    function i_void(){

        //TODO Must get going...
        //Delete all transactions:
        $links_removed = $this->Idea_cache->remove($o__id , $player_e['playerid'], $migrate_s__id);

    }

    function e_void(){

        //TODO Must get going...

        //Validate migration handle, if any:
        $migrate_s__id = 0;
        if(strlen($migrate_s__handle)>0){
            $valid_handle = $this->Source_cache->fetch(array(
                'LOWER(playerhandle)' => strtolower($migrate_s__handle),
            ));
            if(!count($valid_handle)){
                return array(
                    'status' => 0,
                    'message' => '@'.$migrate_s__handle.' is an Invalid Source Handle!',
                );
            } elseif($valid_handle[0]['playerid']==$o__id){
                return array(
                    'status' => 0,
                    'message' => 'You cannot migrate this source to itself! Choose a different source to migrate.',
                );
            }
            $migrate_s__id = $valid_handle[0]['playerid'];
        }

        //Determine what to do after deleted:
        if($o__id==$focus__id){

            //Find Published Followings:
            foreach($this->Mench_ledger->fetch(array(
                'linktype IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'linkvoid' => 0, //Not Void
                'linkdown' => $o__id,
            ), array('linkup'), 1, 0, array('playertext' => 'DESC')) as $up_e) {
                $deletion_redirect = view__memory(42903,42902).$up_e['playerhandle'];
            }

            //If still not found, go to main page if no followings found:
            if(!$deletion_redirect){
                foreach($this->Source_cache->fetch(array('playerid' => $o__id)) as $e2){
                    $deletion_redirect = view__memory(42903,42902).e2['playerhandle'];
                }
            }

        } else {

            //Just delete from UI using JS:
            $delete_element = '.s__12274_' . $o__id;

        }

        //Delete all transactions:
        $links_removed = $this->Source_cache->remove($o__id, $player_e['playerid'], $migrate_s__id);

        //Remove:
        $status = $this->Source_cache->update($o__id, array(), true, $player_e['playerid']);

        //Update Search Index:
        flag_for_search_indexing(12274,  $o__id);

    }

    function i_editor_save(){


        $player_e = superpower_unlocked(null, 0, $this->player_e);
        if (!$player_e) {

            return view__json(array(
                'status' => 0,
                'message' => view__unauthorized_message(),
            ));

        } elseif(!isset($_POST['save_ideatext'])){

            return view__json(array(
                'status' => 0,
                'message' => 'Missing Idea',
            ));

        } elseif(!isset($_POST['focus__node']) || !isset($_POST['focus__id'])){

            return view__json(array(
                'status' => 0,
                'message' => 'Missing focus Card/ID',
            ));

        } elseif(!isset($_POST['save_ideahashtag'])){

            return view__json(array(
                'status' => 0,
                'message' => 'Missing hashtag',
            ));

        } elseif(!isset($_POST['save_ideaid']) || !intval($_POST['save_ideaid'])){

            return view__json(array(
                'status' => 0,
                'message' => 'Missing Idea ID',
            ));

        } elseif(!isset($_POST['next_ideaid']) || !isset($_POST['previous_ideaid']) || !isset($_POST['save_linktype'])){

            return view__json(array(
                'status' => 0,
                'message' => 'Missing Next/Previous ID',
            ));

        } elseif(!isset($_POST['save_linkid']) || !isset($_POST['save_linktext'])){

            return view__json(array(
                'status' => 0,
                'message' => 'Missing Transaction Data',
            ));

        } elseif (!isset($_POST['save_i__type']) || !in_array($_POST['save_i__type'], $this->config->item('n___4737'))) {
            return view__json(array(
                'status' => 0,
                'message' => 'Invalid idea Type',
            ));
        } elseif(strlen($_POST['save_ideatext'])>view__memory(6404,4736)){
            return view__json(array(
                'status' => 0,
                'message' => 'Idea message must be less than '.view__memory(6404,4736).' characters.',
            ));
        }



        $is = $this->Idea_cache->fetch(array(
            'ideaid' => $_POST['save_ideaid'],
        ));
        if(!count($is)){
            return view__json(array(
                'status' => 0,
                'message' => 'Idea Not Valid',
            ));
        }


        $focus__node = ( $_POST['focus__node']==12273 && $_POST['focus__id']==$_POST['save_ideaid'] );
        if(!isset($_POST['uploaded_media']) || !is_array($_POST['uploaded_media'])){
            $_POST['uploaded_media'] = array();
        }

        //Might be new if pre-drafting:
        if( is_null($is[0]['ideatext']) ){

            //See if references only:
            if(strlen($_POST['save_ideatext']) && !count($_POST['uploaded_media']) && !substr_count($_POST['save_ideatext'], "\n") && intval($_POST['save_linktype']) && (intval($_POST['next_ideaid']) || intval($_POST['previous_ideaid']))){

                $all_hashtags = true;
                $i_references = array();
                foreach(explode(' ', trim($_POST['save_ideatext'])) as $word){
                    $found_hashtag = false;
                    if(substr($word, 0, 1)=='#'){
                        $valid_hashtag = false;
                        foreach($this->Idea_cache->fetch(array(
                            'LOWER(ideahashtag)' => strtolower(substr($word, 1)),
                                    )) as $i_found){
                            $found_hashtag = true;
                            $valid_hashtag = true;
                            array_push($i_references, $i_found);
                        }
                        if(!$valid_hashtag && superpower_unlocked(10939, 0, $this->player_e)){
                            return view__json(array(
                                'status' => 0,
                                'message' => 'ERROR: '.$word.' is not a valid/active Idea',
                            ));
                        }
                    }
                    if(!$found_hashtag){
                        $all_hashtags = false;
                        break; //It must be a hashtag only reference
                    }
                }

                if($all_hashtags && count($i_references) && $_POST['save_linktype']>0){

                    //Return success:
                    foreach($this->Idea_cache->fetch(array(
                        'ideaid' => ( intval($_POST['next_ideaid'])>0 ? intval($_POST['next_ideaid']) : intval($_POST['previous_ideaid']) ),
                    )) as $focus_i){

                        //Append all of these hashtags:
                        foreach($i_references as $reference_i){
                            if(intval($_POST['next_ideaid'])>0){
                                $status = $this->Idea_cache->i_link($focus_i, $_POST['save_linktype'], $reference_i, $player_e['playerid']);
                            } elseif(intval($_POST['previous_ideaid'])>0){
                                $status = $this->Idea_cache->i_link($reference_i, $_POST['save_linktype'], $focus_i, $player_e['playerid']);
                            }
                            if(!$status['status']){
                                return view__json($status);
                            }
                        }

                        //What to focus on depends on how many total ideas added:
                        $return_i = ( count($i_references)>=2 ? $focus_i : $reference_i );

                        return view__json(array(
                            'status' => 1,
                            'return_ideacache' => '',
                            'return_ideacache_links' => '',
                            'return_ideacache_full' => view__card_i($_POST['focus_group'], $return_i),
                            'redirect_idea' => view__memory(42903,33286).$return_i['ideahashtag'],
                            'message' => count($i_references).' ideas linked',
                        ));
                    }
                }
            }



            //Update new idea fields:
            $this->Idea_cache->update($is[0]['ideaid'], array(
                'i__type' => $_POST['save_i__type'],
            ), true, $player_e['playerid']);
            $is[0]['i__type'] = trim($_POST['save_i__type']);
        }



        //Process Media:
        $media_stats = process_media($is[0]['ideaid'], $_POST['uploaded_media']);


        //Validate Idea Message:
        if(!$media_stats['total_media'] && !strlen(trim($_POST['save_ideatext']))){
            //Since we do not have media, we must have a message:
            return view__json(array(
                'status' => 0,
                'message' => 'Write or Upload something to save.',
            ));
        }



        //Process dynamic inputs if any:
        $e___42179 = $this->config->item('e___42179'); //Dynamic Input Fields
        if($_POST['save_ideaid'] > 0){
            for ($p = 1; $p <= view__memory(6404,42206); $p++) {

                if(!isset($_POST['save_dynamic_' . $p])){
                    break; //Nothing more to process
                }

                $input_parts = explode('____', $_POST['save_dynamic_' . $p], 3);
                if(!isset($input_parts[0]) || !isset($input_parts[1])){
                    continue;
                }
                $d_linkid = $input_parts[0];
                $dynamic_playerid = $input_parts[1];
                $dynamic_value = trim($input_parts[2]);

                //Required fields must have an input:
                if(in_array($dynamic_playerid, $this->config->item('n___28239')) && !strlen($dynamic_value) && !in_array($dynamic_playerid, $this->config->item('n___33331')) && !in_array($dynamic_playerid, $this->config->item('n___33332'))){
                    return view__json(array(
                        'status' => 0,
                        'message' => 'Missing Required Field: '.$e___42179[$dynamic_playerid]['m__title'],
                    ));
                }

                //Validate input based on its data type, if provided:
                if (strlen($dynamic_value)) {
                    foreach(array_intersect($e___42179[$dynamic_playerid]['m__following'], $this->config->item('n___4592')) as $data_type_this){
                        $data_type_validate = data_type_validate($data_type_this, $dynamic_value, $e___42179[$dynamic_playerid]['m__title']);
                        if (!$data_type_validate['status']) {
                            //We had an error:
                            return view__json($data_type_validate);
                        }
                    }
                }


                //Fetch the current value:
                if($d_linkid > 0){
                    $values = $this->Mench_ledger->fetch(array(
                        'linkid' => $d_linkid,
                    ));
                }

                if(!$d_linkid || !count($values)){
                    $values = $this->Mench_ledger->fetch(array(
                        'linkvoid' => 0, //Not Void
                        'linktype IN (' . join(',', $this->config->item('n___42252')) . ')' => null, //Plain Link
                        'linkright' => $is[0]['ideaid'],
                        'linkup' => $dynamic_playerid,
                    ));
                }


                //Update if needed:
                if(!strlen($dynamic_value)){

                    //Remove Link if we have one:
                    if(count($values) && $dynamic_playerid!=11035 /* HACK: Summary are key links that should not be removed */){
                        $this->Mench_ledger->update($values[0]['linkid'], array(), $player_e['playerid']);
                    }

                } elseif(!count($values)){

                    //Create New Link:
                    $this->Mench_ledger->create(array(
                        'linkplayer' => $player_e['playerid'],
                        'linktype' => 4983, //Co-Author
                        'linkup' => $dynamic_playerid,
                        'linkright' => $is[0]['ideaid'],
                        'linktext' => $dynamic_value,
                        'linknumber' => number_linknumber($dynamic_value),
                    ));

                } elseif($values[0]['linktext']!=$dynamic_value){

                    //Update Link:
                    $this->Mench_ledger->update($values[0]['linkid'], array(
                        'linktext' => $dynamic_value,
                    ), $player_e['playerid']);

                }
            }
        }



        if(strlen($_POST['save_ideahashtag']) && $is[0]['ideahashtag']!==trim($_POST['save_ideahashtag'])){

            $validate_update_handle = validate_update_handle($_POST['save_ideahashtag'], $is[0]['ideaid'], null);
            if(!$validate_update_handle['status']){
                return view__json(array(
                    'status' => 0,
                    'message' => $validate_update_handle['message'],
                ));
            }

            //Save hashtag since changed:
            $this->Idea_cache->update($is[0]['ideaid'], array(
                'ideahashtag' => trim($_POST['save_ideahashtag']),
            ), true, $player_e['playerid']);

            //Now Handles everywhere they are referenced:
            foreach ($this->Mench_ledger->fetch(array(
                'linkleft' => $is[0]['ideaid'],
                'linktype IN (' . join(',', $this->config->item('n___42341')) . ')' => null, //Idea References
                'linkvoid' => 0, //Not Void
            ), array('linkright')) as $ref) {
                view__sync_links(str_replace('#'.$is[0]['ideahashtag'], '#'.trim($_POST['save_ideahashtag']), $ref['ideatext']), true, $ref['ideaid']);
            }

            //Assign new value:
            $is[0]['ideahashtag'] = trim($_POST['save_ideahashtag']);

        }


        //Also have to add as a comment to another idea?
        if(intval($_POST['next_ideaid'])>0 && $_POST['save_linktype']>0){
            $this->Mench_ledger->create(array(
                'linkplayer' => $player_e['playerid'],
                'linkleft' => $_POST['next_ideaid'],
                'linkright' => $is[0]['ideaid'],
                'linktype' => $_POST['save_linktype'],
            ));
        } elseif(intval($_POST['previous_ideaid'])>0 && $_POST['save_linktype']>0){
            $this->Mench_ledger->create(array(
                'linkplayer' => $player_e['playerid'],
                'linkleft' => $is[0]['ideaid'],
                'linkright' => $_POST['previous_ideaid'],
                'linktype' => $_POST['save_linktype'],
            ));
        }


        //Do we have a link reference message that need to be saved?
        if($_POST['save_linkid']>0 && $_POST['save_linktext']!='IGNORE_INPUT'){
            //Fetch transaction:
            foreach($this->Mench_ledger->fetch(array(
                'linkid' => $_POST['save_linkid'],
            )) as $this_x){

                $is[0] = array_merge($is[0], $this_x);

                if($this_x['linktext'] != trim($_POST['save_linktext'])){
                    $this->Mench_ledger->update($this_x['linkid'], array(
                        'linktext' => trim($_POST['save_linktext']),
                    ), $player_e['playerid']);
                }
            }
        }


        //Update Links based on save_ideatext / Sync Idea Synonym & Source References links:
        $view_sync_links = view__sync_links($_POST['save_ideatext'], true, $is[0]['ideaid']);
        $is[0]['ideatext'] = trim($_POST['save_ideatext']);
        $is[0]['ideacache'] = $view_sync_links['ideacache'];


        //Update Search Index:
        flag_for_search_indexing(12273, $is[0]['ideaid']);


        return view__json(array(
            'status' => 1,
            'return_ideacache' => $view_sync_links['ideacache'],
            'return_ideacache_links' => view__i__links($is[0], $player_e['playerid'], $focus__node, $focus__node),
            'return_ideacache_full' => view__card_i($_POST['focus_group'], $is[0]),
            'redirect_idea' => ( isset($is[0]['ideahashtag']) ? view__memory(42903,33286).$is[0]['ideahashtag'] : null ),
            'message' => $media_stats['total_current'].' current & '.$media_stats['total_submitted'].' submitted media: '.$media_stats['total_submitted'].' Created, '.$media_stats['adjust_updated'].' Updated & '.$media_stats['adjust_removed'].' Removed while detected '.$media_stats['adjust_duplicated'].' duplicate uploads. '.$view_sync_links['sync_stats']['old_links_removed'].' old links removed, '.$view_sync_links['sync_stats']['old_links_kept'].' old links kept, '.$view_sync_links['sync_stats']['new_links_added'].' new links added.',
        ));

    }

    function i_copy(){

        //Auth member and check required variables:
        $player_e = superpower_unlocked(10939, 0, $this->player_e);

        if (!$player_e) {
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

        return view__json($this->Idea_cache->recursive_clone(intval($_POST['ideaid']), intval($_POST['do_recursive']), $player_e['playerid']));

    }

    function i_load_cover(){

        if (!isset($_POST['ideaid']) || !isset($_POST['linktype']) || !isset($_POST['first_segment']) || !isset($_POST['counter'])) {
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Missing core variables</div>';
        } else {

            if(in_array($_POST['linktype'], $this->config->item('n___42376')) && !access_level_i(null, $_POST['ideaid'])){

                echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-lock"></i></span>Private</div>';

            } else {

                $target_disccovery = target_disccovery();

                $ui = '';
                $listed_items = 0;
                if(in_array($_POST['linktype'], $this->config->item('n___42261')) || in_array($_POST['linktype'], $this->config->item('n___42284'))){

                    //SOURCES
                    $e___4593 = $this->config->item('e___4593'); //Transaction Types
                    $current_playerhandle = view__valid_handle_e($_POST['first_segment']);
                    foreach(view__i_covers($_POST['linktype'], $_POST['ideaid'], 1, false) as $e_e) {
                        if(isset($e_e['playerid'])){
                            $ui .= view__card(view__memory(42903,42902).$e_e['playerhandle'], $current_playerhandle && $e_e['playerhandle']==$current_playerhandle, $e_e['linktype'], view__cover($e_e['playercover'], true), $e_e['playertext'], $e_e['linktext']);
                            $listed_items++;
                        }
                    }

                } elseif(in_array($_POST['linktype'], $this->config->item('n___11020'))){

                    //IDEAS
                    $e___4737 = $this->config->item('e___4737'); //Idea Types
                    $e___4593 = $this->config->item('e___4593'); //Transaction Types
                    $current_ideahashtag = ( substr($_POST['first_segment'], 0, 1)=='~' ? substr($_POST['first_segment'], 1) : false );

                    foreach(view__i_covers($_POST['linktype'], $_POST['ideaid'], 1, false) as $next_i) {
                        if(isset($next_i['ideaid'])){
                            $ui .= view__card($target_disccovery.view__memory(42903,33286).$next_i['ideahashtag'], $next_i['ideahashtag']==$current_ideahashtag, $next_i['linktype'], ( in_array($next_i['i__type'], $this->config->item('n___32172')) ? $e___4737[$next_i['i__type']]['m__cover'] : '' ), view__i_title($next_i, true), $next_i['linktext']);
                            $listed_items++;
                        }
                    }

                }

                if($listed_items < $_POST['counter']){
                    //We have more to show:
                    foreach($this->Idea_cache->fetch(array(
                        'ideaid' => $_POST['ideaid'],
                    )) as $i){
                        $ui .= view__more($target_disccovery.view__memory(42903,33286).$i['ideahashtag'], false, '&nbsp;', '&nbsp;', 'View All');
                    }
                }

                echo $ui;

            }
        }
    }

    function i_sort_load()
    {

        /*
         *
         * Saves the order of read ideas based on
         * member preferences.
         *
         * */

        $player_e = superpower_unlocked(null, 0, $this->player_e);

        if (!$player_e) {
            return view__json(array(
                'status' => 0,
                'message' => view__unauthorized_message(),
            ));
        } elseif (!isset($_POST['new_x_order']) || !is_array($_POST['new_x_order']) || count($_POST['new_x_order']) < 1) {
            return view__json(array(
                'status' => 0,
                'message' => 'Missing sorting ideas',
            ));
        } elseif (!isset($_POST['linktype']) || !in_array($_POST['linktype'], $this->config->item('n___4603'))) {
            return view__json(array(
                'status' => 0,
                'message' => 'Invalid Transaction Type',
            ));
        }

        //Update the order of their discoveries:
        $updated = 0;
        foreach($_POST['new_x_order'] as $linknumber => $linkid){
            if(intval($linkid) > 0 && intval($linknumber) > 0){
                //Update order of this transaction:
                if($this->Mench_ledger->update(intval($linkid), array(
                    'linknumber' => $linknumber,
                ), $player_e['playerid'])){
                    $updated++;
                }
            }
        }

        //All good:
        return view__json(array(
            'status' => 1,
            'message' => $updated.' Sorted',
        ));
    }

    function view__i_body(){
        //Authenticate Member:
        if (!isset($_POST['ideaid']) || intval($_POST['ideaid']) < 1 || !isset($_POST['counter']) || !isset($_POST['linktype']) || intval($_POST['linktype']) < 1) {
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Missing core variables</div>';
        } else {
            echo view__i_body($_POST['linktype'], $_POST['counter'], $_POST['ideaid']);
        }
    }



    function view__e_body(){
        //Authenticate Member:
        if (!isset($_POST['playerid']) || intval($_POST['playerid']) < 1 || !isset($_POST['linktype']) || intval($_POST['linktype']) < 1) {
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Missing core variables</div>';
        } else {
            echo view__e_body($_POST['linktype'], $_POST['counter'], $_POST['playerid'], $_POST['js_request_uri']);
        }
    }

    function e_load_cover(){

        if (!isset($_POST['playerid']) || !isset($_POST['linktype']) || !isset($_POST['first_segment']) || !isset($_POST['counter'])) {

            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Missing core variables</div>';

        } else {

            if(in_array($_POST['linktype'], $this->config->item('n___42376')) && !access_level_e(null, $_POST['playerid'])){

                echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-lock"></i></span>Private</div>';

            } else {

                $ui = '';
                $listed_items = 0;

                if(in_array($_POST['linktype'], $this->config->item('n___11028'))){

                    //SOURCES
                    $current_playerhandle = view__valid_handle_e($_POST['first_segment']);
                    $e___4593 = $this->config->item('e___4593'); //Transaction Types

                    foreach(view__e_covers($_POST['linktype'], $_POST['playerid'], 1, false) as $e_e) {
                        if(isset($e_e['playerid'])){
                            $ui .= view__card(view__memory(42903,42902).$e_e['playerhandle'], $e_e['playerhandle']==$current_playerhandle, $e_e['linktype'], view__cover($e_e['playercover'], true), $e_e['playertext'], $e_e['linktext']);
                            $listed_items++;
                        }
                    }

                } elseif(in_array($_POST['linktype'], $this->config->item('n___42261')) || in_array($_POST['linktype'], $this->config->item('n___42284'))){

                    //IDEAS
                    $current_ideahashtag = ( substr($_POST['first_segment'], 0, 1)=='~' ? substr($_POST['first_segment'], 1) : false );
                    $e___4737 = $this->config->item('e___4737'); //Idea Types
                    $e___4593 = $this->config->item('e___4593'); //Transaction Types
                    $target_disccovery = target_disccovery();

                    foreach(view__e_covers($_POST['linktype'], $_POST['playerid'], 1, false) as $next_i) {
                        if(isset($next_i['ideaid'])){
                            $ui .= view__card($target_disccovery.view__memory(42903,33286).$next_i['ideahashtag'], $next_i['ideahashtag']==$current_ideahashtag, $next_i['linktype'], ( in_array($next_i['i__type'], $this->config->item('n___32172')) ? $e___4737[$next_i['i__type']]['m__cover'] : '' ), view__i_title($next_i, true), $next_i['linktext']);
                            $listed_items++;
                        }
                    }

                }

                if($listed_items < $_POST['counter']){
                    //We have more to show:
                    foreach($this->Source_cache->fetch(array(
                        'playerid' => $_POST['playerid'],
                    )) as $e_this){
                        $ui .= view__more(view__memory(42903,42902).$e_this['playerhandle'], false, '&nbsp;', '&nbsp;', 'View All');
                    }
                }

                echo $ui;

            }
        }
    }

    function e_sort_save()
    {

        //Authenticate Member:
        $player_e = superpower_unlocked(10939, 0, $this->player_e);
        if (!$player_e) {
            return view__json(array(
                'status' => 0,
                'message' => view__unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['playerid']) || intval($_POST['playerid']) < 1) {
            return view__json(array(
                'status' => 0,
                'message' => 'Invalid playerid',
            ));
        } elseif (!isset($_POST['new_linknumber']) || !is_array($_POST['new_linknumber']) || count($_POST['new_linknumber']) < 1) {
            return view__json(array(
                'status' => 0,
                'message' => 'Nothing passed for sorting',
            ));
        } else {

            //Validate Source:
            $es = $this->Source_cache->fetch(array(
                'playerid' => $_POST['playerid'],
            ));

            //Count followers:
            $list_e_count = $this->Mench_ledger->fetch(array(
                'linkup' => $_POST['playerid'],
                'linktype IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'linkvoid' => 0, //Not Void
            ), array('linkdown'), 0, 0, array(), 'COUNT(playerid) as totals');

            if (count($es) < 1) {

                return view__json(array(
                    'status' => 0,
                    'message' => 'Invalid playerid',
                ));

            } elseif($list_e_count[0]['totals'] > view__memory(6404,11064)){

                return view__json(array(
                    'status' => 0,
                    'message' => 'Cannot sort sources if greater than '.view__memory(6404,11064),
                ));

            } else {

                //Update them all:
                foreach($_POST['new_linknumber'] as $rank => $linkid) {
                    $this->Mench_ledger->update($linkid, array(
                        'linknumber' => intval($rank),
                    ), $player_e['playerid']);
                }

                //Display message:
                return view__json(array(
                    'status' => 1,
                ));

            }
        }
    }

    function e_delete(){

        //Auth member and check required variables:
        $player_e = superpower_unlocked(10939, 0, $this->player_e);

        if (!$player_e) {
            return view__json(array(
                'status' => 0,
                'message' => view__unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['linkid'])) {
            return view__json(array(
                'status' => 0,
                'message' => 'Invalid Transaction ID',
            ));
        }

        //Archive Transaction:
        $this->Mench_ledger->update($_POST['linkid'], array(), $player_e['playerid']);

        return view__json(array(
            'status' => 1,
        ));

    }

    function e_copy(){

        //Auth member and check required variables:
        $player_e = superpower_unlocked(10939, 0, $this->player_e);

        if (!$player_e) {
            return view__json(array(
                'status' => 0,
                'message' => view__unauthorized_message(10939),
            ));
        } elseif (intval($_POST['playerid']) < 1) {
            return view__json(array(
                'status' => 0,
                'message' => 'Invalid Source',
            ));
        } elseif (!strlen($_POST['copy_source_title'])) {
            return view__json(array(
                'status' => 0,
                'message' => 'Invalid Source Title',
            ));
        }


        //Validate Source:
        $fetch_o = $this->Source_cache->fetch(array(
            'playerid' => $_POST['playerid'],
        ));
        if (count($fetch_o) < 1) {
            return view__json(array(
                'status' => 0,
                'message' => 'Invalid followings source ID',
            ));
        }



        //Create:
        $added_e = $this->Source_cache->create($_POST['copy_source_title'], $player_e['playerid'], $fetch_o[0]['playercover']);
        if(!$added_e['status']){
            //We had an error, return it:
            return view__json($added_e);
        } else {
            //Assign new source:
            $focus_e = $added_e['new_e'];
        }


        //Followers:
        foreach($this->Mench_ledger->fetch(array(
            'linkup' => $_POST['playerid'],
            'linktype IN (' . join(',', $this->config->item('n___41303')) . ')' => null, //Clone Source Links
            'linkvoid' => 0, //Not Void
        ), array(), 0) as $x) {
            //Make sure none existent in new source:
            if(!count($this->Mench_ledger->fetch(array(
                'linktype' => $x['linktype'],
                'linkup' => $focus_e['playerid'],
                'linkdown' => $x['linkdown'],
                'linktext' => $x['linktext'],
            )))){
                $this->Mench_ledger->create(array(
                    'linkplayer' => $player_e['playerid'],
                    'linknumber' => $x['linknumber'],
                    'linktype' => $x['linktype'],
                    'linkup' => $focus_e['playerid'],
                    'linkdown' => $x['linkdown'],
                    'linktext' => $x['linktext'],
                ));
            }
        }


        //Followings:
        foreach($this->Mench_ledger->fetch(array(
            'linkdown' => $_POST['playerid'],
            'linktype IN (' . join(',', $this->config->item('n___41303')) . ')' => null, //Clone Source Links
            'linkvoid' => 0, //Not Void
        ), array(), 0) as $x) {
            if(!count($this->Mench_ledger->fetch(array(
                'linktype' => $x['linktype'],
                'linkup' => $x['linkup'],
                'linkdown' => $focus_e['playerid'],
                'linktext' => $x['linktext'],
            )))){
                $this->Mench_ledger->create(array(
                    'linkplayer' => $player_e['playerid'],
                    'linknumber' => $x['linknumber'],
                    'linktype' => $x['linktype'],
                    'linkup' => $x['linkup'],
                    'linkdown' => $focus_e['playerid'],
                    'linktext' => $x['linktext'],
                ));
            }
        }

        //Ideas:
        foreach($this->Mench_ledger->fetch(array(
            'linkvoid' => 0, //Not Void
            'linktype IN (' . join(',', $this->config->item('n___41302')) . ')' => null, //Clone Idea Source Links
            'linkup' => $_POST['playerid'],
        ), array(), 0) as $x){
            if(!count($this->Mench_ledger->fetch(array(
                'linktype' => $x['linktype'],
                'linkup' => $focus_e['playerid'],
                'linkdown' => $x['linkdown'],
                'linkleft' => $x['linkleft'],
                'linkright' => $x['linkright'],
                'linktext' => $x['linktext'],
            )))){
                $this->Mench_ledger->create(array(
                    'linkplayer' => $player_e['playerid'],
                    'linknumber' => $x['linknumber'],
                    'linktype' => $x['linktype'],
                    'linkup' => $focus_e['playerid'],
                    'linkdown' => $x['linkdown'],
                    'linkleft' => $x['linkleft'],
                    'linkright' => $x['linkright'],
                    'linktext' => $x['linktext'],
                ));
            }
        }

        return view__json(array(
            'status' => 1,
            'new_playerhandle' => $focus_e['playerhandle'],
        ));


    }

    function i__add()
    {

        /*
         *
         * Either creates a IDEA transaction between focus_id & link_ideaid
         * OR will create a new idea with outcome ideatext and then transaction it
         * to focus_id (In this case link_ideaid=0)
         *
         * */

        //Authenticate Member:
        $member_e = superpower_unlocked(10939, 0, $this->player_e);
        if (!$member_e) {
            return view__json(array(
                'status' => 0,
                'message' => view__unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['linktype']) || !isset($_POST['focus_id']) || !isset($_POST['focus_card'])) {
            return view__json(array(
                'status' => 0,
                'message' => 'Missing Core Variables',
            ));
        } elseif (!isset($_POST['new_ideatext']) || !isset($_POST['link_ideaid'])) {
            return view__json(array(
                'status' => 0,
                'message' => 'Missing either Idea Outcome OR Follower Idea ID',
            ));
        }

        $validate_ideatext = validate_ideatext($_POST['new_ideatext']);
        if(!$validate_ideatext['status']){
            //We had an error, return it:
            return view__json($validate_ideatext);
        }


        if(!$_POST['link_ideaid'] && view__valid_handle_i($_POST['new_ideatext'])){
            foreach($this->Idea_cache->fetch(array(
                'LOWER(ideahashtag)' => strtolower(view__valid_handle_i($_POST['new_ideatext'])),
            )) as $i){
                $_POST['link_ideaid'] = $i['ideaid'];
            }
        }

        $x_i = array();

        if($_POST['link_ideaid'] > 0){
            //Fetch transaction idea to determine idea type:
            $x_i = $this->Idea_cache->fetch(array(
                'ideaid' => intval($_POST['link_ideaid']),
            ));
            if(count($x_i)==0){
                //validate Idea:
                return view__json(array(
                    'status' => 0,
                    'message' => 'Idea #'.$_POST['link_ideaid'].' is not active.',
                ));
            }
        }

        //All seems good, go ahead and try to create/link the Idea:
        return view__json($this->Idea_cache->create_or_link($_POST['focus_card'], $_POST['linktype'], trim($_POST['new_ideatext']), $member_e['playerid'], $_POST['focus_id'], $_POST['link_ideaid']));

    }


    function e__add()
    {

        //Auth member and check required variables:
        $player_e = superpower_unlocked(10939, 0, $this->player_e);

        if (!$player_e) {
            return view__json(array(
                'status' => 0,
                'message' => view__unauthorized_message(10939),
            ));
        } elseif (intval($_POST['focus__id']) < 1) {
            return view__json(array(
                'status' => 0,
                'message' => 'Invalid Following Source',
            ));
        } elseif (!isset($_POST['linktype'])) {
            return view__json(array(
                'status' => 0,
                'message' => 'Invalid Source Creation Type',
            ));
        } elseif (!isset($_POST['e_existing_id']) || !isset($_POST['e_new_string']) || (intval($_POST['e_existing_id']) < 1 && strlen($_POST['e_new_string']) < 1)) {
            return view__json(array(
                'status' => 0,
                'message' => 'Either New Source ID or Source Name',
            ));
        }

        $adding_to_i = ($_POST['focus__node']==12273);

        if($adding_to_i){

            //Validate Idea:
            $fetch_o = $this->Idea_cache->fetch(array(
                'ideaid' => $_POST['focus__id'],
            ));
            if (count($fetch_o) < 1) {
                return view__json(array(
                    'status' => 0,
                    'message' => 'Invalid followings source ID',
                ));
            }

        } else {

            //Validate Source:
            $fetch_o = $this->Source_cache->fetch(array(
                'playerid' => $_POST['focus__id'],
            ));
            if (count($fetch_o) < 1) {
                return view__json(array(
                    'status' => 0,
                    'message' => 'Invalid followings source ID',
                ));
            }

        }



        //Set some variables:
        $_POST['e_new_string'] = trim($_POST['e_new_string']);
        $_POST['linktype'] = intval($_POST['linktype']);
        $is_upwards = in_array($_POST['linktype'], $this->config->item('n___14686'));

        if(!intval($_POST['e_existing_id']) && view__valid_handle_e($_POST['e_new_string'])){
            foreach($this->Source_cache->fetch(array(
                'LOWER(playerhandle)' => strtolower(substr($_POST['e_new_string'], 1)),
            )) as $e){
                $_POST['e_existing_id'] = $e['playerid'];
            }
        }
        $adding_to_existing = ( intval($_POST['e_existing_id']) > 0 );

        //Are we adding an existing source?
        if ($adding_to_existing) {

            //Validate this existing source:
            $es = $this->Source_cache->fetch(array(
                'playerid' => $_POST['e_existing_id'],
            ));

            if (count($es) < 1) {
                return view__json(array(
                    'status' => 0,
                    'message' => 'Source @'.$_POST['e_existing_id'].' is not active',
                ));
            }

            //All good, assign:
            $focus_e = $es[0];

        } else {

            //We are creating a new source:
            $added_e = $this->Source_cache->create($_POST['e_new_string'], $player_e['playerid']);
            if(!$added_e['status']){
                //We had an error, return it:
                return view__json($added_e);
            } else {
                //Assign new source:
                $focus_e = $added_e['new_e'];
            }

        }


        //We need to check to ensure this is not a duplicate transaction if adding an existing source:
        $ur2 = array();

        if($adding_to_i) {

            //Add Reference:
            $ur2 = $this->Mench_ledger->create(array(
                'linkplayer' => $player_e['playerid'],
                'linktype' => 4983, //Co-Author
                'linkup' => $focus_e['playerid'],
                'linkright' => $fetch_o[0]['ideaid'],
            ));

        } else {

            //Add Up/Down Source:

            //Add transactions only if not previously added by the URL function:
            if ($is_upwards) {

                //Following
                $linkdown = $fetch_o[0]['playerid'];
                $linkup = $focus_e['playerid'];
                $linknumber = 0; //Never sort following, only sort followers

            } else {

                //Followers
                $linkup = $fetch_o[0]['playerid'];
                $linkdown = $focus_e['playerid'];
                $linknumber = 0;

            }


            $linktext = null;

            //Create transaction:
            $ur2 = $this->Mench_ledger->create(array(
                'linkplayer' => $player_e['playerid'],
                'linktype' => 4230,
                'linktext' => $linktext,
                'linkdown' => $linkdown,
                'linkup' => $linkup,
                'linknumber' => $linknumber,
            ));
        }


        //Return source:
        return view__json(array(
            'status' => 1,
            'e_new_echo' => view__card_e($_POST['linktype'], array_merge($focus_e, $ur2), null),
        ));

    }

    function e_editor_load()
    {

        $player_e = superpower_unlocked(null, 0, $this->player_e);
        $e___11035 = $this->config->item('e___11035');
        $e___42776 = $this->config->item('e___42776');
        $e___4592 = $this->config->item('e___4592'); //Data types
        if (!$player_e) {
            return view__json(array(
                'status' => 0,
                'message' => view__unauthorized_message(),
            ));
        } elseif (!isset($_POST['playerid']) || !isset($_POST['linkid'])) {
            return view__json(array(
                'status' => 0,
                'message' => 'Missing Core IDs',
            ));
        }

        $es = $this->Source_cache->fetch(array(
            'playerid' => $_POST['playerid'],
        ));
        if (!count($es)) {
            return view__json(array(
                'status' => 0,
                'message' => 'Source is no longer active',
            ));
        } elseif (!access_level_e($es[0]['playerhandle'], 0, $es[0])) {
            return view__json(array(
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
        foreach($this->Mench_ledger->fetch(array(
            'linkup IN (' . join(',', $this->config->item('n___42178')) . ')' => null, //Dynamic Sources
            'linkdown' => $es[0]['playerid'],
            'linktype IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'linkvoid' => 0, //Not Void
        ), array('linkup'), 0, 0, sort_by(42178)) as $e_group) {

            if(in_array($e_group['playerid'], $scanned_sources)){
                continue;
            }
            array_push($scanned_sources, $e_group['playerid']);

            foreach($this->Mench_ledger->fetch(array(
                'linkdown' => $e_group['playerid'],
                'linkup IN (' . join(',', $this->config->item('n___42145')) . ')' => null, //Dynamic Input Templates
                'linktype IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'linkvoid' => 0, //Not Void
            ), array('linkup'), 0, 0, $order_42145) as $e_template) {

                $profile_header = '<div class="profile_header main__title"><span class="icon-block-sm">'.view__cover($e_template['playercover']).'</span>'.$e_template['playertext'].'<a href="'.view__memory(42903,42902).$e_group['playerhandle'].'" target="_blank" data-toggle="tooltip" data-placement="top" title="Because you follow '.$e_group['playertext'].'... Click to Open in a New Window"><span class="icon-block-sm">'.view__cover($e_group['playercover']).'</span></a></div>';


                //Load template:
                if(!is_array($this->config->item('e___'.$e_template['playerid']))){
                    //Report Error:
                    $this->Mench_ledger->create(array(
                        'linktype' => 44179, //Triggered
                        'linkup' => 4246, //Platform Bug Reports
                        'linkdown' => $e_template['playerid'],
                        'linktext' => 'e_editor_load() ERROR: @'.$e_template['playerid'].' is NOT in memory cache',
                    ));
                    continue;
                } elseif(in_array($e_template['playerid'], $scanned_sources)){
                    continue;
                }
                array_push($scanned_sources, $e_template['playerid']);


                foreach($this->config->item('e___'.$e_template['playerid']) as $dynamic_playerid => $m) {

                    //Make sure it's a dynamic input field:
                    if(!in_array($dynamic_playerid, $this->config->item('n___42179'))){
                        continue;
                    } elseif(in_array($dynamic_playerid, $scanned_sources)){
                        continue;
                    }
                    array_push($scanned_sources, $dynamic_playerid);

                    //Let's first determine the data type:
                    $data_types = array_intersect($m['m__following'], $this->config->item('n___4592'));

                    if(count($data_types)!=1){

                        //This is strange, we are expecting 1 match only report this:
                        $this->Mench_ledger->create(array(
                            'linktype' => 44179, //Triggered
                            'linkup' => 4246, //Platform Bug Reports
                            'linkdown' => $dynamic_playerid,
                            'linkplayer' => $player_e['playerid'],
                            'linktext' => 'Found '.count($data_types).' Data Types (@'.$es[0]['playerid'].') (Expecting exactly 1) for @'.$dynamic_playerid.': Check @4592 to see what is wrong',
                        ));
                        continue; //Go to the next dynamic data type

                    } elseif ($input_pointer >= view__memory(6404, 42206)) {
                        //Monitor if we ever reach the maximum:
                        $this->Mench_ledger->create(array(
                            'linktype' => 44179, //Triggered
                            'linkup' => 4246, //Platform Bug Reports
                            'linkdown' => $dynamic_playerid,
                            'linkplayer' => $player_e['playerid'],
                            'linkright' => $_POST['playerid'],
                            'linktext' => 'Dynamic Fields Reach their maximum limit of ' . view__memory(6404, 42206) . '  which may require field expansion',
                        ));
                    }

                    //We found 1 match as expected:
                    $input_pointer++;
                    foreach($data_types as $data_type_this){
                        $data_type = $data_type_this;
                        break;
                    }

                    if(in_array($data_type, $this->config->item('n___42188'))){

                        //Single or Multiple Choice:
                        array_push($return_inputs, array(
                            'd__id' => $dynamic_playerid,
                            'd__is_radio' => 1,
                            'd_linkid' => 0,
                            'd__html' => view__instant_select($dynamic_playerid, $es[0]['playerid'], 0),
                            'd__value' => ( $es[0]['playerid']>0 ? $es[0]['playerid'] : '' ),
                            'd__type_name' => '',
                            'd__placeholder' => '',
                            'd__profile_header' => $profile_header,
                        ));

                    } else {

                        $this_data_type = $this->config->item('e___'.$data_type);
                        $e___42179 = $this->config->item('e___42179'); //Dynamic Input Field
                        $e___11035 = $this->config->item('e___11035'); //Encyclopedia

                        //Fetch the current value(s):
                        $counted = 0;
                        $unique_values = array();
                        foreach($this->Mench_ledger->fetch(array(
                            'linkvoid' => 0, //Not Void
                            'linktype IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                            'linkdown' => $es[0]['playerid'],
                            'linkup' => $dynamic_playerid,
                        ), array('linkup')) as $selected_e){
                            if(strlen($selected_e['linktext']) && !in_array($selected_e['linktext'], $unique_values)){
                                array_push($unique_values, $selected_e['linktext']);
                                $counted++;
                                array_push($return_inputs, array(
                                    'd__id' => $dynamic_playerid,
                                    'd__is_radio' => 0,
                                    'd_linkid' => $selected_e['linkid'],
                                    'd__html' => view__dynamic_headline($dynamic_playerid, $m, $selected_e),
                                    'd__value' => $selected_e['linktext'],
                                    'd__type_name' => html_input_type($data_type),
                                    'd__placeholder' => ( strlen($this_data_type[$dynamic_playerid]['m__message']) ? $this_data_type[$dynamic_playerid]['m__message'] : $e___4592[$data_type]['m__title'].'...' ),
                                    'd__profile_header' => $profile_header,
                                ));
                            }
                        }

                        if(!$counted){
                            foreach($this->Source_cache->fetch(array(
                                'playerid' => $dynamic_playerid,
                            )) as $selected_e){
                                array_push($return_inputs, array(
                                    'd__id' => $dynamic_playerid,
                                    'd__is_radio' => 0,
                                    'd_linkid' => 0,
                                    'd__html' => view__dynamic_headline($dynamic_playerid, $m, $selected_e),
                                    'd__value' => '',
                                    'd__type_name' => html_input_type($data_type),
                                    'd__placeholder' => ( strlen($this_data_type[$dynamic_playerid]['m__message']) ? $this_data_type[$dynamic_playerid]['m__message'] : $e___4592[$data_type]['m__title'].'...' ),
                                    'd__profile_header' => $profile_header,
                                ));
                            }
                        }
                    }
                }
            }
        }



        //Add universal inputs only if missing bio profiles:
        if(!array_intersect($scanned_sources, $this->config->item('n___42885'))){
            foreach($this->Source_cache->fetch(array(
                'playerid IN (' . join(',', $this->config->item('n___42776')) . ')' => null, //Universal Dynamic Inputs
            )) as $selected_e){
                foreach(array_intersect($e___42776[$selected_e['playerid']]['m__following'], $this->config->item('n___4592')) as $data_type){
                    //Any value?
                    $values = $this->Mench_ledger->fetch(array(
                        'linkvoid' => 0, //Not Void
                        'linktype IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                        'linkdown' => $es[0]['playerid'],
                        'linkup' => $selected_e['playerid'],
                    ));
                    array_push($return_inputs, array(
                        'd__id' => $selected_e['playerid'],
                        'd__is_radio' => 0,
                        'd_linkid' => 0,
                        'd__html' => view__dynamic_headline($selected_e['playerid'], $e___42776[$selected_e['playerid']], $selected_e),
                        'd__value' => ( isset($values[0]['linktext']) && strlen($values[0]['linktext'])>0 ? $values[0]['linktext'] : '' ),
                        'd__type_name' => html_input_type($data_type),
                        'd__placeholder' => ( strlen($e___42776[$selected_e['playerid']]['m__message']) ? $e___42776[$selected_e['playerid']]['m__message'] : $e___4592[$data_type]['m__title'].'...' ),
                        'd__profile_header' => '', //No header for universals
                    ));
                    break;
                }
            }
        }

        //Return everything we found:
        return view__json(array(
            'status' => 1,
            'return_inputs' => $return_inputs,
        ));

    }

    function e_editor_save()
    {

        $player_e = superpower_unlocked(null, 0, $this->player_e);
        if (!$player_e) {
            return view__json(array(
                'status' => 0,
                'message' => view__unauthorized_message(),
            ));
        } elseif (!isset($_POST['save_playerid'])) {
            return view__json(array(
                'status' => 0,
                'message' => 'Invalid Coin ID',
            ));
        } elseif (!isset($_POST['save_playertext'])) {
            return view__json(array(
                'status' => 0,
                'message' => 'Invalid Source Title',
            ));
        } elseif (!isset($_POST['save_playerhandle'])) {
            return view__json(array(
                'status' => 0,
                'message' => 'Invalid Source Handle',
            ));
        } elseif (!isset($_POST['save_playercover'])) {
            return view__json(array(
                'status' => 0,
                'message' => 'Invalid Source Cover',
            ));
        } elseif(!isset($_POST['save_linkid']) || !isset($_POST['save_linktext'])){
            return view__json(array(
                'status' => 0,
                'message' => 'Missing Transaction Data',
            ));
        }



        $es = $this->Source_cache->fetch(array(
            'playerid' => $_POST['save_playerid'],
        ));
        if(!count($es)){
            return view__json(array(
                'status' => 0,
                'message' => 'Source Not Active',
            ));
        }


        //Validate Dynamic Inputs:
        $e___42179 = $this->config->item('e___42179'); //Dynamic Input Fields

        //Process dynamic inputs if any:
        for ($p = 1; $p <= view__memory(6404,42206); $p++) {

            if(!isset($_POST['save_dynamic_' . $p])){
                break; //Nothing more to process
            }

            $input_parts = explode('____', $_POST['save_dynamic_' . $p], 3);
            if(!isset($input_parts[0]) || !isset($input_parts[1])){
                continue;
            }
            $d_linkid = $input_parts[0];
            $dynamic_playerid = $input_parts[1];
            $dynamic_value = trim($input_parts[2]);


            //Required fields must have an input:
            if(in_array($dynamic_playerid, $this->config->item('n___28239')) && !strlen($dynamic_value) && !in_array($dynamic_playerid, $this->config->item('n___33331')) && !in_array($dynamic_playerid, $this->config->item('n___33332'))){
                return view__json(array(
                    'status' => 0,
                    'message' => 'Missing Required Field: '.$e___42179[$dynamic_playerid]['m__title'],
                ));
            }

            //Validate input based on its data type, if provided:
            if (strlen($dynamic_value)) {
                foreach(array_intersect($e___42179[$dynamic_playerid]['m__following'], $this->config->item('n___4592')) as $data_type_this){
                    $data_type_validate = data_type_validate($data_type_this, $dynamic_value, $e___42179[$dynamic_playerid]['m__title']);
                    if (!$data_type_validate['status']) {
                        //We had an error:
                        return view__json($data_type_validate);
                    }
                }
            }


            //Fetch the current value:
            if($d_linkid > 0){
                $values = $this->Mench_ledger->fetch(array(
                    'linkid' => $d_linkid,
                ));
            }

            if(!$d_linkid || !count($values)){
                $values = $this->Mench_ledger->fetch(array(
                    'linkvoid' => 0, //Not Void
                    'linktype IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'linkup' => $dynamic_playerid,
                    'linkdown' => $es[0]['playerid'],
                ));
            }


            //Update if needed:
            if (!strlen($dynamic_value)) {

                //Remove Link if we have one:
                if(count($values) && $dynamic_playerid!=11035 /* HACK: Summary are key links that should not be removed */){
                    $this->Mench_ledger->update($values[0]['linkid'], array(), $player_e['playerid']);
                }

            } elseif (!count($values)) {

                //Create Link:
                $this->Mench_ledger->create(array(
                    'linkplayer' => $player_e['playerid'],
                    'linktype' => 4230,
                    'linkup' => $dynamic_playerid,
                    'linkdown' => $es[0]['playerid'],
                    'linktext' => $dynamic_value,
                    'linknumber' => number_linknumber($dynamic_value),
                ));

            } elseif ($values[0]['linktext'] != $dynamic_value) {

                //Update Link:
                $this->Mench_ledger->update($values[0]['linkid'], array(
                    'linktext' => $dynamic_value,
                ), $player_e['playerid']);

            }
        }




        //Validate Source Handle & save if needed:
        if($es[0]['playerhandle'] !== trim($_POST['save_playerhandle'])){
            $validate_update_handle = validate_update_handle(trim($_POST['save_playerhandle']), null, $es[0]['playerid']);
            if(!$validate_update_handle['status']){
                return view__json(array(
                    'status' => 0,
                    'message' => $validate_update_handle['message'],
                ));
            }
        }

        //Validate Source Title & save if needed:
        $validate_playertext = validate_playertext($_POST['save_playertext']);
        if($es[0]['playertext'] != trim($_POST['save_playertext'])){
            if(!$validate_playertext['status']){
                return view__json(array(
                    'status' => 0,
                    'message' => $validate_playertext['message'],
                ));
            }
            $es[0]['playertext'] = $validate_playertext['playertext_clean'];
        }

        //Save Source Cover if needed:
        if($es[0]['playercover'] != trim($_POST['save_playercover'])){
            //TODO validate playercover?
            $es[0]['playercover'] = trim($_POST['save_playercover']);
        }

        //Update:
        $this->Source_cache->update($es[0]['playerid'], array(
            'playertext' => $validate_playertext['playertext_clean'],
            'playercover' => trim($_POST['save_playercover']),
            'playerhandle' => trim($_POST['save_playerhandle']),
        ), true, $player_e['playerid']);


        $es[0]['playerhandle'] = sync_handle_references($es[0], trim($_POST['save_playerhandle']));

        //Do we have a link reference message that need to be saved?
        if($_POST['save_linkid']>0 && $_POST['save_linktext']!='IGNORE_INPUT'){

            //Fetch transaction:
            foreach($this->Mench_ledger->fetch(array(
                'linkid' => $_POST['save_linkid'],
            )) as $this_x){

                $es[0] = array_merge($es[0], $this_x);

                if($this_x['linktext'] != trim($_POST['save_linktext'])){
                    $this->Mench_ledger->update($this_x['linkid'], array(
                        'linktext' => trim($_POST['save_linktext']),
                    ), $player_e['playerid']);
                }
            }
        }


        //Reset member session data if this data belongs to the logged-in member:
        if ($_POST['save_playerid']==$player_e['playerid']) {
            $this->Source_cache->activate_session($es[0], true);
        }


        return view__json(array(
            'status' => 1,
            'message' => 'Updated ',
        ));


    }

    function e_select_apply()
    {
        /*
         *
         * Saves the radio selection of some account fields
         *
         * */

        $player_e = superpower_unlocked(null, 0, $this->player_e);
        if (!$player_e) {
            return view__json(array(
                'status' => 0,
                'message' => view__unauthorized_message(),
            ));
        } elseif (!isset($_POST['focus__id']) || intval($_POST['focus__id']) < 1) {
            return view__json(array(
                'status' => 0,
                'message' => 'Missing followings source',
            ));
        } elseif (!isset($_POST['selected_playerid']) || intval($_POST['selected_playerid']) < 1) {
            return view__json(array(
                'status' => 0,
                'message' => 'Missing selected source',
            ));
        } elseif (!isset($_POST['down_playerid']) || !isset($_POST['right_ideaid'])) {
            return view__json(array(
                'status' => 0,
                'message' => 'Missing Down/Right Element',
            ));
        } elseif (!isset($_POST['enable_mulitiselect']) || !isset($_POST['was_previously_selected'])) {
            return view__json(array(
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


        if($_POST['down_playerid'] > 0){

            //Dispatch Any Emails Necessary:
            if(isset($_POST['selected_playerid']) && intval($_POST['selected_playerid'])>0){
                foreach($this->Mench_ledger->fetch(array(
                    'linkvoid' => 0, //Not Void
                    'linktype' => 33600, //Draft
                    'linkup' => $_POST['selected_playerid'],
                ), array('linkright'), 0) as $i){
                    if(count($this->Mench_ledger->fetch(array(
                        'linkvoid' => 0, //Not Void
                        'linktype' => 33600, //Draft
                        'linkup' => 31065, //Choice Update Email Templates
                        'linkright' => $i['ideaid'], //Is this the template?
                    )))){
                        //Found the email template to send:
                        $total_sent = $this->Mench_ledger->send_i_mass_dm(array($player_e), $i, website_setting(0), false);
                        break; //Just the first template match
                    }
                }
            }
        }

        $is_required = in_array($_POST['focus__id'], $this->config->item('n___28239')); //Required Settings

        if(!$_POST['enable_mulitiselect'] || $_POST['was_previously_selected']){

            //Since this is not a multi-select we want to delete all existing options

            //Fetch all possible answers based on followings source:
            $query_filters = array(
                'linkup' => $_POST['focus__id'],
                'linktype IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'linkvoid' => 0, //Not Void
            );

            if((!$is_required || $_POST['enable_mulitiselect']) && $_POST['was_previously_selected']){
                //Just delete this single item, not the other ones:
                $query_filters['linkdown'] = $_POST['selected_playerid'];
            }

            //List all possible answers:
            $possible_answers = array();
            foreach($this->Mench_ledger->fetch($query_filters, array('linkdown'), 0, 0) as $answer_e){
                $stats['total']++;
                array_push($possible_answers, $answer_e['playerid']);
            }

            //Delete previously selected options:
            if($_POST['down_playerid']){
                $delete_query = $this->Mench_ledger->fetch(array(
                    'linkup IN (' . join(',', $possible_answers) . ')' => null,
                    'linkdown' => $_POST['down_playerid'],
                    'linktype IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'linkvoid' => 0, //Not Void
                ));
            } elseif($_POST['right_ideaid']){
                $delete_query = $this->Mench_ledger->fetch(array(
                    'linkup IN (' . join(',', $possible_answers) . ')' => null,
                    'linkright' => $_POST['right_ideaid'],
                    'linktype IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                    'linkvoid' => 0, //Not Void
                ));
            }

            foreach($delete_query as $delete){
                $stats['deleted']++;
                //Should usually delete a single option:
                $this->Mench_ledger->update($delete['linkid'], array(), $player_e['playerid']);
            }

        }

        //Add new option if not previously there:
        if((!$_POST['enable_mulitiselect'] && $is_required) || !$_POST['was_previously_selected']){
            if($_POST['down_playerid']){
                $stats['added']++;
                $this->Mench_ledger->create(array(
                    'linkplayer' => $player_e['playerid'],
                    'linkup' => $_POST['selected_playerid'],
                    'linktype' => 4230,
                    'linkdown' => $_POST['down_playerid'],
                ));
            } elseif($_POST['right_ideaid']){

                if(!count($this->Mench_ledger->fetch(array(
                    'linktype IN (' . join(',', $this->config->item('n___31919')) . ')' => null, //IDEA AUTHOR
                    'linkup' => $_POST['selected_playerid'],
                    'linkright' => $_POST['right_ideaid'],
                    'linkvoid' => 0, //Not Void
                )))){
                    $stats['added']++;
                    $this->Mench_ledger->create(array(
                        'linkplayer' => $player_e['playerid'],
                        'linktype' => 4983, //Co-Author
                        'linkup' => $_POST['selected_playerid'],
                        'linkright' => $_POST['right_ideaid'],
                    ));
                }

            }
        }


        //Update Session:
        if($_POST['down_playerid'] && count($player_e)){
            $this->Source_cache->activate_session($player_e, true);
        }


        //All good:
        return view__json(array(
            'status' => 1,
            'message' => 'Updated: '.print_r($stats, true),
        ));
    }

    function e_contact_auth(){


        if (!isset($_POST['account_id'])) {
            return view__json(array(
                'status' => 0,
                'message' => 'Missing user ID',
            ));
        } elseif (!isset($_POST['input_code']) || !intval($_POST['input_code'])) {
            return view__json(array(
                'status' => 0,
                'message' => 'Invalid code',
            ));
        } elseif (!isset($_POST['account_email_phone'])) {
            return view__json(array(
                'status' => 0,
                'message' => 'Missing account_email_phone',
            ));
        } elseif (!isset($_POST['referrer_url'])) {
            return view__json(array(
                'status' => 0,
                'message' => 'Missing referrer URL',
            ));
        } elseif (!isset($_POST['sign_ideaid'])) {
            return view__json(array(
                'status' => 0,
                'message' => 'Missing idea referrer',
            ));
        }

        $_POST['account_email_phone'] = trim(strtolower($_POST['account_email_phone']));

        //Validate member ID
        if($_POST['account_id'] > 0){

            $es = $this->Source_cache->fetch(array(
                'playerid' => $_POST['account_id'],
            ));
            if(!count($es)){
                return view__json(array(
                    'status' => 0,
                    'message' => 'Invalid account ID.',
                ));
            }

        } else {

            $_POST['new_account_email'] = trim(strtolower($_POST['new_account_email']));
            if(!filter_var($_POST['account_email_phone'], FILTER_VALIDATE_EMAIL) && !filter_var($_POST['new_account_email'], FILTER_VALIDATE_EMAIL)){
                return view__json(array(
                    'status' => 0,
                    'message' => 'Enter your email to continue',
                ));
            }

        }



        //Auth Code:
        $is_authenticated = false;
        foreach($this->Mench_ledger->fetch(array(
            'linktype' => 44179, //Triggered
            'linkup' => 32078, //Sign In Key
            'linkvoid' => 0, //Not Void
            'LOWER(linktext) LIKE \''.strtolower($_POST['account_email_phone']).'%\'' => null,
        ), array(), 1, 0, array('linktime' => 'DESC')) as $sent_key){
            if(strtotime($sent_key['linktime']) <= (time() - 86400)) {
                //Expired
                $this->Mench_ledger->update($sent_key['linkid'], array(), $_POST['account_id']); //Code Verified
                break;
            }
            $session_key = $this->session->userdata('session_key');
            $key_parts = explode('/', $sent_key, 2);
            if(strlen($session_key) && $key_parts[1]==md5($session_key.$_POST['input_code'])){
                //Void access code:
                $is_authenticated = $this->Mench_ledger->update($sent_key['linkid'], array(), $_POST['account_id']); //Code Verified
            }
        }
        if(!$is_authenticated){
            return view__json(array(
                'status' => 0,
                'message' => 'Invalid code, try again.',
            ));
        }





        //Validate member ID
        if($_POST['account_id'] > 0){

            //Assign session & log transaction:
            $this->Source_cache->activate_session($es[0]);

        } else {

            //Add new account
            $_POST['account_email_phone'] =  trim(strtolower($_POST['account_email_phone']));
            $is_email = filter_var($_POST['account_email_phone'], FILTER_VALIDATE_EMAIL);

            //Prep inputs & validate further:
            $acc_email = ( $is_email ? $_POST['account_email_phone'] : $_POST['new_account_email'] );
            $player_result = $this->Source_cache->add_member(strstr($acc_email, '@', true), $acc_email, ( !$is_email ? $_POST['account_email_phone'] : '' ));
            if (!$player_result['status']) {
                return view__json($player_result);
            }

            $es[0] = $player_result['e'];

        }


        //Set default sign in URL:
        $sign_url = view__memory(42903,42902).$es[0]['playerhandle'];

        //See if we can find a better one:
        if (intval($_POST['sign_ideaid']) > 0) {
            foreach($this->Idea_cache->fetch(array(
                'ideaid' => $_POST['sign_ideaid'],
            )) as $i){
                $sign_url = $i['ideahashtag'].'/'.view__memory(6404,4235);
            }
        } elseif (isset($_POST['referrer_url']) && strlen(urldecode($_POST['referrer_url'])) > 1) {
            $sign_url = urldecode($_POST['referrer_url']);
        }

        return view__json(array(
            'status' => 1,
            'sign_url' => $sign_url,
        ));

    }

    function e_toggle_e(){

        $player_e = superpower_unlocked(10939, 0, $this->player_e);
        if(!$player_e){

            return view__json(array(
                'status' => 0,
                'message' => view__unauthorized_message(10939),
            ));

        } elseif(!isset($_POST['linkplayer']) || !isset($_POST['playerid']) || !isset($_POST['ideaid']) || !isset($_POST['linkid'])){

            return view__json(array(
                'status' => 0,
                'message' => 'Missing Core Variable',
            ));

        } else {

            $_POST['require_writing'] = intval($_POST['require_writing']);

            $already_added = $this->Mench_ledger->fetch(array(
                'linkup' => $_POST['playerid'],
                'linkdown' => $_POST['linkplayer'],
                'linktype IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'linkvoid' => 0, //Not Void
            ), array('linkup'));

            if(count($already_added)){

                if(intval($_POST['require_writing'])){

                    //Updating current value if changed:
                    if(strlen($_POST['written_answer']) && trim($_POST['written_answer'])!=$already_added[0]['linktext']){
                        $this->Mench_ledger->update($already_added[0]['linkid'], array(
                            'linktext' => $_POST['written_answer'],
                        ), $player_e['playerid']);
                    } elseif(!strlen($_POST['written_answer'])){
                        $this->Mench_ledger->update($already_added[0]['linkid'], array(), $player_e['playerid']);
                    }

                    return view__json(array(
                        'status' => 1,
                        'message' => $_POST['written_answer'],
                    ));

                } else {

                    //Already exists, let's remove:
                    $this->Mench_ledger->update($already_added[0]['linkid'], array(), $player_e['playerid']);

                    return view__json(array(
                        'status' => 1,
                        'message' => '',
                    ));

                }

            } else {

                if(intval($_POST['require_writing']) && !strlen($_POST['written_answer'])){

                    //Nothing to do
                    return view__json(array(
                        'status' => 1,
                        'message' => '',
                    ));

                } else {

                    foreach($this->Source_cache->fetch(array(
                        'playerid' => $_POST['playerid'],
                    )) as $e){

                        //Does not exist, Add:
                        $this->Mench_ledger->create(array(
                            'linkup' => $_POST['playerid'],
                            'linkdown' => $_POST['linkplayer'],
                            'linkplayer' => $player_e['playerid'],
                            'linktext' => $_POST['written_answer'],
                            'linktype' => 4230,
                        ));

                        return view__json(array(
                            'status' => 1,
                            'message' => ( intval($_POST['require_writing']) ? $_POST['written_answer'] : view__cover($e['playercover'], true) ),
                        ));

                    }
                }
            }
        }
    }

    function e_verify_contact(){

        if(!isset($_POST['account_email_phone'])){
            return view__json(array(
                'status' => 0,
                'message' => 'missing account details',
            ));
        }

        //Cleanup input email:
        $e___11035 = $this->config->item('e___11035'); //Encyclopedia
        $_POST['account_email_phone'] = trim(strtolower($_POST['account_email_phone']));
        $valid_email = filter_var($_POST['account_email_phone'], FILTER_VALIDATE_EMAIL);
        if(!$valid_email && strlen($_POST['account_email_phone'])>=10){
            $_POST['account_email_phone'] = preg_replace('/[^0-9]+/', '', $_POST['account_email_phone']);
        }
        $possible_phone = !$valid_email && strlen($_POST['account_email_phone'])>=10;

        if (!$valid_email && !$possible_phone) {
            return view__json(array(
                'status' => 0,
                'message' => ( strlen($_POST['account_email_phone']) ? '['.$_POST['account_email_phone'].'] is Invalid!' : 'Enter your email to continue...' ),
            ));
        } elseif (!isset($_POST['sign_ideaid'])) {
            return view__json(array(
                'status' => 0,
                'message' => 'Missing data ID',
            ));
        }


        if(intval($_POST['sign_ideaid']) > 0){
            //Fetch the idea:
            $referrer_i = $this->Idea_cache->fetch(array(
                'ideaid' => $_POST['sign_ideaid'],
            ));
        } else {
            $referrer_i = array();
        }


        //Search for email/phone to see if it exists
        $linkplayer = 0;
        foreach($this->Mench_ledger->fetch(array(
            'linkvoid' => 0, //Not Void
            'linktext' => $_POST['account_email_phone'],
            'linktype IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'linkup' => ( filter_var($_POST['account_email_phone'], FILTER_VALIDATE_EMAIL) ? 3288 : 4783 ), //Email / Phone
        ), array('linkdown')) as $map_e){
            $u = $map_e;
            $linkplayer = $map_e['playerid'];
            break;
        }

        //Send Sign In Key
        $passcode = rand(1000,9999);
        $session_key = random_string(55);

        //Append to session:
        $session_data = $this->session->all_userdata();
        $session_data['session_key'] = $session_key;
        $this->session->set_userdata($session_data);

        $html_message = $passcode.' is your '.$e___11035[32078]['m__title'].' for your '.get_domain('m__title').' account.';

        if($valid_email) {

            //Email:
            dispatch_email(array($_POST['account_email_phone']), $html_message, '<div class="line">'.$html_message.'</div>', $linkplayer, array(), 0, 0, false);



        } elseif($possible_phone) {

            //SMS:
            dispatch_sms($_POST['account_email_phone'], $html_message, 0, array(), 0, 0, false);

        }

        //Log new key:
        $this->Mench_ledger->create(array(
            'linktype' => 44179, //Triggered
            'linkup' => 32078, //Sign In Key
            'linkdown' => $linkplayer, //Member making request
            'linkplayer' => $linkplayer, //Member making request
            'linkleft' => intval($_POST['sign_ideaid']),
            'linktext' => $_POST['account_email_phone'].'/'.md5($session_key.$passcode),
        ));

        return view__json(array(
            'status' => 1,
            'account_id' => $linkplayer,
            'valid_email' => ( $valid_email ? 1 : 0 ),
            'account_preview' => ( $linkplayer ? '<span class="icon-block">'.view__cover($u['playercover'], true). '</span>'.$u['playertext'] : '' ),
            'clean_contact' => $_POST['account_email_phone'],
        ));

    }

    function x_set_text(){

        //Authenticate Member:
        $player_e = superpower_unlocked(null, 0, $this->player_e);
        $e___12112 = $this->config->item('e___12112');

        if (!$player_e) {

            return view__json(array(
                'status' => 0,
                'message' => view__unauthorized_message(),
                'original_val' => '',
            ));

        } elseif(!isset($_POST['s__id']) || !isset($_POST['cache_playerid']) || !isset($_POST['new_ideatext'])){

            return view__json(array(
                'status' => 0,
                'message' => 'Missing core variables',
                'original_val' => '',
            ));

        } elseif($_POST['cache_playerid']==6197 /* SOURCE FULL NAME */){

            $es = $this->Source_cache->fetch(array(
                'playerid' => $_POST['s__id'],
            ));
            if(!count($es)){
                return view__json(array(
                    'status' => 0,
                    'message' => 'Invalid Source ID #3',
                    'original_val' => '',
                ));
            }


            $validate_playertext = validate_playertext($_POST['new_ideatext']);
            if(!$validate_playertext['status']){
                return view__json(array_merge($validate_playertext, array(
                    'original_val' => $es[0]['playertext'],
                )));
            }

            //All good, go ahead and update:
            $this->Source_cache->update($es[0]['playerid'], array(
                'playertext' => $validate_playertext['playertext_clean'],
            ), true, $player_e['playerid']);

            //Reset member session data if this data belongs to the logged-in member:
            if ($es[0]['playerid']==$player_e['playerid']) {
                //Re-activate Session with new data:
                $es[0]['playertext'] = $validate_playertext['playertext_clean'];
                $this->Source_cache->activate_session($es[0], true);
            }

            return view__json(array(
                'status' => 1,
            ));

        } else {

            return view__json(array(
                'status' => 0,
                'message' => 'Unknown Update Type ['.$_POST['cache_playerid'].']',
                'original_val' => '',
            ));

        }
    }

    function x_mass_apply_preview()
    {

        if(!isset($_POST['apply_id']) || !isset($_POST['s__id'])){
            die('Missing core data');
        }

        //Log Modal View
        $player_e = superpower_unlocked(null, 0, $this->player_e);

        if(!isset($_POST['apply_id']) || !isset($_POST['s__id'])){
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Missing Core Data</div>';
        } else {
            if($_POST['apply_id']==4997){

                //Source list:
                $counter = view__e_covers(12274, $_POST['s__id'], 0, false);
                if(!$counter){
                    echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>No Sources yet</div>';
                } else {
                    echo '<div class="alert" role="alert"><span class="icon-block"><i class="far fa-list"></i></span>Will apply to '.$counter.' source'.view__s($counter).':</div>';
                    echo '<div class="row justify-content">';
                    $ids = array();
                    foreach(view__e_covers(12274, $_POST['s__id'], 1, true) as $e) {
                        array_push($ids, $e['playerid']);
                        echo view__card_e(12274, $e);
                    }
                    echo '</div>';
                    echo '<div class="dotransparent" title="Total of '.count($ids).'">'.join(', ',$ids).'</div>';
                }

            } elseif($_POST['apply_id']==12589){

                //idea list:
                $is_next = $this->Mench_ledger->fetch(array(
                    'linkvoid' => 0, //Not Void
                    'linktype IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //IDEA LINKS
                    'linkleft' => $_POST['s__id'],
                ), array('linkright'), 0, 0, array('linknumber' => 'ASC'));
                $counter = count($is_next);

                if(!$counter){
                    echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>No Ideas yet</div>';
                } else {
                    echo '<div class="alert" role="alert"><span class="icon-block"><i class="far fa-list"></i></span>Will apply to '.$counter.' idea'.view__s($counter).':</div>';
                    echo '<div class="row justify-content">';
                    $ids = array();
                    foreach($is_next as $i) {
                        array_push($ids, $i['ideaid']);
                        echo view__card_i(12273, $i);
                    }
                    echo '</div>';
                    echo '<div class="dotransparent">'.join(',',$ids).'</div>';
                }

            } else {
                echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>Unknown Apply ID</div>';
            }
        }
    }

    function x_view__load_page()
    {

        $focus_e = array();
        $previous_i = array();

        if(!isset($_POST['focus__node'])){
            die('Missing input. Refresh and try again.');
        }
        $success = false;

        if($_POST['focus__node']==12274){

            //SOURCE
            $focus_es = $this->Source_cache->fetch(array(
                'playerid' => $_POST['focus__id'],
            ));
            $focus_e = $focus_es[0];

            foreach(view__e_covers($_POST['linktype'], $_POST['focus__id'], $_POST['current_page']) as $s) {
                if (in_array($_POST['linktype'], $this->config->item('n___11028'))) {
                    echo view__card_e($_POST['linktype'], $s);
                    $success = true;
                } else if ($_POST['linktype']==6255 || in_array($_POST['linktype'], $this->config->item('n___42284')) || in_array($_POST['linktype'], $this->config->item('n___42261')) || in_array($_POST['linktype'], $this->config->item('n___11020'))) {
                    echo view__card_i($_POST['linktype'], $s, $previous_i, null, $focus_e['playerid']);
                    $success = true;
                }
            }

        } elseif($_POST['focus__node']==12273) {

            //IDEA
            $previous_is = $this->Idea_cache->fetch(array(
                'ideaid' => $_POST['focus__id'],
            ));
            $previous_i = $previous_is[0];

            foreach(view__i_covers($_POST['linktype'], $_POST['focus__id'], $_POST['current_page']) as $s) {
                if (in_array($_POST['linktype'], $this->config->item('n___11020'))) {
                    echo view__card_i($_POST['linktype'], $s, $previous_i, null, $focus_e['playerid']);
                    $success = true;
                } else if ($_POST['linktype']==6255 || in_array($_POST['linktype'], $this->config->item('n___42261')) || in_array($_POST['linktype'], $this->config->item('n___42284')) || in_array($_POST['linktype'], $this->config->item('n___11028'))) {
                    echo view__card_e($_POST['linktype'], $s);
                    $success = true;
                }
            }
        }

        if(!$success){
            die('Nothing more to load :)');
        }

    }

    function x_reset_sorting()
    {

        //Authenticate Member:
        $player_e = superpower_unlocked(10939, 0, $this->player_e);

        if (!$player_e) {
            return view__json(array(
                'status' => 0,
                'message' => view__unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['focus__node']) || !in_array($_POST['focus__node'], $this->config->item('n___28956'))) {
            return view__json(array(
                'status' => 0,
                'message' => 'Invalid focus__node',
            ));
        } elseif (!isset($_POST['focus__id']) || intval($_POST['focus__id']) < 1) {
            return view__json(array(
                'status' => 0,
                'message' => 'Invalid focus__id',
            ));
        }

        if($_POST['focus__node']==12273){
            //Ideas order based on alphabetical order
            $order = 0;
            foreach($this->Mench_ledger->fetch(array(
                'linkvoid' => 0, //Not Void
                'linktype IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //IDEA LINKS
                'linkleft' => $_POST['focus__id'],
            ), array('linkright'), 0, 0, array('ideatext' => 'ASC')) as $x) {
                $order++;
                $this->Mench_ledger->update($x['linkid'], array(
                    'linknumber' => $order,
                ), $player_e['playerid']);
            }
        } elseif($_POST['focus__node']==12274){
            //Sources reset order
            foreach($this->Mench_ledger->fetch(array(
                'linkup' => $_POST['focus__id'],
                'linktype IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'linkvoid' => 0, //Not Void
            ), array('linkdown'), 0, 0) as $x) {
                $this->Mench_ledger->update($x['linkid'], array(
                    'linknumber' => 0,
                ), $player_e['playerid']);
            }
        }


        //Display message:
        view__json(array(
            'status' => 1,
        ));
    }

    function go_next(){

        $player_e = superpower_unlocked(null, 0, $this->player_e);
        if(!$player_e){
            return view__json(array(
                'status' => 0,
                'message' => view__unauthorized_message(),
            ));
        } elseif (!isset($_POST['target_ideahashtag']) || !isset($_POST['target_ideaid']) || !isset($_POST['focus_i_data']) || !isset($_POST['do_skip'])) {
            return view__json(array(
                'status' => 0,
                'message' => 'Missing Core Data',
            ));
        }

        if(!isset($_POST['selection_ideaid'])){
            $_POST['selection_ideaid'] = array();
        }
        if(!isset($_POST['focus_i_data']['i__text'])){
            $_POST['focus_i_data']['i__text'] = null;
        }
        if(!isset($_POST['focus_i_data']['uploaded_media'])){
            $_POST['focus_i_data']['uploaded_media'] = array();
        }
        if(!isset($_POST['next_i_data'])){
            $_POST['next_i_data'] = array();
        }


        //Discover Focus Idea:
        $primary_ideaid = null;
        foreach($this->Idea_cache->fetch(array(
            'ideaid' => $_POST['focus_i_data']['ideaid'],
        )) as $focus_i){

            $input__selection = in_array($focus_i['i__type'], $this->config->item('n___7712'));
            $input__upload = in_array($focus_i['i__type'], $this->config->item('n___43004'));
            $skipping_not_allowed = in_array($focus_i['i__type'], $this->config->item('n___43009'));
            $input__text = in_array($focus_i['i__type'], $this->config->item('n___43002')) || in_array($focus_i['i__type'], $this->config->item('n___43003'));
            $total_selected = count($_POST['selection_ideaid']);
            $trying_to_skip = !$skipping_not_allowed &&
                (
                        intval($_POST['do_skip'])
                    || ($input__selection && !$total_selected)
                    || ($input__text && !$input__upload && !strlen($_POST['focus_i_data']['i__text']))
                    || (!$input__text && $input__upload && !count($_POST['focus_i_data']['uploaded_media']))
                    || ($input__text && $input__upload && !count($_POST['focus_i_data']['uploaded_media']) && !strlen($_POST['focus_i_data']['i__text']))
                );
            $i_required = i_required($focus_i);

            if(!$primary_ideaid){
                $primary_ideaid = ( $total_selected ? end($_POST['selection_ideaid']) : $focus_i['ideaid'] );
            }

            //If skipping, make sure they can:
            if($i_required && $trying_to_skip){
                return view__json(array(
                    'status' => 0,
                    'message' => ( $input__selection ? 'Make a selection to continue...' : 'Respond to continue...' ),
                ));
            }

            //Now complete relevant next ideas, if any:
            if($input__selection){

                $is_single_selection = in_array($focus_i['i__type'], $this->config->item('n___33331'));

                //How about the min selection?
                if($i_required && !$is_single_selection){
                    foreach($this->Mench_ledger->fetch(array(
                        'linkvoid' => 0, //Not Void
                        'linktype IN (' . join(',', $this->config->item('n___42991')) . ')' => null, //Active Writes
                        'linkright' => $focus_i['ideaid'],
                        'linkup' => 40834, //Min Selection
                    ), array(), 1) as $limit){
                        if(intval($limit['linktext']) > 0 && $total_selected < intval($limit['linktext'])){
                            return view__json(array(
                                'status' => 0,
                                'message' => 'Select '.$limit['linktext'].' or more ideas to go next.',
                            ));
                        }
                    }
                }


                //How about max selection?
                if(!$is_single_selection){
                    foreach($this->Mench_ledger->fetch(array(
                        'linkvoid' => 0, //Not Void
                        'linktype IN (' . join(',', $this->config->item('n___42991')) . ')' => null, //Active Writes
                        'linkright' => $focus_i['ideaid'],
                        'linkup' => 40833, //Max Selection
                    ), array(), 1) as $limit){
                        if(intval($limit['linktext']) > 0 && $total_selected > intval($limit['linktext'])){
                            return view__json(array(
                                'status' => 0,
                                'message' => 'You cannot select more than '.$limit['linktext'].' items.',
                            ));
                        }
                    }
                }


                //Delete ALL previous answers that are not currently selected, if any:
                $already_answered = array();
                foreach($this->Mench_ledger->fetch(array(
                        'linkvoid' => 0, //Not Void
                    'linktype' => 7712, //Input Choice
                    'linkplayer' => $player_e['playerid'],
                    'linkleft' => $focus_i['ideaid'],
                ), array('linkright')) as $x_selection){

                    if(in_array($x_selection['ideaid'], $_POST['selection_ideaid'])){
                        //Current selection is already in the database from before:
                        array_push($already_answered, $x_selection['ideaid']);
                        continue; //Nothing we need to do here...
                    }

                    $this->Mench_ledger->update($x_selection['linkid'], array(), $player_e['playerid']);

                    //Remove discovery if we can:
                    if(!in_array($x_selection['i__type'], $this->config->item('n___42905'))){
                        foreach($this->Mench_ledger->fetch(array(
                            'linkvoid' => 0, //Not Void
                            'linktype IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                            'linkleft' => $x_selection['ideaid'],
                            'linkplayer' => $player_e['playerid'],
                        ), array(), 0) as $x_discovery){
                            $this->Mench_ledger->update($x_discovery['linkid'], array(), $player_e['playerid']);
                        }
                    }
                }

                //Save New Answers if not already:
                foreach($_POST['selection_ideaid'] as $answer_ideaid){
                    if(!in_array($answer_ideaid, $already_answered)){
                        $this->Mench_ledger->create(array(
                            'linktype' => 7712, //Input Choice
                            'linkplayer' => $player_e['playerid'],
                            'linkleft' => $focus_i['ideaid'],
                            'linkright' => $answer_ideaid,
                        ));
                    }
                }

            }

            //Issue DISCOVERY/IDEA COIN:
            $completion_status = $this->Mench_ledger->mark_complete(i__discovery_link($focus_i, $trying_to_skip), $player_e['playerid'], $_POST['target_ideaid'], $focus_i, $_POST['focus_i_data'], array(
                'linknumber' => $_POST['focus_i_data']['i__quantity'],
            ));
            if(!$completion_status['status']){
                //We had an error with data within target_ideaid:
                return view__json($completion_status);
            }



            //Look through ALL next ideas and see which ones we can complete, if any:
            foreach($_POST['next_i_data'] as $index => $next_i_data){

                if(!isset($next_i_data['ideaid'])){
                    continue;
                }
                if(!isset($next_i_data['i__text'])){
                    $next_i_data['i__text'] = null;
                }
                if(!isset($next_i_data['uploaded_media'])){
                    $next_i_data['uploaded_media'] = array();
                }

                if($input__selection && !in_array($next_i_data['ideaid'], $_POST['selection_ideaid'])){
                    //Not selected, move on:
                    continue;
                }

                foreach($this->Idea_cache->fetch(array(
                    'ideaid' => $next_i_data['ideaid'],
                )) as $i_next){

                    continue; //TODO Reactivate

                    $some_input_required = count($this->Mench_ledger->fetch(array(
                        'linkvoid' => 0, //Not Void
                        'linktype IN (' . join(',', $this->config->item('n___42991')) . ')' => null, //Active Writes
                        'linkright' => $i_next['linkright'],
                        'linkup IN (' . join(',', $this->config->item('n___43050')) . ')' => null, //Input Required Ideas
                    )));

                    //Can we auto-complete?
                    if(in_array($i_next['i__type'], $this->config->item('n___43039')) || (!strlen($next_i_data['i__text']) && !count($next_i_data['uploaded_media']))){
                        //Focus Discovery only, so must go to next level:
                            foreach($this->Mench_ledger->fetch(array(
                                'linkvoid' => 0, //Not Void
                                'linktype IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //IDEA LINKS
                                'linkleft' => $i_next['ideaid'],
                            ), array(), 0, 0) as $result){
                                continue;
                            }

                    }

                    //Analyze input:
                    $input__text = in_array($i_next['i__type'], $this->config->item('n___43002')) || in_array($i_next['i__type'], $this->config->item('n___43003'));
                    $input__upload = in_array($i_next['i__type'], $this->config->item('n___43004'));
                    $trying_to_skip = (($input__text && !$input__upload && !strlen($next_i_data['i__text'])) || (!$input__text && $input__upload && !count($next_i_data['uploaded_media'])) || ($input__text && $input__upload && !count($next_i_data['uploaded_media']) && !strlen($next_i_data['i__text'])));
                    $i_required = i_required($i_next);

                    if(!($i_required && $trying_to_skip)){
                        //Try to complete:
                        $completion_status = $this->Mench_ledger->mark_complete(i__discovery_link($i_next, $trying_to_skip), $player_e['playerid'], $_POST['target_ideaid'], $i_next, $next_i_data, array(
                            'linknumber' => $next_i_data['i__quantity'],
                        ));
                        if($i_required && !$completion_status['status']){
                            //We had an error with data within target_ideaid:
                            return view__json($completion_status);
                        }
                    }
                }
            }

            //Find Next:
            $i_redirect_url = false;
            foreach($this->Idea_cache->fetch(array(
                'ideaid' => $primary_ideaid,
            )) as $primary_i){
                $i_redirect_url = i_redirect_url($primary_i);
            }
            if(!$i_redirect_url){
                $find_next = $this->Mench_ledger->find_next($player_e['playerid'], $_POST['target_ideahashtag'], $focus_i);
            }

            //All good:
            return view__json(array(
                'status' => 1,
                'message' => 'Saved & Next',
                'next__url' => ( $i_redirect_url ? $i_redirect_url : ( $find_next ? $find_next : 'start' ) ),
            ));

        }

        //All good:
        return view__json(array(
            'status' => 0,
            'message' => 'Invalid Idea',
        ));

    }

    function x_update_instant_select(){

        if(!isset($_POST['focus__id']) || !isset($_POST['o__id']) || !isset($_POST['element_id']) || !isset($_POST['new_playerid']) || !isset($_POST['migrate_s__handle']) || !isset($_POST['linkid'])){
            return view__json(array(
                'status' => 0,
                'message' => 'Missing core data',
            ));
        }

        //Validate migration handles if any:
        $_POST['migrate_s__handle'] = trim($_POST['migrate_s__handle']);
        $first_letter = substr($_POST['migrate_s__handle'], 0, 1);
        if($first_letter=='@' && strlen($_POST['migrate_s__handle'])>1){
            if (!count($this->Source_cache->fetch(array(
                'LOWER(playerhandle)' => strtolower(substr($_POST['migrate_s__handle'], 1)),
            )))) {
                return view__json(array(
                    'status' => 0,
                    'message' => $_POST['migrate_s__handle'].' is an invalid Source Handle. Try again if you want to migrate this source links or leave the field blank.',
                ));
            }
        } elseif($first_letter=='#' && strlen($_POST['migrate_s__handle'])>1){
            if(!count($this->Idea_cache->fetch(array(
                'LOWER(ideahashtag)' => strtolower(substr($_POST['migrate_s__handle'], 1)),
            )))){
                return view__json(array(
                    'status' => 0,
                    'message' => $_POST['migrate_s__handle'].' is an invalid Idea Hashtag. Try again if you want to migrate this idea links or leave the field blank.',
                ));
            }
        } else {
            $_POST['migrate_s__handle'] = '';
        }

        if(is_array($_POST['o__id'])){
            $mass_result = array();
            foreach($_POST['o__id'] as $o__id){
                array_push($mass_result, $this->Mench_ledger->x_update_instant_select($_POST['focus__id'],$o__id,$_POST['element_id'],$_POST['new_playerid'],$_POST['migrate_s__handle'],$_POST['linkid']));
            }
            return view__json($mass_result);
        } else {
            return view__json($this->Mench_ledger->x_update_instant_select($_POST['focus__id'],$_POST['o__id'],$_POST['element_id'],$_POST['new_playerid'],$_POST['migrate_s__handle'],$_POST['linkid']));
        }

    }


    function x_remove(){

        /*
         *
         * When members indicate they want to stop
         * a IDEA this function saves the changes
         * necessary and delete the idea from their
         * discoveries.
         *
         * */

        $player_e = superpower_unlocked(null, 0, $this->player_e);

        if (!$player_e) {
            return view__json(array(
                'status' => 0,
                'message' => view__unauthorized_message(),
            ));
        } elseif (!isset($_POST['linkid']) || intval($_POST['linkid']) < 1) {
            return view__json(array(
                'status' => 0,
                'message' => 'Missing Link ID',
            ));
        }

        //Remove Idea
        $this->Mench_ledger->update($_POST['linkid'], array(), $player_e['playerid']);

        return view__json(array(
            'status' => 1,
        ));
    }

    function x_4341(){

        /*
         * Loads the list of transactions based on the
         * filters passed on.
         *
         * */

        $query_filters = unserialize($_POST['x_filters']);
        $joined_by = unserialize($_POST['x_joined_by']);
        $page_num = ( isset($_POST['page_num']) && intval($_POST['page_num'])>=2 ? intval($_POST['page_num']) : 1 );
        $next_page = ($page_num+1);
        $query_offset = (($page_num-1)*view__memory(6404,11064));
        $player_e = superpower_unlocked(null, 0, $this->player_e);

        $message = '';

        //Fetch transactions and total transaction counts:
        $x = $this->Mench_ledger->fetch($query_filters, $joined_by, view__memory(6404,11064), $query_offset);
        $x_count = $this->Mench_ledger->fetch($query_filters, $joined_by, 0, 0, array(), 'COUNT(linkid) as total_count');
        $total_items_loaded = ($query_offset+count($x));
        $has_more_x = ($x_count[0]['total_count'] > 0 && $total_items_loaded < $x_count[0]['total_count']);


        //Display filter:
        if($total_items_loaded > 0){

            //Subsequent messages:
            $message .= '<div class="main__title x-info grey">'.( $x_count[0]['total_count']>$total_items_loaded ? ( $has_more_x && $query_offset==0  ? 'FIRST ' : ($query_offset+1).' - ' ) . ( $total_items_loaded >= ($query_offset+1) ?  $total_items_loaded . ' OF ' : '' ) : '') . number_format($x_count[0]['total_count'] , 0) .' TRANSACTIONS:</div>';

        }


        if(count($x)>0){

            $message .= '<div class="list-group list-grey">';
            foreach($x as $x) {

                $message .= view__card_x($x);

                if($player_e && strlen($x['linktext'])>0 && strlen($_POST['linktext_find'])>0 && strlen($_POST['linktext_replace'])>0 && substr_count($x['linktext'], $_POST['linktext_find'])>0){

                    $new_content = str_replace($_POST['linktext_find'],trim($_POST['linktext_replace']),$x['linktext']);

                    $this->Mench_ledger->update($x['linkid'], array(
                        'linktext' => $new_content,
                    ), $player_e['playerid']);

                    $message .= '<div class="alert alert-info" role="alert"><i class="far fa-check-circle"></i> Replaced ['.$_POST['linktext_find'].'] with ['.trim($_POST['linktext_replace']).']</div>';

                }

            }
            $message .= '</div>';

            //Do we have more to show?
            if($has_more_x){
                $message .= '<div id="x_page_'.$next_page.'"><a href="javascript:void(0);" style="margin:10px 0 72px 0;" class="btn" onclick="x_4341(x_filters, x_joined_by, '.$next_page.');"><span class="icon-block"><i class="far fa-search-plus"></i></span>Page '.$next_page.'</a></div>';
                $message .= '';
            } else {
                $message .= '<div style="margin:10px 0 72px 0;"><span class="icon-block"><i class="far fa-check-circle"></i></span>All '.$x_count[0]['total_count'].' transactions have been loaded</div>';

            }

        } else {

            //Show no transaction warning:
            $message .= '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>No Transactions found with the selected filters. Modify filters and try again.</div>';

        }


        return view__json(array(
            'status' => 1,
            'message' => $message,
        ));


    }

    function refresh_gameplay(){

        $miscstats = '';

        //See if we have any idea or source targets to limit our stats:
        $has_handle = isset($_POST['playerhandle']) && strlen($_POST['playerhandle']) && $_POST['playerhandle'];
        $has_hashtag = isset($_POST['ideahashtag']) && strlen($_POST['ideahashtag']) && $_POST['ideahashtag'];

        if($has_handle){

            //See stats for this source:
            $es = $this->Source_cache->fetch(array(
                'LOWER(playerhandle)' => strtolower($_POST['playerhandle']),
            ));
            if(!count($es)){
                return view__json(array(
                    'status' => 0,
                    'message' => 'Invalid Handle',
                ));
            }

        } elseif($has_hashtag){

            //See stats for this idea:
            $is = $this->Idea_cache->fetch(array(
                'LOWER(ideahashtag)' => strtolower($_POST['ideahashtag']),
            ));
            if(!count($is)){
                return view__json(array(
                    'status' => 0,
                    'message' => 'Invalid Hashtag',
                ));
            }

            $recursive_down_ids = $this->Idea_cache->recursive_down_ids($is[0], 'ALL');

            //List stats:
            $miscstats .= '<div>Tree Ideas: '.number_format(count($recursive_down_ids['recursive_i_ids']), 0).'</div>';

        }


        //Count transactions:
        $return_array = array();
        foreach($this->config->item('e___33292') as $linktype1 => $m1) { //Gameplay
            $level1_total = 0;
            foreach($this->config->item('e___'.$linktype1) as $linktype2 => $m2) { //Nodes/Links

                $e_pinned = e_pinned($linktype2, true);
                $level2_total = 0;
                if(!is_array($this->config->item('e___'.$e_pinned)) || !count($this->config->item('e___'.$e_pinned)) ){
                    continue;
                }
                foreach($this->config->item('e___'.$e_pinned) as $linktype3 => $m3) { //Source/Idea/Discovery

                    if($linktype2==12273){

                        if($has_handle){

                            $sub_counter = $this->Mench_ledger->fetch(array(
                                'linkvoid' => 0, //Not Void
                                'linktype IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                                'linkup' => $es[0]['playerid'],
                                'i__type' => $linktype3,
                                            ), array('linkright'), 0, 0, array(), 'COUNT(linkid) as totals');

                        } elseif($has_hashtag && count($recursive_down_ids['recursive_i_ids'])){

                            //See stats for this idea:
                            $sub_counter = $this->Idea_cache->fetch(array(
                                'i__type' => $linktype3,
                                                'ideaid IN (' . join(',', $recursive_down_ids['recursive_i_ids']) . ')' => null,
                            ), 0, 0, array(), 'COUNT(ideaid) as totals');

                        } else {

                            $sub_counter = $this->Idea_cache->fetch(array(
                                'i__type' => $linktype3,
                                            ), 0, 0, array(), 'COUNT(ideaid) as totals');

                        }

                    } elseif($linktype2==12274){

                        if($has_handle){

                            $sub_counter = $this->Mench_ledger->fetch(array(
                                'linkvoid' => 0, //Not Void
                                'linktype IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                                'linkup' => $es[0]['playerid'],
                            ), array('linkdown'), 0, 0, array(), 'COUNT(linkid) as totals');

                        } elseif($has_hashtag && count($recursive_down_ids['recursive_i_ids'])){

                            //See stats for this idea:
                            $sub_counter = $this->Mench_ledger->fetch(array(
                                'linkvoid' => 0, //Not Void
                                'linktype IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                                'linkright IN (' . join(',', $recursive_down_ids['recursive_i_ids']) . ')' => null,
                            ), array('linkup'), 0, 0, array(), 'COUNT(linkid) as totals');

                        } else {

                            $sub_counter = $this->Source_cache->fetch(array(), 0, 0, array(), 'COUNT(playerid) as totals');

                        }

                    } else {

                        if($has_handle){

                            $sub_counter = $this->Mench_ledger->fetch(array(
                                'linktype' => $linktype3,
                                '( linkdown = ' . $es[0]['playerid'] . ' OR linkup = ' . $es[0]['playerid'] . ' OR linkplayer = ' . $es[0]['playerid'] . ' )' => null,
                                'linkvoid' => 0, //Not Void
                            ), array(), 0, 0, array(), 'COUNT(linkid) as totals');

                        } elseif($has_hashtag && count($recursive_down_ids['recursive_i_ids'])){

                            $sub_counter = $this->Mench_ledger->fetch(array(
                                'linktype' => $linktype3,
                                '( linkleft IN (' . join(',', $recursive_down_ids['recursive_i_ids']) . ') OR linkright IN (' . join(',', $recursive_down_ids['recursive_i_ids']) . '))' => null,
                                'linkvoid' => 0, //Not Void
                            ), array(), 0, 0, array(), 'COUNT(linkid) as totals');

                        } else {

                            $sub_counter = $this->Mench_ledger->fetch(array(
                                'linktype' => $linktype3,
                                'linkvoid' => 0, //Not Void
                            ), array(), 0, 0, array(), 'COUNT(linkid) as totals');

                        }

                    }

                    $level2_total += $sub_counter[0]['totals'];
                    $return_array[$linktype3] = intval($sub_counter[0]['totals']);

                }

                $level1_total += $level2_total;
                $return_array[$linktype2] = intval($level2_total);

            }

            $return_array[$linktype1] = intval($level1_total);

        }
        return view__json(array(
            'status' => 1,
            'return_array' => $return_array,
            'miscstats' => $miscstats,
        ));
    }

}