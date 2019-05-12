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


    function load_basic_stats(){

        //Return stats for the platform home page:

        //Count three objects and return:
        $in_count = $this->Intents_model->in_fetch(array(), array(), 0, 0, array(), 'COUNT(in_id) as total_active_intents');
        $en_count = $this->Entities_model->en_fetch(array(), array(), 0, 0, array(), 'COUNT(en_id) as total_active_entities');
        $ln_count = $this->Links_model->ln_fetch(array(), array(), 0, 0, array(), 'COUNT(ln_id) as total__links');

        return echo_json(array(
            'intents' => array(
                'extended_stats' => number_format($in_count[0]['total_active_intents']),
            ),
            'entities' => array(
                'extended_stats' => number_format($en_count[0]['total_active_entities']),
            ),
            'links' => array(
                'extended_stats' => number_format($ln_count[0]['total__links']),
            )
        ));

    }

    function load_extra_stats($object_id){


        $en_all_4534 = $this->config->item('en_all_4534');
        $fixed_fields = $this->config->item('fixed_fields');




        //Return extra stats for the home page:
        if($object_id=='intents'){

            $obj_en_id = 4535; //Intents
            $created_en_type_id = 4250;
            $objects_count = $this->Intents_model->in_fetch(array(), array(), 0, 0, array(), 'in_status, COUNT(in_id) as totals', 'in_status');

            //Fetch all needed data:
            $in_verbs = $this->Intents_model->in_fetch(array(
                'in_status >=' => 0, //New+
            ), array('in_verb_entity_id'), 0, 0, array('totals' => 'DESC'), 'COUNT(in_id) as totals, in_verb_entity_id, en_name', 'in_verb_entity_id, en_name');


            //Intent Statuses:
            echo '<h6>LAST UPDATED: '.date("Y-m-d H:i:s").' PST</h6>';
            echo '<h4 class="panel-title">4 Statuses</h4>';
            echo '<table class="table table-condensed table-striped stats-table mini-stats-table">';
            //Object Stats grouped by Status:
            $this_ui = '';
            foreach ($fixed_fields['in_status'] as $status_num => $status) {
                //Count this status:
                $objects_count = $this->Intents_model->in_fetch(array(
                    'in_status' => $status_num
                ), array(), 0, 0, array(), 'COUNT(in_id) as totals');

                //Display this status count:
                $this_ui .= '<tr>';
                $this_ui .= '<td style="text-align: left;"><span style="width:29px; display: inline-block; text-align: center;">' . $status['s_icon'] . '</span><span class="underdot" data-toggle="tooltip" title="' . $status['s_desc'] . '" data-placement="top">' . $status['s_name'] . '</span></td>';
                $this_ui .= '<td style="text-align: right;">' . ($objects_count[0]['totals'] > 0 ? '<a href="/links?' . $object_id . '=' . $status_num . '&ln_type_entity_id=' . $created_en_type_id . '"  data-toggle="tooltip" title="View Links" data-placement="top">' . number_format($objects_count[0]['totals'], 0) . '</a>' : $objects_count[0]['totals']) . ' ' . $en_all_4534[$obj_en_id]['m_icon'] . '</td>';
                $this_ui .= '</tr>';

            }
            echo $this_ui;
            echo '</table>';



            //Intent Types:
            echo '<h4 class="panel-title">2 Active Intent Types</h4>';
            echo '<table class="table table-condensed table-striped stats-table mini-stats-table ">';
            foreach(echo_fixed_fields('in_type') as $in_type_id => $in_type){

                //Count this type:
                $in_types = $this->Intents_model->in_fetch(array(
                    'in_status >=' => 0, //New+
                    'in_type' => $in_type_id,
                ), array(), 0, 0, array(), 'COUNT(in_id) as totals');

                echo '<tr>';
                echo '<td style="text-align: left;"><span style="width:29px; display: inline-block; text-align: center;">'.$in_type['s_icon'].'</span><span class="underdot" data-toggle="tooltip" title="'.$in_type['s_desc'].'" data-placement="top">'.$in_type['s_name'].'</span></td>';
                echo '<td style="text-align: right;">'.number_format($in_types[0]['totals'],0).' <i class="fas fa-hashtag"></i></td>';
                echo '</tr>';

            }
            echo '</table>';




            //Intent Completion Methods:
            echo '<h4 class="panel-title">'.count($this->config->item('en_all_4331')).' Active Completion Methods</h4>';
            echo '<table class="table table-condensed table-striped stats-table mini-stats-table ">';
            foreach($this->config->item('en_all_4331') as $completion_en_id => $completion_method){

                //Count this method:
                $in_types = $this->Intents_model->in_fetch(array(
                    'in_status >=' => 0, //New+
                    'in_requirement_entity_id' => $completion_en_id,
                ), array(), 0, 0, array(), 'COUNT(in_id) as totals');

                echo '<tr>';
                echo '<td style="text-align: left;"><span style="width:29px; display: inline-block; text-align: center;">'.$completion_method['m_icon'].'</span>'.$completion_method['m_name'].' Required</td>';
                echo '<td style="text-align: right;">'.number_format($in_types[0]['totals'],0).' <i class="fas fa-hashtag"></i></td>';
                echo '</tr>';

            }
            echo '</table>';




            //Intent Verbs:
            $show_max_verbs = 6;
            echo '<h4 class="panel-title">'.count($in_verbs).' Active Verbs</h4>';
            echo '<table class="table table-condensed table-striped stats-table mini-stats-table ">';
            foreach($in_verbs as $count => $verb){
                echo '<tr class="'.( $count >= $show_max_verbs ? 'hiddenverbs hidden' : '' ).'">';
                echo '<td style="text-align: left;">'.$verb['en_name'].'</td>';
                echo '<td style="text-align: right;"><a href="/links?ln_type_entity_id=4250&in_verb_entity_id='.$verb['in_verb_entity_id'].'"  data-toggle="tooltip" title="View Intents starting with this verb" data-placement="top">'.number_format($verb['totals'],0).'</a> <i class="fas fa-hashtag"></i></td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '<a href="javascript:void(0);" onclick="$(\'.hiddenverbs\').toggleClass(\'hidden\')" class="hiddenverbs" >Show Remaining '.number_format((count($in_verbs)-$show_max_verbs),0).' Verbs</a>';


        } elseif($object_id=='entities'){

            $obj_en_id = 4536; //Entities
            $created_en_type_id = 4251;
            $objects_count = $this->Entities_model->en_fetch(array(), array('skip_en__parents'), 0, 0, array(), 'en_status, COUNT(en_id) as totals', 'en_status');


            //Expert Sources:
            $ie_ens = $this->Entities_model->en_fetch(array(
                'en_id' => 3000, //Industry Expert Sources
            ), array('en__children'), 0, 0, array('en_name' => 'ASC'));


            $expert_source_types = 0;
            $all_source_count = 0;
            $all_source_count_weight = 0;
            $all_mined_source_count = 0;
            $all_mined_source_count_weigh = 0;
            $expert_sources = '';

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
                $expert_sources .= '<td style="text-align: left;"><span style="width: 26px; display: inline-block; text-align: center;">'.( strlen($source_en['en_icon']) > 0 ? $source_en['en_icon'] : '<i class="fas fa-at grey-at"></i>' ).'</span>'.$source_en['en_name'].'<span data-toggle="tooltip" title="'.number_format($mined_source_count,0).'/'.number_format($source_count,0).' '.$source_en['en_name'].' have been mined completely" data-placement="top" class="underdot" style="font-size:0.7em; margin-left:5px;">'.number_format(($mined_source_count/$source_count*100), 1).'%</span></td>';
                $expert_sources .= '<td style="text-align: right;"><a href="/entities/'.$source_en['en_id'].'" data-toggle="tooltip" title="View all '.$source_count.' '.strtolower($source_en['en_name']).'" data-placement="top">'.number_format($source_count, 0).'</a> <i class="fas fa-at"></i></td>';
                $expert_sources .= '</tr>';

            }
            $expert_sources .= '<tr style="font-weight: bold;">';
            $expert_sources .= '<td style="text-align:left;"><span style="width: 26px; display: inline-block; text-align: center;"><i class="fas fa-asterisk"></i></span>All '.$ie_ens[0]['en_name'].'<span data-toggle="tooltip" title="'.number_format($all_mined_source_count_weigh,0).'/'.number_format($all_source_count_weight,0).' expert sources have been mined completely" data-placement="top" class="underdot" style="font-size:0.7em; margin-left:5px;">'.($all_source_count_weight > 0 ? number_format(($all_mined_source_count_weigh/$all_source_count_weight*100), 1) : 0).'%</span>&nbsp;</td>';
            $expert_sources .= '<td style="text-align: right;"><a href="/entities/3000">'.number_format($all_source_count, 0).'</a> <i class="fas fa-at"></i></td>';
            $expert_sources .= '</tr>';




            $all_people = 0;
            $people_group_ui = '';
            foreach($this->config->item('en_all_4432') as $group_en_id=>$people_group){

                //Do a child count:
                $child_links = $this->Links_model->ln_fetch(array(
                    'ln_parent_entity_id' => $group_en_id,
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                    'ln_status >=' => 0, //New+
                    'en_status >=' => 0, //New+
                ), array('en_child'), 0, 0, array(), 'COUNT(en_id) as en__child_count');

                $all_people += $child_links[0]['en__child_count'];

                $people_group_ui .= '<tr>';
                $people_group_ui .= '<td style="text-align: left;"><span style="width: 26px; display: inline-block; text-align: center;">'.$people_group['m_icon'].'</span>'.$people_group['m_name'].'</td>';
                $people_group_ui .= '<td style="text-align: right;"><a href="/entities/'.$group_en_id.'" data-toggle="tooltip" title="View all '.$child_links[0]['en__child_count'].' members" data-placement="top">'.number_format($child_links[0]['en__child_count'], 0).'</a> <i class="fas fa-at"></i></td>';
                $people_group_ui .= '</tr>';
            }

            $people_group_ui .= '<tr style="font-weight: bold;">';
            $people_group_ui .= '<td style="text-align:left;"><span style="width: 26px; display: inline-block; text-align: center;"><i class="fas fa-asterisk"></i></span>All People</td>';
            $people_group_ui .= '<td style="text-align: right;"><a href="/entities/4432">'.number_format($all_people, 0).'</a> <i class="fas fa-at"></i></td>';
            $people_group_ui .= '</tr>';



            echo '<h6>LAST UPDATED: '.date("Y-m-d H:i:s").' PST</h6>';


            //Entity Status:
            echo '<h4 class="panel-title">4 Statuses</h4>';
            echo '<table class="table table-condensed table-striped stats-table mini-stats-table">';
            //Object Stats grouped by Status:
            $this_ui = '';
            foreach ($fixed_fields['en_status'] as $status_num => $status) {
                //Count this status:
                $objects_count = $this->Entities_model->en_fetch(array(
                    'en_status' => $status_num
                ), array(), 0, 0, array(), 'COUNT(en_id) as totals');

                //Display this status count:
                $this_ui .= '<tr>';
                $this_ui .= '<td style="text-align: left;"><span style="width:29px; display: inline-block; text-align: center;">' . $status['s_icon'] . '</span><span class="underdot" data-toggle="tooltip" title="' . $status['s_desc'] . '" data-placement="top">' . $status['s_name'] . '</span></td>';
                $this_ui .= '<td style="text-align: right;">' . ($objects_count[0]['totals'] > 0 ? '<a href="/links?' . $object_id . '=' . $status_num . '&ln_type_entity_id=' . $created_en_type_id . '"  data-toggle="tooltip" title="View Links" data-placement="top">' . number_format($objects_count[0]['totals'], 0) . '</a>' : $objects_count[0]['totals']) . ' ' . $en_all_4534[$obj_en_id]['m_icon'] . '</td>';
                $this_ui .= '</tr>';

            }
            echo $this_ui;
            echo '</table>';


            //Expert Sources:
            echo '<h4 class="panel-title">'.echo_number($all_source_count).' Expert Sources</h4>';
            echo '<table class="table table-condensed table-striped stats-table">';
            echo $expert_sources;
            echo '</table>';


            //Community members:
            echo '<h4 class="panel-title">'.echo_number($all_people).' People</h4>';
            echo '<table class="table table-condensed table-striped stats-table">';
            echo $people_group_ui;
            echo '</table>';


        } elseif($object_id=='links'){

            $obj_en_id = 6205; //Links
            $created_en_type_id = 0; //No particular filters needed
            $objects_count = $this->Links_model->ln_fetch(array(), array(), 0, 0, array(), 'ln_status, COUNT(ln_id) as totals', 'ln_status');


            //Top Miners:
            $top = 7;
            $days_ago = null;
            $top_point_awarded = 0;
            $top_miners = ''; //For the UI table
            $filters = array(
                'ln_points !=' => 0,
            );
            if(!is_null($days_ago)){
                $start_date = date("Y-m-d" , (time() - ($days_ago * 24 * 3600)));
                $filters['ln_timestamp >='] = $start_date.' 00:00:00'; //From beginning of the day
            }
            foreach ($this->Links_model->ln_fetch($filters, array('en_miner'), $top, 0, array('points_sum' => 'DESC'), 'COUNT(ln_miner_entity_id) as trs_count, SUM(ln_points) as points_sum, en_name, en_icon, ln_miner_entity_id', 'ln_miner_entity_id, en_name, en_icon') as $count=>$ln) {
                $top_miners .= '<tr>';
                $top_miners .= '<td style="text-align: left;"><span style="width:29px; display: inline-block; text-align: center; '.( $count > 2 ? 'font-size:0.8em;' : '' ).'">'.echo_rank($count+1).'</span><span class="parent-icon" style="width: 29px; display: inline-block; text-align: center;">'.( strlen($ln['en_icon']) > 0 ? $ln['en_icon'] : '<i class="fas fa-at grey-at"></i>' ).'</span><a href="/entities/'.$ln['ln_miner_entity_id'].'">'.$ln['en_name'].'</a></td>';
                $top_miners .= '<td style="text-align: right;"><a href="/links?ln_miner_entity_id='.$ln['ln_miner_entity_id'].( is_null($days_ago) ? '' : '&start_range='.$start_date ).'"  data-toggle="tooltip" title="Mined with '.number_format($ln['trs_count'],0).' links averaging '.round(($ln['points_sum']/$ln['trs_count']),1).' points/link" data-placement="top">'.number_format($ln['points_sum'], 0).'</a> <i class="fas fa-award"></i></td>';
                $top_miners .= '</tr>';

                $top_point_awarded += $ln['points_sum'];
            }
            $top_miners .= '<tr style="font-weight: bold;">';
            $top_miners .= '<td style="text-align: left;"><span style="width: 26px; display: inline-block; text-align: center;"><i class="fas fa-asterisk"></i></span>Top '.$top.' Miners:</td>';
            $top_miners .= '<td style="text-align: right;">'.number_format($top_point_awarded, 0).' <i class="fas fa-award"></i></td>';
            $top_miners .= '</tr>';



            //Loadup the Platform Glossary:
            $en_all_4463 = $this->config->item('en_all_4463');

            //All Link Types:
            $all_eng_types = $this->Links_model->ln_fetch(array('ln_status >=' => 0), array('en_type'), 0, 0, array('en_name' => 'ASC'), 'COUNT(ln_type_entity_id) as trs_count, en_name, en_icon, ln_type_entity_id', 'ln_type_entity_id, en_name, en_icon');

            $all_link_count = 0;
            $all_ln_types = '';
            foreach ($all_eng_types as $ln) {

                //Echo stats:
                $all_ln_types .= '<tr>';
                $all_ln_types .= '<td style="text-align: left;"><span style="width: 26px; display: inline-block; text-align: center;">'.( strlen($ln['en_icon']) > 0 ? $ln['en_icon'] : '<i class="fas fa-at grey-at"></i>' ).'</span><a href="/entities/'.$ln['ln_type_entity_id'].'">'.$ln['en_name'].'</a>'.( in_array($ln['ln_type_entity_id'] , $this->config->item('en_ids_4755')) ? ' <span data-toggle="tooltip" title="'.$en_all_4463[4755]['m_name'].': '.$en_all_4463[4755]['m_desc'].'" data-placement="top">'.$en_all_4463[4755]['m_icon'].'</span>' : '' ).'</td>';
                $all_ln_types .= '<td style="text-align: right;"><a href="/links?ln_type_entity_id='.$ln['ln_type_entity_id'].'"  data-toggle="tooltip" title="View all '.number_format($ln['trs_count'],0).' links" data-placement="top">'.number_format($ln['trs_count'], 0).'</a> <i class="fas fa-link rotate90"></i></td>';
                $all_ln_types .= '</tr>';

                $all_link_count += $ln['trs_count'];

            }






            echo '<h6>LAST UPDATED: '.date("Y-m-d H:i:s").' PST</h6>';

            //Link Status:
            echo '<h4 class="panel-title">4 Statuses</h4>';
            echo '<table class="table table-condensed table-striped stats-table mini-stats-table">';
            //Object Stats grouped by Status:
            $this_ui = '';
            foreach ($fixed_fields['ln_status'] as $status_num => $status) {
                //Count this status:
                $objects_count = $this->Links_model->ln_fetch(array(
                    'ln_status' => $status_num
                ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');

                //Display this status count:
                $this_ui .= '<tr>';
                $this_ui .= '<td style="text-align: left;"><span style="width:29px; display: inline-block; text-align: center;">' . $status['s_icon'] . '</span><span class="underdot" data-toggle="tooltip" title="' . $status['s_desc'] . '" data-placement="top">' . $status['s_name'] . '</span></td>';
                $this_ui .= '<td style="text-align: right;">' . ($objects_count[0]['totals'] > 0 ? '<a href="/links?' . $object_id . '=' . $status_num . '&ln_type_entity_id=' . $created_en_type_id . '"  data-toggle="tooltip" title="View Links" data-placement="top">' . number_format($objects_count[0]['totals'], 0) . '</a>' : $objects_count[0]['totals']) . ' ' . $en_all_4534[$obj_en_id]['m_icon'] . '</td>';
                $this_ui .= '</tr>';

            }
            echo $this_ui;
            echo '</table>';



            //Point Top Miners:
            echo '<h4 class="panel-title">Top '.$top.' Miners</h4>';
            echo '<table class="table table-condensed table-striped stats-table">';
            echo $top_miners;
            echo '</table>';



            //Link Types:
            echo '<h4 class="panel-title">'.count($all_eng_types).' Link Types</h4>';
            echo '<table class="table table-condensed table-striped stats-table mini-stats-table">';
            echo $all_ln_types;
            echo '</table>';


        }

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