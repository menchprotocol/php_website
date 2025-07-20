<?php

$commission_rate = intval(website_setting(27017))/100;
$handles___6287 = $this->config->item('handles___6287'); //APP
$gross_chains = 0;
$gross_sales = 0;
$gross_revenue = 0;
$gross_paypal_fee = 0;
$gross_commission = 0;
$gross_payout = 0;
$gross_currencies = array();
$hashtag_query = array();
$daily_sales = array();
$origin_sales = array();
$all_e = array();



if(!isset($_GET['handlestring']) || !strlen($_GET['handlestring']) || !$_GET['handlestring'] || $_GET['handlestring']=='0'){
    
    echo '<h1>'.$handles___6287[27004]['m__title'].'</h1>';
    foreach($this->Handles->tree(11029, $handle_session['handleid'], array(27004)) as $e){
        echo '<div><a href="'.view_app_chain(27004).view_memory(42903,42902).$e['handlestring'].'" class="main__title">'.$e['handlevalue'].'</a></div>';
    }

} else {


    //Show header:
    echo '<div style="padding: 0 0 0 10px; font-weight: bold; margin-bottom: -13px;"><a href="'.view_app_chain(27004).'"><b>'.$handles___6287[27004]['m__title'].'</b></a></div>';

    $es = $this->Handles->read(array(
        'LOWER(handlestring)' => strtolower($_GET['handlestring']),
    ));
    echo '<h2>'.$es[0]['handlevalue'].' @'.$es[0]['handlestring'].'</h2>';

    $hashtag_query = $this->Chains->read(array(
            'chainhandletype IN (' . join(',', $this->config->item('handleids___33602')) . ')' => null, //Hashtag/Handle Chains Active
        'hashtagtype IN (' . join(',', $this->config->item('handleids___41055')) . ')' => null, //Payment Hashtags
        'chainhandleinput' => $es[0]['handleid'],
    ), array('chainhashtagoutput'), 0, 0, array('chainkey' => 'ASC'));


    //List all payment Hashtags and their total earnings
    $x_updated = 0;
    echo '<p>'.count($hashtag_query).' results found:</p>';

    $sale_type_content = '';
    foreach($hashtag_query as $i){

        //Total earnings:
        $chain_content = '';
        $total_chains = 0;
        $total_sales = 0;
        $total_revenue = 0;
        $total_paypal_fee = 0;
        $currencies = array();

        foreach($this->Chains->read(array(
                    'chainhandletype IN (' . join(',', $this->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
            'chainhashtaginput' => $i['hashtagid'],
        ), array(), 0, 0, array('chainhandlecreator' => 'ASC')) as $x){

            $chainvalue = unserialize($x['chainvalue']);
            $total_chains++;
            $this_quantity = 1;//Default assumption:

            //Handle for quantity?
            unset($chainvalue2);

            if(isset($chainvalue2) && $chainvalue2['quantity']>1){
                $this_quantity = $chainvalue2['quantity'];
                $chainvalue['mc_fee'] = $chainvalue2['mc_fee'] * -1;
            } elseif(isset($chainvalue['quantity']) && $chainvalue['quantity']>1){
                $this_quantity = $chainvalue['quantity'];
            } elseif(count($x2) && $x2['chainkey']>=2){
                $this_quantity = $x2['chainkey'];
            }

            //Count only if a TICKET hashtag:
            if(!in_array($x['chainhandletype'], $this->config->item('handleids___30469'))){
                $chainvalue['mc_gross'] = 0;
                $chainvalue['mc_fee'] = 0;
                $chainvalue['mc_currency'] = '';
                $chainvalue['item_number'] = '';
                $chainvalue['first_name'] = '';
                $chainvalue['last_name'] = '';
            }

            if(!isset($chainvalue['mc_currency'])){
                continue;
            }

            if(!isset($chainvalue['mc_fee'])){
                $chainvalue['mc_fee'] = 0;
            }

            $this_commission = $chainvalue['mc_gross']*$commission_rate;
            $this_payout = $chainvalue['mc_gross']-$chainvalue['mc_fee']-$this_commission;
            if($this_payout < 0){
                $this_quantity = $this_quantity * -1;
            }

            $total_sales += $this_quantity;
            $total_paypal_fee += doubleval($chainvalue['mc_fee']);
            $total_revenue += doubleval($chainvalue['mc_gross']);
            if(!in_array($chainvalue['mc_currency'], $currencies) && strlen($chainvalue['mc_currency'])>0){
                array_push($currencies, $chainvalue['mc_currency']);
            }
            if(!in_array($chainvalue['mc_currency'], $gross_currencies) && strlen($chainvalue['mc_currency'])>0){
                array_push($gross_currencies, $chainvalue['mc_currency']);
            }

            $item_parts = explode('-',$chainvalue['item_number']);
            $this_e = intval(isset($item_parts[3]) ? $item_parts[3] : $x['chainhandlecreator'] );
            array_push($all_e, $this_e);
            $es = $this->Handles->read(array(
                'handleid' => $this_e,
            ));


            $chain_content .= '<tr class="chain_columns chains_'.$i['hashtagid'].' hidden">';
            $chain_content .= '<td>'.( count($es) ? '<span class="icon-block-sm e_cover_micro">'.view_cover($es[0]['handlecover'],true).'</span><a href="'.view_memory(42903,42902).$es[0]['handlestring'].'" style="font-weight:bold; display: inline-block;">'.$es[0]['handlevalue'].'</a> ' : '' ).$chainvalue['first_name'].' '.$chainvalue['last_name'].'</td>';
            $chain_content .= '<td style="text-align: right;" class="advance_columns hidden">'.( $chainvalue['mc_gross']!=0 && strlen($chainvalue['txn_id'])>0 ? '<a href="https://www.paypal.com/activity/payment/'.$chainvalue['txn_id'].'" target="_blank" data-toggle="tooltip" data-placement="top" title="View Paypal Chain"><i class="fab fa-paypal" style="font-size:1em !important;"></i></a> ' : '' ).'<a href="'.view_app_chain(4341).'?chainid='.$x['chainid'].'" target="_blank" style="font-size:1em !important;" data-toggle="tooltip" data-placement="top" title="View Platform Chain"><i class="far fa-atlas"></i></a></td>';
            $chain_content .= '<td style="text-align: right;" class="advance_columns hidden">&nbsp;</td>';
            $chain_content .= '<td style="text-align: right;">'.$this_quantity.'&nbsp;x</td>';
            $chain_content .= '<td class="advance_columns hidden" style="text-align: right;">$'.number_format($chainvalue['mc_gross'], 2).'</td>';
            $chain_content .= '<td class="advance_columns hidden" style="text-align: right;" title="'.($commission_rate*100).'%">$'.number_format($this_commission, 2).'</td>';
            $chain_content .= '<td class="advance_columns hidden" style="text-align: right;" title="'.( $chainvalue['mc_gross'] > 0 ? ($chainvalue['mc_fee']/$chainvalue['mc_gross']*100) : 0 ).'%">$'.number_format($chainvalue['mc_fee'], 2).'</td>';
            $chain_content .= '<td style="text-align: left;"><b>&nbsp;'.( $this_quantity>1 ? '$'.number_format(($this_payout/$this_quantity), 2) : '' ).'</b></td>';
            $chain_content .= '<td style="text-align: right;">$'.number_format($this_payout, 2).'</td>';
            $chain_content .= '<td style="text-align: right;" class="advance_columns hidden">'.$chainvalue['mc_currency'].'</td>';

            $chain_content .= '</tr>';

            if($this_payout > 0){
                $date = date("md", strtotime($x['chaintime']));
                if(isset($daily_sales[$date])){
                    $daily_sales[$date] += $this_payout;
                } else {
                    $daily_sales[$date] = $this_payout;
                }

                $origin_e = $x['chainhashtagoutput'];
                if(isset($origin_sales[$origin_e])){
                    $origin_sales[$origin_e] += number_format($this_payout, 0, '','');
                } else {
                    $origin_sales[$origin_e] = number_format($this_payout, 0, '','');
                }

            }

        }
        $total_commission = ( $commission_rate * $total_revenue );
        $payout = $total_revenue-$total_commission-$total_paypal_fee;


        if($i['hashtagtype']==6183 && !$total_chains){
            continue;
        }

        $gross_sales += $total_sales;
        $gross_chains += $total_chains;
        $gross_revenue += $total_revenue;
        $gross_paypal_fee += $total_paypal_fee;
        $gross_commission += $total_commission;
        $gross_payout += $payout;

        $max_available = $this->Chains->read(array(
                    'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
            'chainhashtagoutput' => $i['hashtagid'],
            'chainhandleinput' => 26189,
        ), array(), 1);
        $available_chains = (count($max_available) && is_numeric($max_available[0]['chainvalue']) ? intval($max_available[0]['chainvalue']) : '∞');

        if(fmod($total_chains, 2)==1){
            $chain_content .= '<tr class="chain_columns hidden"></tr>';
        }

        $sale_type_content .= '<tr class="main__title">';
        $sale_type_content .= '<td>'.( $total_sales>0 ? '<a href="javascript:void(0)" onclick="$(\'.chains_'.$i['hashtagid'].'\').toggleClass(\'hidden\');" style="font-weight:bold;">'.view_hashtag_title($i).'</a>' : view_hashtag_title($i) ).' <a href="'.view_memory(42903,33286).$i['hashtagstring'].'"><i class="far fa-cog" style="font-size:1em !important;"></i></a></td>';
        $sale_type_content .= '<td style="text-align: right;" class="advance_columns hidden">'.$total_chains.'</td>';
        $sale_type_content .= '<td style="text-align: right;" class="advance_columns hidden">/'.$available_chains.'</td>';
        $sale_type_content .= '<td style="text-align: right;">'.( $total_sales>0 ? $total_sales.'&nbsp;x' : '&nbsp;' ).'</td>';
        $sale_type_content .= '<td class="advance_columns hidden" style="text-align: right;">'.( $total_sales!=0 ? '$'.number_format($total_revenue, 2) : '&nbsp;' ).'</td>';
        $sale_type_content .= '<td class="advance_columns hidden" style="text-align: right;">'.( $total_sales!=0 ? '$'.number_format($total_commission, 2) : '&nbsp;' ).'</td>';
        $sale_type_content .= '<td class="advance_columns hidden" style="text-align: right;">'.( $total_sales!=0 ? '$'.number_format($total_paypal_fee, 2) : '&nbsp;').'</td>';
        $sale_type_content .= '<td style="text-align: left;">&nbsp;'.( $total_sales!=0 ? '$'.number_format(($payout/$total_sales), 2) : '&nbsp;' ).'</td>';
        $sale_type_content .= '<td style="text-align: right;"><b>'.( $total_sales!=0 ? '$'.number_format($payout, 2) : '' ).'</b></td>';
        $sale_type_content .= '<td style="text-align: right;" class="advance_columns hidden">'.join(', ',$currencies).'</td>';
        $sale_type_content .= '</tr>';
        $sale_type_content .= $chain_content;

    }

    $otherhandle_content = '';











    $other_es = array();

    foreach($this->Handles->read(array(
        'LOWER(handlestring)' => strtolower($_GET['handlestring']),
    )) as $e){
        $filters = array(
                    'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
            'chainhandleinput' => $e['handleid'], //Member
        );
        if(count($all_e)){
            $filters[ 'chainhandleoutput NOT IN (' . join(',', $all_e) . ')'] = null;
        }
        $other_es = $this->Chains->read($filters, array('chainhandleoutput'), 0);
    }





    if(count($other_es)){

        $handles___4593 = $this->config->item('handles___4593');

        //Show Other Handles:
        $otherhandle_content .= '<tr class="main__title">';
        $otherhandle_content .= '<td><a href="javascript:void(0)" onclick="$(\'.thr_e\').toggleClass(\'hidden\');" style="font-weight:bold;">'.$handles___4593[29393]['m__title'].'</a></td>';
        $otherhandle_content .= '<td style="text-align: right;" class="advance_columns hidden">0</td>';
        $otherhandle_content .= '<td style="text-align: right;" class="advance_columns hidden"></td>';
        $otherhandle_content .= '<td style="text-align: right;">'.count($other_es).'&nbsp;x'.'</td>';
        $otherhandle_content .= '<td class="advance_columns hidden" style="text-align: right;">&nbsp;</td>';
        $otherhandle_content .= '<td class="advance_columns hidden" style="text-align: right;">&nbsp;</td>';
        $otherhandle_content .= '<td class="advance_columns hidden" style="text-align: right;">&nbsp;</td>';
        $otherhandle_content .= '<td style="text-align: left;">&nbsp;$0.00</td>';
        $otherhandle_content .= '<td style="text-align: right;">&nbsp;$0.00</td>';
        $otherhandle_content .= '<td style="text-align: right;" class="advance_columns hidden">&nbsp;</td>';
        $otherhandle_content .= '</tr>';


        //Doo We Have other?
        foreach($other_es as $other_e){
            $otherhandle_content .= '<tr class="chain_columns thr_e hidden">';
            $otherhandle_content .= '<td><span class="icon-block e_cover_micro">'.view_cover($other_e['handlecover'],true).'</span><a href="'.view_memory(42903,42902).$other_e['handlestring'].'" style="font-weight:bold; display: inline-block;">'.$other_e['handlevalue'].'</a></td>';
            $otherhandle_content .= '<td style="text-align: right;" class="advance_columns hidden">&nbsp;</td>';
            $otherhandle_content .= '<td style="text-align: right;" class="advance_columns hidden">&nbsp;</td>';
            $otherhandle_content .= '<td style="text-align: right;"><a href="'.view_app_chain(4341).'?chainid='.$other_e['chainid'].'" target="_blank" style="font-size:1em !important;" data-toggle="tooltip" data-placement="top" title="View Platform Chain"><i class="far fa-atlas"></i></a></td>';
            $otherhandle_content .= '<td class="advance_columns hidden" style="text-align: right;">&nbsp;</td>';
            $otherhandle_content .= '<td class="advance_columns hidden" style="text-align: right;">&nbsp;</td>';
            $otherhandle_content .= '<td class="advance_columns hidden" style="text-align: right;">&nbsp;</td>';
            $otherhandle_content .= '<td style="text-align: left;">&nbsp;$0.00</td>';
            $otherhandle_content .= '<td style="text-align: right;">&nbsp;$0.00</td>';
            $otherhandle_content .= '<td style="text-align: right;" class="advance_columns hidden">&nbsp;</td>';
            $otherhandle_content .= '</tr>';
            $gross_sales++;
        }

    }

}



if(count($hashtag_query)){

    echo '<table id="sortable_table" class="table table-sm image-mini" style="margin: 0 5px; width:calc(100% - 10px) !important;">';
    echo '<tr style="vertical-align: baseline;" class="main__title">';
    echo '<th id="th_primary">&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onclick="$(\'.chain_columns\').toggleClass(\'hidden\');" style="font-weight:bold;" data-toggle="tooltip" data-placement="top" title="Toggle Chains"><i class="far fa-arrows-v"></i></a>&nbsp;&nbsp;<a href="javascript:void(0)" onclick="toggle_max_view(\'advance_columns\')" style="font-weight:bold;" data-toggle="tooltip" data-placement="top" title="Toggle Advanced Columns"><i class="far fa-arrows-h"></i></a></th>';
    echo '<th style="text-align: right;" id="th_paid" class="advance_columns hidden">Payments</th>';
    echo '<th style="text-align: right;" id="th_paid" class="advance_columns hidden">&nbsp;</th>';
    echo '<th style="text-align: right;" id="th_paid">Tickets</th>';
    echo '<th style="text-align: right;" class="advance_columns hidden" id="th_rev">Sales</th>';
    echo '<th style="text-align: right;" class="advance_columns hidden" id="th_payout">Platform<br />Fee</th>';
    echo '<th style="text-align: right;" class="advance_columns hidden" id="th_payout">Paypal<br />Fee</th>';
    echo '<th style="text-align: left;" id="th_average">&nbsp;Average</th>';
    echo '<th style="text-align: right;" id="th_payout">Payout</th>';
    echo '<th style="text-align: right;" id="th_currency" class="advance_columns hidden">&nbsp;</th>';
    echo '</tr>';

    echo $otherhandle_content;
    echo $sale_type_content;

    echo '<tr class="main__title">';
    echo '<th style="text-align: left; font-weight: bold;" id="th_primary">Totals</th>';
    echo '<th style="text-align: right; font-weight: bold;" class="advance_columns hidden">'.$gross_chains.'</th>';
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
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script>

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
                    if(($sales/$gross_revenue)>=0.5 || count($this->Chains->read(array(
                                                    'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
                            'chainhashtagoutput' => $origin,
                            'chainhandleinput' => 30564, //None Promoter
                        )))){
                        //This item has more than 50% of sales, remove it:
                        continue;
                    }
                    if($sales > 0){
                        //Fetch this origin:
                        $is = $this->Hashtags->read(array(
                            'hashtagid' => $origin,
                        ));
                        echo "['".( count($is) ? '$'.number_format($sales, 0).' '.str_replace('\'','`',view_hashtag_title($is[0], true)) : 'Unknown' )."', ".number_format($sales, 0, '.', '')."],";
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
    tr.main__title{
        background-color: #CCCCCC !important;
        border-top:1px solid #999999 !important;
    }
    .table-striped tr:nth-of-type(odd) td {
        background-color: #FFFFFF !important;
        -webkit-print-color-adjust:exact;
    }
    .table-striped td {
        border-bottom: 1px dotted #000000 !important;
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
    .chain_columns td{
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