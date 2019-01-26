

<?php

//Display filters:
echo '<h5 class="badge badge-h" style="display: inline-block;"><i class="fas fa-chart-bar"></i> Platform Stats</h5>';

//Load core Mench Objects:
$en_all_4534 = $this->config->item('en_all_4534');

//Just be logged in to browse:
$udata = fn___en_auth();

if(!$udata){
    echo '<style> .main-raised { max-width: 1240px !important; } </style>';

}


echo '<div class="row">';
foreach (fn___echo_status() as $object_id => $statuses) {


    //Define object type and run count query:
    if($object_id=='in_status'){

        $obj_en_id = 4535; //Intents
        $created_en_type_id = 4250;
        $objects_count = $this->Database_model->fn___in_fetch(array(), array(), 0, 0, array(), 'in_status, COUNT(in_id) as totals', 'in_status');

    } elseif($object_id=='en_status'){

        $obj_en_id = 4536; //Entities
        $created_en_type_id = 4251;
        $objects_count = $this->Database_model->fn___en_fetch(array(), array('skip_en__parents'), 0, 0, array(), 'en_status, COUNT(en_id) as totals', 'en_status');

    } elseif($object_id=='tr_status'){

        $obj_en_id = 4341; //Ledger
        $created_en_type_id = 0; //No particular filters needed
        $objects_count = $this->Database_model->fn___tr_fetch(array(), array(), 0, 0, array(), 'tr_status, COUNT(tr_id) as totals', 'tr_status');

    } else {

        //Unsupported
        continue;

    }


    //Start section:
    echo '<div class="col-sm-4">';
    echo '<table class="table table-condensed table-striped stats-table" style="max-width:300px;">';


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
        $this_ui .= '<tr class="obj-'.$object_id.'" style="display:none;">';
        $this_ui .= '<td style="text-align: left;">'.fn___echo_status($object_id, $status_num, false, 'top').'</td>';
        $this_ui .= '<td style="text-align: right;">'.( $count > 0 ? '<a href="/ledger?'.$object_id.'='.$status_num.'&tr_en_type_id='.$created_en_type_id.'"  data-toggle="tooltip" title="View Transactions" data-placement="top">'.number_format($count,0).'</a>' : $count ).'</td>';
        $this_ui .= '</tr>';


        //Increase total counter:
        $this_totals += $count;
    }


    //Object Header:
    echo '<tr>';
    echo '<td colspan="2" style="text-align: left;"><a href="javascript:void(0);" onclick="$(\'.obj-'.$object_id.'\').toggle();" style="padding:0; font-weight:300; font-size: 1.6em; line-height:160%; text-decoration: none;"><span style="font-size: 2em; display: block; margin-left: -3px;">'. number_format($this_totals) . '</span>'.$en_all_4534[$obj_en_id]['m_name'].' <i class="fal fa-plus-circle"></i></a></td>';
    echo '</tr>';


    //Object Total count:
    echo $this_ui;


    //End Section:
    echo '</table>';
    echo '</div>';

}
echo '</div>';



//Second row...
echo '<h5 class="badge badge-h" style="display:inline-block;"><i class="fal fa-coins"></i> Coins Issued</h5>';

echo '<div class="row">';


//Count coins per Transaction
echo '<div class="col-sm-4">';
echo '<table class="table table-condensed table-striped stats-table" style="max-width:100%;">';


//Object Header:
echo '<tr style="font-weight: bold;">';
echo '<td style="text-align: left;">Transaction Types</td>';
echo '<td style="text-align: right;">Count</td>';
echo '<td style="text-align: right;">Rate</td>';
echo '<td style="text-align: right;">Coins <i class="fal fa-coins"></i></td>';
echo '</tr>';

//Object Stats grouped by Status:
$all_transaction_count = 0;
$all_coin_payouts = 0;
$all_engs = $this->Database_model->fn___tr_fetch(array('tr_en_credit_id >' => 0), array('en_type'), 0, 0, array('coins_sum' => 'DESC'), 'COUNT(tr_en_type_id) as trs_count, SUM(tr_coins) as coins_sum, en_name, en_icon, tr_en_type_id', 'tr_en_type_id, en_name, en_icon');
foreach ($all_engs as $tr) {

    //DOes it have a rate?
    $rate_trs = $this->Database_model->fn___tr_fetch(array(
        'tr_status >=' => 2, //Must be published+
        'en_status >=' => 2, //Must be published+
        //'tr_en_type_id' => 4319, //Number
        'tr_en_parent_id' => 4374, //Mench Coins
        'tr_en_child_id' => $tr['tr_en_type_id'],
    ), array('en_child'), 1);

    //Echo stats:
    echo '<tr>';
    echo '<td style="text-align: left;"><span style="width: 26px; display: inline-block; text-align: center;">'.( strlen($tr['en_icon']) > 0 ? $tr['en_icon'] : '<i class="fas fa-at grey-at"></i>' ).'</span><a href="/entities/'.$tr['tr_en_type_id'].'">'.$tr['en_name'].'</a></td>';
    echo '<td style="text-align: right;">'.number_format($tr['trs_count'],0).'</td>';
    echo '<td style="text-align: right;">'.( count($rate_trs) > 0 ? 'x'.number_format($rate_trs[0]['tr_content'],0) : '-' ).'</td>';
    echo '<td style="text-align: right;"><a href="/ledger?tr_en_type_id='.$tr['tr_en_type_id'].'"  data-toggle="tooltip" title="View Transactions" data-placement="top">'.number_format($tr['coins_sum'], 0).'</a></td>';
    echo '</tr>';

    $all_transaction_count += $tr['trs_count'];
    $all_coin_payouts += $tr['coins_sum'];

}

echo '<tr style="font-weight: bold;">';
echo '<td style="text-align: right;">Totals:&nbsp;</td>';
echo '<td style="text-align: right;">'.number_format($all_transaction_count,0).'</td>';
echo '<td style="text-align: right;">x'.round($all_coin_payouts/$all_transaction_count).'</td>';
echo '<td style="text-align: right;">'.number_format($all_coin_payouts,0).'</td>';
echo '</tr>';


//End Section:
echo '</table>';
echo '</div>';








//Count coins per Miner
echo '<div class="col-sm-4">';
echo '<table class="table table-condensed table-striped stats-table" style="max-width:100%;">';


//Object Header:
echo '<tr style="font-weight: bold;">';
echo '<td colspan="2" style="text-align: left;">All-Time Miners</td>';
echo '<td style="text-align: right;">Count</td>';
echo '<td style="text-align: right;">Rate</td>';
echo '<td style="text-align: right;">Coins <i class="fal fa-coins"></i></td>';
echo '</tr>';

//Object Stats grouped by Status:
$all_transaction_count = 0;
$all_coin_payouts = 0;
$all_engs = $this->Database_model->fn___tr_fetch(array('tr_en_credit_id >' => 0), array('en_credit'), 25, 0, array('coins_sum' => 'DESC'), 'COUNT(tr_en_credit_id) as trs_count, SUM(tr_coins) as coins_sum, en_name, en_icon, tr_en_credit_id', 'tr_en_credit_id, en_name, en_icon');
foreach ($all_engs as $count=>$tr) {

    //Echo stats:
    echo '<tr>';
    echo '<td style="text-align: center;">#'.($count+1).'</td>';
    echo '<td style="text-align: left;"><span style="width: 29px; display: inline-block; text-align: center;">'.( strlen($tr['en_icon']) > 0 ? $tr['en_icon'] : '<i class="fas fa-at grey-at"></i>' ).'</span><a href="/entities/'.$tr['tr_en_credit_id'].'">'.$tr['en_name'].'</a></td>';
    echo '<td style="text-align: right;">'.number_format($tr['trs_count'],0).'</td>';
    echo '<td style="text-align: right;">x'.round(($tr['coins_sum']/$tr['trs_count']),0).'</td>';
    echo '<td style="text-align: right;"><a href="/ledger?tr_en_credit_id='.$tr['tr_en_credit_id'].'"  data-toggle="tooltip" title="View Transactions" data-placement="top">'.number_format($tr['coins_sum'], 0).'</a></td>';
    echo '</tr>';

    $all_transaction_count += $tr['trs_count'];
    $all_coin_payouts += $tr['coins_sum'];

}

echo '<tr style="font-weight: bold;">';
echo '<td colspan="2" style="text-align: right;">Top '.($count+1).' Totals:&nbsp;</td>';
echo '<td style="text-align: right;">'.number_format($all_transaction_count,0).'</td>';
echo '<td style="text-align: right;">x'.round($all_coin_payouts/$all_transaction_count).'</td>';
echo '<td style="text-align: right;">'.number_format($all_coin_payouts,0).'</td>';
echo '</tr>';


//End Section:
echo '</table>';
echo '</div>';








//Count coins per Miner
echo '<div class="col-sm-4">';
echo '<table class="table table-condensed table-striped stats-table" style="max-width:100%;">';


//Object Header:
echo '<tr style="font-weight: bold;">';
echo '<td colspan="2" style="text-align: left;">Last 7 Days Miners</td>';
echo '<td style="text-align: right;">Count</td>';
echo '<td style="text-align: right;">Rate</td>';
echo '<td style="text-align: right;">Coins <i class="fal fa-coins"></i></td>';
echo '</tr>';

//Object Stats grouped by Status:
$all_transaction_count = 0;
$all_coin_payouts = 0;
$seven_days_ago = date("Y-m-d" , (time() - (7 * 24 * 3600)));
$all_engs = $this->Database_model->fn___tr_fetch(array(
    'tr_timestamp >=' => $seven_days_ago.' 00:00:00',
    'tr_en_credit_id >' => 0,
), array('en_credit'), 25, 0, array('coins_sum' => 'DESC'), 'COUNT(tr_en_credit_id) as trs_count, SUM(tr_coins) as coins_sum, en_name, en_icon, tr_en_credit_id', 'tr_en_credit_id, en_name, en_icon');
foreach ($all_engs as $count=>$tr) {

    //Echo stats:
    echo '<tr>';
    echo '<td style="text-align: center;">#'.($count+1).'</td>';
    echo '<td style="text-align: left;"><span style="width: 29px; display: inline-block; text-align: center;">'.( strlen($tr['en_icon']) > 0 ? $tr['en_icon'] : '<i class="fas fa-at grey-at"></i>' ).'</span><a href="/entities/'.$tr['tr_en_credit_id'].'">'.$tr['en_name'].'</a></td>';
    echo '<td style="text-align: right;">'.number_format($tr['trs_count'],0).'</td>';
    echo '<td style="text-align: right;">x'.round(($tr['coins_sum']/$tr['trs_count']),0).'</td>';
    echo '<td style="text-align: right;"><a href="/ledger?start_range='.$seven_days_ago.'&tr_en_credit_id='.$tr['tr_en_credit_id'].'"  data-toggle="tooltip" title="View Transactions" data-placement="top">'.number_format($tr['coins_sum'], 0).'</a></td>';
    echo '</tr>';

    $all_transaction_count += $tr['trs_count'];
    $all_coin_payouts += $tr['coins_sum'];

}

echo '<tr style="font-weight: bold;">';
echo '<td colspan="2" style="text-align: right;">Top '.($count+1).' Totals:&nbsp;</td>';
echo '<td style="text-align: right;">'.number_format($all_transaction_count,0).'</td>';
echo '<td style="text-align: right;">x'.round($all_coin_payouts/$all_transaction_count).'</td>';
echo '<td style="text-align: right;">'.number_format($all_coin_payouts,0).'</td>';
echo '</tr>';


//End Section:
echo '</table>';
echo '</div>';




echo '</div>'; //End second row



//Give some space for the chat button not to overlap:
echo '<div class="row"><div class="col-sm-6" style="padding-bottom: 40px;">&nbsp;</div></div>';




?>