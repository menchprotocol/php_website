<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Miner_app extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        //Load our buddies:
        $this->output->enable_profiler(FALSE);
    }




    function admin_tools($action = null, $command1 = null, $command2 = null)
    {

        boost_power();

        //Validate moderator:
        $session_en = en_auth(array(1281), true);

        //Load tools:
        $this->load->view('view_miner_app/miner_app_header', array(
            'title' => 'Moderation Tools',
        ));
        $this->load->view('view_miner_app/admin_tools' , array(
            'action' => $action,
            'command1' => $command1,
            'command2' => $command2,
            'session_en' => $session_en,
        ));
        $this->load->view('view_miner_app/miner_app_footer');
    }


    function dashboard()
    {
        $session_en = en_auth(array(1308)); //Just be logged in to browse
        $en_all_7368 = $this->config->item('en_all_7368');
        $this->load->view(($session_en ? 'view_miner_app/miner_app_header' : 'view_user_app/user_app_header'), array(
            'title' => $en_all_7368[7161]['m_name'],
        ));
        $this->load->view('view_miner_app/mench_dashboard');
        $this->load->view(($session_en ? 'view_miner_app/miner_app_footer' : 'view_user_app/user_app_footer'));
    }


    function php_info(){
        echo phpinfo();
    }

    function my_session()
    {
        echo_json($this->session->all_userdata());
    }


    function basic_stats_all(){

        //Return stats for the platform home page:
        $in_count = $this->Intents_model->in_fetch(array(
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
        ), array(), 0, 0, array(), 'COUNT(in_id) as total_active_intents');
        $en_count = $this->Entities_model->en_fetch(array(
            'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Entity Statuses Active
        ), array(), 0, 0, array(), 'COUNT(en_id) as total_active_entities');
        $ln_count = $this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        ), array(), 0, 0, array(), 'COUNT(ln_id) as total_active_links');

        return echo_json(array(
            'intents' => array(
                'extended_stats' => number_format($in_count[0]['total_active_intents']),
            ),
            'entities' => array(
                'extended_stats' => number_format($en_count[0]['total_active_entities']),
            ),
            'links' => array(
                'extended_stats' => number_format($ln_count[0]['total_active_links']),
            )
        ));

    }

    function extra_stats_intents(){

        $en_all_7302 = $this->config->item('en_all_7302'); //Intent Stats

        //Intent Statuses:
        echo '<table class="table table-condensed table-striped stats-table mini-stats-table">';
        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;">'.$en_all_7302[4737]['m_name'].echo__s(count($this->config->item('en_all_4737')), true).'</td>';
        echo '<td style="text-align: right;">Intents</td>';
        echo '</tr>';
        foreach ($this->config->item('en_all_4737') as $en_id => $m) {

            //Count this status:
            $objects_count = $this->Intents_model->in_fetch(array(
                'in_status_entity_id' => $en_id
            ), array(), 0, 0, array(), 'COUNT(in_id) as totals');

            //Display this status count:
            echo '<tr class="'.( $en_id==6182 ? 'is-removed' : '' ).'">';
            echo '<td style="text-align: left;"><span class="icon-block">' . $m['m_icon'] . '</span><a href="/entities/'.$en_id.'">' . $m['m_name'] . '</a></td>';
            echo '<td style="text-align: right;">' . '<a href="/links?in_status_entity_id=' . $en_id . '&ln_type_entity_id=4250">' . echo_number($objects_count[0]['totals']) . '</a>' . '<i class="fal fa-info-circle icon-block" data-toggle="tooltip" title="' . number_format($objects_count[0]['totals'], 0) . ' '. $m['m_desc'] . '" data-placement="top"></i>' . '</td>';
            echo '</tr>';

        }
        echo '</table>';


        //Intent Start Modes:
        echo echo_in_setting(7596,'in_start_mode_entity_id');


        //Intent Types:
        echo echo_in_setting(7585,'in_type_entity_id');


        //Intent Verbs:
        $show_max_verbs = 5;

        //Fetch all needed data:
        $in_verbs = $this->Intents_model->in_fetch(array(
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
        ), array('in_verb_entity_id'), 0, 0, array('totals' => 'DESC'), 'COUNT(in_id) as totals, in_verb_entity_id, en_name, en_icon', 'in_verb_entity_id, en_name, en_icon');

        echo '<table class="table table-condensed table-striped stats-table mini-stats-table ">';

        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;">'.$en_all_7302[5008]['m_name'].echo__s(count($in_verbs)).'</td>';
        echo '<td style="text-align: right;">Intents</td>';
        echo '</tr>';

        foreach($in_verbs as $count => $verb){

            echo '<tr class="'.( $count >= $show_max_verbs ? 'hiddenverbs hidden' : '' ).'">';
            echo '<td style="text-align: left;"><span style="width:29px; display: inline-block; text-align: center;">'.echo_en_icon($verb).'</span><a href="/entities/'.$verb['in_verb_entity_id'].'">'.$verb['en_name'].'</a></td>';
            echo '<td style="text-align: right;"><a href="/links?ln_type_entity_id=4250&in_status_entity_id=' . join(',', $this->config->item('en_ids_7356')) . '&in_verb_entity_id='.$verb['in_verb_entity_id'].'">'.echo_number($verb['totals']).'</a><i class="fal fa-info-circle icon-block" data-toggle="tooltip" title="'.number_format($verb['totals'],0).' Intent'.echo__s($verb['totals']).' help you '.$verb['en_name'].'" data-placement="top"></i></td>';
            echo '</tr>';

            if(($count+1)==$show_max_verbs){
                //Show expand button:
                echo '<tr class="hiddenverbs">';
                echo '<td style="text-align: left;" colspan="2"><span style="width:29px; display: inline-block; text-align: center;"><i class="fas fa-plus-circle"></i></span><a href="javascript:void(0);" onclick="$(\'.hiddenverbs\').toggleClass(\'hidden\')">See All '.echo_number(count($in_verbs)).' '.$en_all_7302[5008]['m_name'].'</a></td>';
                echo '</tr>';
                //To keep stripe color in balance
                echo '<tr class="hidden"><td style="text-align: left;" colspan="2"></td></tr>';
            }
        }

        echo '</table>';




        //Intent Mining Stats
        echo echo_ln_type_group_stats($this->config->item('en_all_7302'),7166);

    }





    function extra_stats_entities(){

        $en_all_7303 = $this->config->item('en_all_7303'); //Platform Dashboard
        $en_all_6177 = $this->config->item('en_all_6177'); //Entity Statuses



        //Entity Statuses
        echo '<table class="table table-condensed table-striped stats-table mini-stats-table">';
        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;">'.$en_all_7303[6177]['m_name'].echo__s(count($this->config->item('en_all_6177')), true).'</td>';
        echo '<td style="text-align: right;">Entities</td>';
        echo '</tr>';
        foreach ($this->config->item('en_all_6177') as $en_id => $m) {

            //Count this status:
            $objects_count = $this->Entities_model->en_fetch(array(
                'en_status_entity_id' => $en_id
            ), array(), 0, 0, array(), 'COUNT(en_id) as totals');

            //Display this status count:
            echo '<tr class="'.( $en_id==6178 ? 'is-removed' : '' ).'">';
            echo '<td style="text-align: left;"><span class="icon-block">' . $m['m_icon'] . '</span><a href="/entities/'.$en_id.'">' . $m['m_name'] . '</a></td>';
            echo '<td style="text-align: right;">' . '<a href="/links?en_status_entity_id=' . $en_id . '&ln_type_entity_id=4251">' . echo_number($objects_count[0]['totals']) . '</a>' .'<i class="fal fa-info-circle icon-block" data-toggle="tooltip" title="' . number_format($objects_count[0]['totals'], 0).' '.$m['m_desc'] . '" data-placement="top"></i>' . '</td>';
            echo '</tr>';
        }
        echo '</table>';






        //Expert Sources
        $total_counts = array();
        $expert_sources = ''; //Saved the UI for later view...
        foreach ($this->config->item('en_all_3000') as $en_id => $m) {

            //Echo stats:
            $expert_sources .= '<tr>';
            $expert_sources .= '<td style="text-align: left;"><span class="icon-block">'.$m['m_icon'].'</span><a href="/entities/'.$en_id.'">'.$m['m_name'].'</a></td>';

            //Count totals for each active status:
            foreach($this->config->item('en_all_7358') /* Entity Active Statuses */ as $en_status_entity_id => $m_status){

                //Count this type:
                $source_count = $this->Entities_model->en_child_count($en_id, array($en_status_entity_id)); //Count completed

                //Addup count:
                if(isset($total_counts[$en_status_entity_id])){
                    $total_counts[$en_status_entity_id] += $source_count;
                } else {
                    $total_counts[$en_status_entity_id] = $source_count;
                }

                //Display row:
                $expert_sources .= '<td style="text-align: right;"'.( $en_status_entity_id != 6181 /* Entity Featured */ ? ' class="' . advance_mode() . '"' : '' ).'><a href="/entities/' . $en_id .'#status-'.$en_status_entity_id.'">'.number_format($source_count,0).'</a><i class="fal fa-info-circle icon-block" data-toggle="tooltip" title="'.number_format($source_count,0).' '.$m['m_name'].' are '. $en_all_6177[$en_status_entity_id]['m_desc'] . '" data-placement="top"></i></td>';


            }

            $expert_sources .= '</tr>';
        }


        echo '<table class="table table-condensed table-striped stats-table">';

        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;">'.$en_all_7303[3000]['m_name'].'</td>';
        foreach($this->config->item('en_all_7358') /* Entity Active Statuses */ as $en_status_entity_id => $m_status){
            echo '<td style="text-align: right;" '.( $en_status_entity_id != 6181 /* Entity Featured */ ? ' class="' . advance_mode() . '"' : '' ).'>' . $en_all_6177[$en_status_entity_id]['m_name'] . '</td>';
        }
        echo '</tr>';


        echo $expert_sources;


        echo '<tr style="font-weight: bold;">';
        echo '<td style="text-align: left;"><span class="icon-block"><i class="fas fa-asterisk"></i></span>Total</td>';
        foreach($this->config->item('en_all_7358') /* Entity Active Statuses */ as $en_status_entity_id => $m_status){
            echo '<td style="text-align: right;" '.( $en_status_entity_id != 6181 /* Entity Featured */ ? ' class="' . advance_mode() . '"' : '' ).'>' . echo_number($total_counts[$en_status_entity_id]) . '<i class="fal fa-info-circle icon-block" data-toggle="tooltip" title="'.number_format($total_counts[$en_status_entity_id], 0).' '.$en_all_7303[3000]['m_name'].' are '.$en_all_6177[$en_status_entity_id]['m_name'] . '" data-placement="top"></i>' . '</td>';
        }
        echo '</tr>';


        echo '</table>';



        //Mench Community
        echo echo_en_stats_overview($this->config->item('en_all_6827'), $en_all_7303[6827]['m_name']);



        //Entity Mining Stats
        echo echo_ln_type_group_stats($this->config->item('en_all_7303'), 7167);

    }
    function extra_stats_links(){


        $en_all_4463 = $this->config->item('en_all_4463'); //Platform Glossary
        $en_all_4593 = $this->config->item('en_all_4593'); //Load all link types
        $en_all_7304 = $this->config->item('en_all_7304'); //Link Stats


        //Link Status:
        echo '<table class="table table-condensed table-striped stats-table mini-stats-table">';
        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;">'.$en_all_7304[6186]['m_name'].echo__s(count($this->config->item('en_all_6186')), true).'</td>';
        echo '<td style="text-align: right;">Links</td>';
        echo '</tr>';
        foreach ($this->config->item('en_all_6186') as $en_id => $m) {

            //Count this status:
            $objects_count = $this->Links_model->ln_fetch(array(
                'ln_status_entity_id' => $en_id
            ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');

            //Display this status count:
            echo '<tr class="'.( $en_id==6173 ? 'is-removed' : '' ).'">';
            echo '<td style="text-align: left;"><span class="icon-block">' . $m['m_icon'] . '</span><a href="/entities/'.$en_id.'">' . $m['m_name'] . '</a></td>';
            echo '<td style="text-align: right;">';
            echo '<a href="/links?ln_status_entity_id=' . $en_id . '">' . echo_number($objects_count[0]['totals']) . '</a>';
            echo '<i class="fal fa-info-circle icon-block" data-toggle="tooltip" title="' . number_format($objects_count[0]['totals'], 0).' '.$m['m_desc'] . '" data-placement="top"></i>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</table>';













        //Fetch top certified miners vs top users:
        $days_ago = 7; //Both miners and users
        $days_term = ( !is_null($days_ago) ? 'Last '.$days_ago.' Days' : 'All-Time' );
        $certified_miners_en_ids = array();
        foreach($this->Links_model->ln_fetch(array(
            'ln_parent_entity_id' => 1308, //Mench Certified Miners
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        )) as $ln){
            array_push($certified_miners_en_ids, $ln['ln_child_entity_id']);
        }

        //Top Miners:
        $top = 10;
        $filters = array(
            'ln_credits >' => 0,
            'ln_miner_entity_id >' => 0, //Must have a miner
            'ln_miner_entity_id IN ('.join(',', $certified_miners_en_ids).')' => null,
        );
        if(!is_null($days_ago)){
            $start_date = date("Y-m-d" , (time() - ($days_ago * 24 * 3600)));
            $filters['ln_timestamp >='] = $start_date.' 00:00:00'; //From beginning of the day
        }
        $top_users = $this->Links_model->ln_fetch($filters, array('en_miner'), $top, 0, array('credits_sum' => 'DESC'), 'COUNT(ln_miner_entity_id) as trs_count, SUM(ln_credits) as credits_sum, en_name, en_icon, ln_miner_entity_id', 'ln_miner_entity_id, en_name, en_icon');

        if(count($top_users) < $top){
            $top = count($top_users);
        }

        echo '<table class="table table-condensed table-striped stats-table">';

        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;">'.$en_all_7304[7162]['m_name'].' ['.$days_term.']</td>';
        echo '<td style="text-align: right;">Credits</td>';
        echo '</tr>';

        foreach ($top_users as $count=>$ln) {
            echo '<tr>';

            echo '<td style="text-align: left;"><span class="parent-icon" style="width: 29px; display: inline-block; text-align: center;">'.echo_en_icon($ln).'</span><a href="/entities/'.$ln['ln_miner_entity_id'].'">'.one_two_explode('',' ', $ln['en_name']).'</a> '.echo_rank($count+1).'</td>';

            echo '<td style="text-align: right;"><a href="/links?ln_miner_entity_id='.$ln['ln_miner_entity_id'].( is_null($days_ago) ? '' : '&start_range='.$start_date ).'">'.echo_number($ln['credits_sum'], 1).'</a><i class="fal fa-info-circle icon-block" data-toggle="tooltip" title="'.$ln['en_name'].' earned '.number_format($ln['credits_sum'], 0).' credits with '.number_format($ln['trs_count'],0).' links ['.$days_term.'] averaging '.round(($ln['credits_sum']/$ln['trs_count']),1).' credits/link" data-placement="top"></i></td>';

            echo '</tr>';
        }
        echo '</table>';





        //Top Users:
        $top = 10;
        $filters = array(
            'ln_credits >' => 0,
            'ln_miner_entity_id >' => 0, //Must have a miner
            'ln_miner_entity_id NOT IN ('.join(',', $certified_miners_en_ids).')' => null,
        );
        if(!is_null($days_ago)){
            $start_date = date("Y-m-d" , (time() - ($days_ago * 24 * 3600)));
            $filters['ln_timestamp >='] = $start_date.' 00:00:00'; //From beginning of the day
        }
        $top_users = $this->Links_model->ln_fetch($filters, array('en_miner'), $top, 0, array('credits_sum' => 'DESC'), 'COUNT(ln_miner_entity_id) as trs_count, SUM(ln_credits) as credits_sum, en_name, en_icon, ln_miner_entity_id', 'ln_miner_entity_id, en_name, en_icon');

        if(count($top_users) < $top){
            $top = count($top_users);
        }

        echo '<table class="table table-condensed table-striped stats-table">';

        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;">'.$en_all_7304[7163]['m_name'].' ['.$days_term.']</td>';
        echo '<td style="text-align: right;">Credits</td>';
        echo '</tr>';

        foreach ($top_users as $count=>$ln) {
            echo '<tr>';
            echo '<td style="text-align: left;"><span class="parent-icon icon-block">'.echo_en_icon($ln).'</span><a href="/entities/'.$ln['ln_miner_entity_id'].'">'.one_two_explode('',' ',$ln['en_name']).'</a> '.echo_rank($count+1).'</td>';
            echo '<td style="text-align: right;"><a href="/links?ln_miner_entity_id='.$ln['ln_miner_entity_id'].( is_null($days_ago) ? '' : '&start_range='.$start_date ).'">'.echo_number($ln['credits_sum'], 1).'</a><i class="fal fa-info-circle icon-block" data-toggle="tooltip" title="'.$ln['en_name'].' earned '.number_format($ln['credits_sum'], 0).' credits with '.number_format($ln['trs_count'],0).' links ['.$days_term.'] averaging '.round(($ln['credits_sum']/$ln['trs_count']),1).' credits/link" data-placement="top"></i></td>';
            echo '</tr>';
        }
        echo '</table>';






        //Links User Engagement Stats
        echo echo_ln_type_group_stats($this->config->item('en_all_7304'), 7159);






        //All Link Types:
        $all_link_types = $this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        ), array('en_type'), 0, 0, array('en_name' => 'ASC'), 'COUNT(ln_type_entity_id) as trs_count, en_name, en_icon, ln_type_entity_id', 'ln_type_entity_id, en_name, en_icon');

        echo '<table class="table table-condensed table-striped stats-table mini-stats-table">';

        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;">'.echo_number(count($all_link_types)).' '.$en_all_7304[4593]['m_name'].echo__s(count($all_link_types)).'</td>';
        echo '<td style="text-align: right;" class="' . advance_mode() . '">Credits</td>';
        echo '<td style="text-align: right;">Links</td>';
        echo '</tr>';


        foreach ($all_link_types as $ln) {

            //Echo stats:
            echo '<tr>';
            echo '<td style="text-align: left;">';

                echo '<span class="icon-block">'.echo_en_icon($ln).'</span>';

                echo '<a href="/entities/'.$ln['ln_type_entity_id'].'">'.$ln['en_name'].'</a>';

            echo '</td>';


            //Current Credits Rate
            $fetch_credits = fetch_credits($ln['ln_type_entity_id']);
            echo '<td style="text-align: right;" class="' . advance_mode() . '">'.( $fetch_credits > 0 ? '<a href="/links?ln_status_entity_id='.join(',', $this->config->item('en_ids_7359')) /* Link Statuses Public */.'&ln_parent_entity_id=4595&ln_type_entity_id=4319&ln_child_entity_id='.$ln['ln_type_entity_id'].'" data-toggle="tooltip" title="Credits per link" data-placement="top">'.number_format($fetch_credits, 0).'</a>' : '0' ).'</td>';

            //<span class="icon-block"><i class="far fa-badge-check"></i></span>

            //Links count:
            echo '<td style="text-align: right;"><a href="/links?ln_status_entity_id='.join(',', $this->config->item('en_ids_7360')) /* Link Statuses Active */.'&ln_type_entity_id='.$ln['ln_type_entity_id'].'">'.echo_number($ln['trs_count']).'</a>'

                //Info:
                .( strlen($en_all_4593[$ln['ln_type_entity_id']]['m_desc']) > 0 ? '<i class="fal fa-info-circle icon-block" data-toggle="tooltip" title="'.number_format($ln['trs_count'], 0).' '.$en_all_4593[$ln['ln_type_entity_id']]['m_desc'].'" data-placement="top"></i>' : '<i class="fal fa-info-circle icon-block" data-toggle="tooltip" title="'.number_format($ln['trs_count'], 0).' Links" data-placement="top" style="color: #AAA;"></i>' ).'</td>';

            echo '</tr>';

        }
        echo '</table>';

    }

    function platform_cache(){

        /*
         *
         * This function prepares a PHP-friendly text to be copied to platform_cache.php
         * (which is auto loaded) to provide a cache image of some entities in
         * the tree for faster application processing.
         *
         * */

        //First first all entities that have Cache in PHP Config @4527 as their parent:
        $config_ens = $this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'ln_parent_entity_id' => 4527,
        ), array('en_child'), 0);

        echo '//Generated '.date("Y-m-d H:i:s").' PST<br />';

        foreach($config_ens as $en){

            //Now fetch all its children:
            $children = $this->Links_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Entity Statuses Public
                'ln_parent_entity_id' => $en['ln_child_entity_id'],
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            ), array('en_child'), 0, 0, array('ln_order' => 'ASC', 'en_id' => 'ASC'));


            $child_ids = array();
            foreach($children as $child){
                array_push($child_ids , $child['en_id']);
            }

            echo '<br />//'.$en['en_name'].':<br />';
            echo '$config[\'en_ids_'.$en['ln_child_entity_id'].'\'] = array('.join(',',$child_ids).');<br />';
            echo '$config[\'en_all_'.$en['ln_child_entity_id'].'\'] = array(<br />';
            foreach($children as $child){

                //Do we have an omit command?
                if(substr_count($en['ln_content'], '&var_trimcache=') == 1){
                    $child['en_name'] = trim(str_replace(one_two_explode('&var_trimcache=','',$en['ln_content']) , '', $child['en_name']));
                }

                //Fetch all parents for this child:
                $child_parent_ids = array(); //To be populated soon
                $child_parents = $this->Links_model->ln_fetch(array(
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                    'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Entity Statuses Public
                    'ln_child_entity_id' => $child['en_id'],
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                ), array('en_parent'), 0);
                foreach($child_parents as $cp_en){
                    array_push($child_parent_ids, intval($cp_en['en_id']));
                }

                echo '&nbsp;&nbsp;&nbsp;&nbsp; '.$child['en_id'].' => array(<br />';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_icon\' => \''.htmlentities($child['en_icon']).'\',<br />';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_name\' => \''.str_replace('\'','\\\'',$child['en_name']).'\',<br />';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_desc\' => \''.str_replace('\'','\\\'',$child['ln_content']).'\',<br />';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_parents\' => array('.join(',',$child_parent_ids).'),<br />';
                echo '&nbsp;&nbsp;&nbsp;&nbsp; ),<br />';

            }
            echo ');<br />';
        }
    }
}