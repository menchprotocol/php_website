<?php

$commission_rate = intval(website_setting(27017))/100;
$superpower_28727 = superpower_active(28727, true);
$e___6287 = $this->config->item('e___6287'); //APP
$gross_transactions = 0;
$gross_sales = 0;
$gross_revenue = 0;
$gross_paypal_fee = 0;
$gross_commission = 0;
$gross_payout = 0;
$gross_currencies = array();
$i_query = array();
$daily_sales = array();
$origin_sales = array();
$all_sources = array();



if(!isset($_GET['e__id']) || $_GET['e__id']<1){


    echo '<h1>'.$e___6287[27004]['m__title'].'</h1>';
    foreach($this->E_model->fetch_recursive(12274, $member_e['e__id'], array(27004)) as $e){
        echo '<div><a href="/-27004?e__id='.$e['e__id'].'" class="main__title">'.$e['e__title'].'</a></div>';
    }

} else {


    //Show header:
    echo '<div style="padding: 0 0 0 10px; font-weight: bold; margin-bottom: -13px;"><a href="/-27004"><b>'.$e___6287[27004]['m__title'].'</b></a></div>';

    $es = $this->E_model->fetch(array(
        'e__id' => $_GET['e__id'],
    ));
    echo '<h2>'.$es[0]['e__title'].'</h2>';

    $i_query = $this->X_model->fetch(array(
        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
        'i__type IN (' . join(',', $this->config->item('n___30469')) . ')' => null, //Payment Idea
        'x__up' => $_GET['e__id'],
    ), array('x__right'), 0, 0, array('x__weight' => 'ASC', 'i__title' => 'ASC'));


    //List all payment Ideas and their total earnings
    $x_updated = 0;
    $sale_type_content = '';
    foreach($i_query as $i){

        //Total earnings:
        $transaction_content = '';
        $total_transactions = 0;
        $total_sales = 0;
        $total_revenue = 0;
        $total_paypal_fee = 0;
        $currencies = array();

        foreach($this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__left' => $i['i__id'],
        ), array(), 0, 0, array('x__creator' => 'ASC')) as $x){

            if(isset($_GET['include_e']) && strlen($_GET['include_e']) && !count($this->X_model->fetch(array(
                    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__up IN (' . $_GET['include_e'] . ')' => null,
                    'x__down' => $x['x__creator'],
                )))){
                continue;
            }
            if(isset($_GET['exclude_e']) && intval($_GET['exclude_e']) && count($this->X_model->fetch(array(
                    'x__up IN (' . $_GET['exclude_e'] . ')' => null, //All of these
                    'x__down' => $x['x__creator'],
                    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                )))){
                continue;
            }

            $x__metadata = unserialize($x['x__metadata']);
            $total_transactions++;
            $this_quantity = 1;//Default assumption:

            //Source for quantity?
            if($x__metadata['mc_gross']<0 && $x['x__reference']>0){
                //This is a refund, fetch quantity from original transaction:
                foreach($this->X_model->fetch(array(
                    'x__up' => $x['x__reference'],
                ), array(), 0) as $x2){
                    $x__metadata2 = unserialize($x2['x__metadata']);
                }
            }

            if(isset($x__metadata['quantity']) && $x__metadata['quantity']>1){
                $this_quantity = $x__metadata['quantity'];
            } elseif(isset($x__metadata2) && $x__metadata2['quantity']>1){
                $this_quantity = $x__metadata2['quantity'];
            } else {
                for($t=20;$t>=2;$t--){
                    if(substr_count(strtolower($i['i__title']),$t.'x')==1){
                        $this_quantity = $t;
                        break;
                    }
                }
            }

            //Count only if a payment idea:
            if(!in_array($x['x__type'], $this->config->item('n___30469'))){
                $x__metadata['mc_gross'] = 0;
                $x__metadata['mc_fee'] = 0;
                $x__metadata['mc_currency'] = '';
                $x__metadata['item_number'] = '';
                $x__metadata['first_name'] = '';
                $x__metadata['last_name'] = '';
            }

            if(!isset($x__metadata['mc_currency'])){
                continue;
            }

            $this_commission = $x__metadata['mc_gross']*$commission_rate;
            $this_payout = $x__metadata['mc_gross']-$x__metadata['mc_fee']-$this_commission;
            if($this_payout < 0){
                $this_quantity = $this_quantity * -1;
            }

            $total_sales += $this_quantity;
            $total_paypal_fee += doubleval($x__metadata['mc_fee']);
            $total_revenue += doubleval($x__metadata['mc_gross']);
            if(!in_array($x__metadata['mc_currency'], $currencies) && strlen($x__metadata['mc_currency'])>0){
                array_push($currencies, $x__metadata['mc_currency']);
            }
            if(!in_array($x__metadata['mc_currency'], $gross_currencies) && strlen($x__metadata['mc_currency'])>0){
                array_push($gross_currencies, $x__metadata['mc_currency']);
            }

            $item_parts = explode('-',$x__metadata['item_number']);
            $this_sourced = intval(isset($item_parts[3]) ? $item_parts[3] : $x['x__creator'] );
            array_push($all_sources, $this_sourced);
            $es = $this->E_model->fetch(array(
                'e__id' => $this_sourced,
            ));


            $transaction_content .= '<tr class="transaction_columns transactions_'.$i['i__id'].' hidden">';
            $transaction_content .= '<td>'.( count($es) ? '<span class="icon-block-xs source_cover_micro">'.view_cover($es[0]['e__cover'],true).'</span><a href="/@'.$es[0]['e__id'].'" style="font-weight:bold; display: inline-block;"><u>'.$es[0]['e__title'].'</u></a> ' : '' ).$x__metadata['first_name'].' '.$x__metadata['last_name'].' Ticket'.$i['i__id'].'</td>';
            $transaction_content .= '<td style="text-align: right;" class="advance_columns hidden">'.( $x__metadata['mc_gross']!=0 && strlen($x__metadata['txn_id'])>0 ? '<a href="https://www.paypal.com/activity/payment/'.$x__metadata['txn_id'].'" target="_blank" data-toggle="tooltip" data-placement="top" title="View Paypal Transaction"><i class="fab fa-paypal" style="font-size:1em !important;"></i></a> ' : '' ).'<a href="/-4341?x__id='.$x['x__id'].'" target="_blank" style="font-size:1em !important;" data-toggle="tooltip" data-placement="top" title="View Platform Transaction"><i class="fal fa-atlas"></i></a></td>';
            $transaction_content .= '<td style="text-align: right;" class="advance_columns hidden">&nbsp;</td>';
            $transaction_content .= '<td style="text-align: right;">'.$this_quantity.'&nbsp;x</td>';
            $transaction_content .= '<td class="advance_columns hidden" style="text-align: right;">$'.number_format($x__metadata['mc_gross'], 2).'</td>';
            $transaction_content .= '<td class="advance_columns hidden" style="text-align: right;" title="'.($commission_rate*100).'%">$'.number_format($this_commission, 2).'</td>';
            $transaction_content .= '<td class="advance_columns hidden" style="text-align: right;" title="'.( $x__metadata['mc_gross'] > 0 ? ($x__metadata['mc_fee']/$x__metadata['mc_gross']*100) : 0 ).'%">$'.number_format($x__metadata['mc_fee'], 2).'</td>';
            $transaction_content .= '<td style="text-align: left;"><b>&nbsp;'.( $this_quantity>1 ? '$'.number_format(($this_payout/$this_quantity), 2) : '' ).'</b></td>';
            $transaction_content .= '<td style="text-align: right;">$'.number_format($this_payout, 2).'</td>';
            $transaction_content .= '<td style="text-align: right;" class="advance_columns hidden">'.$x__metadata['mc_currency'].'</td>';

            $transaction_content .= '</tr>';

            if($this_payout > 0){
                $date = date("md", strtotime($x['x__time']));
                if(isset($daily_sales[$date])){
                    $daily_sales[$date] += $this_payout;
                } else {
                    $daily_sales[$date] = $this_payout;
                }

                $origin_source = $x['x__right'];
                if(isset($origin_sales[$origin_source])){
                    $origin_sales[$origin_source] += number_format($this_payout, 0, '','');
                } else {
                    $origin_sales[$origin_source] = number_format($this_payout, 0, '','');
                }

            }

        }
        $total_commission = ( $commission_rate * $total_revenue );
        $payout = $total_revenue-$total_commission-$total_paypal_fee;


        if($i['i__type']==6183 && !$total_transactions){
            continue;
        }

        $gross_sales += $total_sales;
        $gross_transactions += $total_transactions;
        $gross_revenue += $total_revenue;
        $gross_paypal_fee += $total_paypal_fee;
        $gross_commission += $total_commission;
        $gross_payout += $payout;

        $max_available = $this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
            'x__right' => $i['i__id'],
            'x__up' => 26189,
        ), array(), 1);
        $available_transactions = (count($max_available) && is_numeric($max_available[0]['x__message']) ? intval($max_available[0]['x__message']) : '∞');

        if(fmod($total_transactions, 2)==1){
            $transaction_content .= '<tr class="transaction_columns hidden"></tr>';
        }

        $sale_type_content .= '<tr class="main__title">';
        $sale_type_content .= '<td>'.( $total_sales>0 ? '<a href="javascript:void(0)" onclick="$(\'.transactions_'.$i['i__id'].'\').toggleClass(\'hidden\');" style="font-weight:bold;"><u>'.$i['i__title'].'</u></a>' : $i['i__title'] ).' <a href="/~'.$i['i__id'].'"><i class="fal fa-cog" style="font-size:1em !important;"></i></a></td>';
        $sale_type_content .= '<td style="text-align: right;" class="advance_columns hidden">'.$total_transactions.'</td>';
        $sale_type_content .= '<td style="text-align: right;" class="advance_columns hidden">'.$available_transactions.'</td>';
        $sale_type_content .= '<td style="text-align: right;">'.( $total_sales>0 ? $total_sales.'&nbsp;x' : '&nbsp;' ).'</td>';
        $sale_type_content .= '<td class="advance_columns hidden" style="text-align: right;">'.( $total_sales!=0 ? '$'.number_format($total_revenue, 2) : '&nbsp;' ).'</td>';
        $sale_type_content .= '<td class="advance_columns hidden" style="text-align: right;">'.( $total_sales!=0 ? '$'.number_format($total_commission, 2) : '&nbsp;' ).'</td>';
        $sale_type_content .= '<td class="advance_columns hidden" style="text-align: right;">'.( $total_sales!=0 ? '$'.number_format($total_paypal_fee, 2) : '&nbsp;').'</td>';
        $sale_type_content .= '<td style="text-align: left;">&nbsp;'.( $total_sales!=0 ? '$'.number_format(($payout/$total_sales), 2) : '&nbsp;' ).'</td>';
        $sale_type_content .= '<td style="text-align: right;"><b>'.( $total_sales!=0 ? '$'.number_format($payout, 2) : '' ).'</b></td>';
        $sale_type_content .= '<td style="text-align: right;" class="advance_columns hidden">'.join(', ',$currencies).'</td>';
        $sale_type_content .= '</tr>';
        $sale_type_content .= $transaction_content;

    }

    $other_source_content = '';












    $filters = array(
        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
        'x__up' => $_GET['e__id'], //Member
    );
    if(count($all_sources)){
        $filters[ 'x__down NOT IN (' . join(',', $all_sources) . ')'] = null;
    }
    $other_es = $this->X_model->fetch($filters, array('x__down'), 0);


    if(count($other_es)){

        $e___4593 = $this->config->item('e___4593');

        //Show Other Sources:
        $other_source_content .= '<tr class="main__title">';
        $other_source_content .= '<td><a href="javascript:void(0)" onclick="$(\'.thr_sources\').toggleClass(\'hidden\');" style="font-weight:bold;"><u>'.$e___4593[29393]['m__title'].'</u></a></td>';
        $other_source_content .= '<td style="text-align: right;" class="advance_columns hidden">0</td>';
        $other_source_content .= '<td style="text-align: right;" class="advance_columns hidden"></td>';
        $other_source_content .= '<td style="text-align: right;">'.count($other_es).'&nbsp;x'.'</td>';
        $other_source_content .= '<td class="advance_columns hidden" style="text-align: right;">&nbsp;</td>';
        $other_source_content .= '<td class="advance_columns hidden" style="text-align: right;">&nbsp;</td>';
        $other_source_content .= '<td class="advance_columns hidden" style="text-align: right;">&nbsp;</td>';
        $other_source_content .= '<td style="text-align: left;">&nbsp;$0.00</td>';
        $other_source_content .= '<td style="text-align: right;">&nbsp;$0.00</td>';
        $other_source_content .= '<td style="text-align: right;" class="advance_columns hidden">&nbsp;</td>';
        $other_source_content .= '<td style="text-align: right;"><a href="/@'.$_GET['e__id'].'"><i class="fal fa-cog" style="font-size:1em !important;"></i></a></td>';
        $other_source_content .= '</tr>';


        //Doo We Have other?
        foreach($other_es as $other_e){
            $other_source_content .= '<tr class="transaction_columns thr_sources hidden">';
            $other_source_content .= '<td><span class="icon-block source_cover_micro">'.view_cover($other_e['e__cover'],true).'</span><a href="/@'.$other_e['e__id'].'" style="font-weight:bold; display: inline-block;"><u>'.$other_e['e__title'].'</u></a></td>';
            $other_source_content .= '<td style="text-align: right;" class="advance_columns hidden">&nbsp;</td>';
            $other_source_content .= '<td style="text-align: right;" class="advance_columns hidden">&nbsp;</td>';
            $other_source_content .= '<td style="text-align: right;">1&nbsp;x</td>';
            $other_source_content .= '<td class="advance_columns hidden" style="text-align: right;">&nbsp;</td>';
            $other_source_content .= '<td class="advance_columns hidden" style="text-align: right;">&nbsp;</td>';
            $other_source_content .= '<td class="advance_columns hidden" style="text-align: right;">&nbsp;</td>';
            $other_source_content .= '<td style="text-align: left;">&nbsp;$0.00</td>';
            $other_source_content .= '<td style="text-align: right;">&nbsp;$0.00</td>';
            $other_source_content .= '<td style="text-align: right;" class="advance_columns hidden">&nbsp;</td>';
            $other_source_content .= '<td style="text-align: right;"><a href="/-4341?x__id='.$other_e['x__id'].'" target="_blank" style="font-size:1em !important;" data-toggle="tooltip" data-placement="top" title="View Platform Transaction"><i class="fal fa-atlas"></i></a></td>';
            $other_source_content .= '</tr>';
            $gross_sales++;
        }

    }

}



if(count($i_query)){

    echo '<table id="sortable_table" class="table table-sm table-striped image-mini" style="margin: 0 5px; width:calc(100% - 10px) !important;">';
    echo '<tr style="vertical-align: baseline;" class="main__title">';
    echo '<th id="th_primary">&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onclick="$(\'.transaction_columns\').toggleClass(\'hidden\');" style="font-weight:bold;" data-toggle="tooltip" data-placement="top" title="Toggle Transactions"><i class="fas fa-arrows-v"></i></a>&nbsp;&nbsp;<a href="javascript:void(0)" onclick="toggle_max_view(\'advance_columns\')" style="font-weight:bold;" data-toggle="tooltip" data-placement="top" title="Toggle Advanced Columns"><i class="fas fa-arrows-h"></i></a></th>';
    echo '<th style="text-align: right;" id="th_paid" class="advance_columns hidden">Transactions</th>';
    echo '<th style="text-align: right;" id="th_paid" class="advance_columns hidden">Of</th>';
    echo '<th style="text-align: right;" id="th_paid">Quantity</th>';
    echo '<th style="text-align: right;" class="advance_columns hidden" id="th_rev">Sales</th>';
    echo '<th style="text-align: right;" class="advance_columns hidden" id="th_payout">Platform<br />Fee</th>';
    echo '<th style="text-align: right;" class="advance_columns hidden" id="th_payout">Paypal<br />Fee</th>';
    echo '<th style="text-align: left;" id="th_average">&nbsp;Average</th>';
    echo '<th style="text-align: right;" id="th_payout">Payout</th>';
    echo '<th style="text-align: right;" id="th_currency" class="advance_columns hidden">&nbsp;</th>';
    echo '</tr>';

    echo $other_source_content;
    echo $sale_type_content;

    echo '<tr class="main__title">';
    echo '<th style="text-align: left; font-weight: bold;" id="th_primary">Totals</th>';
    echo '<th style="text-align: right; font-weight: bold;" class="advance_columns hidden">'.$gross_transactions.'</th>';
    echo '<th style="text-align: right; font-weight: bold;" class="advance_columns hidden">&nbsp;</th>';
    echo '<th style="text-align: right; font-weight: bold;">'.$gross_sales.'&nbsp;x</th>';
    echo '<th style="text-align: right; font-weight: bold;" class="advance_columns hidden">'.'$'.number_format($gross_revenue, 2).'</th>';
    echo '<th style="text-align: right; font-weight: bold;" class="advance_columns hidden">-$'.number_format($gross_commission, 2).'</th>';
    echo '<th style="text-align: right; font-weight: bold;" class="advance_columns hidden">-$'.number_format($gross_paypal_fee, 2).'</th>';
    echo '<th style="text-align: left; font-weight: bold;">&nbsp;$'.number_format(( $gross_sales > 0 ? $gross_payout / $gross_sales : 0 ), 2).'</th>';
    echo '<th style="text-align: right; font-weight: bold;"><b>$'.number_format($gross_payout, 2).'</b></th>';
    echo '<th style="text-align: right; font-weight: bold;" class="advance_columns hidden">'.join(', ',$gross_currencies).'</th>';
    echo '</tr>';
    echo '</table>';
    echo ( $x_updated > 0 ? '<div>'.$x_updated.' Halfed!<hr /></div>' : '' );




    //Show Charts:
    echo '<div id="chart_div" style="margin:0 0 21px;"></div>';
    echo '<div id="chart_origin_div" style="margin:0 0 21px;"></div>';
    ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

        // Load the Visualization API and the corechart package.
        google.charts.load('current', {'packages':['corechart']});


        google.charts.setOnLoadCallback(drawChart2);
        function drawChart2() {
            var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
            var options = {
                title: 'Sales by Day',
                hAxis: {showTextEvery:1, slantedText:true, slantedTextAngle:45}
            }
            var data = google.visualization.arrayToDataTable([
                ['Day', 'Sales'],
                <?php
                ksort($daily_sales);
                foreach($daily_sales as $day => $sales){
                    if($sales > 0){
                        echo "['".$day."', ".number_format($sales, 0, '.', '')."],";
                    }
                }
                ?>
            ]);
            chart.draw(data, options);
        }


        google.charts.setOnLoadCallback(drawChart3);
        function drawChart3() {
            var chart = new google.visualization.PieChart(document.getElementById('chart_origin_div'));
            var options = {
                title: 'Sales by Promoter',
                hAxis: {showTextEvery:1, slantedText:true, slantedTextAngle:45}
            }
            var data = google.visualization.arrayToDataTable([
                ['Origin', 'Sales'],
                <?php
                arsort($origin_sales);
                foreach($origin_sales as $origin => $sales){
                    if(($sales/$gross_revenue)>=0.5 || count($this->X_model->fetch(array(
                            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                            'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                            'x__right' => $origin,
                            'x__up' => 30564, //None Promoter
                        )))){
                        //This item has more than 50% of sales, remove it:
                        continue;
                    }
                    if($sales > 0){
                        //Fetch this origin:
                        $is = $this->I_model->fetch(array(
                            'i__id' => $origin,
                        ));
                        echo "['".( count($is) ? '$'.number_format($sales, 0).' '.str_replace('\'','`',$is[0]['i__title']) : 'Unknown' )."', ".number_format($sales, 0, '.', '')."],";
                    }
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
    .table-striped tr:nth-of-type(odd) td {
        background-color: #FFFFFF !important;
        -webkit-print-color-adjust:exact;
    }
    .table-striped td {
        border-bottom: 1px dotted #FFFFFF !important;
    }
    .fa-filter, .fa-sort{
        font-size: 1.01em !important;
        margin-bottom: 3px;
    }
    #sortable_table th{
        cursor: ns-resize !important;
        border: 0 !important;
    }
    #sortable_table tr th{
       padding: 8px 0 !important;
        font-weight: bold;
        font-size: 1.12em;
    }
    #sortable_table tr td {
        padding: 5px 0 !important;
        font-size: 1.01em;
    }
    .transaction_columns td{
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
        height:71px;
        display:inline-block;
        text-align: left;
        width: 8px;
    }
</style>