

<?php

//Display filters:
echo '<h1 style="display:block; text-align: center !important; margin-top: 40px;">Platform Stats</h1>';
echo '<p style="text-align: center !important; font-size: 1.3em;">Key metrics that offer a broad overview of the Mench platform.</p>';

//Load core Mench Objects:
$en_all_4534 = $this->config->item('en_all_4534');

//Just be logged in to browse:
$udata = fn___en_auth();

if(!$udata){
    echo '<style> .main-raised { max-width:1240px !important; } </style>';

}


echo '<div class="row stat-row">';
foreach (fn___echo_status() as $object_id => $statuses) {


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
            continue;
        }

        //Display this status count:
        $this_ui .= '<tr>';
        $this_ui .= '<td style="text-align: left;">'.fn___echo_status($object_id, $status_num, false, 'top').'</td>';
        $this_ui .= '<td style="text-align: right;">'.( $count > 0 ? '<a href="/ledger?'.$object_id.'='.$status_num.'&tr_type_en_id='.$created_en_type_id.'"  data-toggle="tooltip" title="View Transactions" data-placement="top">'.number_format($count,0).'</a>' : $count ).'</td>';
        $this_ui .= '</tr>';


        //Increase total counter:
        $this_totals += $count;
    }



    //Start section:
    echo '<div class="'.$spacing.' col-md-4">';

    echo '<a href="javascript:void(0);" onclick="$(\'.obj-'.$object_id.'\').toggleClass(\'hidden\');" class="large-stat"><span>'.$en_all_4534[$obj_en_id]['m_icon']. ' <span class="obj-'.$object_id.'">'. fn___echo_number($this_totals) . '</span><span class="obj-'.$object_id.' hidden">'. number_format($this_totals) . '</span></span>'.$en_all_4534[$obj_en_id]['m_name'].' <i class="obj-'.$object_id.' fal fa-plus-circle"></i><i class="obj-'.$object_id.' fal fa-minus-circle hidden"></i></a>';

    echo '<table class="table table-condensed table-striped stats-table mini-stats-table obj-'.$object_id.' hidden">';

    //Object Header:
    echo '<tr style="font-weight: bold;">';
    echo '<td style="text-align: left;">Status</td>';
    echo '<td style="text-align: right;">Count</td>';
    echo '</tr>';

    //Object Total count:
    echo $this_ui;


    //End Section:
    echo '</table>';
    echo '</div>';

}

echo '</div>';










//echo member stats:
/*
echo '<div class="row stat-row">';

    echo '<div class="col-md-4">';

    echo '</div>';

    echo '<div class="col-md-4">';

    echo '</div>';

    echo '<div class="col-md-4">';

    echo '</div>';

echo '</div>';
*/




echo '<div class="row stat-row">';





//Count coins per Transaction
echo '<div class="col-md-6">';

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
    $weight = ( substr_count($source_en['tr_content'], '&weight=')==1 ? intval(fn___one_two_explode('&weight=','',$source_en['tr_content'])) : 0 );
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
$all_source_progress = number_format(($all_mined_source_count_weigh/$all_source_count_weight*100), 1);

//Echo title:
echo '<a href="javascript:void(0);" onclick="$(\'.sources-mined\').toggleClass(\'hidden\');" class="large-stat"><span>'.fn___echo_en_icon($ie_ens[0]).' <span class="sources-mined">'. round($all_source_progress, 0) . '</span><span class="sources-mined hidden">'. $all_source_progress . '</span>%</span>Of '.number_format($all_source_count , 0).' sources mined <i class="sources-mined fal fa-plus-circle"></i><i class="sources-mined fal fa-minus-circle hidden"></i></a>';


//Echo table:
echo '<table class="table table-condensed table-striped stats-table sources-mined hidden" style="max-width:100%;">';

//Object Header:
echo '<tr style="font-weight: bold;">';
echo '<td style="text-align: left;">'.strtolower($ie_ens[0]['en_name']).':</td>';
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







//Count coins per Transaction
echo '<div class="col-md-6">';

//Count variables:
$all_engs = $this->Database_model->fn___tr_fetch(array(
    'tr_coins !=' => 0,
), array('en_type'), 0, 0, array('en_name' => 'DESC'), 'COUNT(tr_type_en_id) as trs_count, SUM(tr_coins) as coins_sum, en_name, en_icon, tr_type_en_id', 'tr_type_en_id, en_name, en_icon');

$all_transaction_count = 0;
$all_coin_payouts = 0;
$table_body = '';
foreach ($all_engs as $tr) {

    //DOes it have a rate?
    $rate_trs = $this->Database_model->fn___tr_fetch(array(
        'tr_status >=' => 2, //Must be published+
        'en_status >=' => 2, //Must be published+
        'tr_en_parent_id' => 4374, //Mench Coins
        'tr_type_en_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
        'tr_en_child_id' => $tr['tr_type_en_id'],
    ), array('en_child'), 1);



    //Echo stats:
    $table_body .= '<tr>';
    $table_body .= '<td style="text-align: left;"><span style="width: 26px; display: inline-block; text-align: center;">'.( strlen($tr['en_icon']) > 0 ? $tr['en_icon'] : '<i class="fas fa-at grey-at"></i>' ).'</span><a href="/entities/'.$tr['tr_type_en_id'].'">'.$tr['en_name'].'</a></td>';
    $table_body .= '<td style="text-align: right;"><span class="underdot" data-toggle="tooltip" title="Each '.$tr['en_name'].' transaction currently issues '.$rate_trs[0]['tr_content'].' coins. Transaction rates may fluctuate." data-placement="top">'.( count($rate_trs) > 0 ? number_format($rate_trs[0]['tr_content'],0) : 'Not Set' ).'</span></td>';
    $table_body .= '<td style="text-align: right;"><a href="/ledger?tr_type_en_id='.$tr['tr_type_en_id'].'"  data-toggle="tooltip" title="View all '.number_format($tr['trs_count'],0).' transactions on the ledger" data-placement="top">'.number_format($tr['coins_sum'], 0).'</a></td>';
    $table_body .= '</tr>';

    $all_transaction_count += $tr['trs_count'];
    $all_coin_payouts += $tr['coins_sum'];

}

//Echo title:
echo '<a href="javascript:void(0);" onclick="$(\'.coins-issued\').toggleClass(\'hidden\');" class="large-stat"><span><i class="fal fa-coins"></i> <span class="coins-issued">'. fn___echo_number($all_coin_payouts) . '</span><span class="coins-issued hidden">'. number_format($all_coin_payouts) . '</span></span> Mench coins issued to date <i class="coins-issued fal fa-plus-circle"></i><i class="coins-issued fal fa-minus-circle hidden"></i></a>';


//Echo table:
echo '<table class="table table-condensed table-striped stats-table coins-issued hidden" style="max-width:100%;">';


//Object Header:
echo '<tr style="font-weight: bold;">';
echo '<td style="text-align: left;">Ledger transactions:</td>';
echo '<td style="text-align: right;">Rate</td>';
echo '<td style="text-align: right;"><i class="fal fa-coins"></i> Coins</td>';
echo '</tr>';

echo $table_body;

echo '<tr style="font-weight: bold;">';
echo '<td colspan="2" style="text-align: right;">Totals:&nbsp;</td>';
echo '<td style="text-align: right;"><span data-toggle="tooltip" title="'.number_format($all_transaction_count,0).' Transactions" data-placement="top">'.number_format($all_coin_payouts,0).'</td>';
echo '</tr>';


//End Section:
echo '</table>';
echo '</div>';









echo '</div>';





echo '<div class="row stat-row">';
echo '<div class="col-md-6">' . fn___echo_leaderboard(null) . '</div>';
echo '<div class="col-md-6">' . fn___echo_leaderboard(7) . '</div>';
echo '</div>';





//Give some space for the chat button not to overlap:
echo '<div class="row"><div class="col-sm-6" style="padding-bottom: 40px;">&nbsp;</div></div>';




?>