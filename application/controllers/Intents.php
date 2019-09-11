<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Intents extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);
    }


    function test($in_id){

        $ins = $this->Intents_model->in_fetch(array(
            'in_id' => $in_id,
        ));

        echo_json($this->Intents_model->in_unlock_paths($ins[0]));

    }

    function fix__completion_marks($in_id){

        die('adjust variables to begin');

        boost_power();

        foreach($this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
            'ln_type_entity_id' => 4228, //Intent Link Regular Step
            'ln_parent_intent_id' => $in_id,
        ), array('in_child'), 0, 0, array('ln_order' => 'ASC')) as $rank => $assessment_in){

            echo '<br /><b>'.($rank+1). ') '. $assessment_in['in_outcome'].'</b><br />';

            //Assessments:
            foreach($this->Links_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
                'ln_type_entity_id' => 4228, //Intent Link Regular Step
                'in_subtype_entity_id IN (' . join(',', $this->config->item('en_ids_6193')) . ')' => null, //OR Intents
                'ln_parent_intent_id' => $assessment_in['in_id'],
            ), array('in_child'), 0, 0, array('ln_order' => 'ASC')) as $rank2 => $assessment2_in){
                echo '&nbsp;&nbsp;&nbsp;&nbsp;'.($rank+1).'.'.($rank2+1). ') '. $assessment2_in['in_outcome'].'<br />';

                //Questions:
                foreach($this->Links_model->ln_fetch(array(
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                    'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
                    'ln_type_entity_id' => 4228, //Intent Link Regular Step
                    'ln_parent_intent_id' => $assessment2_in['in_id'],
                ), array('in_child'), 0, 0, array('ln_order' => 'ASC')) as $rank3 => $assessment3_in){

                    //prep metadata:
                    $ln_metadata = unserialize($assessment3_in['ln_metadata']);

                    if(is_numeric($ln_metadata['tr__assessment_points']) && intval($ln_metadata['tr__assessment_points']) > 0){
                        $new_value = 5;
                    } else {
                        $new_value = -2;
                    }

                    if($new_value != intval($ln_metadata['tr__assessment_points'])){
                        update_metadata('ln', $assessment3_in['ln_id'], array(
                            'tr__assessment_points' => $new_value,
                        ), 1);
                        echo '[UPDATED]';
                    }


                    echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.($rank+1).'.'.($rank2+1). '.'.($rank3+1). ') '. htmlentities($assessment3_in['in_outcome']).' ['.$ln_metadata['tr__assessment_points'].']<br />';
                }

            }
        }
    }

    //Loaded as default function of the default controller:
    function index()
    {

        $session_en = $this->session->userdata('user');

        if ((isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'mench.co')) {

            //Go to mench.com for now:
            return redirect_message('https://mench.com');

        } elseif (filter_array($session_en['en__parents'], 'en_id', 1308)) {

            //Go to the Mench dashboard:
            return redirect_message('/dashboard');

        } else {

            //Go to focus intent
            return redirect_message('/' . $this->config->item('in_focus_id'));

        }
    }


    function in_public_ui($in_id, $referrer_en_id = 0)
    {

        /*
         *
         * Loads public landing page that Users can use
         * to review intents before adding to Action Plan
         *
         * */

        //Fetch user session:
        $session_en = en_auth();

        //This is here to redirect from /start to /10430 so everything works fine...
        if($referrer_en_id==0 && $this->uri->segment(1) != $in_id){
            return redirect_message('/'.$in_id);
        }

        //Fetch data:
        $ins = $this->Intents_model->in_fetch(array(
            'in_id' => $in_id,
        ));

        //Make sure we found it:
        if ( count($ins) < 1) {
            return redirect_message('/' . $this->config->item('in_focus_id'), '<div class="alert alert-danger" role="alert">Intent #' . $in_id . ' not found</div>');
        }

        //Make sure intent is public:
        $public_in = $this->Intents_model->in_is_public($ins[0]);

        //Did we have any issues?
        if(!$public_in['status']){
            //Return error:
            return redirect_message('/' . $this->config->item('in_focus_id'), '<div class="alert alert-danger" role="alert">'.$public_in['message'].'</div>');
        }

        //Fetch/Create landing page view cookie:

        //Log Intent Viewed by User:
        $this->Links_model->ln_create(array(
            'ln_creator_entity_id' => ( isset($session_en['en_id']) ? intval($session_en['en_id']) : 0 ), //if user was available, they are logged as parent entity
            'ln_type_entity_id' => 7610, //Intent Viewed by User
            'ln_parent_intent_id' => $in_id,
            'ln_order' => fetch_cookie_order('7610_'.$in_id),
        ));

        //Load home page:
        //Mench header:
        $this->load->view('view_user_app/user_app_header', array(
            'in' => $ins[0],
            'session_en' => $session_en,
            'title' => echo_in_outcome($ins[0]['in_outcome'], true),
        ));

        //Load specific view based on intent scope:
        $this->load->view(( in_array($ins[0]['in_scope_entity_id'], $this->config->item('en_ids_7582')) /* Intent Scopes Get Started */ ? 'view_user_app/in_starting_point' : 'view_user_app/in_passing_point'  ), array(
            'in' => $ins[0],
            'referrer_en_id' => $referrer_en_id,
            'session_en' => $session_en,
            'autoexpand' => (isset($_GET['autoexpand']) && intval($_GET['autoexpand'])),
        ));

        $this->load->view('view_user_app/user_app_footer');

    }



    function in_report_conditional_steps(){

        //Authenticate Miner:
        $session_en = en_auth(array(1308));

        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh the Page to Continue',
            ));
        } elseif (!isset($_POST['starting_in']) || intval($_POST['starting_in']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Starting Intent',
            ));
        } elseif (!isset($_POST['depth_levels']) || intval($_POST['depth_levels']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Depth',
            ));
        }

        //Fetch/Validate intent:
        $ins = $this->Intents_model->in_fetch(array(
            'in_id' => $_POST['starting_in'],
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
        ));
        if(count($ins) != 1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Could not find intent #'.$_POST['starting_in'],
            ));
        }


        //Load AND/OR Intents:
        $en_all_7585 = $this->config->item('en_all_7585'); // Intent Types
        $en_all_4737 = $this->config->item('en_all_4737'); // Intent Statuses


        //Return report:
        return echo_json(array(
            'status' => 1,
            'message' => '<h3>'.$en_all_7585[$ins[0]['in_subtype_entity_id']]['m_icon'].' '.$en_all_4737[$ins[0]['in_status_entity_id']]['m_icon'].' '.echo_in_outcome($ins[0]['in_outcome'], false, true).'</h3>'.echo_in_answer_scores($_POST['starting_in'], $_POST['depth_levels'], $_POST['depth_levels'], $ins[0]['in_subtype_entity_id']),
        ));

    }


    function in_miner_ui($in_id = 0)
    {

        /*
         *
         * Main intent view that Miners use to manage the
         * intent networks.
         *
         * */

        //Authenticate Miner:
        $session_en = en_auth(array(1308,7512), true);

        if($in_id == 0){
            return redirect_message('/intents/' . $this->session->userdata('user_default_intent'));
        }

        //Fetch intent with 2 levels of children:
        $ins = $this->Intents_model->in_fetch(array(
            'in_id' => $in_id,
        ), array('in__parents','in__grandchildren'));
        //Make sure we found it:
        if ( count($ins) < 1) {
            return redirect_message('/intents/' . $this->session->userdata('user_default_intent'), '<div class="alert alert-danger" role="alert">Intent #' . $in_id . ' not found</div>');
        }

        //Update session count and log link:
        $new_order = ( $this->session->userdata('user_session_count') + 1 );
        $this->session->set_userdata('user_session_count', $new_order);
        $this->Links_model->ln_create(array(
            'ln_creator_entity_id' => $session_en['en_id'],
            'ln_type_entity_id' => 4993, //Miner Opened Intent
            'ln_child_intent_id' => $in_id,
            'ln_order' => $new_order,
        ));

        //Load views:
        $this->load->view('view_miner_app/miner_app_header', array(
            'title' => $ins[0]['in_outcome'].' | Intents'
        ));
        $this->load->view('view_miner_app/in_miner_ui', array(
            'in' => $ins[0] ,
        ));
        $this->load->view('view_miner_app/miner_app_footer');

    }




    function in_link_or_create()
    {

        /*
         *
         * Either creates an intent link between in_linked_id & in_link_child_id
         * OR will create a new intent with outcome in_outcome and then link it
         * to in_linked_id (In this case in_link_child_id=0)
         *
         * */

        //Authenticate Miner:
        $session_en = en_auth(array(1308,7512));
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh the Page to Continue',
            ));
        } elseif (!isset($_POST['in_linked_id']) || intval($_POST['in_linked_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Parent Intent ID',
            ));
        } elseif (!isset($_POST['is_parent']) || !in_array(intval($_POST['is_parent']), array(0,1))) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Is Parent setting',
            ));
        } elseif (!isset($_POST['next_level']) || !in_array(intval($_POST['next_level']), array(2,3))) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent Level',
            ));
        } elseif (!isset($_POST['in_outcome']) || !isset($_POST['in_link_child_id']) || ( strlen($_POST['in_outcome']) < 1 && intval($_POST['in_link_child_id']) < 1)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing either Intent Outcome OR Child Intent ID',
            ));
        } elseif (strlen($_POST['in_outcome']) > $this->config->item('in_outcome_max')) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Intent outcome cannot be longer than '.$this->config->item('in_outcome_max').' characters',
            ));
        } elseif(!$_POST['is_parent'] && $_POST['in_link_child_id']==$this->config->item('in_mission_id')){
            return echo_json(array(
                'status' => 0,
                'message' => 'Our mission cannot be added as a child intent, as its the top intention',
            ));
        } elseif($_POST['in_link_child_id'] >= 2147483647){
            return echo_json(array(
                'status' => 0,
                'message' => 'Value must be less than 2147483647',
            ));
        }


        $new_intent_type = 6677; //Intent Read-Only
        $linked_ins = array();

        if($_POST['in_link_child_id'] > 0){

            //Fetch link intent to determine intent type:
            $linked_ins = $this->Intents_model->in_fetch(array(
                'in_id' => intval($_POST['in_link_child_id']),
                'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
            ));

            if(count($linked_ins)==0){
                //validate linked intent:
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Intent #'.$_POST['in_link_child_id'].' is not active',
                ));
            }

            if(!intval($_POST['is_parent']) && in_array($linked_ins[0]['in_subtype_entity_id'], $this->config->item('en_ids_7712'))){
                $new_intent_type = 6914; //Require All
            }
        }

        //All seems good, go ahead and try creating the intent:
        return echo_json($this->Intents_model->in_link_or_create($_POST['in_linked_id'], intval($_POST['is_parent']), trim($_POST['in_outcome']), $session_en['en_id'], 6183 /* Intent New */, $new_intent_type, $_POST['in_link_child_id'], $_POST['next_level']));

    }




    function in_sitemap(){
        $session_en = en_auth();
        $this->load->view('view_user_app/user_app_header', array(
            'session_en' => $session_en,
            'title' => 'Published Intents',
        ));
        $this->load->view('view_user_app/in_sitemap', array(
            'session_en' => $session_en,
        ));
        $this->load->view('view_user_app/user_app_footer');
    }



    function in_migrate()
    {

        //Authenticate Miner:
        $session_en = en_auth(array(1308,7512));
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Sign In again to Continue.',
            ));
        } elseif (!isset($_POST['ln_id']) || intval($_POST['ln_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid ln_id',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid in_id',
            ));
        } elseif (!isset($_POST['from_in_id']) || intval($_POST['from_in_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing from_in_id',
            ));
        } elseif (!isset($_POST['to_in_id']) || intval($_POST['to_in_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing to_in_id',
            ));
        }


        //Fetch all three intents to ensure they are all valid and use them for link logging:
        $this_in = $this->Intents_model->in_fetch(array(
            'in_id' => intval($_POST['in_id']),
        ));
        $from_in = $this->Intents_model->in_fetch(array(
            'in_id' => intval($_POST['from_in_id']),
        ));
        $to_in = $this->Intents_model->in_fetch(array(
            'in_id' => intval($_POST['to_in_id']),
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
        ));

        if (!isset($this_in[0]) || !isset($from_in[0]) || !isset($to_in[0])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid intent IDs',
            ));
        }

        //Make the move:
        $this->Links_model->ln_update(intval($_POST['ln_id']), array(
            'ln_parent_intent_id' => $to_in[0]['in_id'],
        ), $session_en['en_id']);

        //Return success
        echo_json(array(
            'status' => 1,
        ));
    }

    function in_action_plan_users(){

        //Authenticate User:
        $session_en = en_auth();

        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Session Expired',
            ));
        } elseif (!isset($_POST['in_filters'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing filter settings',
            ));
        } elseif (!isset($_POST['in_focus_id']) || intval($_POST['in_focus_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Focus Intent ID',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));
        }

        //Validate intent:
        $ins = $this->Intents_model->in_fetch(array(
            'in_id' => intval($_POST['in_id']),
        ));
        if(count($ins) < 1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Intent not found',
            ));
        }


        //Fetch Action Plan users:
        $actionplan_users = $this->Links_model->ln_fetch(array(
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //Action Plan Steps Progressed
            'ln_parent_intent_id' => $ins[0]['in_id'],
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Entity Statuses Public
        ), array('ln_creator'), 500);
        if(count($actionplan_users) < 1){
            return echo_json(array(
                'status' => 0,
                'message' => '<i class="fas fa-exclamation-triangle"></i> Nobody has completed this intention yet',
            ));
        }


        //Get filters from variables:
        $in_filters = $_POST['in_filters'];
        $filter_applied = ( isset($in_filters['get_filter_query']) && count($in_filters['get_filter_query']) > 0 );

        //Go through match list:
        $filters_list_counter = 0;
        $regular_list_counter = 0;
        $filters_list_ui = '';
        $regular_list_ui = '';

        foreach($actionplan_users as $apu){

            //Count user Action Plan Progression Completed:
            $count_progression = $this->Links_model->ln_fetch(array(
                'ln_creator_entity_id' => $apu['en_id'],
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //User Steps Progress
            ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');

            if($filter_applied){

                //Search "ZEEBRA" to find dependant code

                //See if this user matches the applied filters:
                if($in_filters['get_filter_user'] > 0 && $in_filters['get_filter_user'] != $apu['ln_creator_entity_id']){
                    $is_a_match = false;
                } elseif($in_filters['get_filter_start'] > 0 && strtotime($apu['ln_timestamp']) < $in_filters['get_filter_start']){
                    $is_a_match = false;
                } elseif($in_filters['get_filter_end'] > 0 && strtotime($apu['ln_timestamp']) > $in_filters['get_filter_end']){
                    $is_a_match = false;
                } else {
                    $is_a_match = true; //It passed all filter requirements!
                }
            }


            if($filter_applied && $is_a_match){
                $filters_list_counter++;
                $current_count = $filters_list_counter;
            } else {
                $regular_list_counter++;
                $current_count = $regular_list_counter;
            }


            //Create the UI for this user:
            $item_ui = '<tr>';
            $item_ui .= '<td valign="top">'.$current_count.'</td>';
            $item_ui .= '<td style="text-align:left;">';
            $item_ui .= '<span class="icon-block en-icon">'.echo_en_icon($apu).'</span> '.$apu['en_name'];
            $item_ui .= ( strlen($apu['ln_content']) > 0 ? '<div class="user-comment">'.$this->Communication_model->dispatch_message($apu['ln_content']).'</div>' : '' );
            $item_ui .= '</td>';

            $item_ui .= '<td style="text-align:left;"><a href="/links?ln_id='.$apu['ln_id'].'" target="_blank" class="' . advance_mode() . '">'.echo_en_cache('en_all_6255' /* User Steps Progress */, $apu['ln_type_entity_id']).'</a></td>';
            $item_ui .= '<td style="text-align:left;">'.echo_number($count_progression[0]['totals']).'</td>';
            $item_ui .= '<td style="text-align:left;">'.echo_time_difference(strtotime($apu['ln_timestamp'])).'</td>';
            $item_ui .= '<td style="text-align:left;">';

                $item_ui .= '<a href="/intents/'.$_POST['in_focus_id'].'?filter_user='.urlencode('@'.$apu['en_id'].' '.$apu['en_name']).'#actionplanusers-'.$_POST['in_id'].'" data-toggle="tooltip" data-placement="top" title="Filter by this user"><i class="far fa-filter"></i></a>';
                $item_ui .= '&nbsp;<a href="/entities/'.$apu['en_id'].'" data-toggle="tooltip" data-placement="top" title="User Entity" class="' . advance_mode() . '"><i class="fas fa-at"></i></a>';

                $item_ui .= '&nbsp;<a href="/links?ln_creator_entity_id='.$apu['en_id'].'" data-toggle="tooltip" data-placement="top" title="Full History" class="' . advance_mode() . '"><i class="fas fa-link"></i></a>';

            $item_ui .= '</td>';
            $item_ui .= '</tr>';

            //Decide which list it should go to:
            if($filter_applied && $is_a_match){
                $filters_list_ui .= $item_ui;
            } else {
                $regular_list_ui .= $item_ui;
            }
        }



        //Filtered list if any:
        $ui = '<table class="table table-condensed table-striped">';


        if($filter_applied){

            $ui .= '<tr style="font-weight: bold;">';
            $ui .= '<td style="text-align:left; padding-left:3px;" colspan="2">Matching User'.echo__s($filters_list_counter).' ['.$filters_list_counter.']</td>';
            $ui .= '<td style="text-align:left;">&nbsp;</td>';
            $ui .= '<td style="text-align:left;"><i class="fas fa-walking" data-toggle="tooltip" data-placement="top" title="User Steps Progressed"></i></td>';
            $ui .= '<td style="text-align:left;"><i class="far fa-clock" data-toggle="tooltip" data-placement="top" title="Completion time"></i></td>';
            $ui .= '<td style="text-align:left;">&nbsp;</td>';
            $ui .= '</tr>';

            $ui .= $filters_list_ui;

            //Add two space blocks:
            $ui .= '<tr><td colspan="6">&nbsp;</td></tr>';
            $ui .= '<tr><td colspan="6">&nbsp;</td></tr>';

        }

        //Regular list:
        if($regular_list_ui){
            $ui .= '<tr style="font-weight: bold;">';
            $ui .= '<td style="text-align:left; padding-left:3px;" colspan="2">' . ( $filter_applied ? 'Other ' : 'Completed ' ) . 'User' . echo__s($regular_list_counter).' ['.$regular_list_counter.']</td>';
            $ui .= '<td style="text-align:left;">&nbsp;</td>';
            $ui .= '<td style="text-align:left;"><i class="fas fa-walking" data-toggle="tooltip" data-placement="top" title="User Steps Progressed"></i></td>';
            $ui .= '<td style="text-align:left;"><i class="far fa-clock" data-toggle="tooltip" data-placement="top" title="Completion time"></i></td>';
            $ui .= '<td style="text-align:left;">&nbsp;</td>';
            $ui .= '</tr>';
            $ui .= $regular_list_ui;
            $ui .= '</table>';
        }


        echo_json(array(
            'status' => 1,
            'message' => $ui,
        ));
    }


    function in_unlink_only(){

        //Authenticate Miner:
        $session_en = en_auth(array(1308,7512));

        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Session Expired',
            ));
        } elseif (!isset($_POST['ln_id']) || intval($_POST['ln_id'])<1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing ln_id',
            ));
        } elseif(!count($this->Links_model->ln_fetch(array(
            'ln_id' => intval($_POST['ln_id']),
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        )))){
            return echo_json(array(
                'status' => 0,
                'message' => 'Link not found (already removed?)',
            ));
        }

        //Unlink:
        $this->Links_model->ln_update($_POST['ln_id'], array(
            'ln_status_entity_id' => 6173, //Link Unlinked
        ), $session_en['en_id']);

        //Return success:
        return echo_json(array(
            'status' => 1,
        ));

    }


    function in_modify_save()
    {

        //Authenticate Miner:
        $session_en = en_auth(array(1308,7512));
        $ln_id = intval($_POST['ln_id']);

        //Validate intent:
        $ins = $this->Intents_model->in_fetch(array(
            'in_id' => intval($_POST['in_id']),
        ), array('in__parents'));

        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Session Expired',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid in_id',
            ));
        } elseif (intval($_POST['level'])==1 && intval($_POST['ln_id'])>0) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Level 1 intent should not have a link',
            ));
        } elseif (!isset($_POST['tr__conditional_score_min']) || !isset($_POST['tr__conditional_score_max'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing tr__conditional_score_min or tr__conditional_score_max',
            ));
        } elseif (!isset($_POST['tr__assessment_points'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing tr__assessment_points',
            ));
        } elseif (!isset($_POST['in_subtype_entity_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid in_subtype_entity_id',
            ));
        } elseif (!isset($_POST['level']) || intval($_POST['level']) < 1 || intval($_POST['level']) > 3) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid level',
            ));
        } elseif (!isset($_POST['in_outcome'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing in_outcome',
            ));
        } elseif (!isset($_POST['in_completion_seconds']) || intval($_POST['in_completion_seconds']) < 0) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing in_completion_seconds',
            ));
        } elseif (intval($_POST['in_completion_seconds']) > $this->config->item('in_max_seconds')) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Maximum estimated time is ' . round(($this->config->item('in_max_seconds') / 3600), 2) . ' hours for each intent. If larger, break the intent down into smaller intents.',
            ));
        } elseif (!isset($_POST['apply_recursively'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing apply_recursively',
            ));
        } elseif (!isset($_POST['in_status_entity_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing in_status_entity_id',
            ));
        } elseif (!isset($_POST['in_scope_entity_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing in_scope_entity_id',
            ));
        } elseif (count($ins) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Intent Not Found',
            ));
        } elseif($ln_id > 0 && intval($_POST['ln_type_entity_id']) == 4229){
            //Conditional Step Links, we require range values:
            if(strlen($_POST['tr__conditional_score_min']) < 1 || !is_numeric($_POST['tr__conditional_score_min'])){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Missing MIN range, enter 0 or greater',
                ));
            } elseif(strlen($_POST['tr__conditional_score_max']) < 1 || !is_numeric($_POST['tr__conditional_score_max'])){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Missing MAX range, enter 0 or greater',
                ));
            } elseif(doubleval($_POST['tr__conditional_score_min']) > doubleval($_POST['tr__conditional_score_max'])){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'MIN range cannot be larger than MAX',
                ));
            }
        } elseif (!in_array($_POST['in_subtype_entity_id'], $this->config->item('en_ids_7585'))) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid in_subtype_entity_id',
            ));
        } elseif (!in_array($_POST['in_status_entity_id'], $this->config->item('en_ids_4737'))) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid in_status_entity_id',
            ));
        } elseif (!in_array($_POST['in_scope_entity_id'], $this->config->item('en_ids_7596'))) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid in_scope_entity_id',
            ));
        } elseif (!in_array($_POST['in_scope_entity_id'], $this->config->item('en_ids_7596'))) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid in_scope_entity_id',
            ));
        }


        //Validate Intent Outcome:
        $in_outcome_validation = $this->Intents_model->in_validate_outcome($_POST['in_outcome'], $_POST['in_scope_entity_id']);
        if(!$in_outcome_validation['status']){
            //We had an error, return it:
            return echo_json($in_outcome_validation);
        }

        //Transform intent type into standard DB field:
        $in_current = $ins[0];

        //So we consistently have all variables in POST:
        $_POST['in_verb_entity_id'] = $in_outcome_validation['detected_in_verb_entity_id'];
        $_POST['in_outcome'] = $in_outcome_validation['in_cleaned_outcome'];


        //Prep new variables:
        $in_update = array(
            'in_subtype_entity_id' => $_POST['in_subtype_entity_id'],
            'in_status_entity_id' => $_POST['in_status_entity_id'],
            'in_scope_entity_id' => $_POST['in_scope_entity_id'],
            'in_completion_seconds' => intval($_POST['in_completion_seconds']),
            'in_outcome' => $_POST['in_outcome'],
            'in_verb_entity_id' => $_POST['in_verb_entity_id'],
        );


        //Prep current intent metadata:
        $in_metadata = unserialize($in_current['in_metadata']);

        //Determines if Intent has been removed OR unlinked:
        $remove_from_ui = 0; //Assume not
        $remove_redirect_url = null;

        //Did anything change?
        $recursive_update_count = 0;

        //Check to see which variables actually changed:
        foreach ($in_update as $key => $value) {

            //Did this value change?
            if ($value == $in_current[$key]) {

                //No it did not! Remove it!
                unset($in_update[$key]);

            } else {

                if ($key == 'in_subtype_entity_id') {

                    //If it was locked and not being changed to a non-locked type, make sure no Lock Link Parents exist:
                    if(!in_array($value, $this->config->item('en_ids_7309') /* Action Plan Step Locked */)){

                        //Previously locked but now being changed to a non-locked intent type, let's see if none of the the parent links are link lock:
                        if(count($this->Links_model->ln_fetch(array(
                                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                                'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
                                'ln_type_entity_id' => 4229, //Intent Link Locked Step
                                'ln_child_intent_id' => $_POST['in_id'],
                            ), array('in_parent'))) > 0){
                            return echo_json(array(
                                'status' => 0,
                                'message' => 'Cannot change to non-locked intent type because there are parent intent locked steps that require this intent to be locked',
                            ));
                        }

                    }

                } elseif ($key == 'in_status_entity_id') {

                    $links_removed = 0;

                    //Is this a recursive removal?
                    if(intval($_POST['apply_recursively'])) {
                        //Fetch all children before removal:
                        $all_child_ids = $this->Intents_model->in_recursive_child_ids($_POST['in_id']);
                        if (count($all_child_ids) < 1) {
                            //Inform them that no children exist here:
                            return echo_json(array(
                                'status' => 0,
                                'message' => 'Cannot apply recursively as this intent has no children. Uncheck recursive box to continue.',
                            ));
                        }
                    }


                    //Has intent been removed?
                    if(!in_array($value, $this->config->item('en_ids_7356') /* Intent Statuses Active */)){

                        //Intent has been removed:
                        $remove_from_ui = 1;

                        //Did we remove the main intent?
                        if($_POST['level']==1){
                            //Yes, redirect to a parent intent if we have any:
                            if(count($in_current['in__parents']) > 0){
                                $remove_redirect_url = '/intents/' . $in_current['in__parents'][0]['in_id'];
                            } else {
                                //No parents, redirect to default intent:
                                $remove_redirect_url = '/intents/' . $this->session->userdata('user_default_intent');
                            }
                        }

                        //Unlink intent links:
                        $links_removed += $this->Intents_model->in_unlink($_POST['in_id'] , $session_en['en_id']);

                        //Will be removed soon:
                        $recursive_update_count++;

                        //Treat as if no link (Since it was removed):
                        $ln_id = 0;

                    }



                    if(intval($_POST['apply_recursively'])){

                        //Now see which children match the current status:
                        $matching_child_ids = array();
                        foreach($this->Intents_model->in_fetch(array(
                            'in_id IN (' . join(',', $all_child_ids) . ')' => null, //All child intents
                            'in_status_entity_id' => $in_current['in_status_entity_id'],
                        )) as $recursive_in){

                            //Do we also need to unlink?
                            if(!in_array($value, $this->config->item('en_ids_7356') /* Intent Statuses Active */)){
                                $links_removed += $this->Intents_model->in_unlink($recursive_in['in_id'] , $session_en['en_id']);
                            }

                            //We're updating the status:
                            $recursive_update_count += $this->Intents_model->in_update($recursive_in['in_id'], array(
                                'in_status_entity_id' => $value,
                            ), true, $session_en['en_id']);

                            //Add to matchind children array:
                            array_push($matching_child_ids, intval($recursive_in['in_id']));

                        }

                        //Success message:
                        $update_message = 'Successfully updated '.$recursive_update_count.' '.echo_clean_db_name($key).' from ['.$in_current['in_status_entity_id'].'] to ['.$value.']'.( $links_removed>0 ? ' and removed ['.$links_removed.'] intent links' : '' );

                        //Log recursive update:
                        $this->Links_model->ln_create(array(
                            'ln_creator_entity_id' => $session_en['en_id'],
                            'ln_type_entity_id' => 6226, //Intents Recursively Updated
                            'ln_parent_intent_id' => $_POST['in_id'],
                            'ln_content' => $update_message,
                            'ln_metadata' => array(
                                'in_field' => $key,
                                'match_value' => $in_current['in_status_entity_id'],
                                'replace_value' => $value,
                                'matching_children' => $matching_child_ids,
                                'all_children' => $all_child_ids,
                            ),
                        ));

                        //Set message in session to inform miner:
                        $this->session->set_flashdata('flash_message', '<div class="alert alert-success" role="alert">'.$update_message.'</div>');

                    }
                }

                //This field has been updated, update one field at a time:
                $this->Intents_model->in_update($_POST['in_id'], array(
                    $key => $_POST[$key],
                ), true, $session_en['en_id'], ( $key=='in_outcome' ? 10644 /* Intent Outcome Iterated */ : 4264 /* Intent Updated */ ));

            }
        }




        //Assume link is not updated:
        $link_was_updated = false;


        //Does this request has an intent link?
        if($ln_id > 0){

            //Validate Link and inputs:
            $lns = $this->Links_model->ln_fetch(array(
                'ln_id' => $ln_id,
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            ), array(( $_POST['is_parent'] ? 'in_child' : 'in_parent')));
            if(count($lns) < 1){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid link ID',
                ));
            }

            //Prep link Metadata to see if the Conditional Step Links score variables have changed:
            $ln_update = array(
                'ln_type_entity_id'     => intval($_POST['ln_type_entity_id']),
                'ln_status_entity_id'   => intval($_POST['ln_status_entity_id']),
            );





            //Prep variables:
            $ln_metadata = ( strlen($lns[0]['ln_metadata']) > 0 ? unserialize($lns[0]['ln_metadata']) : array() );

            //Check to see if anything changed in the link?
            $link_meta_updated = ( (($ln_update['ln_type_entity_id'] == 4228 && (
                        !isset($ln_metadata['tr__assessment_points']) ||
                        !(intval($ln_metadata['tr__assessment_points'])==intval($_POST['tr__assessment_points']))
                    ))) || (($ln_update['ln_type_entity_id'] == 4229 && (
                        !isset($ln_metadata['tr__conditional_score_min']) ||
                        !isset($ln_metadata['tr__conditional_score_max']) ||
                        !(doubleval($ln_metadata['tr__conditional_score_max'])==doubleval($_POST['tr__conditional_score_max'])) ||
                        !(doubleval($ln_metadata['tr__conditional_score_min'])==doubleval($_POST['tr__conditional_score_min']))
                    ))));



            foreach ($ln_update as $key => $value) {

                //Did this value change?
                if ($value == $lns[0][$key]) {

                    //No it did not! Remove it!
                    unset($ln_update[$key]);

                } else {

                    if($key=='ln_status_entity_id'){

                        if($value == 6173 /* Link Removed */){
                            $remove_from_ui = 1;
                        }

                    } elseif($key=='ln_type_entity_id'){

                        if($value == 4229 /* Intent Link Locked Step */){

                            //Intent Link Locked Step requires the child intent to be locked:

                            //Fetch child intent (we might not have it):
                            $child_ins = $this->Intents_model->in_fetch(array(
                                'in_id' => $lns[0]['ln_child_intent_id'],
                            ));

                            //Ensure child is locked:
                            if(!in_array($child_ins[0]['in_subtype_entity_id'], $this->config->item('en_ids_7309') /* Action Plan Step Locked */)){
                                return echo_json(array(
                                    'status' => 0,
                                    'message' => 'Locked Step requires a locked child intent (AND Lock or Or Lock)',
                                ));
                            }
                        }
                    }
                }
            }


            //Was anything updated?
            if(count($ln_update) > 0 || $link_meta_updated){
                $link_was_updated = true;
            }



            //Did anything change?
            if( $link_was_updated ){

                if($link_meta_updated && (!isset($ln_update['ln_status_entity_id']) || in_array($ln_update['ln_status_entity_id'], $this->config->item('en_ids_7360') /* Link Statuses Active */))){
                    $ln_update['ln_metadata'] = array_merge( $ln_metadata, array(
                        'tr__conditional_score_min' => doubleval($_POST['tr__conditional_score_min']),
                        'tr__conditional_score_max' => doubleval($_POST['tr__conditional_score_max']),
                        'tr__assessment_points' => intval($_POST['tr__assessment_points']),
                    ));
                }

                //Also update the timestamp & new miner:
                $ln_update['ln_timestamp'] = date("Y-m-d H:i:s");
                $ln_update['ln_creator_entity_id'] = $session_en['en_id'];

                //Update links:
                $this->Links_model->ln_update($ln_id, $ln_update, $session_en['en_id']);
            }

        }


        //Let's see how many intents, if any, have unlocked completions:
        //See if we should check for unlocking this intent:
        //Keep track of stats for reporting:
        $ins_unlocked_completions_count = 0;
        $steps_unlocked_completions_count = 0;

        //Should we check for new unlocks?
        if(!in_is_unlockable($in_current) /* Old Settings */ && in_is_unlockable($_POST) /* New Settings */){

            //First see if this locked intent is completed for any users:
            $step_completed_users = array();

        }


        $return_data = array(
            'status' => 1,
            'message' => '<i class="fas fa-check"></i> Saved',
            'remove_from_ui' => $remove_from_ui,
            'formatted_in_outcome' => ( isset($in_update['in_outcome']) ? echo_in_outcome($in_update['in_outcome'], false, true) : null ),
            'remove_redirect_url' => $remove_redirect_url,
            'recursive_update_count' => $recursive_update_count,
            'in__metadata_max_steps' => -( isset($in_metadata['in__metadata_max_steps']) ? $in_metadata['in__metadata_max_steps'] : 0 ),
            //Passon unlock data, if any:
            'ins_unlocked_completions_count' => $ins_unlocked_completions_count,
            'steps_unlocked_completions_count' => $steps_unlocked_completions_count,
        );


        //Did we have an intent link update? If so, update the last updated UI:
        if($link_was_updated){

            //Fetch last intent Link:
            $lns = $this->Links_model->ln_fetch(array(
                'ln_id' => $ln_id,
            ), array('ln_creator'));

        }

        //Show success:
        return echo_json($return_data);

    }

    function in_review_metadata($in_id){
        //Fetch Intent:
        $ins = $this->Intents_model->in_fetch(array(
            'in_id' => $in_id,
        ));
        if(count($ins) > 0){
            echo_json(unserialize($ins[0]['in_metadata']));
        } else {
            echo 'Intent #'.$in_id.' not found!';
        }
    }

    function in_sort_save()
    {

        //Authenticate Miner:
        $session_en = en_auth(array(1308,7512));
        if (!$session_en) {
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Sign In again to Continue.',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid in_id',
            ));
        } elseif (!isset($_POST['new_ln_orders']) || !is_array($_POST['new_ln_orders']) || count($_POST['new_ln_orders']) < 1) {
            echo_json(array(
                'status' => 0,
                'message' => 'Nothing passed for sorting',
            ));
        } else {

            //Validate Parent intent:
            $parent_ins = $this->Intents_model->in_fetch(array(
                'in_id' => intval($_POST['in_id']),
            ));
            if (count($parent_ins) < 1) {
                echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid in_id',
                ));
            } else {

                //Fetch for the record:
                $children_before = $this->Links_model->ln_fetch(array(
                    'ln_parent_intent_id' => intval($_POST['in_id']),
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));

                //Update them all:
                foreach ($_POST['new_ln_orders'] as $rank => $ln_id) {
                    $this->Links_model->ln_update(intval($ln_id), array(
                        'ln_order' => intval($rank),
                    ), $session_en['en_id']);
                }

                //Fetch again for the record:
                $children_after = $this->Links_model->ln_fetch(array(
                    'ln_parent_intent_id' => intval($_POST['in_id']),
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));

                //Display message:
                echo_json(array(
                    'status' => 1,
                    'message' => '<i class="fas fa-check"></i> Sorted',
                ));
            }
        }
    }


    function in_messages_iframe($in_id)
    {

        //Authenticate as a Miner:
        $session_en = en_auth(array(1308,7512));
        if (!$session_en) {
            //Display error:
            die('<span style="color:#FF0000;">Error: Invalid Session. Sign In again to continue.</span>');
        } elseif (intval($in_id) < 1) {
            die('<span style="color:#FF0000;">Error: Invalid Intent id.</span>');
        }

        //Don't show the heading here as we're loading inside an iframe:
        $_GET['skip_header'] = 1;

        //Load view:
        $this->load->view('view_miner_app/miner_app_header', array(
            'title' => 'Intent #' . $in_id . ' Messages',
        ));
        $this->load->view('view_miner_app/in_messages_frame', array(
            'in_id' => $in_id,
        ));
        $this->load->view('view_miner_app/miner_app_footer');

    }


    function in_new_message_from_text()
    {

        //Authenticate Miner:
        $session_en = en_auth(array(1308,7512));

        if (!$session_en) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Session Expired. Sign In and Try again.',
            ));

        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));

        } elseif (!isset($_POST['focus_ln_type_entity_id']) || intval($_POST['focus_ln_type_entity_id']) < 1) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Message Type',
            ));

        }


        //Fetch/Validate the intent:
        $ins = $this->Intents_model->in_fetch(array(
            'in_id' => intval($_POST['in_id']),
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
        ));
        if(count($ins)<1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent',
            ));
        }

        //Make sure message is all good:
        $msg_validation = $this->Communication_model->dispatch_validate_message($_POST['ln_content'], $session_en, false, array(), $_POST['focus_ln_type_entity_id'], $_POST['in_id']);

        if (!$msg_validation['status']) {
            //There was some sort of an error:
            return echo_json($msg_validation);
        }

        //Create Message:
        $ln = $this->Links_model->ln_create(array(
            'ln_status_entity_id' => 6176, //Link Published
            'ln_creator_entity_id' => $session_en['en_id'],
            'ln_order' => 1 + $this->Links_model->ln_max_order(array(
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                    'ln_type_entity_id' => intval($_POST['focus_ln_type_entity_id']),
                    'ln_child_intent_id' => intval($_POST['in_id']),
                )),
            //Referencing attributes:
            'ln_type_entity_id' => intval($_POST['focus_ln_type_entity_id']),
            'ln_parent_entity_id' => $msg_validation['ln_parent_entity_id'],
            'ln_parent_intent_id' => $msg_validation['ln_parent_intent_id'],
            'ln_child_intent_id' => intval($_POST['in_id']),
            'ln_content' => $msg_validation['input_message'],
        ), true);

        //Print the challenge:
        return echo_json(array(
            'status' => 1,
            'message' => echo_in_message_manage(array_merge($ln, array(
                'ln_child_entity_id' => $session_en['en_id'],
            ))),
        ));
    }


    function in_new_message_from_attachment()
    {

        //Authenticate Miner:
        $session_en = en_auth(array(1308,7512));
        if (!$session_en) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh to Continue',
            ));

        } elseif (!isset($_POST['in_id']) || !isset($_POST['focus_ln_type_entity_id'])) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Missing intent data.',
            ));

        } elseif (!isset($_POST['upload_type']) || !in_array($_POST['upload_type'], array('file', 'drop'))) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Unknown upload type.',
            ));

        } elseif (!isset($_FILES[$_POST['upload_type']]['tmp_name']) || strlen($_FILES[$_POST['upload_type']]['tmp_name']) == 0 || intval($_FILES[$_POST['upload_type']]['size']) == 0) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Unknown error while trying to save file.',
            ));

        } elseif ($_FILES[$_POST['upload_type']]['size'] > ($this->config->item('max_file_mb_size') * 1024 * 1024)) {

            return echo_json(array(
                'status' => 0,
                'message' => 'File is larger than ' . $this->config->item('max_file_mb_size') . ' MB.',
            ));

        }

        //Validate Intent:
        $ins = $this->Intents_model->in_fetch(array(
            'in_id' => $_POST['in_id'],
        ));
        if(count($ins)<1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));
        }

        //See if this message type has specific input requirements:
        $valid_file_types = array(4258, 4259, 4260, 4261); //This must be a valid file type:  Video, Image, Audio or File

        //Attempt to save file locally:
        $file_parts = explode('.', $_FILES[$_POST['upload_type']]["name"]);
        $temp_local = "application/cache/temp_files/" . md5($file_parts[0] . $_FILES[$_POST['upload_type']]["type"] . $_FILES[$_POST['upload_type']]["size"]) . '.' . $file_parts[(count($file_parts) - 1)];
        move_uploaded_file($_FILES[$_POST['upload_type']]['tmp_name'], $temp_local);


        //Attempt to store in Mench Cloud on Amazon S3:
        if (isset($_FILES[$_POST['upload_type']]['type']) && strlen($_FILES[$_POST['upload_type']]['type']) > 0) {
            $mime = $_FILES[$_POST['upload_type']]['type'];
        } else {
            $mime = mime_content_type($temp_local);
        }

        $cdn_status = upload_to_cdn($temp_local, $session_en['en_id'], $_FILES[$_POST['upload_type']], true);
        if (!$cdn_status['status']) {
            //Oops something went wrong:
            return echo_json($cdn_status);
        }


        //Create message:
        $ln = $this->Links_model->ln_create(array(
            'ln_status_entity_id' => 6176, //Link Published
            'ln_creator_entity_id' => $session_en['en_id'],
            'ln_type_entity_id' => $_POST['focus_ln_type_entity_id'],
            'ln_parent_entity_id' => $cdn_status['cdn_en']['en_id'],
            'ln_child_intent_id' => intval($_POST['in_id']),
            'ln_content' => '@' . $cdn_status['cdn_en']['en_id'], //Just place the entity reference as the entire message
            'ln_order' => 1 + $this->Links_model->ln_max_order(array(
                'ln_type_entity_id' => $_POST['focus_ln_type_entity_id'],
                'ln_child_intent_id' => $_POST['in_id'],
            )),
        ));


        //Fetch full message for proper UI display:
        $new_messages = $this->Links_model->ln_fetch(array(
            'ln_id' => $ln['ln_id'],
        ));

        //Echo message:
        echo_json(array(
            'status' => 1,
            'message' => echo_in_message_manage(array_merge($new_messages[0], array(
                'ln_child_entity_id' => $session_en['en_id'],
            ))),
        ));
    }


    function in_load_data()
    {

        /*
         *
         * An AJAX function that is triggered every time a Miner
         * selects to modify an intent. It will check the
         * Requires Manual Response of an intent so it can
         * check proper boxes to help Miner modify the intent.
         *
         * */

        $session_en = en_auth(array(1308,7512));
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh.',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Intent ID',
            ));
        } elseif (!isset($_POST['ln_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Intent Link ID',
            ));
        }

        //Fetch Intent:
        $ins = $this->Intents_model->in_fetch(array(
            'in_id' => $_POST['in_id'],
        ));
        if(count($ins) < 1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));
        }

        //Prep metadata:
        $ins[0]['in_metadata'] = ( strlen($ins[0]['in_metadata']) > 0 ? unserialize($ins[0]['in_metadata']) : array());


        if(intval($_POST['ln_id'])>0){

            //Fetch intent link:
            $lns = $this->Links_model->ln_fetch(array(
                'ln_id' => $_POST['ln_id'],
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            ), array(( $_POST['is_parent'] ? 'in_child' : 'in_parent' )));

            if(count($lns) < 1){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid Intent Link ID',
                ));
            }

            //Add link connector:
            $lns[0]['ln_metadata'] = ( strlen($lns[0]['ln_metadata']) > 0 ? unserialize($lns[0]['ln_metadata']) : array());

            //Make sure marks are set:
            if(!isset($lns[0]['ln_metadata']['tr__assessment_points'])){
                $lns[0]['ln_metadata']['tr__assessment_points'] = 0;
            }

        }

        $actionplan_users = $this->Links_model->ln_fetch(array(
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //Action Plan Steps Progressed
            'ln_parent_intent_id' => $_POST['in_id'],
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        ), array(), 0, 0, array(), 'COUNT(ln_id) as total_steps');

        //Return results:
        return echo_json(array(
            'status' => 1,
            'in' => $ins[0],
            'in_action_plan_count' => $actionplan_users[0]['total_steps'],
            'ln' => ( isset($lns[0]) ? $lns[0] : array() ),
        ));

    }



    function in_message_sort()
    {

        //Authenticate Miner:
        $session_en = en_auth(array(1308,7512));
        if (!$session_en) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Session Expired. Sign In and try again',
            ));

        } elseif (!isset($_POST['new_ln_orders']) || !is_array($_POST['new_ln_orders']) || count($_POST['new_ln_orders']) < 1) {

            //Do not treat this case as error as it could happen in moving Messages between types:
            return echo_json(array(
                'status' => 1,
                'message' => 'There was nothing to sort',
            ));

        }

        //Update all link orders:
        $sort_count = 0;
        foreach ($_POST['new_ln_orders'] as $ln_order => $ln_id) {
            if (intval($ln_id) > 0) {
                $sort_count++;
                //Log update and give credit to the session Miner:
                $this->Links_model->ln_update($ln_id, array(
                    'ln_order' => intval($ln_order),
                ), $session_en['en_id']);
            }
        }

        //Return success:
        return echo_json(array(
            'status' => 1,
            'message' => $sort_count . ' Sorted', //Does not matter as its currently not displayed in UI
        ));
    }

    function in_message_modify_save()
    {

        //Authenticate Miner:
        $session_en = en_auth(array(1308,7512));
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh.',
            ));
        } elseif (!isset($_POST['ln_id']) || intval($_POST['ln_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Link ID',
            ));
        } elseif (!isset($_POST['message_ln_status_entity_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Message Status',
            ));
        } elseif (!isset($_POST['ln_content'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Message',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));
        }

        //Validate Intent:
        $ins = $this->Intents_model->in_fetch(array(
            'in_id' => $_POST['in_id'],
        ));
        if (count($ins) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Intent Not Found',
            ));
        }

        //Validate Message:
        $messages = $this->Links_model->ln_fetch(array(
            'ln_id' => intval($_POST['ln_id']),
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        ));
        if (count($messages) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Message Not Found',
            ));
        }


        //Did the message status change?
        if($messages[0]['ln_status_entity_id'] != $_POST['message_ln_status_entity_id']){

            //Are we deleting this message?
            if($_POST['message_ln_status_entity_id'] == 6173 /* Link Removed*/){

                //yes, do so and return results:
                $affected_rows = $this->Links_model->ln_update(intval($_POST['ln_id']), array(
                    'ln_status_entity_id' => $_POST['message_ln_status_entity_id'],
                ), $session_en['en_id']);

                //Return success:
                if($affected_rows > 0){
                    return echo_json(array(
                        'status' => 1,
                        'message' => echo_random_message('saving_notify'),
                    ));
                } else {
                    return echo_json(array(
                        'status' => 0,
                        'message' => 'Error trying to remove message',
                    ));
                }

            } elseif($_POST['message_ln_status_entity_id'] == 6176 /* Link Published */){

                //We're publishing, make sure potential entity references are also published:
                $string_references = extract_references($_POST['ln_content']);

                if (count($string_references['ref_entities']) > 0) {

                    //We do have an entity reference, what's its status?
                    $ref_ens = $this->Entities_model->en_fetch(array(
                        'en_id' => $string_references['ref_entities'][0],
                    ));

                    if(count($ref_ens)>0 && !in_array($ref_ens[0]['en_status_entity_id'], $this->config->item('en_ids_7357') /* Entity Statuses Public */)){
                        return echo_json(array(
                            'status' => 0,
                            'message' => 'You cannot published this message because its referenced entity is not yet public',
                        ));
                    }
                }
            }
        }



        //Validate new message:
        $msg_validation = $this->Communication_model->dispatch_validate_message($_POST['ln_content'], $session_en, false, array(), $messages[0]['ln_type_entity_id'], $_POST['in_id']);
        if (!$msg_validation['status']) {
            //There was some sort of an error:
            return echo_json($msg_validation);
        }


        //All good, lets move on:
        //Define what needs to be updated:
        $to_update = array(
            'ln_status_entity_id' => $_POST['message_ln_status_entity_id'],
            'ln_content' => $msg_validation['input_message'],
            'ln_parent_entity_id' => $msg_validation['ln_parent_entity_id'],
            'ln_parent_intent_id' => $msg_validation['ln_parent_intent_id'],
        );

        //Now update the DB:
        $this->Links_model->ln_update(intval($_POST['ln_id']), $to_update, $session_en['en_id']);

        //Re-fetch the message for display purposes:
        $new_messages = $this->Links_model->ln_fetch(array(
            'ln_id' => intval($_POST['ln_id']),
        ));

        $en_all_6186 = $this->config->item('en_all_6186');

        //Print the challenge:
        return echo_json(array(
            'status' => 1,
            'message' => $this->Communication_model->dispatch_message($msg_validation['input_message'], $session_en, false, array(), $_POST['in_id']),
            'message_new_status_icon' => '<span title="' . $en_all_6186[$to_update['ln_status_entity_id']]['m_name'] . ': ' . $en_all_6186[$to_update['ln_status_entity_id']]['m_desc'] . '" data-toggle="tooltip" data-placement="top">' . $en_all_6186[$to_update['ln_status_entity_id']]['m_icon'] . '</span>', //This might have changed
            'success_icon' => '<span><i class="fas fa-check"></i> Saved</span>',
        ));
    }




    function cron__sync_common_base($in_id = 0)
    {

        /*
         *
         * Updates common base metadata for published intents
         *
         * */

        if($in_id < 0){
            //Gateway URL to give option to run...
            die('<a href="/intents/cron__sync_common_base">Click here</a> to start running this function.');
        }

        boost_power();
        $start_time = time();
        $filters = array(
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        );
        if($in_id > 0){
            $filters['in_id'] = $in_id;
        }

        $published_ins = $this->Intents_model->in_fetch($filters);
        foreach($published_ins as $published_in){
            $tree = $this->Intents_model->in_metadata_common_base($published_in);
        }

        $total_time = time() - $start_time;
        $success_message = 'Common Base Metadata updated for '.count($published_ins).' published intent'.echo__s(count($published_ins)).'.';
        if (isset($_GET['redirect']) && strlen($_GET['redirect']) > 0) {
            //Now redirect;
            $this->session->set_flashdata('flash_message', '<div class="alert alert-success" role="alert">' . $success_message . '</div>');
            header('Location: ' . $_GET['redirect']);
        } else {
            //Show json:
            echo_json(array(
                'message' => $success_message,
                'total_time' => echo_time_minutes($total_time),
                'item_time' => round(($total_time/count($published_ins)),1).' Seconds',
                'last_tree' => $tree,
            ));
        }
    }


    function cron__sync_extra_insights($in_id = 0)
    {

        /*
         *
         * Updates tree insights (like min/max steps, time & cost)
         * based on its common and expansion tree.
         *
         * */


        if($in_id < 0){
            //Gateway URL to give option to run...
            die('<a href="/intents/cron__sync_extra_insights">Click here</a> to start running this function.');
        }

        boost_power();
        $start_time = time();
        $update_count = 0;

        if($in_id > 0){

            //Increment count by 1:
            $update_count++;

            //Start with common base:
            foreach($this->Intents_model->in_fetch(array('in_id' => $in_id)) as $published_in){
                $this->Intents_model->in_metadata_common_base($published_in);
            }

            //Update extra insights:
            $tree = $this->Intents_model->in_metadata_extra_insights($in_id);

        } else {

            //Update all Recommended Intentions and their tree:
            foreach ($this->Intents_model->in_fetch(array(
                'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
                'in_scope_entity_id IN (' . join(',', $this->config->item('en_ids_7582')) . ')' => null, //Intent Scopes Get Started
            )) as $published_in) {
                $tree = $this->Intents_model->in_metadata_extra_insights($published_in['in_id']);
                if($tree){
                    $update_count++;
                }
            }

        }



        $end_time = time() - $start_time;
        $success_message = 'Extra Insights Metadata updated for '.$update_count.' intent'.echo__s($update_count).'.';

        if (isset($_GET['redirect']) && strlen($_GET['redirect']) > 0) {
            //Now redirect;
            $this->session->set_flashdata('flash_message', '<div class="alert alert-success" role="alert">' . $success_message . '</div>');
            header('Location: ' . $_GET['redirect']);
        } else {
            //Show json:
            echo_json(array(
                'message' => $success_message,
                'total_time' => echo_time_minutes($end_time),
                'item_time' => round(($end_time/$update_count),1).' Seconds',
                'last_tree' => $tree,
            ));
        }
    }

}