<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        //Load our buddies:
        $this->output->enable_profiler(FALSE);
    }


    function tools($action = null, $command1 = null, $command2 = null)
    {

        //Validate moderator:
        $session_en = en_auth(array(1281), true);

        //Load tools:
        $this->load->view('view_shared/platform_header', array(
            'title' => 'Moderation Tools',
        ));
        $this->load->view('view_admin/admin_tools' , array(
            'action' => $action,
            'command1' => $command1,
            'command2' => $command2,
            'session_en' => $session_en,
        ));
        $this->load->view('view_shared/platform_footer');
    }


    function platform()
    {
        $session_en = en_auth(array(1308)); //Just be logged in to browse
        $this->load->view(($session_en ? 'view_shared/platform_header' : 'view_shared/public_header'), array(
            'title' => 'Mench Personal Assistant',
        ));
        $this->load->view('view_admin/platform_home');
        $this->load->view(($session_en ? 'view_shared/platform_footer' : 'view_shared/public_footer'));
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
        $in_count = $this->Intents_model->in_fetch(array('in_status >=' => 0), array(), 0, 0, array(), 'COUNT(in_id) as total_active_intents');
        $en_count = $this->Entities_model->en_fetch(array('en_status >=' => 0), array(), 0, 0, array(), 'COUNT(en_id) as total_active_entities');
        $ln_count = $this->Links_model->ln_fetch(array('ln_status >=' => 0), array(), 0, 0, array(), 'COUNT(ln_id) as total_active_links');

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

        $en_all_4534 = $this->config->item('en_all_4534');
        $en_all_7161 = $this->config->item('en_all_7161'); //Platform Dashboard
        $fixed_fields = $this->config->item('fixed_fields');


        //Intent Statuses:
        echo '<table class="table table-condensed table-striped stats-table mini-stats-table">';

        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;">'.count($this->config->item('en_ids_4737')).' '.$en_all_7161[4737]['m_name'].'</td>';
        echo '<td style="text-align: right;">Intents</td>';
        echo '</tr>';

        //Object Stats grouped by Status:
        $this_ui = '';
        $objects_count = $this->Intents_model->in_fetch(array(), array(), 0, 0, array(), 'in_status, COUNT(in_id) as totals', 'in_status');
        foreach ($fixed_fields['in_status'] as $status_num => $status) {
            //Count this status:
            $objects_count = $this->Intents_model->in_fetch(array(
                'in_status' => $status_num
            ), array(), 0, 0, array(), 'COUNT(in_id) as totals');

            //Display this status count:
            $this_ui .= '<tr class="'.( $status_num < 0 ? 'is-removed' : '' ).'">';
            $this_ui .= '<td style="text-align: left;"><span style="width:29px; display: inline-block; text-align: center;">' . $status['s_icon'] . '</span><a href="/entities/'.$status['s_en_id'].'">' . $status['s_name'] . '</a> <i class="fal fa-info-circle" data-toggle="tooltip" title="' . $status['s_desc'] . '" data-placement="top"></i></td>';
            $this_ui .= '<td style="text-align: right;">' . ($objects_count[0]['totals'] > 0 ? '<a href="/links?in_status=' . $status_num . '&ln_type_entity_id=4250"  data-toggle="tooltip" title="View Links" data-placement="top">' . number_format($objects_count[0]['totals'], 0) . '</a>' : $objects_count[0]['totals']) . '</td>';
            $this_ui .= '</tr>';

        }
        echo $this_ui;
        echo '</table>';


        //Entity Mining Stats
        echo echo_link_type_group_stats(7166);


        //Intent Types:
        echo '<table class="table table-condensed table-striped stats-table mini-stats-table ">';

        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;">'.count(array_merge($this->config->item('en_all_6192'), $this->config->item('en_all_6193'))).' '.$en_all_7161[6676]['m_name'].'</td>';
        echo '<td style="text-align: right;">Intents</td>';
        echo '</tr>';

        $types_ui = '';
        foreach ($this->config->item('en_all_6676') as $type_en_id => $type) {

            //Count totals:
            $all_intent_types = 0;
            $sub_types_ui = '';


            //Now list all Children:
            foreach ($this->config->item('en_all_'.$type_en_id) as $sub_type_en_id => $sub_type) {

                //Count this sub-type from the database:
                $in_count = $this->Intents_model->in_fetch(array(
                    'in_type_entity_id' => $sub_type_en_id,
                    'in_status >=' => 0,
                ), array(), 0, 0, array(), 'COUNT(in_id) as total_active_intents');

                $all_intent_types += $in_count[0]['total_active_intents'];

                //Echo this as the main title:
                $sub_types_ui .= '<tr>';
                $sub_types_ui .= '<td style="text-align: left;"><span style="width:29px; display: inline-block; text-align: center; margin-left:23px;">'.$sub_type['m_icon'].'</span><a href="/entities/'.$sub_type_en_id.'">'.$sub_type['m_name'].'</a> <i class="fal fa-info-circle" data-toggle="tooltip" title="'.$sub_type['m_desc'].'" data-placement="top"></i></td>';
                $sub_types_ui .= '<td style="text-align: right;"><a href="/links?ln_type_entity_id=4250&in_status=0,1,2&in_type_entity_id='.$sub_type_en_id.'">'.number_format($in_count[0]['total_active_intents'],0).'</a></td>';
                $sub_types_ui .= '</tr>';

            }


            //Echo this as the main title:
            $types_ui .= '<tr style="font-weight: bold;">';
            $types_ui .= '<td style="text-align: left;"><span style="width:29px; display: inline-block; text-align: center;">'.$type['m_icon'].'</span><a href="/entities/'.$type_en_id.'">'.$type['m_name'].' Intents</a> <i class="fal fa-info-circle" data-toggle="tooltip" title="'.$type['m_desc'].'" data-placement="top"></i></td>';
            $types_ui .= '<td style="text-align: right;"><a href="/links?ln_type_entity_id=4250&in_status=0,1,2&in_type_entity_id='.join(',',$this->config->item('en_ids_'.$type_en_id)).'">'.number_format($all_intent_types,0).'</a></td>';
            $types_ui .= '</tr>';

            //Add sub-types:
            $types_ui .= $sub_types_ui;
        }

        echo $types_ui;
        echo '</table>';




        //Intent Verbs:
        $show_max_verbs = 15;

        //Fetch all needed data:
        $in_verbs = $this->Intents_model->in_fetch(array(
            'in_status >=' => 0, //New+
        ), array('in_verb_entity_id'), 0, 0, array('totals' => 'DESC'), 'COUNT(in_id) as totals, in_verb_entity_id, en_name', 'in_verb_entity_id, en_name');

        echo '<table class="table table-condensed table-striped stats-table mini-stats-table ">';

        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;">'.count($in_verbs).' '.$en_all_7161[5008]['m_name'].'</td>';
        echo '<td style="text-align: right;">Intents</td>';
        echo '</tr>';

        foreach($in_verbs as $count => $verb){
            echo '<tr class="'.( $count >= $show_max_verbs ? 'hiddenverbs hidden' : '' ).'">';
            echo '<td style="text-align: left;"><a href="/entities/'.$verb['in_verb_entity_id'].'">'.$verb['en_name'].'</a></td>';
            echo '<td style="text-align: right;"><a href="/links?ln_type_entity_id=4250&in_status=0,1,2&in_verb_entity_id='.$verb['in_verb_entity_id'].'">'.number_format($verb['totals'],0).'</a></td>';
            echo '</tr>';
        }
        echo '</table>';
        //Show expand button:
        echo '<div style="margin:-10px 0 0 0; padding: 0;"><a href="javascript:void(0);" onclick="$(\'.hiddenverbs\').toggleClass(\'hidden\')" class="hiddenverbs"><i class="fal fa-plus-circle"></i> See All '.number_format(count($in_verbs),0).' '.$en_all_7161[5008]['m_name'].'</a></div>';

    }


    function extra_stats_entities(){

        $en_all_4534 = $this->config->item('en_all_4534');
        $en_all_7161 = $this->config->item('en_all_7161'); //Platform Dashboard
        $fixed_fields = $this->config->item('fixed_fields');


        //Entity Status:
        $objects_count = $this->Entities_model->en_fetch(array(), array('skip_en__parents'), 0, 0, array(), 'en_status, COUNT(en_id) as totals', 'en_status');


        echo '<table class="table table-condensed table-striped stats-table mini-stats-table">';

        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;">'.count($this->config->item('en_ids_6177')).' '.$en_all_7161[6177]['m_name'].'</td>';
        echo '<td style="text-align: right;">Entities</td>';
        echo '</tr>';

        //Object Stats grouped by Status:
        foreach ($fixed_fields['en_status'] as $status_num => $status) {

            //Count this status:
            $objects_count = $this->Entities_model->en_fetch(array(
                'en_status' => $status_num
            ), array(), 0, 0, array(), 'COUNT(en_id) as totals');

            //Display this status count:
            echo '<tr class="'.( $status_num < 0 ? 'is-removed' : '' ).'">';
            echo '<td style="text-align: left;"><span style="width:29px; display: inline-block; text-align: center;">' . $status['s_icon'] . '</span><a href="/entities/'.$status['s_en_id'].'">' . $status['s_name'] . '</a> <i class="fal fa-info-circle" data-toggle="tooltip" title="' . $status['s_desc'] . '" data-placement="top"></i></td>';
            echo '<td style="text-align: right;">' . ($objects_count[0]['totals'] > 0 ? '<a href="/links?en_status=' . $status_num . '&ln_type_entity_id=4251"  data-toggle="tooltip" title="View Links" data-placement="top">' . number_format($objects_count[0]['totals'], 0) . '</a>' : $objects_count[0]['totals']) . '</td>';
            echo '</tr>';

        }
        echo '</table>';



        //Entity Mining Stats
        echo echo_link_type_group_stats(7167);


        //Verified Accounts
        echo echo_en_stats_overview($this->config->item('en_all_4432'), $en_all_7161[4432]['m_name']);


        //Mench Contributors
        //echo echo_en_stats_overview($this->config->item('en_all_6827'), $en_all_7161[6827]['m_name']);




        //Expert Sources:
        $ie_ens = $this->Entities_model->en_fetch(array(
            'en_id' => 3000, //Industry Expert Sources
            'en_status >=' => 0, //New+
        ), array(), 0, 0, array('en_name' => 'ASC'));



        $expert_source_types = 0;
        $all_source_count = 0;
        $all_source_count_weight = 0;
        $all_mined_source_count = 0;
        $all_mined_source_count_weigh = 0;
        $expert_sources = ''; //Saved the UI for later view...

        foreach ($this->Links_model->ln_fetch(array(
            'ln_parent_entity_id' => 3000,
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'ln_status >=' => 0, //New+
            'en_status >=' => 0, //New+
        ), array('en_child'), $this->config->item('items_per_page'), 0, array('en_trust_score' => 'DESC')) as $source_en) {

            //Count any/all sources (complete or incomplete):
            $source_count = $this->Entities_model->en_child_count($source_en['en_id']);
            $weight = ( substr_count($source_en['ln_content'], '&var_weight=')==1 ? intval(one_two_explode('&var_weight=','',$source_en['ln_content'])) : 0 );
            $all_source_count += $source_count;
            $all_source_count_weight += ($source_count * $weight);
            if($source_count < 1 || $weight < 1){
                continue;
            }

            $expert_source_types++;

            //Count completed sources:
            $mined_source_count = $this->Entities_model->en_child_count($source_en['en_id'], 2);
            $all_mined_source_count += $mined_source_count;
            $all_mined_source_count_weigh += ($mined_source_count * $weight);


            //Echo stats:
            $expert_sources .= '<tr>';
            $expert_sources .= '<td style="text-align: left;"><span class="icon-block">'.echo_icon($source_en).'</span><a href="/entities/'.$source_en['en_id'].'">'.$source_en['en_name'].'</a></td>';
            $expert_sources .= '<td style="text-align: right;"><span data-toggle="tooltip" title="'.number_format($mined_source_count,0).'/'.number_format($source_count,0).' '.$source_en['en_name'].' have been mined completely" class="underdot" data-placement="top">'.number_format(($mined_source_count/$source_count*100), 1).'%</span></td>';
            $expert_sources .= '<td style="text-align: right;"><a href="/links?ln_status=0,1,2&ln_type_entity_id='.join(',', $this->config->item('en_ids_4592')).'&ln_parent_entity_id=' . $source_en['en_id'].'">'.number_format($source_count, 0).'</a></td>';
            $expert_sources .= '</tr>';

        }


        echo '<table class="table table-condensed table-striped stats-table">';

        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;">'.echo_number($all_source_count).' '.$en_all_7161[3000]['m_name'].'</td>';
        echo '<td style="text-align: right;">Mined</td>';
        echo '<td style="text-align: right;">Entities</td>';
        echo '</tr>';

        echo $expert_sources;

        echo '<tr style="font-weight: bold;">';
        echo '<td style="text-align: left;"><span class="icon-block"><i class="fas fa-asterisk"></i></span>Totals</td>';
        echo '<td style="text-align: right;"><span data-toggle="tooltip" title="Overall mining progress on all expert sources" data-placement="top" class="underdot">'.($all_source_count_weight > 0 ? number_format(($all_mined_source_count_weigh/$all_source_count_weight*100), 1) : 0).'%</span></td>';
        echo '<td style="text-align: right;">'.echo_number($all_source_count).'</td>';
        echo '</tr>';


        echo '</table>';



        //Fetch top certified miners vs top users:
        $days_ago = 7; //Both miners and users
        $certified_miners_en_ids = array();
        foreach($this->Links_model->ln_fetch(array(
            'ln_parent_entity_id' => 1308, //Mench Certified Miners
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'ln_status' => 2, //Published
        )) as $ln){
            array_push($certified_miners_en_ids, $ln['ln_child_entity_id']);
        }


        //Top Miners:
        $top = 10;
        $filters = array(
            'ln_points >' => 0,
            'ln_miner_entity_id IN ('.join(',', $certified_miners_en_ids).')' => null,
        );
        if(!is_null($days_ago)){
            $start_date = date("Y-m-d" , (time() - ($days_ago * 24 * 3600)));
            $filters['ln_timestamp >='] = $start_date.' 00:00:00'; //From beginning of the day
        }
        $top_users = $this->Links_model->ln_fetch($filters, array('en_miner'), $top, 0, array('points_sum' => 'DESC'), 'COUNT(ln_miner_entity_id) as trs_count, SUM(ln_points) as points_sum, en_name, en_icon, ln_miner_entity_id', 'ln_miner_entity_id, en_name, en_icon');

        if(count($top_users) < $top){
            $top = count($top_users);
        }

        echo '<table class="table table-condensed table-striped stats-table">';

        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;">'.$en_all_7161[7162]['m_name'].'</td>';
        echo '<td style="text-align: right;">'.( !is_null($days_ago) ? $days_ago.'-Day ' : '' ).'Points</td>';
        echo '</tr>';

        foreach ($top_users as $count=>$ln) {
            echo '<tr>';
            echo '<td style="text-align: left;"><span style="width:29px; display: inline-block; text-align: center; '.( $count > 2 ? 'font-size:0.8em;' : '' ).'">'.echo_rank($count+1).'</span><span class="parent-icon" style="width: 29px; display: inline-block; text-align: center;">'.echo_icon($ln).'</span><a href="/entities/'.$ln['ln_miner_entity_id'].'">'.$ln['en_name'].'</a></td>';
            echo '<td style="text-align: right;"><a href="/links?ln_miner_entity_id='.$ln['ln_miner_entity_id'].( is_null($days_ago) ? '' : '&start_range='.$start_date ).'"  data-toggle="tooltip" title="Mined with '.number_format($ln['trs_count'],0).' links averaging '.round(($ln['points_sum']/$ln['trs_count']),1).' points/link" data-placement="top">'.number_format($ln['points_sum'], 0).'</a></td>';
            echo '</tr>';
        }
        echo '</table>';





        //Top Users:
        $top = 10;
        $filters = array(
            'ln_points >' => 0,
            'ln_miner_entity_id NOT IN ('.join(',', $certified_miners_en_ids).')' => null,
        );
        if(!is_null($days_ago)){
            $start_date = date("Y-m-d" , (time() - ($days_ago * 24 * 3600)));
            $filters['ln_timestamp >='] = $start_date.' 00:00:00'; //From beginning of the day
        }
        $top_users = $this->Links_model->ln_fetch($filters, array('en_miner'), $top, 0, array('points_sum' => 'DESC'), 'COUNT(ln_miner_entity_id) as trs_count, SUM(ln_points) as points_sum, en_name, en_icon, ln_miner_entity_id', 'ln_miner_entity_id, en_name, en_icon');

        if(count($top_users) < $top){
            $top = count($top_users);
        }

        echo '<table class="table table-condensed table-striped stats-table">';

        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;">'.$en_all_7161[7163]['m_name'].'</td>';
        echo '<td style="text-align: right;">'.( !is_null($days_ago) ? $days_ago.'-Day ' : '' ).'Points</td>';
        echo '</tr>';

        foreach ($top_users as $count=>$ln) {
            echo '<tr>';
            echo '<td style="text-align: left;"><span style="width:29px; display: inline-block; text-align: center; '.( $count > 2 ? 'font-size:0.8em;' : '' ).'">'.echo_rank($count+1).'</span><span class="parent-icon" style="width: 29px; display: inline-block; text-align: center;">'.echo_icon($ln).'</span><a href="/entities/'.$ln['ln_miner_entity_id'].'">'.$ln['en_name'].'</a></td>';
            echo '<td style="text-align: right;"><a href="/links?ln_miner_entity_id='.$ln['ln_miner_entity_id'].( is_null($days_ago) ? '' : '&start_range='.$start_date ).'"  data-toggle="tooltip" title="Mined with '.number_format($ln['trs_count'],0).' links averaging '.round(($ln['points_sum']/$ln['trs_count']),1).' points/link" data-placement="top">'.number_format($ln['points_sum'], 0).'</a></td>';
            echo '</tr>';
        }
        echo '</table>';



    }
    function extra_stats_links(){


        $en_all_4534 = $this->config->item('en_all_4534');
        $fixed_fields = $this->config->item('fixed_fields');
        $en_all_4463 = $this->config->item('en_all_4463'); //Platform Glossary
        $en_all_4593 = $this->config->item('en_all_4593'); //Load all link types
        $en_all_7161 = $this->config->item('en_all_7161'); //Platform Dashboard



        //Link Status:
        echo '<table class="table table-condensed table-striped stats-table mini-stats-table">';

        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;">'.count($this->config->item('en_ids_6186')).' '.$en_all_7161[6186]['m_name'].'</td>';
        echo '<td style="text-align: right;">Links</td>';
        echo '</tr>';

        //Object Stats grouped by Status:
        $objects_count = $this->Links_model->ln_fetch(array(), array(), 0, 0, array(), 'ln_status, COUNT(ln_id) as totals', 'ln_status');
        foreach ($fixed_fields['ln_status'] as $status_num => $status) {
            //Count this status:
            $objects_count = $this->Links_model->ln_fetch(array(
                'ln_status' => $status_num
            ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');

            //Display this status count:
            echo '<tr class="'.( $status_num < 0 ? 'is-removed' : '' ).'">';
            echo '<td style="text-align: left;"><span style="width:29px; display: inline-block; text-align: center;">' . $status['s_icon'] . '</span><a href="/entities/'.$status['s_en_id'].'">' . $status['s_name'] . '</a> <i class="fal fa-info-circle" data-toggle="tooltip" title="' . $status['s_desc'] . '" data-placement="top"></i></td>';
            echo '<td style="text-align: right;">' . ($objects_count[0]['totals'] > 0 ? '<a href="/links?ln_status=' . $status_num . '"  data-toggle="tooltip" title="View Links" data-placement="top">' . number_format($objects_count[0]['totals'], 0) . '</a>' : $objects_count[0]['totals']) . '</td>';
            echo '</tr>';

        }
        echo '</table>';



        //Links User Engagement Stats
        echo echo_link_type_group_stats(7159);


        //All Link Types:
        $all_link_types = $this->Links_model->ln_fetch(array('ln_status >=' => 0), array('en_type'), 0, 0, array('en_name' => 'ASC'), 'COUNT(ln_type_entity_id) as trs_count, en_name, en_icon, ln_type_entity_id', 'ln_type_entity_id, en_name, en_icon');

        echo '<table class="table table-condensed table-striped stats-table mini-stats-table">';

        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;">'.count($all_link_types).' '.$en_all_7161[4593]['m_name'].'</td>';
        echo '<td style="text-align: right;">Points</td>';
        echo '<td style="text-align: right;">Links</td>';
        echo '</tr>';

        foreach ($all_link_types as $ln) {

            //Echo stats:
            echo '<tr>';
            echo '<td style="text-align: left;"><span class="icon-block">'.echo_icon($ln).'</span><a href="/entities/'.$ln['ln_type_entity_id'].'">'.$ln['en_name'].'</a>';

            //Does it have a description?
            echo ( strlen($en_all_4593[$ln['ln_type_entity_id']]['m_desc']) > 0 ? ' <i class="fal fa-info-circle" data-toggle="tooltip" title="'.$en_all_4593[$ln['ln_type_entity_id']]['m_desc'].'" data-placement="top"></i>' : '' );

            //Is it a private link?
            echo ( in_array($ln['ln_type_entity_id'] , $this->config->item('en_ids_4755')) ? ' <span data-toggle="tooltip" title="'.$en_all_4463[4755]['m_name'].': '.$en_all_4463[4755]['m_desc'].'" data-placement="top">'.$en_all_4463[4755]['m_icon'].'</span>' : '' );

            echo '</td>';


            //Current Points Rate
            $fetch_points = fetch_points($ln['ln_type_entity_id']);
            echo '<td style="text-align: right;">'.( $fetch_points > 0 ? '<a href="/links?ln_status=2&ln_parent_entity_id=4595&ln_type_entity_id=4319&ln_child_entity_id='.$ln['ln_type_entity_id'].'" data-toggle="tooltip" title="Points per link" data-placement="top">'.number_format($fetch_points, 0).'</a>' : '0' ).'</td>';


            //Links count:
            echo '<td style="text-align: right;"><a href="/links?ln_status=0,1,2&ln_type_entity_id='.$ln['ln_type_entity_id'].'">'.number_format($ln['trs_count'], 0).'</a></td>';

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
            'ln_status' => 2, //Published
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'ln_parent_entity_id' => 4527,
        ), array('en_child'), 0);

        echo '//Generated '.date("Y-m-d H:i:s").' PST<br />';

        foreach($config_ens as $en){

            //Now fetch all its children:
            $children = $this->Links_model->ln_fetch(array(
                'ln_status' => 2, //Published
                'en_status' => 2, //Published
                'ln_parent_entity_id' => $en['ln_child_entity_id'],
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            ), array('en_child'), 0, 0, array('ln_order' => 'ASC', 'en_id' => 'ASC'));


            $child_ids = array();
            foreach($children as $child){
                array_push($child_ids , $child['en_id']);
            }

            echo '<br />//'.$en['en_name'].':<br />';
            echo '$config[\'en_ids_'.$en['ln_child_entity_id'].'\'] = array('.join(', ',$child_ids).');<br />';
            echo '$config[\'en_all_'.$en['ln_child_entity_id'].'\'] = array(<br />';
            foreach($children as $child){

                //Do we have an omit command?
                if(substr_count($en['ln_content'], '&var_trimcache=') == 1){
                    $child['en_name'] = trim(str_replace(one_two_explode('&var_trimcache=','',$en['ln_content']) , '', $child['en_name']));
                }

                //Fetch all parents for this child:
                $child_parent_ids = array(); //To be populated soon
                $child_parents = $this->Links_model->ln_fetch(array(
                    'ln_status' => 2, //Published
                    'en_status' => 2, //Published
                    'ln_child_entity_id' => $child['en_id'],
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                ), array('en_parent'), 0);
                foreach($child_parents as $cp_en){
                    array_push($child_parent_ids, $cp_en['en_id']);
                }

                echo '&nbsp;&nbsp;&nbsp;&nbsp; '.$child['en_id'].' => array(<br />';

                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_icon\' => \''.htmlentities($child['en_icon']).'\',<br />';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_name\' => \''.$child['en_name'].'\',<br />';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_desc\' => \''.str_replace('\'','\\\'',$child['ln_content']).'\',<br />';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_parents\' => array('.join(', ',$child_parent_ids).'),<br />';

                echo '&nbsp;&nbsp;&nbsp;&nbsp; ),<br />';

            }
            echo ');<br />';
        }
    }

}