<script type="text/javascript">

    $(document).ready(function () {

        $('#en_group_by').change(function () {
            //Hide all tables:
            $('.en_group_by').addClass('hidden');
            //Except the one selected:
            $('.' + $(this).val()).removeClass('hidden');
        });

        $('#in_group_by').change(function () {
            //Hide all tables:
            $('.in_group_by').addClass('hidden');
            //Except the one selected:
            $('.' + $(this).val()).removeClass('hidden');
        });

        $('#tr_group_by').change(function () {
            //Hide all tables:
            $('.tr_group_by').addClass('hidden');
            //Except the one selected:
            $('.' + $(this).val()).removeClass('hidden');
        });

    });

</script>
<?php

//Fetch & Display Intent Note Messages to explain links:
$en_all_2738 = $this->config->item('en_all_2738');
echo '<h1 style="text-align: center; margin-top: 50px;">Mench '.$en_all_2738[4488]['m_name'].'</h1>';
echo '<p style="text-align: center; margin-top: 20px; font-size:1.5em !important;">'.$en_all_2738[4488]['m_desc'].'</p>';

//Load core Mench Objects:
$en_all_4534 = $this->config->item('en_all_4534');
echo '<div class="row stat-row" style="margin-bottom:75px;">';
foreach (echo_fixed_fields() as $object_id => $statuses) {

    //Define object type and run count query:
    if($object_id=='in_status'){

        $obj_en_id = 4535; //Intents
        $created_en_type_id = 4250;
        $spacing = 'bottom-spacing col-lg-offset-2';
        $css_add = 'yellow';
        $objects_count = $this->Intents_model->in_fetch(array(), array(), 0, 0, array(), 'in_status, COUNT(in_id) as totals', 'in_status');

    } elseif($object_id=='en_status'){

        $obj_en_id = 4536; //Entities
        $created_en_type_id = 4251;
        $spacing = 'bottom-spacing';
        $css_add = 'blue';
        $objects_count = $this->Entities_model->en_fetch(array(), array('skip_en__parents'), 0, 0, array(), 'en_status, COUNT(en_id) as totals', 'en_status');

    } elseif($object_id=='ln_status'){

        $obj_en_id = 6205; //Links
        $created_en_type_id = 0; //No particular filters needed
        $spacing = 'col-lg-offset-4';
        $css_add = '';
        $objects_count = $this->Links_model->ln_fetch(array(), array(), 0, 0, array(), 'ln_status, COUNT(ln_id) as totals', 'ln_status');

    } else {

        //Unsupported
        continue;

    }


    //Object Stats grouped by Status:
    $this_totals = 0;
    $this_ui = '';
    foreach ($statuses as $status_num => $status) {

        $count = 0;
        foreach($objects_count as $oc){
            if($oc[$object_id]==$status_num){
                $count = intval($oc['totals']);
                break;
            }
        }


        //Display this status count:
        $this_ui .= '<tr>';
        $this_ui .= '<td style="text-align: left;"><span style="width:29px; display: inline-block; text-align: center;">'.$status['s_icon'].'</span><span class="underdot" data-toggle="tooltip" title="'.$status['s_desc'].'" data-placement="top">'.$status['s_name'].'</span></td>';
        $this_ui .= '<td style="text-align: right;">'.( $count > 0 ? '<a href="/links?'.$object_id.'='.$status_num.'&ln_type_entity_id='.$created_en_type_id.'"  data-toggle="tooltip" title="View Links" data-placement="top">'.number_format($count,0).'</a>' : $count ).' '.$en_all_4534[$obj_en_id]['m_icon'].'</td>';
        $this_ui .= '</tr>';

        //Increase total counter:
        if($status_num >= 0 || $object_id=='ln_status' /* Count all for statuses */ ){
            $this_totals += $count;
        }
    }



    //Start section:
    echo '<div class="col-lg-4 '.$spacing.'">';


    echo '<a href="javascript:void(0);" onclick="$(\'.obj-'.$object_id.'\').toggleClass(\'hidden\');" class="large-stat '.$css_add.'" style="font-weight:bold;"><span>'.$en_all_4534[$obj_en_id]['m_icon']. ' <span class="obj-'.$object_id.'">'. echo_number($this_totals) . '</span><span class="obj-'.$object_id.' hidden">'. number_format($this_totals) . '</span></span>'.$en_all_4534[$obj_en_id]['m_name'].' <i class="obj-'.$object_id.' fal fa-plus-circle"></i><i class="obj-'.$object_id.' fal fa-minus-circle hidden"></i></a>';


    echo '<div class="obj-'.$object_id.' hidden">';


    if($object_id=='in_status'){

        //Fetch all needed data:
        $in_verbs = $this->Intents_model->in_fetch(array(
            'in_status >=' => 0, //New+
        ), array('in_verb_entity_id'), 0, 0, array('totals' => 'DESC'), 'COUNT(in_id) as totals, in_verb_entity_id, en_name', 'in_verb_entity_id, en_name');

        //Report types:
        echo '<select id="in_group_by" class="form-control border stats-select">';
        echo '<option value="by_in_types">2 Intent Types</option>';
        echo '<option value="by_in_verb">'.count($in_verbs).' Verbs</option>';
        echo '<option value="by_in_completion">'.count($this->config->item('en_all_4331')).' Completion Methods</option>';
        echo '<option value="by_in_status">4 Statuses</option>';
        echo '</select>';


        //Intent Statuses:
        echo '<table class="table table-condensed table-striped stats-table mini-stats-table in_group_by by_in_status hidden">';
        echo $this_ui;
        echo '</table>';


        //Intent Verbs:
        echo '<table class="table table-condensed table-striped stats-table mini-stats-table in_group_by by_in_verb hidden">';
        foreach($in_verbs as $verb){
            echo '<tr>';
            echo '<td style="text-align: left;">'.$verb['en_name'].'</td>';
            echo '<td style="text-align: right;"><a href="/links?ln_type_entity_id=4250&in_verb_entity_id='.$verb['in_verb_entity_id'].'"  data-toggle="tooltip" title="View Intents starting with this verb" data-placement="top">'.number_format($verb['totals'],0).'</a> <i class="fas fa-hashtag"></i></td>';
            echo '</tr>';
        }
        echo '</table>';


        //Intent Types:
        echo '<table class="table table-condensed table-striped stats-table mini-stats-table in_group_by by_in_types">';
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
        echo '<table class="table table-condensed table-striped stats-table mini-stats-table in_group_by by_in_completion hidden">';
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


    } elseif($object_id=='en_status'){


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
            $child_trs = $this->Links_model->ln_fetch(array(
                'ln_parent_entity_id' => $group_en_id,
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                'ln_status >=' => 0, //New+
                'en_status >=' => 0, //New+
            ), array('en_child'), 0, 0, array(), 'COUNT(en_id) as en__child_count');

            $all_people += $child_trs[0]['en__child_count'];

            $people_group_ui .= '<tr>';
            $people_group_ui .= '<td style="text-align: left;"><span style="width: 26px; display: inline-block; text-align: center;">'.$people_group['m_icon'].'</span>'.$people_group['m_name'].'</td>';
            $people_group_ui .= '<td style="text-align: right;"><a href="/entities/'.$group_en_id.'" data-toggle="tooltip" title="View all '.$child_trs[0]['en__child_count'].' members" data-placement="top">'.number_format($child_trs[0]['en__child_count'], 0).'</a> <i class="fas fa-at"></i></td>';
            $people_group_ui .= '</tr>';
        }

        $people_group_ui .= '<tr style="font-weight: bold;">';
        $people_group_ui .= '<td style="text-align:left;"><span style="width: 26px; display: inline-block; text-align: center;"><i class="fas fa-asterisk"></i></span>All People</td>';
        $people_group_ui .= '<td style="text-align: right;"><a href="/entities/4432">'.number_format($all_people, 0).'</a> <i class="fas fa-at"></i></td>';
        $people_group_ui .= '</tr>';





        //Report types:
        echo '<select id="en_group_by" class="form-control border stats-select">';
        echo '<option value="by_en_experts">'.echo_number($all_source_count).' Expert Sources</option>';
        echo '<option value="by_en_people_groups">'.echo_number($all_people).' People</option>';
        echo '<option value="by_en_status">4 Statuses</option>';
        echo '</select>';


        //Entity Status:
        echo '<table class="table table-condensed table-striped stats-table mini-stats-table en_group_by by_en_status hidden">';
        echo $this_ui;
        echo '</table>';


        //Expert Sources:
        echo '<table class="table table-condensed table-striped stats-table en_group_by by_en_experts">';
        echo $expert_sources;
        echo '</table>';


        //Community members:
        echo '<table class="table table-condensed table-striped stats-table en_group_by by_en_people_groups hidden">';
        echo $people_group_ui;
        echo '</table>';


    } elseif($object_id=='ln_status'){


        //Top Miners:
        $top = 20;
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




        //All Link Types:
        $all_eng_types = $this->Links_model->ln_fetch(array('ln_status >=' => 0), array('en_type'), 0, 0, array('en_name' => 'ASC'), 'COUNT(ln_type_entity_id) as trs_count, en_name, en_icon, ln_type_entity_id', 'ln_type_entity_id, en_name, en_icon');

        $all_link_count = 0;
        $all_ln_types = '';
        foreach ($all_eng_types as $ln) {

            //Echo stats:
            $all_ln_types .= '<tr>';
            $all_ln_types .= '<td style="text-align: left;"><span style="width: 26px; display: inline-block; text-align: center;">'.( strlen($ln['en_icon']) > 0 ? $ln['en_icon'] : '<i class="fas fa-at grey-at"></i>' ).'</span><a href="/entities/'.$ln['ln_type_entity_id'].'">'.$ln['en_name'].'</a></td>';
            $all_ln_types .= '<td style="text-align: right;"><a href="/links?ln_type_entity_id='.$ln['ln_type_entity_id'].'"  data-toggle="tooltip" title="View all '.number_format($ln['trs_count'],0).' links" data-placement="top">'.number_format($ln['trs_count'], 0).'</a> <i class="fas fa-link rotate90"></i></td>';
            $all_ln_types .= '</tr>';

            $all_link_count += $ln['trs_count'];

        }



        //Point Link Types:
        $all_engs = $this->Links_model->ln_fetch(array(
            'ln_points !=' => 0,
        ), array('en_type'), 0, 0, array('en_name' => 'ASC'), 'COUNT(ln_type_entity_id) as trs_count, SUM(ln_points) as points_sum, en_name, en_icon, ln_type_entity_id', 'ln_type_entity_id, en_name, en_icon');

        $all_point_payouts = 0;
        $point_ln_types = '';
        foreach ($all_engs as $ln) {

            //DOes it have a rate?
            //TODO use PHP cache version, dont make a call
            $rate_trs = $this->Links_model->ln_fetch(array(
                'ln_status' => 2, //Published
                'en_status' => 2, //Published
                'ln_parent_entity_id' => 4595, //Link Points
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                'ln_child_entity_id' => $ln['ln_type_entity_id'],
            ), array('en_child'), 1);

            //Echo stats:
            $point_ln_types .= '<tr>';
            $point_ln_types .= '<td style="text-align: left;"><span style="width: 26px; display: inline-block; text-align: center;">'.( strlen($ln['en_icon']) > 0 ? $ln['en_icon'] : '<i class="fas fa-at grey-at"></i>' ).'</span><a href="/entities/'.$ln['ln_type_entity_id'].'">'.$ln['en_name'].'</a>'.( count($rate_trs) > 0 ? '<span class="underdot" data-toggle="tooltip" title="Miners earn '.$rate_trs[0]['ln_content'].' points per each '.$ln['en_name'].' link" data-placement="top" style="font-size:0.7em; margin-left:5px;">'.number_format($rate_trs[0]['ln_content'],0).'<i class="fas fa-award" style="margin-left: 2px;"></i></span>' : '' ).'</td>';
            $point_ln_types .= '<td style="text-align: right;"><a href="/links?ln_type_entity_id='.$ln['ln_type_entity_id'].'"  data-toggle="tooltip" title="View all '.number_format($ln['trs_count'],0).' links" data-placement="top">'.number_format($ln['points_sum'], 0).'</a> <i class="fas fa-award"></i></td>';
            $point_ln_types .= '</tr>';

            $all_point_payouts += $ln['points_sum'];

        }

        $point_ln_types .= '<tr style="font-weight: bold;">';
        $point_ln_types .= '<td style="text-align: left;"><span style="width: 26px; display: inline-block; text-align: center;"><i class="fas fa-asterisk"></i></span>'.count($all_engs).' Link Types:</td>';
        $point_ln_types .= '<td style="text-align: right;">'.number_format($all_point_payouts, 0).' <i class="fas fa-award"></i></td>';
        $point_ln_types .= '</tr>';


        //Report types:
        echo '<select id="tr_group_by" class="form-control border stats-select">';
        echo '<option value="by_tr_top_miners">Top '.$top.' Miners</option>';
        echo '<option value="by_tr_point_types">'.echo_number($all_point_payouts).' Points</option>';
        echo '<option value="by_ln_type">'.count($all_eng_types).' Link Types</option>';
        echo '<option value="by_ln_status">4 Statuses</option>';
        echo '</select>';

        //Link Status:
        echo '<table class="table table-condensed table-striped stats-table mini-stats-table tr_group_by by_ln_status hidden">';
        echo $this_ui;
        echo '</table>';

        //Link Types:
        echo '<table class="table table-condensed table-striped stats-table mini-stats-table tr_group_by by_ln_type hidden">';
        echo $all_ln_types;
        echo '</table>';

        //Point Top Miners:
        echo '<table class="table table-condensed table-striped stats-table tr_group_by by_tr_top_miners">';
        echo $top_miners;
        echo '</table>';


        //Point Link Types
        echo '<table class="table table-condensed table-striped stats-table tr_group_by by_tr_point_types hidden">';
        echo $point_ln_types;
        echo '</table>';



    }


    echo '</div>';
    echo '</div>';

}
echo '</div>';


?>