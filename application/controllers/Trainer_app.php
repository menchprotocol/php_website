<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trainer_app extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        //Load our buddies:
        $this->output->enable_profiler(FALSE);
    }


    function trainer_tools($action = null, $command1 = null, $command2 = null)
    {

        boost_power();

        //Validate trainer:
        $session_en = en_auth($this->config->item('en_ids_10704') /* Mench Administrators */, true);

        //Load tools:
        $this->load->view('view_trainer_app/trainer_app_header', array(
            'title' => 'Moderation Tools',
        ));
        $this->load->view('view_trainer_app/trainer_tools' , array(
            'action' => $action,
            'command1' => $command1,
            'command2' => $command2,
            'session_en' => $session_en,
        ));
        $this->load->view('view_trainer_app/trainer_app_footer');
    }


    function mench_stats()
    {
        $session_en = en_auth($this->config->item('en_ids_10691') /* Mench Trainers */);
        $en_all_7368 = $this->config->item('en_all_7368');
        $this->load->view(($session_en ? 'view_trainer_app/trainer_app_header' : 'view_user_app/user_app_header'), array(
            'title' => $en_all_7368[7161]['m_name'],
        ));
        $this->load->view('view_trainer_app/mench_stats');
        $this->load->view(($session_en ? 'view_trainer_app/trainer_app_footer' : 'view_user_app/user_app_footer'));
    }


    function php_info(){
        echo phpinfo();
    }

    function my_session()
    {
        echo_json($this->session->all_userdata());
    }


    function count_new_words_in(){

        $session_en = en_auth($this->config->item('en_ids_10691') /* Mench Trainers */);

        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Session Expired',
            ));
        }

        $last_word_in_ln_id = intval($this->session->userdata('last_word_in_ln_id'));

        //Count all new words since the last one:
        $all_stats = $this->Links_model->ln_fetch(array(
            'ln_creator_entity_id' => $session_en['en_id'],
            'ln_id>' => $last_word_in_ln_id,
            'ln_words>' => 0, //Words IN only
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        ), array(), 0, 0, array(), 'COUNT(ln_id) as total_links, SUM(ln_words) as total_words');

        if(count($all_stats)>0 && $all_stats[0]['total_words']>=1){

            //Update session:
            $this->session->set_userdata('last_word_in_ln_id', last_word_in_ln_id($session_en['en_id']));

            return echo_json(array(
                'status' => 1,
                'message' => '<b class="ispink"><i class="fas fa-file-word ispink"></i> '.number_format($all_stats[0]['total_words'], 0).' WORD'.strtoupper(echo__s(round($all_stats[0]['total_words']))).' IN</b>',
            ));

        } else {

            //Did not find any results:
            return echo_json(array(
                'status' => 0,
                'message' => 'No new words',
            ));

        }
    }


    function extra_stats_intents(){

        $en_all_7302 = $this->config->item('en_all_7302'); //Intent Stats


        //Intent Statuses:
        echo '<table class="table table-sm table-striped stats-table mini-stats-table intent_statuses '.advance_mode().'">';
        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;" colspan="2">'.$en_all_7302[4737]['m_name'].echo__s(count($this->config->item('en_all_4737')), true).'</td>';
        echo '</tr>';
        foreach ($this->config->item('en_all_4737') as $en_id => $m) {

            //Count this status:
            $objects_count = $this->Intents_model->in_fetch(array(
                'in_status_entity_id' => $en_id
            ), array(), 0, 0, array(), 'COUNT(in_id) as totals');

            //Display this status count:
            echo '<tr>';
            echo '<td style="text-align: left;"><span class="icon-block">' . $m['m_icon'] . '</span><a href="/entities/'.$en_id.'">' . $m['m_name'] . '</a></td>';

            echo '<td style="text-align: right;">' . '<a href="/links?in_status_entity_id=' . $en_id . '&ln_type_entity_id=4250">' . number_format($objects_count[0]['totals'],0) .'</a></td>';

            echo '</tr>';

        }
        echo '</table>';





        //Count all Intent Subtypes:
        $intent_types_counts = $this->Intents_model->in_fetch(array(
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        ), array('in_type'), 0, 0, array(), 'COUNT(in_completion_method_entity_id) as total_count, en_name, en_icon, en_id', 'en_id, en_name, en_icon');

        //Count totals:
        $addup_total_count = addup_array($intent_types_counts, 'total_count');

        //Link Stages
        echo_2level_stats($en_all_7302[10602]['m_name'], 10602, 7585, $intent_types_counts, $addup_total_count, 'in_completion_method_entity_id', 'total_count');


        //Intent Levels:
        echo echo_in_setting(7596,'in_level_entity_id', $addup_total_count);

        //Intent Verbs:
        $show_max_verbs = 5;


        //Fetch all needed data:
        $in_verbs = $this->Intents_model->in_fetch(array(
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        ), array('in_verb'), 0, 0, array('totals' => 'DESC'), 'COUNT(in_id) as totals, in_verb_entity_id, en_name, en_icon', 'in_verb_entity_id, en_name, en_icon');


        echo '<table class="table table-sm table-striped stats-table mini-stats-table">';

        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;" colspan="2">'.$en_all_7302[5008]['m_name'].'s ['.number_format(count($in_verbs)-1, 0).']</td>';
        echo '</tr>';

        $inherit_verbs = 0;
        foreach($in_verbs as $count => $verb){

            if($verb['in_verb_entity_id']==10569){
                $inherit_verbs = $verb['totals'];
                continue;
            }

            echo '<tr class="'.( $count >= $show_max_verbs ? 'hiddenverbs hidden' : '' ).'">';
            echo '<td style="text-align: left;"><span style="width:29px; display: inline-block; text-align: center;">'.echo_en_icon($verb).'</span><a href="/entities/'.$verb['in_verb_entity_id'].'">'.$verb['en_name'].'</a></td>';
            echo '<td style="text-align: right;"><a href="/links?ln_type_entity_id=4250&in_status_entity_id=' . join(',', $this->config->item('en_ids_7356')) . '&in_verb_entity_id='.$verb['in_verb_entity_id'].'" data-toggle="tooltip" data-placement="top" title="'.number_format($verb['totals'], 0).' Intent'.echo__s($verb['totals']).'">'.number_format($verb['totals']/($addup_total_count-$inherit_verbs)*100, 1).'%</a></td>';
            echo '</tr>';

            if(($count+1)==$show_max_verbs){
                //Show expand button:
                echo '<tr class="hiddenverbs">';
                echo '<td style="text-align: left;" colspan="2"><span style="width:29px; display: inline-block; text-align: center;"><i class="fas fa-plus-circle"></i></span><a href="javascript:void(0);" onclick="$(\'.hiddenverbs\').toggleClass(\'hidden\')">View All</a></td>';
                echo '</tr>';
                //To keep stripe color in balance
                echo '<tr class="hidden"><td style="text-align: left;" colspan="2"></td></tr>';
            }
        }

        echo '</table>';


    }





    function extra_stats_entities(){


        $en_all_7303 = $this->config->item('en_all_7303'); //Platform Dashboard
        $en_all_6177 = $this->config->item('en_all_6177'); //Entity Statuses







        //Entity Statuses
        echo '<table class="table table-sm table-striped stats-table mini-stats-table entity_statuses '.advance_mode().'">';
        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;" colspan="2">'.$en_all_7303[6177]['m_name'].echo__s(count($this->config->item('en_all_6177')), true).'</td>';
        echo '</tr>';
        foreach ($this->config->item('en_all_6177') as $en_id => $m) {

            //Count this status:
            $objects_count = $this->Entities_model->en_fetch(array(
                'en_status_entity_id' => $en_id
            ), array(), 0, 0, array(), 'COUNT(en_id) as totals');

            //Display this status count:
            echo '<tr>';
            echo '<td style="text-align: left;"><span class="icon-block">' . $m['m_icon'] . '</span><a href="/entities/'.$en_id.'">' . $m['m_name'] . '</a></td>';
            echo '<td style="text-align: right;">' . '<a href="/links?en_status_entity_id=' . $en_id . '&ln_type_entity_id=4251">' . number_format($objects_count[0]['totals'], 0) . '</a>' . '</td>';
            echo '</tr>';

        }
        echo '</table>';





        //Mench Community
        echo echo_en_stats_overview($this->config->item('en_all_6827'), $en_all_7303[6827]['m_name']);




        //Expert Sources
        $expert_sources_unpublished = ''; //Saved the UI for later view...
        $expert_sources_published = ''; //Saved the UI for later view...
        $total_total_counts = array();
        foreach ($this->config->item('en_all_3000') as $en_id => $m) {

            $expert_source_statuses = '';
            unset($total_counts);
            $total_counts = array();

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


                if(isset($total_total_counts[$en_status_entity_id])){
                    $total_total_counts[$en_status_entity_id] += $source_count;
                } else {
                    $total_total_counts[$en_status_entity_id] = $source_count;
                }

                //Display row:
                $expert_source_statuses .= '<td style="text-align: right;"'.( $en_status_entity_id != 6181 /* Entity Featured */ ? ' class="' . advance_mode() . '"' : '' ).'><a href="/entities/' . $en_id .'#status-'.$en_status_entity_id.'">'.number_format($source_count,0).'</a></td>';

            }

            //Echo stats:
            $expert_sources = '<tr class="' .( !$total_counts[6181] ? advance_mode() : '' ) . '">';
            $expert_sources .= '<td style="text-align: left;"><span class="icon-block">'.$m['m_icon'].'</span><a href="/entities/'.$en_id.'">'.$m['m_name'].'</a></td>';
            $expert_sources .= $expert_source_statuses;
            $expert_sources .= '</tr>';

            if($total_counts[6181]){
                $expert_sources_published .= $expert_sources;
            } else {
                $expert_sources_unpublished .= $expert_sources;
            }

        }

        echo '<table class="table table-sm table-striped stats-table">';

        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;">'.$en_all_7303[3000]['m_name'].' ['.number_format($total_total_counts[6181], 0).']</td>';
        foreach($this->config->item('en_all_7358') /* Entity Active Statuses */ as $en_status_entity_id => $m_status){
            if($en_status_entity_id == 6181 /* Entity Published */){
                echo '<td style="text-align:right;"><div class="' . advance_mode() . '">' . $en_all_6177[$en_status_entity_id]['m_name'] . '</div></td>';
            } else {
                echo '<td style="text-align:right;" class="' . advance_mode() . '">' . $en_all_6177[$en_status_entity_id]['m_name'] . '</td>';
            }
        }
        echo '</tr>';


        echo $expert_sources_published;
        echo $expert_sources_unpublished;


        echo '<tr style="font-weight: bold;" class="'.advance_mode().'">';
        echo '<td style="text-align: left;"><span class="icon-block"><i class="fas fa-asterisk"></i></span>Totals</td>';
        foreach($this->config->item('en_all_7358') /* Entity Active Statuses */ as $en_status_entity_id => $m_status){
            echo '<td style="text-align: right;" '.( $en_status_entity_id != 6181 /* Entity Featured */ ? ' class="' . advance_mode() . '"' : '' ).'>' . number_format($total_total_counts[$en_status_entity_id], 0) . '</td>';
        }
        echo '</tr>';


        echo '</table>';



    }



    function extra_stats_links(){


        $en_all_4593 = $this->config->item('en_all_4593'); //Load all link types
        $en_all_7304 = $this->config->item('en_all_7304'); //Link Stats


        //Link Status:
        echo '<table class="table table-sm table-striped stats-table mini-stats-table link_statuses '.advance_mode().'">';
        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;" colspan="2">'.$en_all_7304[6186]['m_name'].echo__s(count($this->config->item('en_all_6186')), true).'</td>';
        echo '</tr>';
        foreach ($this->config->item('en_all_6186') as $en_id => $m) {

            //Count this status:
            $objects_count = $this->Links_model->ln_fetch(array(
                'ln_status_entity_id' => $en_id
            ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');

            //Display this status count:
            echo '<tr>';
            echo '<td style="text-align: left;"><span class="icon-block">' . $m['m_icon'] . '</span><a href="/entities/'.$en_id.'">' . $m['m_name'] . '</a></td>';
            echo '<td style="text-align: right;">';
            echo '<a href="/links?ln_status_entity_id=' . $en_id . '">' . number_format($objects_count[0]['totals'],0) . '</a>';
            echo '</td>';
            echo '</tr>';

        }

        echo '</table>';






        //Count all rows:
        $link_types_counts = $this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        ), array('ln_type'), 0, 0, array(), 'COUNT(ln_id) as total_count, SUM(ABS(ln_words)) as total_words, en_name, en_icon, en_id', 'en_id, en_name, en_icon');

        //Count totals:
        $addup_total_count = addup_array($link_types_counts, 'total_count');

        //Link Direction
        echo_2level_stats('Types', 10591, 4593, $link_types_counts, $addup_total_count, 'ln_type_entity_id', 'total_words');


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
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity-to-Entity Links
            'ln_parent_entity_id' => 4527,
        ), array('en_child'), 0);

        echo htmlentities('<?php').'<br /><br />';
        echo 'defined(\'BASEPATH\') OR exit(\'No direct script access allowed\');'.'<br /><br />';

        echo '/*<br />
 * Keep a cache of certain parts of the Intent tree for faster processing<br />
 * So we don\'t have to make DB calls to figure them out every time!<br />
 * See here for all entities cached: https://mench.com/entities/4527<br />
 * use-case format: $this->config->item(\'\')<br />
 *<br />
 * ATTENTION: Also search for "en_ids_" and "en_all_" when trying to manage these throughout the code base<br />
 *<br />
 */<br /><br />';
        echo '//Generated '.date("Y-m-d H:i:s").' PST<br />';


        foreach($config_ens as $en){

            //Now fetch all its children:
            $children = $this->Links_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Entity Statuses Public
                'ln_parent_entity_id' => $en['ln_child_entity_id'],
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity-to-Entity Links
            ), array('en_child'), 0, 0, array('ln_order' => 'ASC', 'en_name' => 'ASC'));


            $child_ids = array();
            foreach($children as $child){
                array_push($child_ids , $child['en_id']);
            }

            echo '<br />//'.$en['en_name'].':<br />';
            echo '$config[\'en_ids_'.$en['ln_child_entity_id'].'\'] = array('.join(',',$child_ids).');<br />';
            echo '$config[\'en_all_'.$en['ln_child_entity_id'].'\'] = array(<br />';
            foreach($children as $child){

                //Do we have an omit command?
                if(substr_count($en['ln_content'], '&trim=') == 1){
                    $child['en_name'] = trim(str_replace(one_two_explode('&trim=','',$en['ln_content']) , '', $child['en_name']));
                }

                //Fetch all parents for this child:
                $child_parent_ids = array(); //To be populated soon
                $child_parents = $this->Links_model->ln_fetch(array(
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                    'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Entity Statuses Public
                    'ln_child_entity_id' => $child['en_id'],
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity-to-Entity Links
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