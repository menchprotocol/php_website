<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        cookie_check();

    }

    function index(){
        //App directory is a modal, redirect for now:
        return redirect_message('/');
    }

    function home(){
        $this->load(14565);
    }

    function error(){
        $this->load(14563);
    }

    function load($app_e__id){

        $memory_detected = is_array($this->config->item('n___6287')) && count($this->config->item('n___6287'));
        if(!$memory_detected){
            $app_e__id = 4527;
        }

        //Validate App
        if($memory_detected && !in_array($app_e__id, $this->config->item('n___6287'))){
            return redirect_message('/', '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle discover"></i></span>Invalid App ID</div>');
        }

        //Run App
        boost_power();
        $user_e = false;
        $is_u_request = isset($_SERVER['SERVER_NAME']);

        if($memory_detected && $is_u_request){
            //Needs superpowers?
            $user_e = superpower_unlocked();
            $e___6287 = $this->config->item('e___6287'); //MENCH APP
            $superpower_actives = array_intersect($this->config->item('n___10957'), $e___6287[$app_e__id]['m__profile']);
            if($is_u_request && count($superpower_actives) && !superpower_active(end($superpower_actives), true)){
                die(view_unauthorized_message(end($superpower_actives)));
            }
        }

        $x__source = ( $is_u_request ? ( $user_e ? $user_e['e__id'] : 14068 /* GUEST */ ) : 7274 /* CRON JOB */ );


        //Special App Conditions:
        if($app_e__id==14565 && $user_e){
            //Home Page with member, redirect to source:
            return redirect_message('/@'.$user_e['e__id']);
        }


        //Cache App?
        $ui = null;
        $new_cache = false;
        $cache_x__id = 0;
        $cache_x__time = null;
        if($memory_detected && in_array($app_e__id, $this->config->item('n___14599')) && !in_array($app_e__id, $this->config->item('n___12741'))){

            //Fetch Most Recent Cache:
            foreach($this->X_model->fetch(array(
                'x__type' => 14599, //Cache App
                'x__up' => $app_e__id,
                'x__time >' => date("Y-m-d H:i:s", (time() - view_memory(6404,14599))),
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            ), array(), 1, 0, array('x__time' => 'DESC')) as $latest_cache){
                $ui = $latest_cache['x__message'];
                $cache_x__id = $latest_cache['x__id'];
                $cache_x__time = '<div class="btn-tiny grey margin-top-down center css__title">AS OF '.date("F j, Y, g:i a", strtotime($latest_cache['x__time'])).' '.view_memory(6404,11079).'</div>';
            }
            if(!$ui){
                //No recent cache found, create a new one:
                $new_cache = true;
            }

        }



        if(!$ui){

            //Prep view:
            $raw_app = $this->load->view('app/'.$app_e__id, array(
                'app_e__id' => $app_e__id,
                'user_e' => $user_e,
                'is_u_request' => $is_u_request,
                'memory_detected' => $memory_detected,
            ), true);


            $ui .= '<div class="container">';
            if($memory_detected && !in_array($app_e__id, $this->config->item('n___14597'))){
                $e___6287 = $this->config->item('e___6287'); //MENCH APP
                $ui .= '<h1 class="'.extract_icon_color($e___6287[$app_e__id]['m__icon']).'">' . $e___6287[$app_e__id]['m__title'] . '</h1>';
                if(strlen($e___6287[$app_e__id]['m__message']) > 0){
                    $ui .= '<p class="msg">'.$e___6287[$app_e__id]['m__message'].'</p>';
                }
            }
            $ui .= $raw_app;
            $ui .= '</div>';
        }


        if($new_cache){
            $cache_x = $this->X_model->create(array(
                'x__source' => $x__source,
                'x__type' => 14599, //Cache App
                'x__up' => $app_e__id,
                'x__message' => $ui,
            ));
            $cache_x__id = $cache_x['x__id'];
        }



        //Log App Load:
        $log_data = array(
            'x__source' => $x__source,
            'x__type' => 14067, //MENCH APP LOADED
            'x__up' => $app_e__id,
            'x__reference' => $cache_x__id,
        );
        if(count($_GET)){
            $log_data['x__metadata'] = array(
                'GET' => $_GET,
            );
            foreach(array(
                'e__id' => 'x__down',
                'i__id' => 'x__left'
            ) as $get_key => $x_save){
                if(isset($_GET[$get_key]) && intval($_GET[$get_key])){
                    $log_data[$x_save] = intval($_GET[$get_key]);
                }
            }
        }
        $this->X_model->create($log_data);





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
                    'title' => $e___6287[$app_e__id]['m__title'].' | MENCH',
                    'basic_header_footer' => $basic_header,
                ), true);
                echo $ui;
                echo $cache_x__time;
                echo $this->load->view('footer', array(
                    'basic_header_footer' => $basic_header,
                ), true);

            }
        }
    }



    function app_7264(){

        //Authenticate User:
        $user_e = superpower_unlocked(12700);

        if (!$user_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(12700),
            ));
        } elseif (!isset($_POST['i__id']) || intval($_POST['i__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Starting Idea',
            ));
        } elseif (!isset($_POST['depth_levels']) || intval($_POST['depth_levels']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Depth',
            ));
        }

        //Fetch/Validate idea:
        $is = $this->I_model->fetch(array(
            'i__id' => $_POST['i__id'],
            'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
        ));
        if(count($is) != 1){
            return view_json(array(
                'status' => 0,
                'message' => 'Could not find idea #'.$_POST['i__id'],
            ));
        }


        //Load AND/OR Ideas:
        $e___4737 = $this->config->item('e___4737'); // Idea Status


        //Return report:
        return view_json(array(
            'status' => 1,
            'message' => '<h3>'.$e___4737[$is[0]['i__type']]['m__icon'].' '.view_i_title($is[0]).'</h3>'.view_i_scores_answer($_POST['i__id'], $_POST['depth_levels'], $_POST['depth_levels'], $is[0]['i__type']),
        ));

    }



    function app_4341(){

        /*
         * Loads the list of transactions based on the
         * filters passed on.
         *
         * */

        $filters = unserialize($_POST['x_filters']);
        $joined_by = unserialize($_POST['x_joined_by']);
        $page_num = ( isset($_POST['page_num']) && intval($_POST['page_num'])>=2 ? intval($_POST['page_num']) : 1 );
        $next_page = ($page_num+1);
        $query_offset = (($page_num-1)*view_memory(6404,11064));
        $user_e = superpower_unlocked();

        $message = '';

        //Fetch transactions and total transaction counts:
        $x = $this->X_model->fetch($filters, $joined_by, view_memory(6404,11064), $query_offset);
        $x_count = $this->X_model->fetch($filters, $joined_by, 0, 0, array(), 'COUNT(x__id) as total_count');
        $total_items_loaded = ($query_offset+count($x));
        $has_more_x = ($x_count[0]['total_count'] > 0 && $total_items_loaded < $x_count[0]['total_count']);


        //Display filter:
        if($total_items_loaded > 0){

            if($page_num==1){
                $message .= view_mench_coins();
            }

            //Subsequent messages:
            $message .= '<div class="css__title x-info grey">'.( $x_count[0]['total_count']>$total_items_loaded ? ( $has_more_x && $query_offset==0  ? 'FIRST ' : ($query_offset+1).' - ' ) . ( $total_items_loaded >= ($query_offset+1) ?  $total_items_loaded . ' OF ' : '' ) : '') . number_format($x_count[0]['total_count'] , 0) .' TRANSACTIONS</div>';

        }


        if(count($x)>0){

            $message .= '<div class="list-group list-grey">';
            foreach($x as $x) {

                $message .= view_x($x);

                if($user_e && strlen($x['x__message'])>0 && strlen($_POST['x__message_search'])>0 && strlen($_POST['x__message_replace'])>0 && substr_count($x['x__message'], $_POST['x__message_search'])>0){

                    $new_content = str_replace($_POST['x__message_search'],trim($_POST['x__message_replace']),$x['x__message']);

                    $this->X_model->update($x['x__id'], array(
                        'x__message' => $new_content,
                    ), $user_e['e__id'], 12360, update_description($x['x__message'], $new_content));

                    $message .= '<div class="msg alert alert-info" role="alert"><i class="fas fa-check-circle"></i> Replaced ['.$_POST['x__message_search'].'] with ['.trim($_POST['x__message_replace']).']</div>';

                }

            }
            $message .= '</div>';

            //Do we have more to show?
            if($has_more_x){
                $message .= '<div id="x_page_'.$next_page.'"><a href="javascript:void(0);" style="margin:10px 0 72px 0;" class="btn btn-discover" onclick="app_4341(x_filters, x_joined_by, '.$next_page.');"><span class="icon-block"><i class="fas fa-search-plus"></i></span>Page '.$next_page.'</a></div>';
                $message .= '';
            } else {
                $message .= '<div style="margin:10px 0 72px 0;"><span class="icon-block"><i class="far fa-check-circle"></i></span>All '.$x_count[0]['total_count'].' transactions have been loaded</div>';

            }

        } else {

            //Show no transaction warning:
            $message .= '<div class="msg alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>No Transactions found with the selected filters. Modify filters and try again.</div>';

        }


        return view_json(array(
            'status' => 1,
            'message' => $message,
        ));


    }




    function auth0_create($sign_i__id){

        //TODO make this more secure

        //New account to be created:
        $member_result = $this->E_model->add_member(urldecode($_GET['name']), urldecode($_GET['email']));
        if(!$member_result['status']) {
            $this->X_model->create(array(
                'x__type' => 4246, //Platform Bug Reports
                'x__message' => 'auth0_callback() Failed to create new member: '.$member_result['message'],
            ));
            die('Error creating a new account: '.$member_result['message']);
        }

        header('Location: /app/14517' . ($sign_i__id > 0 ? '?i__id='.$sign_i__id :  '' ));

    }

}