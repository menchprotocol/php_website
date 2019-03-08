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

    });

</script>

<?php

$has_filters = ( count($_GET) > 0 );

//Display stats if no filters have been applied:
if(!$has_filters){

    echo '<h1>Ledger Stats</h1>';

//Load core Mench Objects:
    $en_all_4534 = $this->config->item('en_all_4534');

//Just be logged in to browse:
    $session_en = fn___en_auth();

    if(!$session_en){
        echo '<style> .main-raised { max-width:1240px !important; } </style>';

    }


    echo '<div class="row stat-row">';
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

            if($count < 1){
                //continue;
            }

            //Display this status count:
            $this_ui .= '<tr>';
            $this_ui .= '<td style="text-align: left;">'.fn___echo_fixed_fields($object_id, $status_num, false, 'top').'</td>';
            $this_ui .= '<td style="text-align: right;">'.( $count > 0 ? '<a href="/ledger?'.$object_id.'='.$status_num.'&tr_type_entity_id='.$created_en_type_id.'"  data-toggle="tooltip" title="View Transactions" data-placement="top">'.number_format($count,0).'</a>' : $count ).'</td>';
            $this_ui .= '</tr>';


            //Increase total counter:
            $this_totals += $count;
        }



        //Start section:
        echo '<div class="col-md-4">'; //'.$spacing.'

        echo '<a href="javascript:void(0);" onclick="$(\'.obj-'.$object_id.'\').toggleClass(\'hidden\');" class="large-stat"><span>'.$en_all_4534[$obj_en_id]['m_icon']. ' <span class="obj-'.$object_id.'">'. fn___echo_number($this_totals) . '</span><span class="obj-'.$object_id.' hidden">'. number_format($this_totals) . '</span></span>'.$en_all_4534[$obj_en_id]['m_name'].' <i class="obj-'.$object_id.' fal fa-plus-circle"></i><i class="obj-'.$object_id.' fal fa-minus-circle hidden"></i></a>';

        echo '<table class="table table-condensed table-striped stats-table mini-stats-table obj-'.$object_id.' hidden">';

        //Object Header:
        echo '<tr style="font-weight: bold;">';
        echo '<td style="text-align: left;">Status:</td>';
        echo '<td style="text-align: right;">Count</td>';
        echo '</tr>';

        //Object Total count:
        echo $this_ui;


        //End Section:
        echo '</table>';
        echo '</div>';

    }

    echo '</div>';






    echo '<div class="row stat-row">';









//Count coins per Transaction
    echo '<div class="col-md-4">';

//Count variables:
    $all_engs = $this->Database_model->fn___tr_fetch(array(
        'tr_coins !=' => 0,
    ), array('en_type'), 0, 0, array('en_name' => 'DESC'), 'COUNT(tr_type_entity_id) as trs_count, SUM(tr_coins) as coins_sum, en_name, en_icon, tr_type_entity_id', 'tr_type_entity_id, en_name, en_icon');

    $all_transaction_count = 0;
    $all_coin_payouts = 0;
    $table_body = '';
    foreach ($all_engs as $tr) {

        //DOes it have a rate?
        $rate_trs = $this->Database_model->fn___tr_fetch(array(
            'tr_status >=' => 2, //Must be published+
            'en_status >=' => 2, //Must be published+
            'tr_parent_entity_id' => 4374, //Mench Coins
            'tr_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'tr_child_entity_id' => $tr['tr_type_entity_id'],
        ), array('en_child'), 1);



        //Echo stats:
        $table_body .= '<tr>';
        $table_body .= '<td style="text-align: left;"><span style="width: 26px; display: inline-block; text-align: center;">'.( strlen($tr['en_icon']) > 0 ? $tr['en_icon'] : '<i class="fas fa-at grey-at"></i>' ).'</span><a href="/entities/'.$tr['tr_type_entity_id'].'">'.$tr['en_name'].'</a></td>';
        $table_body .= '<td style="text-align: right;"><span class="underdot" data-toggle="tooltip" title="Each '.$tr['en_name'].' transaction currently issues '.$rate_trs[0]['tr_content'].' coins. Transaction rates may fluctuate to balance supply/demand" data-placement="top">'.( count($rate_trs) > 0 ? number_format($rate_trs[0]['tr_content'],0) : 'Not Set' ).'</span></td>';
        $table_body .= '<td style="text-align: right;"><a href="/ledger?tr_type_entity_id='.$tr['tr_type_entity_id'].'"  data-toggle="tooltip" title="View all '.number_format($tr['trs_count'],0).' transactions on the ledger" data-placement="top">'.number_format($tr['coins_sum'], 0).'</a></td>';
        $table_body .= '</tr>';

        $all_transaction_count += $tr['trs_count'];
        $all_coin_payouts += $tr['coins_sum'];

    }

//Echo title:
    echo '<a href="javascript:void(0);" onclick="$(\'.coins-issued\').toggleClass(\'hidden\');" class="large-stat"><span><i class="fal fa-coins"></i> <span class="coins-issued">'. fn___echo_number($all_coin_payouts) . '</span><span class="coins-issued hidden">'. number_format($all_coin_payouts) . '</span></span> Coins Awarded <i class="coins-issued fal fa-plus-circle"></i><i class="coins-issued fal fa-minus-circle hidden"></i></a>';


//Echo table:
    echo '<table class="table table-condensed table-striped stats-table coins-issued hidden" style="max-width:100%;">';


//Object Header:
    echo '<tr style="font-weight: bold;">';
    echo '<td style="text-align: left;">Ledger Transactions Types:</td>';
    echo '<td style="text-align: right;">Rate</td>';
    echo '<td style="text-align: right;"><i class="fal fa-coins"></i> Coins</td>';
    echo '</tr>';

    echo $table_body;


    //End Section:
    echo '</table>';
    echo '</div>';




    echo '<div class="col-md-4">' . fn___echo_leaderboard(null) . '</div>';



    //Count coins per Transaction
    echo '<div class="col-md-4">';

    //Fetch entity and it's children:
    $ie_ens = $this->Database_model->fn___en_fetch(array(
        'en_id' => 3000, //Industry Expert Sources
    ), array('en__children'), 0, 0, array('en_name' => 'ASC'));

    $expert_source_types = 0;
    $all_source_count = 0;
    $all_source_count_weight = 0;
    $all_mined_source_count = 0;
    $all_mined_source_count_weigh = 0;
    $table_body = '';

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
        $table_body .= '<tr>';
        $table_body .= '<td style="text-align: left;"><span style="width: 26px; display: inline-block; text-align: center;">'.( strlen($source_en['en_icon']) > 0 ? $source_en['en_icon'] : '<i class="fas fa-at grey-at"></i>' ).'</span><a href="/entities/'.$source_en['en_id'].'">'.$source_en['en_name'].'</a></td>';
        $table_body .= '<td style="text-align: right;"><span class="underdot" data-toggle="tooltip" title="Mench has identified '.$source_count.' notable industry expert '.strtolower($source_en['en_name']).' worth mining." data-placement="top">'.number_format($source_count, 0).'</span></td>';
        $table_body .= '<td style="text-align: right;"><span data-toggle="tooltip" title="'.number_format($mined_source_count,0).'/'.number_format($source_count,0).' '.$source_en['en_name'].' have been fully mined" data-placement="top" class="underdot">'.number_format(($mined_source_count/$source_count*100), 1).'%</span></td>';
        $table_body .= '</tr>';

    }

    //Calculate the weighted mined sources:
    if($all_source_count_weight > 0){
        $all_source_progress = number_format(($all_mined_source_count_weigh/$all_source_count_weight*100), 1);
    } else {
        $all_source_progress = 0;
    }

    //Echo title:
    echo '<a href="javascript:void(0);" onclick="$(\'.sources-mined\').toggleClass(\'hidden\');" class="large-stat"><span>'.fn___echo_en_icon($ie_ens[0]).' <span class="sources-mined">'. fn___echo_number($all_source_count) . '</span><span class="sources-mined hidden">'. number_format($all_source_count , 0) . '</span></span>'.$ie_ens[0]['en_name'].' <i class="sources-mined fal fa-plus-circle"></i><i class="sources-mined fal fa-minus-circle hidden"></i></a>';


    //Echo table:
    echo '<table class="table table-condensed table-striped stats-table sources-mined hidden" style="max-width:100%;">';

    //Object Header:
    echo '<tr style="font-weight: bold;">';
    echo '<td style="text-align: left;">'.$ie_ens[0]['en_name'].':</td>';
    echo '<td style="text-align: right;">Count</td>';
    echo '<td style="text-align: right;">Mined</td>';
    echo '</tr>';

    echo $table_body;

    echo '<tr style="font-weight: bold;">';
    echo '<td style="text-align: right;">Totals:&nbsp;</td>';
    echo '<td style="text-align: right;">'.number_format($all_source_count, 0).'</td>';
    echo '<td style="text-align: right;">'.$all_source_progress.'%</td>';
    echo '</tr>';


    //End Section:
    echo '</table>';
    echo '</div>';



    echo '</div>';

    echo '<div class="row"><div class="col-sm-6" style="padding-bottom: 40px;">&nbsp;</div></div>';

}











echo '<h1>Ledger Transactions</h1>';




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
    if(!fn___includes_any($key, array('in_status', 'en_status'))){
        $ini_filter[$key] = $value;
    }
}
$all_engs = $this->Database_model->fn___tr_fetch($ini_filter, array('en_type'), 0, 0, array('en_name' => 'DESC'), 'COUNT(tr_type_entity_id) as trs_count, SUM(tr_coins) as coins_sum, en_name, tr_type_entity_id', 'tr_type_entity_id, en_name');




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
$trs_count = $this->Database_model->fn___tr_fetch($filters, $join_by, 0, 0, array(), 'COUNT(tr_id) as trs_count, SUM(tr_coins) as coins_sum');
if(count($_GET) < 1){
    //This makes the public ledger focus on transactions with coins which is a nicer initial view into the ledger:
    $filters['tr_coins >'] = 0;
}
$trs = $this->Database_model->fn___tr_fetch($filters, $join_by, (fn___is_dev() ? 50 : 200));




//button to show:
echo '<a href="javascript:void();" onclick="$(\'.show-filter\').toggleClass(\'hidden\');" class="' . fn___echo_advance() . '">'.( $has_filters ? '<i class="fal fa-minus-circle show-filter"></i><i class="fal fa-plus-circle show-filter hidden"></i>' : '<i class="fal fa-plus-circle show-filter"></i><i class="fal fa-minus-circle show-filter hidden"></i>').' Toggle Filters</a>';



echo '<div class="' . fn___echo_advance() . '">';
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
echo '</div>';




if($has_filters){
    //Display Transactions:
    echo '<p style="margin: 10px 0 0 0;">Showing '.count($trs) . ( $trs_count[0]['trs_count'] > count($trs) ? ' of '. number_format($trs_count[0]['trs_count'] , 0) : '' ) .' transactions with '.number_format($trs_count[0]['coins_sum'], 0).' awarded coins:</p>';
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