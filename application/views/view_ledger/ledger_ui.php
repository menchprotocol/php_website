<script type="text/javascript">

    function check_in_en_status(){
        //Checks to see if the Intent/Entity status filter should be visible
        //Would only make visible if Transaction type is Created Intent/Entity

        //Hide both in/en status:
        $(".filter-statuses").addClass('hidden');

        //Show only if creating new in/en Transaction type:
        if($("#tr_type_entity_id").val()==4250){
            $(".filter-in-status").removeClass('hidden');
        } else if($("#tr_type_entity_id").val()==4251){
            $(".filter-en-status").removeClass('hidden');
        }
    }

    $(document).ready(function () {

        check_in_en_status();

        //Watch for intent status change:
        $("#tr_type_entity_id").change(function () {
            check_in_en_status();
        });


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

$has_filters = ( count($_GET) > 0 );
$fixed_fields = $this->config->item('fixed_fields');

//Display stats if no filters have been applied:
if(!$has_filters){

    //Fetch & Display Intent Note Messages to explain the ledger:
    echo '<h1>Mench Ledger <a href="javascript:void(0);" onclick="$(\'.ledger_intro\').toggleClass(\'hidden\')"><i class="fal fa-info-circle"></i></a></h1>';
    echo '<div class="ledger_intro hidden">';
    foreach ($this->Database_model->fn___tr_fetch(array(
        'tr_status' => 2, //Published
        'tr_type_entity_id' => 4231, //Intent Note Messages
        'tr_child_intent_id' => $this->config->item('in_leger_intro'),
    ), array(), 0, 0, array('tr_order' => 'ASC')) as $tr) {
        echo $this->Chat_model->fn___dispatch_message($tr['tr_content']);
    }
    echo '<div style="margin-top:12px;"><a href="/'. $this->config->item('in_leger_intro') .'"><u>Lead More</u> &raquo;</a></div>';
    echo '</div>';


    //Load core Mench Objects:
    $en_all_4534 = $this->config->item('en_all_4534');
    echo '<div class="row stat-row" style="margin-bottom:75px;">';
    foreach (fn___echo_fixed_fields() as $object_id => $statuses) {

        //Define object type and run count query:
        if($object_id=='in_status'){

            $obj_en_id = 4535; //Intents
            $created_en_type_id = 4250;
            $spacing = 'col-md-offset-2';
            $objects_count = $this->Database_model->fn___in_fetch(array(), array(), 0, 0, array(), 'in_status, COUNT(in_id) as totals', 'in_status');

        } elseif($object_id=='en_status'){

            $obj_en_id = 4536; //Entities
            $created_en_type_id = 4251;
            $spacing = '';
            $objects_count = $this->Database_model->fn___en_fetch(array(), array('skip_en__parents'), 0, 0, array(), 'en_status, COUNT(en_id) as totals', 'en_status');

        } elseif($object_id=='tr_status'){

            $obj_en_id = 4341; //Ledger
            $created_en_type_id = 0; //No particular filters needed
            $spacing = 'col-md-offset-4 bottom-space';
            $objects_count = $this->Database_model->fn___tr_fetch(array(), array(), 0, 0, array(), 'tr_status, COUNT(tr_id) as totals', 'tr_status');

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
            $this_ui .= '<tr'.( $status_num < 0 ? ' class="is-removed" ' : '' ).'>';
            $this_ui .= '<td style="text-align: left;"><span style="width:29px; display: inline-block; text-align: center;">'.$status['s_icon'].'</span><span class="underdot" data-toggle="tooltip" title="'.$status['s_desc'].'" data-placement="top">'.$status['s_name'].'</span></td>';
            $this_ui .= '<td style="text-align: right;">'.( $count > 0 ? '<a href="/ledger?'.$object_id.'='.$status_num.'&tr_type_entity_id='.$created_en_type_id.'"  data-toggle="tooltip" title="View Transactions" data-placement="top">'.number_format($count,0).'</a>' : $count ).' '.$en_all_4534[$obj_en_id]['m_icon'].'</td>';
            $this_ui .= '</tr>';

            if($status_num >= 0){
                //Increase total counter:
                $this_totals += $count;
            }

        }



        //Start section:
        echo '<div class="col-lg-4">';


        echo '<a href="javascript:void(0);" onclick="$(\'.obj-'.$object_id.'\').toggleClass(\'hidden\');" class="large-stat"><span><span class="obj-'.$object_id.'">'. fn___echo_number($this_totals) . '</span><span class="obj-'.$object_id.' hidden">'. number_format($this_totals) . '</span> '.$en_all_4534[$obj_en_id]['m_icon']. '</span>'.$en_all_4534[$obj_en_id]['m_name'].' <i class="obj-'.$object_id.' fal fa-plus-circle"></i><i class="obj-'.$object_id.' fal fa-minus-circle hidden"></i></a>';


        echo '<div class="obj-'.$object_id.' hidden">';


        if($object_id=='in_status'){

            //Fetch all needed data:
            $in_verbs = $this->Database_model->fn___in_fetch(array(
                'in_status >=' => 0, //New+
            ), array('in_verb_entity_id'), 0, 0, array('totals' => 'DESC'), 'COUNT(in_id) as totals, in_verb_entity_id, en_name', 'in_verb_entity_id, en_name');

            //Report types:
            echo '<select id="in_group_by" class="form-control border stats-select">';
            echo '<option value="by_in_status">Group By: 4 Statuses</option>';
            echo '<option value="by_in_verb">Group By: '.count($in_verbs).' Starting Verbs</option>';
            echo '<option value="by_in_types">Group By: 2 Intent Types</option>';
            echo '<option value="by_in_completion">Group By: '.count($this->config->item('en_all_4331')).' Completion Methods</option>';
            echo '</select>';


            //Intent Statuses:
            echo '<table class="table table-condensed table-striped stats-table mini-stats-table in_group_by by_in_status">';
            echo $this_ui;
            echo '</table>';


            //Intent Verbs:
            echo '<table class="table table-condensed table-striped stats-table mini-stats-table in_group_by by_in_verb hidden">';
            foreach($in_verbs as $verb){
                echo '<tr>';
                echo '<td style="text-align: left;">'.$verb['en_name'].'</td>';
                echo '<td style="text-align: right;"><a href="/ledger?tr_type_entity_id=4250&in_verb_entity_id='.$verb['in_verb_entity_id'].'"  data-toggle="tooltip" title="View Intents starting with this verb" data-placement="top">'.number_format($verb['totals'],0).'</a> <i class="fas fa-hashtag"></i></td>';
                echo '</tr>';
            }
            echo '</table>';


            //Intent Types:
            echo '<table class="table table-condensed table-striped stats-table mini-stats-table in_group_by by_in_types hidden">';
            foreach($fixed_fields['in_type'] as $in_type_id => $in_type){

                //Count this type:
                $in_types = $this->Database_model->fn___in_fetch(array(
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
                $in_types = $this->Database_model->fn___in_fetch(array(
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
            $ie_ens = $this->Database_model->fn___en_fetch(array(
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
                $source_count = $this->Matrix_model->fn___en_child_count($source_en['en_id']);
                $weight = ( substr_count($source_en['tr_content'], '&var_weight=')==1 ? intval(fn___one_two_explode('&var_weight=','',$source_en['tr_content'])) : 0 );
                $all_source_count += $source_count;
                $all_source_count_weight += ($source_count * $weight);
                if($source_count < 1 || $weight < 1){
                    continue;
                }

                $expert_source_types++;

                //Count completed sources:
                $mined_source_count = $this->Matrix_model->fn___en_child_count($source_en['en_id'], 2);
                $all_mined_source_count += $mined_source_count;
                $all_mined_source_count_weigh += ($mined_source_count * $weight);


                //Echo stats:
                $expert_sources .= '<tr>';
                $expert_sources .= '<td style="text-align: left;"><span style="width: 26px; display: inline-block; text-align: center;">'.( strlen($source_en['en_icon']) > 0 ? $source_en['en_icon'] : '<i class="fas fa-at grey-at"></i>' ).'</span>'.$source_en['en_name'].'<span data-toggle="tooltip" title="'.number_format($mined_source_count,0).'/'.number_format($source_count,0).' '.$source_en['en_name'].' have been fully mined" data-placement="top" class="underdot" style="font-size:0.7em; margin-left:5px;">'.number_format(($mined_source_count/$source_count*100), 1).'%</span></td>';
                $expert_sources .= '<td style="text-align: right;"><a href="/entities/'.$source_en['en_id'].'" data-toggle="tooltip" title="View all '.$source_count.' '.strtolower($source_en['en_name']).'" data-placement="top">'.number_format($source_count, 0).'</a> <i class="fas fa-at"></i></td>';
                $expert_sources .= '</tr>';

            }
            $expert_sources .= '<tr style="font-weight: bold;">';
            $expert_sources .= '<td style="text-align:left;"><span style="width: 26px; display: inline-block; text-align: center;"><i class="fas fa-asterisk"></i></span>All '.$ie_ens[0]['en_name'].'<span data-toggle="tooltip" title="'.number_format($all_mined_source_count_weigh,0).'/'.number_format($all_source_count_weight,0).' expert sources have been fully mined" data-placement="top" class="underdot" style="font-size:0.7em; margin-left:5px;">'.($all_source_count_weight > 0 ? number_format(($all_mined_source_count_weigh/$all_source_count_weight*100), 1) : 0).'%</span>&nbsp;</td>';
            $expert_sources .= '<td style="text-align: right;"><a href="/entities/3000">'.number_format($all_source_count, 0).'</a> <i class="fas fa-at"></i></td>';
            $expert_sources .= '</tr>';




            $all_people = 0;
            $people_group_ui = '';
            foreach($this->config->item('en_all_4432') as $group_en_id=>$people_group){

                //Do a child count:
                $child_trs = $this->Database_model->fn___tr_fetch(array(
                    'tr_parent_entity_id' => $group_en_id,
                    'tr_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                    'tr_status >=' => 0, //New+
                    'en_status >=' => 0, //New+
                ), array('en_child'), 0, 0, array(), 'COUNT(en_id) as en__child_count');

                $all_people += $child_trs[0]['en__child_count'];

                $people_group_ui .= '<tr>';
                $people_group_ui .= '<td style="text-align: left;"><span style="width: 26px; display: inline-block; text-align: center;">'.$people_group['m_icon'].'</span>'.$people_group['m_name'].'</td>';
                $people_group_ui .= '<td style="text-align: right;"><a href="/entities/'.$source_en['en_id'].'" data-toggle="tooltip" title="View all '.$child_trs[0]['en__child_count'].' members" data-placement="top">'.number_format($child_trs[0]['en__child_count'], 0).'</a> <i class="fas fa-at"></i></td>';
                $people_group_ui .= '</tr>';
            }

            $people_group_ui .= '<tr style="font-weight: bold;">';
            $people_group_ui .= '<td style="text-align:left;"><span style="width: 26px; display: inline-block; text-align: center;"><i class="fas fa-asterisk"></i></span>All People</td>';
            $people_group_ui .= '<td style="text-align: right;"><a href="/entities/4432">'.number_format($all_people, 0).'</a> <i class="fas fa-at"></i></td>';
            $people_group_ui .= '</tr>';





            //Report types:
            echo '<select id="en_group_by" class="form-control border stats-select">';
            echo '<option value="by_en_status">Group By: 4 Statuses</option>';
            echo '<option value="by_en_people_groups">List Subset: '.fn___echo_number($all_people).' People</option>';
            echo '<option value="by_en_experts">List Subset: '.fn___echo_number($all_source_count).' Expert Sources</option>';
            echo '</select>';


            //Entity Status:
            echo '<table class="table table-condensed table-striped stats-table mini-stats-table en_group_by by_en_status">';
            echo $this_ui;
            echo '</table>';


            //Expert Sources:
            echo '<table class="table table-condensed table-striped stats-table en_group_by by_en_experts hidden">';
            echo $expert_sources;
            echo '</table>';


            //Community members:
            echo '<table class="table table-condensed table-striped stats-table en_group_by by_en_people_groups hidden">';
            echo $people_group_ui;
            echo '</table>';


        } elseif($object_id=='tr_status'){


            //Top Miners:
            $top = 20;
            $days_ago = null;
            $top_coin_awarded = 0;
            $top_miners = ''; //For the UI table
            $filters = array(
                'tr_coins !=' => 0,
            );
            if(!is_null($days_ago)){
                $start_date = date("Y-m-d" , (time() - ($days_ago * 24 * 3600)));
                $filters['tr_timestamp >='] = $start_date.' 00:00:00'; //From beginning of the day
            }
            foreach ($this->Database_model->fn___tr_fetch($filters, array('en_miner'), $top, 0, array('coins_sum' => 'DESC'), 'COUNT(tr_miner_entity_id) as trs_count, SUM(tr_coins) as coins_sum, en_name, en_icon, tr_miner_entity_id', 'tr_miner_entity_id, en_name, en_icon') as $count=>$tr) {
                $top_miners .= '<tr>';
                $top_miners .= '<td style="text-align: left;"><span style="width:29px; display: inline-block; text-align: center; '.( $count > 2 ? 'font-size:0.8em;' : '' ).'">'.fn___echo_rank($count+1).'</span><span class="parent-icon" style="width: 29px; display: inline-block; text-align: center;">'.( strlen($tr['en_icon']) > 0 ? $tr['en_icon'] : '<i class="fas fa-at grey-at"></i>' ).'</span><a href="/entities/'.$tr['tr_miner_entity_id'].'">'.$tr['en_name'].'</a></td>';
                $top_miners .= '<td style="text-align: right;"><a href="/ledger?tr_miner_entity_id='.$tr['tr_miner_entity_id'].( is_null($days_ago) ? '' : '&start_range='.$start_date ).'"  data-toggle="tooltip" title="Mined with '.number_format($tr['trs_count'],0).' transactions averaging '.round(($tr['coins_sum']/$tr['trs_count']),1).' coins/transaction" data-placement="top">'.number_format($tr['coins_sum'], 0).'</a> <i class="fal fa-coins"></i></td>';
                $top_miners .= '</tr>';

                $top_coin_awarded += $tr['coins_sum'];
            }
            $top_miners .= '<tr style="font-weight: bold;">';
            $top_miners .= '<td style="text-align: left;"><span style="width: 26px; display: inline-block; text-align: center;"><i class="fas fa-asterisk"></i></span>Top '.$top.' Miners:</td>';
            $top_miners .= '<td style="text-align: right;">'.number_format($top_coin_awarded, 0).' <i class="fal fa-coins"></i></td>';
            $top_miners .= '</tr>';





            //All Transaction Types:
            $all_eng_types = $this->Database_model->fn___tr_fetch(array('tr_status >=' => 0), array('en_type'), 0, 0, array('en_name' => 'ASC'), 'COUNT(tr_type_entity_id) as trs_count, en_name, en_icon, tr_type_entity_id', 'tr_type_entity_id, en_name, en_icon');

            $all_transaction_count = 0;
            $all_tr_types = '';
            foreach ($all_eng_types as $tr) {

                //Echo stats:
                $all_tr_types .= '<tr>';
                $all_tr_types .= '<td style="text-align: left;"><span style="width: 26px; display: inline-block; text-align: center;">'.( strlen($tr['en_icon']) > 0 ? $tr['en_icon'] : '<i class="fas fa-at grey-at"></i>' ).'</span><a href="/entities/'.$tr['tr_type_entity_id'].'">'.$tr['en_name'].'</a></td>';
                $all_tr_types .= '<td style="text-align: right;"><a href="/ledger?tr_type_entity_id='.$tr['tr_type_entity_id'].'"  data-toggle="tooltip" title="View all '.number_format($tr['trs_count'],0).' transactions" data-placement="top">'.number_format($tr['trs_count'], 0).'</a> <i class="fas fa-atlas"></i></td>';
                $all_tr_types .= '</tr>';

                $all_transaction_count += $tr['trs_count'];

            }



            //Coin Transaction Types:
            $all_engs = $this->Database_model->fn___tr_fetch(array(
                'tr_coins !=' => 0,
            ), array('en_type'), 0, 0, array('en_name' => 'ASC'), 'COUNT(tr_type_entity_id) as trs_count, SUM(tr_coins) as coins_sum, en_name, en_icon, tr_type_entity_id', 'tr_type_entity_id, en_name, en_icon');

            $all_coin_payouts = 0;
            $coin_tr_types = '';
            foreach ($all_engs as $tr) {

                //DOes it have a rate?
                //TODO use PHP cache version, dont make a call
                $rate_trs = $this->Database_model->fn___tr_fetch(array(
                    'tr_status' => 2, //Published
                    'en_status' => 2, //Published
                    'tr_parent_entity_id' => 4595, //Mench Coins
                    'tr_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                    'tr_child_entity_id' => $tr['tr_type_entity_id'],
                ), array('en_child'), 1);

                //Echo stats:
                $coin_tr_types .= '<tr>';
                $coin_tr_types .= '<td style="text-align: left;"><span style="width: 26px; display: inline-block; text-align: center;">'.( strlen($tr['en_icon']) > 0 ? $tr['en_icon'] : '<i class="fas fa-at grey-at"></i>' ).'</span><a href="/entities/'.$tr['tr_type_entity_id'].'">'.$tr['en_name'].'</a>'.( count($rate_trs) > 0 ? '<span class="underdot" data-toggle="tooltip" title="Each transaction currently issues '.$rate_trs[0]['tr_content'].' coins" data-placement="top" style="font-size:0.7em; margin-left:5px;">'.number_format($rate_trs[0]['tr_content'],0).'<i class="fal fa-coins" style="margin-left: 2px;"></i></span>' : '' ).'</td>';
                $coin_tr_types .= '<td style="text-align: right;"><a href="/ledger?tr_type_entity_id='.$tr['tr_type_entity_id'].'"  data-toggle="tooltip" title="View all '.number_format($tr['trs_count'],0).' transactions" data-placement="top">'.number_format($tr['coins_sum'], 0).'</a> <i class="fal fa-coins"></i></td>';
                $coin_tr_types .= '</tr>';

                $all_coin_payouts += $tr['coins_sum'];

            }

            $coin_tr_types .= '<tr style="font-weight: bold;">';
            $coin_tr_types .= '<td style="text-align: left;"><span style="width: 26px; display: inline-block; text-align: center;"><i class="fas fa-asterisk"></i></span>'.count($all_engs).' Transaction Types:</td>';
            $coin_tr_types .= '<td style="text-align: right;">'.number_format($all_coin_payouts, 0).' <i class="fal fa-coins"></i></td>';
            $coin_tr_types .= '</tr>';


            //Report types:
            echo '<select id="tr_group_by" class="form-control border stats-select">';
            echo '<option value="by_tr_status">Group By: 4 Statuses</option>';
            echo '<option value="by_tr_type">Group By: '.count($all_eng_types).' Transaction Types</option>';
            echo '<option value="by_tr_coin_types">List Subset: '.fn___echo_number($all_coin_payouts).' Mench Coins</option>';
            echo '<option value="by_tr_top_miners">List Subset: '.$top.' Top Miners</option>';
            echo '</select>';

            //Transaction Status:
            echo '<table class="table table-condensed table-striped stats-table mini-stats-table tr_group_by by_tr_status">';
            echo $this_ui;
            echo '</table>';

            //Transaction Types:
            echo '<table class="table table-condensed table-striped stats-table mini-stats-table tr_group_by by_tr_type hidden">';
            echo $all_tr_types;
            echo '</table>';

            //Coin Top Miners:
            echo '<table class="table table-condensed table-striped stats-table tr_group_by by_tr_top_miners hidden">';
            echo $top_miners;
            echo '</table>';


            //Coin Transaction Types
            echo '<table class="table table-condensed table-striped stats-table tr_group_by by_tr_coin_types hidden">';
            echo $coin_tr_types;
            echo '</table>';



        }


        echo '</div>';
        echo '</div>';

    }
    echo '</div>';


}






echo '<h1>Recent Transactions</h1>';


//Construct filters based on GET variables:
$filters = array();
$join_by = array();

//We have a special OR filter when combined with any_en_id & any_in_id
$any_in_en_set = ( ( isset($_GET['any_en_id']) && $_GET['any_en_id'] > 0 ) || ( isset($_GET['any_in_id']) && $_GET['any_in_id'] > 0 ) );
$parent_tr_filter = ( isset($_GET['tr_parent_transaction_id']) && $_GET['tr_parent_transaction_id'] > 0 ? ' OR tr_parent_transaction_id = '.$_GET['tr_parent_transaction_id'].' ' : false );


//Apply filters:
if(isset($_GET['in_status']) && strlen($_GET['in_status']) > 0){
    if(isset($_GET['tr_type_entity_id']) && $_GET['tr_type_entity_id']==4250){ //Intent created
        //Filter intent status based on
        $join_by = array('in_child');

        if (substr_count($_GET['in_status'], ',') > 0) {
            //This is multiple IDs:
            $filters['( in_status IN (' . $_GET['in_status'] . '))'] = null;
        } else {
            $filters['in_status'] = intval($_GET['in_status']);
        }
    } else {
        unset($_GET['in_status']);
    }
}

if(isset($_GET['in_verb_entity_id']) && strlen($_GET['in_verb_entity_id']) > 0){
    if(isset($_GET['tr_type_entity_id']) && $_GET['tr_type_entity_id']==4250){ //Intent created
        //Filter intent status based on
        $join_by = array('in_child');
        if (substr_count($_GET['in_verb_entity_id'], ',') > 0) {
            //This is multiple IDs:
            $filters['( in_verb_entity_id IN (' . $_GET['in_verb_entity_id'] . '))'] = null;
        } else {
            $filters['in_verb_entity_id'] = intval($_GET['in_verb_entity_id']);
        }
    } else {
        unset($_GET['in_verb_entity_id']);
    }
}

if(isset($_GET['en_status']) && strlen($_GET['en_status']) > 0){
    if(isset($_GET['tr_type_entity_id']) && $_GET['tr_type_entity_id']==4251){ //Entity Created

        //Filter intent status based on
        $join_by = array('en_child');

        if (substr_count($_GET['en_status'], ',') > 0) {
            //This is multiple IDs:
            $filters['( en_status IN (' . $_GET['en_status'] . '))'] = null;
        } else {
            $filters['en_status'] = intval($_GET['en_status']);
        }
    } else {
        unset($_GET['en_status']);
    }
}

if(isset($_GET['tr_status']) && strlen($_GET['tr_status']) > 0){
    if (substr_count($_GET['tr_status'], ',') > 0) {
        //This is multiple IDs:
        $filters['( tr_status IN (' . $_GET['tr_status'] . '))'] = null;
    } else {
        $filters['tr_status'] = intval($_GET['tr_status']);
    }
}

if(isset($_GET['tr_miner_entity_id']) && strlen($_GET['tr_miner_entity_id']) > 0){
    if (substr_count($_GET['tr_miner_entity_id'], ',') > 0) {
        //This is multiple IDs:
        $filters['( tr_miner_entity_id IN (' . $_GET['tr_miner_entity_id'] . '))'] = null;
    } elseif (intval($_GET['tr_miner_entity_id']) > 0) {
        $filters['tr_miner_entity_id'] = $_GET['tr_miner_entity_id'];
    }
}


if(isset($_GET['tr_parent_entity_id']) && strlen($_GET['tr_parent_entity_id']) > 0){
    if (substr_count($_GET['tr_parent_entity_id'], ',') > 0) {
        //This is multiple IDs:
        $filters['( tr_parent_entity_id IN (' . $_GET['tr_parent_entity_id'] . '))'] = null;
    } elseif (intval($_GET['tr_parent_entity_id']) > 0) {
        $filters['tr_parent_entity_id'] = $_GET['tr_parent_entity_id'];
    }
}

if(isset($_GET['tr_child_entity_id']) && strlen($_GET['tr_child_entity_id']) > 0){
    if (substr_count($_GET['tr_child_entity_id'], ',') > 0) {
        //This is multiple IDs:
        $filters['( tr_child_entity_id IN (' . $_GET['tr_child_entity_id'] . '))'] = null;
    } elseif (intval($_GET['tr_child_entity_id']) > 0) {
        $filters['tr_child_entity_id'] = $_GET['tr_child_entity_id'];
    }
}

if(isset($_GET['tr_parent_intent_id']) && strlen($_GET['tr_parent_intent_id']) > 0){
    if (substr_count($_GET['tr_parent_intent_id'], ',') > 0) {
        //This is multiple IDs:
        $filters['( tr_parent_intent_id IN (' . $_GET['tr_parent_intent_id'] . '))'] = null;
    } elseif (intval($_GET['tr_parent_intent_id']) > 0) {
        $filters['tr_parent_intent_id'] = $_GET['tr_parent_intent_id'];
    }
}

if(isset($_GET['tr_child_intent_id']) && strlen($_GET['tr_child_intent_id']) > 0){
    if (substr_count($_GET['tr_child_intent_id'], ',') > 0) {
        //This is multiple IDs:
        $filters['( tr_child_intent_id IN (' . $_GET['tr_child_intent_id'] . '))'] = null;
    } elseif (intval($_GET['tr_child_intent_id']) > 0) {
        $filters['tr_child_intent_id'] = $_GET['tr_child_intent_id'];
    }
}

if(isset($_GET['tr_parent_transaction_id']) && strlen($_GET['tr_parent_transaction_id']) > 0 && !$any_in_en_set){
    if (substr_count($_GET['tr_parent_transaction_id'], ',') > 0) {
        //This is multiple IDs:
        $filters['( tr_parent_transaction_id IN (' . $_GET['tr_parent_transaction_id'] . '))'] = null;
    } elseif (intval($_GET['tr_parent_transaction_id']) > 0) {
        $filters['tr_parent_transaction_id'] = $_GET['tr_parent_transaction_id'];
    }
}

if(isset($_GET['tr_id']) && strlen($_GET['tr_id']) > 0){
    if (substr_count($_GET['tr_id'], ',') > 0) {
        //This is multiple IDs:
        $filters['( tr_id IN (' . $_GET['tr_id'] . '))'] = null;
    } elseif (intval($_GET['tr_id']) > 0) {
        $filters['tr_id'] = $_GET['tr_id'];
    }
}

if(isset($_GET['any_en_id']) && strlen($_GET['any_en_id']) > 0){
    //We need to look for both parent/child
    if (substr_count($_GET['any_en_id'], ',') > 0) {
        //This is multiple IDs:
        $filters['( tr_child_entity_id IN (' . $_GET['any_en_id'] . ') OR tr_parent_entity_id IN (' . $_GET['any_en_id'] . ') OR tr_miner_entity_id IN (' . $_GET['any_en_id'] . ') ' . $parent_tr_filter . ' )'] = null;
    } elseif (intval($_GET['any_en_id']) > 0) {
        $filters['( tr_child_entity_id = ' . $_GET['any_en_id'] . ' OR tr_parent_entity_id = ' . $_GET['any_en_id'] . ' OR tr_miner_entity_id = ' . $_GET['any_en_id'] . $parent_tr_filter . ' )'] = null;
    }
}

if(isset($_GET['any_in_id']) && strlen($_GET['any_in_id']) > 0){
    //We need to look for both parent/child
    if (substr_count($_GET['any_in_id'], ',') > 0) {
        //This is multiple IDs:
        $filters['( tr_child_intent_id IN (' . $_GET['any_in_id'] . ') OR tr_parent_intent_id IN (' . $_GET['any_in_id'] . ') ' . $parent_tr_filter . ' )'] = null;
    } elseif (intval($_GET['any_in_id']) > 0) {
        $filters['( tr_child_intent_id = ' . $_GET['any_in_id'] . ' OR tr_parent_intent_id = ' . $_GET['any_in_id'] . $parent_tr_filter . ')'] = null;
    }
}

if(isset($_GET['any_tr_id']) && strlen($_GET['any_tr_id']) > 0){
    //We need to look for both parent/child
    if (substr_count($_GET['any_tr_id'], ',') > 0) {
        //This is multiple IDs:
        $filters['( tr_id IN (' . $_GET['any_tr_id'] . ') OR tr_parent_transaction_id IN (' . $_GET['any_tr_id'] . '))'] = null;
    } elseif (intval($_GET['any_tr_id']) > 0) {
        $filters['( tr_id = ' . $_GET['any_tr_id'] . ' OR tr_parent_transaction_id = ' . $_GET['any_tr_id'] . ')'] = null;
    }
}

if(isset($_GET['start_range']) && fn___isDate($_GET['start_range'])){
    $filters['tr_timestamp >='] = $_GET['start_range'].' 00:00:00';
}
if(isset($_GET['end_range']) && fn___isDate($_GET['end_range'])){
    $filters['tr_timestamp <='] = $_GET['end_range'].' 23:59:59';
}









//Fetch unique transaction types recorded so far:
$ini_filter = array();
foreach($filters as $key => $value){
    if(!fn___includes_any($key, array('in_status', 'in_verb_entity_id', 'en_status'))){
        $ini_filter[$key] = $value;
    }
}
$all_engs = $this->Database_model->fn___tr_fetch($ini_filter, array('en_type'), 0, 0, array('en_name' => 'ASC'), 'COUNT(tr_type_entity_id) as trs_count, SUM(tr_coins) as coins_sum, en_name, tr_type_entity_id', 'tr_type_entity_id, en_name');




//Make sure its a valid type considering other filters:
if(isset($_GET['tr_type_entity_id'])){

    $found = false;
    foreach ($all_engs as $tr) {
        if($_GET['tr_type_entity_id'] == $tr['tr_type_entity_id']){
            $found = true;
            break;
        }
    }

    if(!$found){
        unset($_GET['tr_type_entity_id']);
    } else {
        //Assign filter:
        $filters['tr_type_entity_id'] = intval($_GET['tr_type_entity_id']);
    }

}




//Fetch transactions:

$filter_note = '';
if(!fn___en_auth(array(1281))){
    //Not a moderator:

    if(count($_GET) < 1){
        //This makes the public ledger focus on transactions with coins which is a nicer initial view into the ledger:
        $filters['tr_coins >'] = 0;
        //Also give warning about this applied filter on the UI:
        $filter_note = 'Showing recent transaction with awarded coins.';
    } else {
        //We do have some filters passed...
        //Make sure not to show the invisible transaction types:
        $filters['tr_type_entity_id NOT IN ('.join(',' , $this->config->item('en_ids_4755')).')'] = null;

        //Also give warning about this applied filter on the UI:
        $filter_note = 'Only showing publicly visible transaction.';
    }
}

$trs_count = $this->Database_model->fn___tr_fetch($filters, $join_by, 0, 0, array(), 'COUNT(tr_id) as trs_count, SUM(tr_coins) as coins_sum');
$trs = $this->Database_model->fn___tr_fetch($filters, $join_by, (fn___is_dev() ? 50 : 200));




//button to show:
echo '<div><a href="javascript:void();" onclick="$(\'.show-filter\').toggleClass(\'hidden\');">'.( $has_filters ? '<i class="fal fa-minus-circle show-filter"></i><i class="fal fa-plus-circle show-filter hidden"></i>' : '<i class="fal fa-plus-circle show-filter"></i><i class="fal fa-minus-circle show-filter hidden"></i>').' Toggle Filters</a>'.( fn___en_auth(array(1281)) ? ' | <a href="/ledger/fn___moderate"><i class="fal fa-cog"></i> <u>Moderation Tools</u> &raquo;</a>' : '').'</div>';


echo '<div class="inline-box show-filter '.( $has_filters ? '' : 'hidden' ).'">';
echo '<form action="" method="GET">';


//Filters UI:
echo '<table class="table table-condensed maxout"><tr>';

    echo '<td valign="top" style="vertical-align: top;"><div style="padding-right:5px;">';
    echo '<span class="mini-header">Start Date:</span>';
    echo '<input type="date" class="form-control border" name="start_range" value="'.( isset($_GET['start_range']) ? $_GET['start_range'] : '' ).'">';
    echo '</div></td>';

    echo '<td valign="top" style="vertical-align: top;"><div style="padding-right:5px;">';
    echo '<span class="mini-header">End Date:</span>';
    echo '<input type="date" class="form-control border" name="end_range" value="'.( isset($_GET['end_range']) ? $_GET['end_range'] : '' ).'">';
    echo '</div></td>';

    //Transaction Type:
    $all_transaction_count = 0;
    $all_coins = 0;
    $select_ui = '';
    foreach ($all_engs as $tr) {
        //Echo drop down:
        $select_ui .= '<option value="' . $tr['tr_type_entity_id'] . '" ' . ((isset($_GET['tr_type_entity_id']) && $_GET['tr_type_entity_id'] == $tr['tr_type_entity_id']) ? 'selected="selected"' : '') . '>' . $tr['en_name'] . ' ('  . fn___echo_number($tr['trs_count']) . 'T' . ' = '.fn___echo_number($tr['coins_sum']).'C' . ')</option>';
        $all_transaction_count += $tr['trs_count'];
        $all_coins += $tr['coins_sum'];
    }

    echo '<td>';
    echo '<div>';
    echo '<span class="mini-header">Transaction Type:</span>';
    echo '<select class="form-control border" name="tr_type_entity_id" id="tr_type_entity_id" class="border" style="width: 100% !important;">';
    echo '<option value="0">All ('  . fn___echo_number($all_transaction_count) . 'T' . ' = '.fn___echo_number($all_coins).'C' . ')</option>';
    echo $select_ui;
    echo '</select>';
    echo '</div>';

    //Optional Intent/Entity status filter ONLY IF Transaction Type = Create New Intent/Entity

    echo '<div class="filter-statuses filter-in-status hidden"><span class="mini-header">Intent Status:</span><input type="text" name="in_status" value="' . ((isset($_GET['in_status'])) ? $_GET['in_status'] : '') . '" class="form-control border"></div>';
    echo '<div class="filter-statuses filter-in-status hidden"><span class="mini-header">Intent Verb Entity IDS:</span><input type="text" name="in_verb_entity_id" value="' . ((isset($_GET['in_verb_entity_id'])) ? $_GET['in_verb_entity_id'] : '') . '" class="form-control border"></div>';

    echo '<div class="filter-statuses filter-en-status hidden"><span class="mini-header">Entity Status:</span><input type="text" name="en_status" value="' . ((isset($_GET['en_status'])) ? $_GET['en_status'] : '') . '" class="form-control border"></div>';

echo '</td>';

echo '</tr></table>';







echo '<table class="table table-condensed maxout"><tr>';

//ANY Intent
echo '<td><div style="padding-right:5px;">';
echo '<span class="mini-header">Any Intent IDs:</span>';
echo '<input type="text" name="any_in_id" value="' . ((isset($_GET['any_in_id'])) ? $_GET['any_in_id'] : '') . '" class="form-control border">';
echo '</div></td>';

echo '<td><span class="mini-header">Intent Parent IDs:</span><input type="text" name="tr_parent_intent_id" value="' . ((isset($_GET['tr_parent_intent_id'])) ? $_GET['tr_parent_intent_id'] : '') . '" class="form-control border"></td>';

echo '<td><span class="mini-header">Intent Child IDs:</span><input type="text" name="tr_child_intent_id" value="' . ((isset($_GET['tr_child_intent_id'])) ? $_GET['tr_child_intent_id'] : '') . '" class="form-control border"></td>';

echo '</tr></table>';







echo '<table class="table table-condensed maxout"><tr>';

    //ANY Entity
    echo '<td><div style="padding-right:5px;">';
    echo '<span class="mini-header">Any Entity IDs:</span>';
    echo '<input type="text" name="any_en_id" value="' . ((isset($_GET['any_en_id'])) ? $_GET['any_en_id'] : '') . '" class="form-control border">';
    echo '</div></td>';

    echo '<td><span class="mini-header">Entity Miner IDs:</span><input type="text" name="tr_miner_entity_id" value="' . ((isset($_GET['tr_miner_entity_id'])) ? $_GET['tr_miner_entity_id'] : '') . '" class="form-control border"></td>';

    echo '<td><span class="mini-header">Entity Parent IDs:</span><input type="text" name="tr_parent_entity_id" value="' . ((isset($_GET['tr_parent_entity_id'])) ? $_GET['tr_parent_entity_id'] : '') . '" class="form-control border"></td>';

    echo '<td><span class="mini-header">Entity Child IDs:</span><input type="text" name="tr_child_entity_id" value="' . ((isset($_GET['tr_child_entity_id'])) ? $_GET['tr_child_entity_id'] : '') . '" class="form-control border"></td>';

echo '</tr></table>';





echo '<table class="table table-condensed maxout"><tr>';

//ANY Transaction
echo '<td><div style="padding-right:5px;">';
echo '<span class="mini-header">Any Trans. IDs:</span>';
echo '<input type="text" name="any_tr_id" value="' . ((isset($_GET['any_tr_id'])) ? $_GET['any_tr_id'] : '') . '" class="form-control border">';
echo '</div></td>';

echo '<td><span class="mini-header">Trans. IDs:</span><input type="text" name="tr_id" value="' . ((isset($_GET['tr_id'])) ? $_GET['tr_id'] : '') . '" class="form-control border"></td>';

echo '<td><span class="mini-header">Parent Trans. IDs:</span><input type="text" name="tr_parent_transaction_id" value="' . ((isset($_GET['tr_parent_transaction_id'])) ? $_GET['tr_parent_transaction_id'] : '') . '" class="form-control border"></td>';

echo '<td><span class="mini-header">Trans. Status:</span><input type="text" name="tr_status" value="' . ((isset($_GET['tr_status'])) ? $_GET['tr_status'] : '') . '" class="form-control border"></td>';

echo '</tr></table>';






echo '<input type="submit" class="btn btn-sm btn-primary" value="Apply" />';

if($has_filters){
    echo ' &nbsp;<a href="/ledger" style="font-size: 0.8em;">Remove Filters</a>';
}

echo '</form>';
echo '</div>';




if($has_filters){
    //Display Transactions:
    echo '<p style="margin: 10px 0 0 0;">Showing '.count($trs) . ( $trs_count[0]['trs_count'] > count($trs) ? ' of '. number_format($trs_count[0]['trs_count'] , 0) : '' ) .' transactions with '.number_format($trs_count[0]['coins_sum'], 0).' awarded coins:</p>';
}

if($filter_note){
    echo '<p style="margin: 10px 0 0 0;">'.$filter_note.'</p>';
}


echo '<div class="row">';
    echo '<div class="col-md-7">';

        if(count($trs)>0){
            echo '<div class="list-group list-grey">';
            foreach ($trs as $tr) {
                echo fn___echo_tr_row($tr);
            }
            echo '</div>';
        } else {
            //Show no transaction warning:
            echo '<div class="alert alert-info" role="alert" style="margin-top: 0;"><i class="fas fa-exclamation-triangle"></i> No Transactions found with the selected filters. Modify filters and try again.</div>';
        }

    echo '</div>';

    echo '<div class="col-md-5">';
        //TODO Maybe eventually merge intent/entity modification widgets and also place here?
    echo '</div>';
echo '</div>';


?>