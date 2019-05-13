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
        $fixed_fields = $this->config->item('fixed_fields');


        //Intent Statuses:
        echo '<h6>LAST UPDATED: '.date("Y-m-d H:i:s").' PST</h6>';



        echo '<h4 class="panel-title">4 Statuses</h4>';
        echo '<table class="table table-condensed table-striped stats-table mini-stats-table">';
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
            $this_ui .= '<td style="text-align: right;">' . ($objects_count[0]['totals'] > 0 ? '<a href="/links?in_status=' . $status_num . '&ln_type_entity_id=4250"  data-toggle="tooltip" title="View Links" data-placement="top">' . number_format($objects_count[0]['totals'], 0) . '</a>' : $objects_count[0]['totals']) . ' ' . $en_all_4534[4535]['m_icon'] . '</td>';
            $this_ui .= '</tr>';

        }
        echo $this_ui;
        echo '</table>';



        //Intent Types:
        echo '<h4 class="panel-title">2 Types & '.count(array_merge($this->config->item('en_ids_6192'), $this->config->item('en_ids_6193'))).' Sub-Types</h4>';
        echo '<table class="table table-condensed table-striped stats-table mini-stats-table ">';


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
                $sub_types_ui .= '<td style="text-align: right;"><a href="/links?ln_type_entity_id=4250&in_status=0,1,2&in_type_entity_id='.$sub_type_en_id.'">'.number_format($in_count[0]['total_active_intents'],0).'</a> <i class="fas fa-hashtag"></i></td>';
                $sub_types_ui .= '</tr>';

            }


            //Echo this as the main title:
            $types_ui .= '<tr style="font-weight: bold;">';
            $types_ui .= '<td style="text-align: left;"><span style="width:29px; display: inline-block; text-align: center;">'.$type['m_icon'].'</span><a href="/entities/'.$type_en_id.'">'.$type['m_name'].'</a> <i class="fal fa-info-circle" data-toggle="tooltip" title="'.$type['m_desc'].'" data-placement="top"></i></td>';
            $types_ui .= '<td style="text-align: right;"><a href="/links?ln_type_entity_id=4250&in_status=0,1,2&in_type_entity_id='.join(',',$this->config->item('en_ids_'.$type_en_id)).'">'.number_format($all_intent_types,0).'</a> <i class="fas fa-hashtag"></i></td>';
            $types_ui .= '</tr>';

            //Add sub-types:
            $types_ui .= $sub_types_ui;
        }

        echo $types_ui;
        echo '</table>';




        //Intent Verbs:
        $show_max_verbs = 10;

        //Fetch all needed data:
        $in_verbs = $this->Intents_model->in_fetch(array(
            'in_status >=' => 0, //New+
        ), array('in_verb_entity_id'), 0, 0, array('totals' => 'DESC'), 'COUNT(in_id) as totals, in_verb_entity_id, en_name', 'in_verb_entity_id, en_name');

        echo '<h4 class="panel-title">'.count($in_verbs).' Starting Verbs</h4>';
        echo '<table class="table table-condensed table-striped stats-table mini-stats-table ">';
        foreach($in_verbs as $count => $verb){
            echo '<tr class="'.( $count >= $show_max_verbs ? 'hiddenverbs hidden' : '' ).'">';
            echo '<td style="text-align: left;"><a href="/entities/'.$verb['in_verb_entity_id'].'">'.$verb['en_name'].'</a></td>';
            echo '<td style="text-align: right;"><a href="/links?ln_type_entity_id=4250&in_status=0,1,2&in_verb_entity_id='.$verb['in_verb_entity_id'].'">'.number_format($verb['totals'],0).'</a> <i class="fas fa-hashtag"></i></td>';
            echo '</tr>';
        }
        echo '</table>';
        //Show expand button:
        echo '<div style="margin:-10px 0 0 0; padding: 0;"><a href="javascript:void(0);" onclick="$(\'.hiddenverbs\').toggleClass(\'hidden\')" class="hiddenverbs"><i class="fal fa-plus-circle"></i> See All '.number_format(count($in_verbs),0).' Verbs</a></div>';

    }


    function extra_stats_entities(){

        $en_all_4534 = $this->config->item('en_all_4534');
        $fixed_fields = $this->config->item('fixed_fields');



        echo '<h6>LAST UPDATED: '.date("Y-m-d H:i:s").' PST</h6>';



        //Entity Status:
        $objects_count = $this->Entities_model->en_fetch(array(), array('skip_en__parents'), 0, 0, array(), 'en_status, COUNT(en_id) as totals', 'en_status');
        echo '<h4 class="panel-title">4 Statuses</h4>';
        echo '<table class="table table-condensed table-striped stats-table mini-stats-table">';
        //Object Stats grouped by Status:
        foreach ($fixed_fields['en_status'] as $status_num => $status) {

            //Count this status:
            $objects_count = $this->Entities_model->en_fetch(array(
                'en_status' => $status_num
            ), array(), 0, 0, array(), 'COUNT(en_id) as totals');

            //Display this status count:
            echo '<tr class="'.( $status_num < 0 ? 'is-removed' : '' ).'">';
            echo '<td style="text-align: left;"><span style="width:29px; display: inline-block; text-align: center;">' . $status['s_icon'] . '</span><a href="/entities/'.$status['s_en_id'].'">' . $status['s_name'] . '</a> <i class="fal fa-info-circle" data-toggle="tooltip" title="' . $status['s_desc'] . '" data-placement="top"></i></td>';
            echo '<td style="text-align: right;">' . ($objects_count[0]['totals'] > 0 ? '<a href="/links?en_status=' . $status_num . '&ln_type_entity_id=4251"  data-toggle="tooltip" title="View Links" data-placement="top">' . number_format($objects_count[0]['totals'], 0) . '</a>' : $objects_count[0]['totals']) . ' ' . $en_all_4534[4536]['m_icon'] . '</td>';
            echo '</tr>';

        }
        echo '</table>';




        //Mench Users:
        $people_group_ui = '';
        $all_users = 0;
        foreach($this->config->item('en_all_4432') as $group_en_id=>$people_group) {

            //Do a child count:
            $child_links = $this->Links_model->ln_fetch(array(
                'ln_parent_entity_id' => $group_en_id,
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                'ln_status >=' => 0, //New+
                'en_status >=' => 0, //New+
            ), array('en_child'), 0, 0, array(), 'COUNT(en_id) as en__child_count');

            $people_group_ui .= '<tr>';
            $people_group_ui .= '<td style="text-align: left;"><span style="width: 26px; display: inline-block; text-align: center;">' . $people_group['m_icon'] . '</span><a href="/entities/'.$group_en_id.'">' . $people_group['m_name'] . '</a> <i class="fal fa-info-circle" data-toggle="tooltip" data-placement="top" title="'.$people_group['m_desc'].'"></i></td>';
            $people_group_ui .= '<td style="text-align: right;"><a href="/links?ln_status=0,1,2&ln_type_entity_id='.join(',', $this->config->item('en_ids_4592')).'&ln_parent_entity_id=' . $group_en_id . '">' . number_format($child_links[0]['en__child_count'], 0) . '</a> <i class="fas fa-at"></i></td>';
            $people_group_ui .= '</tr>';

            if($group_en_id==4430){
                //This is all users!
                $all_users = $child_links[0]['en__child_count'];
            }

        }

        echo '<h4 class="panel-title">'.number_format($all_users, 0).' Users</h4>';
        echo '<table class="table table-condensed table-striped stats-table">';
        echo $people_group_ui;
        echo '</table>';






        //Expert Sources:
        $ie_ens = $this->Entities_model->en_fetch(array(
            'en_id' => 3000, //Industry Expert Sources
            'en_status >=' => 0, //New+
        ), array('en__children'), 0, 0, array('en_name' => 'ASC'));

        $expert_source_types = 0;
        $all_source_count = 0;
        $all_source_count_weight = 0;
        $all_mined_source_count = 0;
        $all_mined_source_count_weigh = 0;
        $expert_sources = ''; //Saved the UI for later view...

        foreach ($ie_ens[0]['en__children'] as $source_en) {

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
            $expert_sources .= '<td style="text-align: left;"><span style="width: 26px; display: inline-block; text-align: center;">'.( strlen($source_en['en_icon']) > 0 ? $source_en['en_icon'] : '<i class="fas fa-at grey-at"></i>' ).'</span><a href="/entities/'.$source_en['en_id'].'">'.$source_en['en_name'].'</a><span data-toggle="tooltip" title="'.number_format($mined_source_count,0).'/'.number_format($source_count,0).' '.$source_en['en_name'].' have been mined completely" data-placement="top" class="underdot" style="font-size:0.7em; margin-left:5px;">'.number_format(($mined_source_count/$source_count*100), 1).'% Mined</span></td>';
            $expert_sources .= '<td style="text-align: right;"><a href="/links?ln_status=0,1,2&ln_type_entity_id='.join(',', $this->config->item('en_ids_4592')).'&ln_parent_entity_id=' . $source_en['en_id'].'">'.number_format($source_count, 0).'</a> <i class="fas fa-at"></i></td>';
            $expert_sources .= '</tr>';

        }


        echo '<h4 class="panel-title">'.echo_number($all_source_count).' Expert Sources <span data-toggle="tooltip" title="'.number_format($all_mined_source_count_weigh,0).'/'.number_format($all_source_count_weight,0).' expert sources have been mined completely" data-placement="top" class="underdot" style="font-size:0.7em; margin-left:5px;">'.($all_source_count_weight > 0 ? number_format(($all_mined_source_count_weigh/$all_source_count_weight*100), 1) : 0).'% Mined</span></h4>';
        echo '<table class="table table-condensed table-striped stats-table">';
        echo $expert_sources;
        echo '</table>';






        //Count industry experts:
        $child_links = $this->Links_model->ln_fetch(array(
            'ln_parent_entity_id' => 3084,
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'ln_status >=' => 0, //New+
            'en_status >=' => 0, //New+
        ), array('en_child'), 0, 0, array(), 'COUNT(en_id) as en__child_count');



        //List Top Industry Experts:
        $en_all_4463 = $this->config->item('en_all_4463'); //Platform Glossary
        $fetch_top = 10;
        echo '<h4 class="panel-title">'.number_format($child_links[0]['en__child_count'], 0).' Industry Experts</h4>';
        echo '<table class="table table-condensed table-striped stats-table">';
        foreach ($this->Links_model->ln_fetch(array(
            'ln_parent_entity_id' => 3084,
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'ln_status >=' => 0, //New+
            'en_status >=' => 0, //New+
        ), array('en_child'), $fetch_top, 0, array('en_trust_score' => 'DESC')) as $count=>$ln) {

            //Count total sources:
            echo '<tr>';
            echo '<td style="text-align: left;"><span class="parent-icon" style="width: 29px; display: inline-block; text-align: center;">'.( strlen($ln['en_icon']) > 0 ? $ln['en_icon'] : '<i class="fas fa-at grey-at"></i>' ).'</span><a href="/entities/'.$ln['en_id'].'">'.$ln['en_name'].'</a></td>';
            echo '<td style="text-align: right;"><a href="/links?any_en_id='.$ln['en_id'].'">'.$ln['en_trust_score'].'</a> <span data-toggle="tooltip" title="'.$en_all_4463[6199]['m_name'].': '.$en_all_4463[6199]['m_desc'].'" data-placement="top">'.$en_all_4463[6199]['m_icon'].'</span></td>';
            echo '</tr>';
        }
        echo '</table>';

        //Show expand button:
        echo '<div style="margin:-10px 0 0 0; padding: 0;"><a href="/entities/3084">Browse All '.number_format($child_links[0]['en__child_count'], 0).' Industry Experts <i class="fas fa-long-arrow-right"></i></a></div>';



    }
    function extra_stats_links(){


        $en_all_4534 = $this->config->item('en_all_4534');
        $fixed_fields = $this->config->item('fixed_fields');
        $en_all_4463 = $this->config->item('en_all_4463'); //Platform Glossary
        $en_all_4593 = $this->config->item('en_all_4593'); //Load all link types



        echo '<h6>LAST UPDATED: '.date("Y-m-d H:i:s").' PST</h6>';



        //Link Status:
        echo '<h4 class="panel-title">4 Statuses</h4>';
        echo '<table class="table table-condensed table-striped stats-table mini-stats-table">';
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
            echo '<td style="text-align: right;">' . ($objects_count[0]['totals'] > 0 ? '<a href="/links?ln_status=' . $status_num . '"  data-toggle="tooltip" title="View Links" data-placement="top">' . number_format($objects_count[0]['totals'], 0) . '</a>' : $objects_count[0]['totals']) . ' ' . $en_all_4534[6205]['m_icon'] . '</td>';
            echo '</tr>';

        }
        echo '</table>';





        //Top Miners:
        $top = 7;
        $days_ago = null;
        $filters = array(
            'ln_points !=' => 0,
        );
        if(!is_null($days_ago)){
            $start_date = date("Y-m-d" , (time() - ($days_ago * 24 * 3600)));
            $filters['ln_timestamp >='] = $start_date.' 00:00:00'; //From beginning of the day
        }
        echo '<h4 class="panel-title">Top '.$top.' Miners</h4>';
        echo '<table class="table table-condensed table-striped stats-table">';
        foreach ($this->Links_model->ln_fetch($filters, array('en_miner'), $top, 0, array('points_sum' => 'DESC'), 'COUNT(ln_miner_entity_id) as trs_count, SUM(ln_points) as points_sum, en_name, en_icon, ln_miner_entity_id', 'ln_miner_entity_id, en_name, en_icon') as $count=>$ln) {
            echo '<tr>';
            echo '<td style="text-align: left;"><span style="width:29px; display: inline-block; text-align: center; '.( $count > 2 ? 'font-size:0.8em;' : '' ).'">'.echo_rank($count+1).'</span><span class="parent-icon" style="width: 29px; display: inline-block; text-align: center;">'.( strlen($ln['en_icon']) > 0 ? $ln['en_icon'] : '<i class="fas fa-at grey-at"></i>' ).'</span><a href="/entities/'.$ln['ln_miner_entity_id'].'">'.$ln['en_name'].'</a></td>';
            echo '<td style="text-align: right;"><a href="/links?ln_miner_entity_id='.$ln['ln_miner_entity_id'].( is_null($days_ago) ? '' : '&start_range='.$start_date ).'"  data-toggle="tooltip" title="Mined with '.number_format($ln['trs_count'],0).' links averaging '.round(($ln['points_sum']/$ln['trs_count']),1).' points/link" data-placement="top">'.number_format($ln['points_sum'], 0).'</a> <i class="fas fa-award"></i></td>';
            echo '</tr>';
        }
        echo '</table>';





        //All Link Types:
        $all_link_types = $this->Links_model->ln_fetch(array('ln_status >=' => 0), array('en_type'), 0, 0, array('en_name' => 'ASC'), 'COUNT(ln_type_entity_id) as trs_count, en_name, en_icon, ln_type_entity_id', 'ln_type_entity_id, en_name, en_icon');
        echo '<h4 class="panel-title">'.count($all_link_types).' Link Types</h4>';
        echo '<table class="table table-condensed table-striped stats-table mini-stats-table">';
        foreach ($all_link_types as $ln) {

            //Echo stats:
           echo '<tr>';
           echo '<td style="text-align: left;"><span style="width: 26px; display: inline-block; text-align: center;">'.( strlen($ln['en_icon']) > 0 ? $ln['en_icon'] : '<i class="fas fa-at grey-at"></i>' ).'</span><a href="/entities/'.$ln['ln_type_entity_id'].'">'.$ln['en_name'].'</a>';

            //Does it have a description?
           echo ( strlen($en_all_4593[$ln['ln_type_entity_id']]['m_desc']) > 0 ? ' <i class="fal fa-info-circle" data-toggle="tooltip" title="'.$en_all_4593[$ln['ln_type_entity_id']]['m_desc'].'" data-placement="top"></i>' : '' );

            //Is it a private link?
           echo ( in_array($ln['ln_type_entity_id'] , $this->config->item('en_ids_4755')) ? ' <span data-toggle="tooltip" title="'.$en_all_4463[4755]['m_name'].': '.$en_all_4463[4755]['m_desc'].'" data-placement="top">'.$en_all_4463[4755]['m_icon'].'</span>' : '' );

           echo '</td>';

           echo '<td style="text-align: right;"><a href="/links?ln_type_entity_id='.$ln['ln_type_entity_id'].'">'.number_format($ln['trs_count'], 0).'</a> <i class="fas fa-link rotate90"></i></td>';
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


    function cron__clean_metadatas(){

        /*
         *
         * A function that would run through all
         * object metadata variables and remove
         * all variables that are not indexed
         * as part of Variables Names entity @6232
         *
         * https://mench.com/entities/6232
         *
         *
         * */

        boost_power();

        //Fetch all valid variable names:
        $valid_variables = array();
        foreach($this->Links_model->ln_fetch(array(
            'ln_parent_entity_id' => 6232, //Variables Names
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'ln_status' => 2, //Published
            'en_status' => 2, //Published
            'LENGTH(ln_content) > 0' => null,
        ), array('en_child'), 0) as $var_name){
            array_push($valid_variables, $var_name['ln_content']);
        }

        //Now let's start the cleanup process...
        $invalid_variables = array();

        //Intent Metadata
        foreach($this->Intents_model->in_fetch(array()) as $in){

            if(strlen($in['in_metadata']) < 1){
                continue;
            }

            foreach(unserialize($in['in_metadata']) as $key => $value){
                if(!in_array($key, $valid_variables)){
                    //Remove this:
                    update_metadata('in', $in['in_id'], array(
                        $key => null,
                    ));

                    //Add to index:
                    if(!in_array($key, $invalid_variables)){
                        array_push($invalid_variables, $key);
                    }
                }
            }

        }

        //Entity Metadata
        foreach($this->Entities_model->en_fetch(array()) as $en){

            if(strlen($en['en_metadata']) < 1){
                continue;
            }

            foreach(unserialize($en['en_metadata']) as $key => $value){
                if(!in_array($key, $valid_variables)){
                    //Remove this:
                    update_metadata('en', $en['en_id'], array(
                        $key => null,
                    ));

                    //Add to index:
                    if(!in_array($key, $invalid_variables)){
                        array_push($invalid_variables, $key);
                    }
                }
            }

        }

        $ln_metadata = array(
            'invalid' => $invalid_variables,
            'valid' => $valid_variables,
        );

        if(count($invalid_variables) > 0){
            //Did we have anything to remove? Report with system bug:
            $this->Links_model->ln_create(array(
                'ln_content' => 'cron__clean_metadatas() removed '.count($invalid_variables).' unknown variables from intent/entity metadatas. To prevent this from happening, register the variables via Variables Names @6232',
                'ln_type_entity_id' => 4246, //Platform Bug Reports
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_parent_entity_id' => 6232, //Variables Names
                'ln_metadata' => $ln_metadata,
            ));
        }

        echo_json($ln_metadata);

    }

}