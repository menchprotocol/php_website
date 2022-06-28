<?php

$commission_rate = doubleval(view_memory(6404,27017))/100;

$gross_units = 0;
$gross_revenue = 0;
$gross_paypal_fee = 0;
$gross_commission = 0;
$gross_payout = 0;
$gross_currencies = array();
$i_query = array();
$daily_sales = array();

//Generate list of payments:
$payment_es = $this->X_model->fetch(array(
    'x__up' => 27004,
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
    'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //ACTIVE
), array('x__down'), 0, 0, array('x__spectrum' => 'ASC', 'e__title' => 'ASC'));


if (isset($_GET['e__id'])) {

    //Show header:
    foreach($payment_es as $e){
        if($e['e__id']==$_GET['e__id']){

            echo '<h2>'.$e['e__title'].'</h2>';

            $i_query = $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
                'i__type IN (' . join(',', $this->config->item('n___27005')) . ')' => null, //Payment Idea
                'x__up' => $e['e__id'],
            ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC', 'i__title' => 'ASC'));
            break;
        }
    }

} else {

    //Fetch all assigned ideas:
    $assigned_i_ids = array();
    foreach($payment_es as $e){

        echo '<div><a href="/-27004?e__id='.$e['e__id'].'">'.$e['e__title'].'</a></div>';

        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
            'i__type IN (' . join(',', $this->config->item('n___27005')) . ')' => null, //Payment Idea
            'x__up' => $e['e__id'],
        ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC', 'i__title' => 'ASC')) as $i_assigned){
            array_push($assigned_i_ids, $i_assigned['x__right']);
        }
    }

    //Show all non-assigned payment ideas:
    $i_query = $this->I_model->fetch(array(
        'i__id NOT IN (' . join(',', $assigned_i_ids) . ')' => null,
        'i__type IN (' . join(',', $this->config->item('n___27005')) . ')' => null, //Payment Idea
    ), 0, 0, array('i__title' => 'ASC'));

}

//List all payment Ideas and their total earnings
$x_updated = 0;
$body_content = '';
foreach($i_query as $i){

    //Total earnings:
    $transaction_content = '';
    $total_units = 0;
    $total_revenue = 0;
    $total_paypal_fee = 0;
    $currencies = array();

    foreach($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERY COIN
        'x__left' => $i['i__id'],
    ), array(), 0) as $x){

        if(isset($_GET['include_e']) && strlen($_GET['include_e']) && !count($this->X_model->fetch(array(
                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__up IN (' . $_GET['include_e'] . ')' => null,
                'x__down' => $x['x__source'],
            )))){
            continue;
        }
        if(isset($_GET['exclude_e']) && intval($_GET['exclude_e']) && count($this->X_model->fetch(array(
                'x__up IN (' . $_GET['exclude_e'] . ')' => null, //All of these
                'x__down' => $x['x__source'],
                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            )))){
            continue;
        }

        $x__metadata = unserialize($x['x__metadata']);
        if(doubleval($x__metadata['mc_gross']) <= 0){
            continue;
        }
        $total_units++;
        $total_paypal_fee += doubleval($x__metadata['mc_fee']);
        $total_revenue += doubleval($x__metadata['mc_gross']);
        if(!in_array($x__metadata['mc_currency'], $currencies) && strlen($x__metadata['mc_currency'])>0){
            array_push($currencies, $x__metadata['mc_currency']);
        }
        if(!in_array($x__metadata['mc_currency'], $gross_currencies) && strlen($x__metadata['mc_currency'])>0){
            array_push($gross_currencies, $x__metadata['mc_currency']);
        }


        //Half only if not halfed before
        if(isset($_GET['half']) && !isset($x__metadata['mc_gross_old'])){
            $this->X_model->update($x['x__id'], array(
                'x__message' => number_format(($x__metadata['mc_gross']/2),2),
            ));
            update_metadata(6255, $x['x__id'], array(
                'mc_fee' => number_format(($x__metadata['mc_fee']/2),2),
                'mc_gross' => number_format(($x__metadata['mc_gross']/2),2),
                'mc_gross_old' => $x__metadata['mc_gross'],
                'mc_fee_old' => $x__metadata['mc_fee'],
            ));
            $x_updated++;
        }



        $item_parts = explode('-',$x__metadata['item_number']);
        $es = $this->E_model->fetch(array(
            'e__id' => $item_parts[3],
        ));
        $this_commission = $x__metadata['mc_gross']*$commission_rate;
        $this_payout = $x__metadata['mc_gross']-$x__metadata['mc_fee']-$this_commission;


        $transaction_content .= '<tr class="tr_row transactions_'.$i['i__id'].' hidden" title="Transaction #'.$x['x__id'].'">';
        $transaction_content .= '<td><div style="padding-left: 34px;">'.( count($es) ? '<a href="/@'.$es[0]['e__id'].'" style="font-weight:bold; display: inline-block;"><u>'.$es[0]['e__title'].'</u></a> ' : '' ).$x__metadata['first_name'].' '.$x__metadata['last_name'].'</div></td>';
        $transaction_content .= '<td style="text-align: right;">1x</td>';
        $transaction_content .= '<td style="text-align: right;">$'.number_format($x__metadata['mc_gross'], 2).'</td>';
        $transaction_content .= '<td class="advance_columns hidden" style="text-align: right;">+$'.number_format($x__metadata['mc_gross'], 2).'</td>';
        $transaction_content .= '<td class="advance_columns hidden" style="text-align: right;" title="'.($commission_rate*100).'%">-$'.number_format($this_commission, 2).'</td>';
        $transaction_content .= '<td class="advance_columns hidden" style="text-align: right;" title="'.($x__metadata['mc_fee']/$x__metadata['mc_gross']*100).'%">-$'.number_format($x__metadata['mc_fee'], 2).'</td>';
        $transaction_content .= '<td style="text-align: right;" title="'.(( $x__metadata['mc_gross']>0 ? $this_payout/$x__metadata['mc_gross'] : 0 )*100).'%"><b>$'.number_format($this_payout, 2).'</b></td>';
        $transaction_content .= '<td style="text-align: right;">'.$x__metadata['mc_currency'].'</td>';
        $transaction_content .= '<td class="advance_columns hidden" style="text-align: right;" title="Transaction ID">'. $x['x__id'].'</td>';
        $transaction_content .= '<td style="text-align: right;" id="refund_'.$x['x__id'].'"><a href="#" onclick="paypal_refund('.$x['x__id'].', '.number_format($x__metadata['mc_gross'], 2).')" style="font-weight:bold;"><u>Refund</u></a> <a href="https://www.paypal.com/activity/payment/'.$x__metadata['txn_id'].'" target="_blank"><i class="fas fa-info-circle"></i></a> <a href="/-4341?x__id='.$x['x__id'].'" target="_blank"><i class="fas fa-atlas"></i></a></td>';

        $transaction_content .= '</tr>';

        $date = date("y-m-d", strtotime($x['x__time']));
        if(isset($daily_sales[$date])){
            $daily_sales[$date] += $x__metadata['mc_gross'];
        } else {
            $daily_sales[$date] = $x__metadata['mc_gross'];
        }

    }
    $total_commission = ( $commission_rate * $total_revenue );
    $payout = $total_revenue-$total_commission-$total_paypal_fee;


    $gross_units += $total_units;
    $gross_revenue += $total_revenue;
    $gross_paypal_fee += $total_paypal_fee;
    $gross_commission += $total_commission;
    $gross_payout += $payout;

    $has_limits = $this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type' => 4983, //References
        'x__right' => $i['i__id'],
        'x__up' => 26189,
    ), array(), 1);
    $available_units = (count($has_limits) && is_numeric($has_limits[0]['x__message']) ? intval($has_limits[0]['x__message']) : '∞');

    if(fmod($total_units, 2)==1){
        $transaction_content .= '<tr class="tr_row hidden"></tr>';
    }

    $body_content .= '<tr>';
    $body_content .= '<td><a href="javascript:void(0)" onclick="$(\'.transactions_'.$i['i__id'].'\').toggleClass(\'hidden\');" style="font-weight:bold;"><u>'.$i['i__title'].'</u></a></td>';
    $body_content .= '<td style="text-align: right;">'.$total_units.'</td>';
    $body_content .= '<td style="text-align: right;">'.$available_units.'</td>';
    $body_content .= '<td style="text-align: right;">$'.number_format(( $total_units > 0 ? $total_revenue / $total_units : 0 ), 2).'</td>';
    $body_content .= '<td class="advance_columns hidden" style="text-align: right;">+$'.number_format($total_revenue, 2).'</td>';
    $body_content .= '<td class="advance_columns hidden" style="text-align: right;" title="'.($commission_rate*100).'%">-$'.number_format($total_commission, 2).'</td>';
    $body_content .= '<td class="advance_columns hidden" style="text-align: right;" title="'.(( $total_revenue>0 ? $total_paypal_fee/$total_revenue : 0 )*100).'%">-$'.number_format($total_paypal_fee, 2).'</td>';
    $body_content .= '<td style="text-align: right;" title="'.(( $total_revenue>0 ? $payout/$total_revenue : 0 )*100).'%"><b>$'.number_format($payout, 2).'</b></td>';
    $body_content .= '<td style="text-align: right;">'.join(', ',$currencies).'</td>';
    $body_content .= '<td class="advance_columns hidden" style="text-align: right;" >&nbsp;</td>';
    $body_content .= '<td style="text-align: right;"><a href="/~'.$i['i__id'].'" style="font-weight:bold;"><u>Edit</u></a></td>';
    $body_content .= '</tr>';
    $body_content .= $transaction_content;

}


if(count($i_query)){

    echo '<div style="text-align: center;"><a href="javascript:void(0)" onclick="$(\'.advance_columns\').toggleClass(\'hidden\');" class="texttransparent">Toggle Advance Columns</a></div>';


    echo '<table id="sortable_table" class="table table-sm table-striped image-mini">';
    echo '<tr style="vertical-align: baseline;">';
    echo '<th id="th_primary">Paid Ideas</th>';
    echo '<th style="text-align: right;" id="th_paid">Sold</th>';
    echo '<th style="text-align: right;" id="th_paid">Limit</th>';
    echo '<th style="text-align: right;" id="th_average">Price</th>';
    echo '<th style="text-align: right;" class="advance_columns hidden" id="th_rev">Revenue</th>';
    echo '<th style="text-align: right;" class="advance_columns hidden" id="th_payout">Commission</th>';
    echo '<th style="text-align: right;" class="advance_columns hidden" id="th_payout">Paypal Fee</th>';
    echo '<th style="text-align: right;" id="th_payout">Payout</th>';
    echo '<th style="text-align: right;" id="th_currency">&nbsp;</th>';
    echo '<th style="text-align: right;" class="advance_columns hidden" id="th_payout">Transaction ID</th>';
    echo '<th style="text-align: right;">Action</th>';
    echo '</tr>';
    echo $body_content;

    echo '<tr>';
    echo '<th style="text-align: right;" id="th_primary">Totals</th>';
    echo '<th style="text-align: right;">'.$gross_units.'</th>';
    echo '<th style="text-align: right;">&nbsp;</th>';
    echo '<th style="text-align: right;">$'.number_format(( $gross_units > 0 ? $gross_revenue / $gross_units : 0 ), 2).'</th>';
    echo '<th style="text-align: right;" class="advance_columns hidden">+$'.number_format($gross_revenue, 2).'</th>';
    echo '<th style="text-align: right;" class="advance_columns hidden" title="'.($commission_rate*100).'%">-$'.number_format($gross_commission, 2).'</th>';
    echo '<th style="text-align: right;" class="advance_columns hidden" title="'.(( $gross_revenue>0 ? $gross_paypal_fee/$gross_revenue : 0 )*100).'%">-$'.number_format($gross_paypal_fee, 2).'</th>';
    echo '<th style="text-align: right;" title="'.(( $gross_revenue>0 ? $gross_payout/$gross_revenue : 0 )*100).'%"><b>$'.number_format($gross_payout, 2).'</b></th>';
    echo '<th style="text-align: right;">'.join(', ',$gross_currencies).'</th>';
    echo '<th style="text-align: right;" class="advance_columns hidden">&nbsp;</th>';
    echo '<th style="text-align: right;">&nbsp;</th>';
    echo '</tr>';
    echo '</table>';
    echo ( $x_updated > 0 ? '<div>'.$x_updated.' Halfed!<hr /></div>' : '' );






    ksort($daily_sales);

    //Create % chart:
    $total_sales = 0;
    $daily_percent = array();
    foreach($daily_sales as $day => $sales){
        $total_sales += $sales;
        $daily_percent[$day] = $total_sales/$gross_revenue*100;
    }

    echo '<div id="chart_div" style="margin:0 0 21px;"></div>';
    echo '<div id="chart_div_percent" style="margin:0 0 21px;"></div>';
    ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

        // Load the Visualization API and the corechart package.
        google.charts.load('current', {'packages':['corechart']});

        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart);

        // Callback that creates and populates a data table,
        // instantiates the pie chart, passes in the data and
        // draws it.
        function drawChart() {
            var chart = new google.visualization.ColumnChart(document.getElementById('chart_div_percent'));
            var options = {
                hAxis: {showTextEvery:1, slantedText:true, slantedTextAngle:45}
            }
            var data = google.visualization.arrayToDataTable([
                ['Day', 'Percent'],
                <?php
                foreach($daily_percent as $day => $sales){
                    echo "['".$day."', ".$sales."],";
                }
                ?>
            ]);
            chart.draw(data, options);
        }


        // Set a callback to run when the Google Visualization API is loaded.
        google.charts.setOnLoadCallback(drawChart2);

        // Callback that creates and populates a data table,
        // instantiates the pie chart, passes in the data and
        // draws it.
        function drawChart2() {
            var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
            var options = {
                hAxis: {showTextEvery:1, slantedText:true, slantedTextAngle:45}
            }
            var data = google.visualization.arrayToDataTable([
                ['Day', 'Sales'],
                <?php
                foreach($daily_sales as $day => $sales){
                    echo "['".$day."', ".number_format($sales, 0, '.', '')."],";
                }
                ?>
            ]);
            chart.draw(data, options);
        }
    </script>
    <?php



}

?>


<style>
    /* CSS Adjustments for Printing View */
    .fixed-top{
        background-color: transparent !important;
    }
    .top_nav{
        display:none !important;
    }
    .table-striped tr:nth-of-type(odd) td {
        background-color: #FFFFFF !important;
        -webkit-print-color-adjust:exact;
    }
    .table-striped td {
        border-bottom: 1px dotted #FFFFFF !important;
    }
    .fa-filter, .fa-sort{
        font-size: 1.1em !important;
        margin-bottom: 3px;
    }
    th{
        cursor: ns-resize !important;
        border: 0 !important;
    }
    tr th{
       padding: 8px 0 !important;
        font-weight: bold;
        font-size: 1.2em;
    }
    tr td {
        padding: 5px 0 !important;
        font-size: 1.1em;
    }
    .tr_row td{
        padding: 1px 0 !important;
        font-size: 0.9em;
    }

    .vertical_col {
        writing-mode: tb-rl;
        white-space: nowrap;
        display: block;
        padding-bottom: 8px;
    }
    .col_stat{
        height:55px;
        display:inline-block;
        text-align: left;
        width: 8px;
    }
</style>
<script>

    function paypal_refund(x__id, transaction_total){
        var confirm_refund = confirm("Process a full refund of "+transaction_total+"?");
        if(confirm_refund){
            $.post("/x/paypal_refund", {
                x__id: x__id,
                refund_total: transaction_total
            }, function (data) {
                if(data.status){
                    $('#refund_'+x__id).html('✅ Refunded');
                } else {
                    alert(data.message);
                }
            });
        }
    }

</script>