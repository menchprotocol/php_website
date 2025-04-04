<?php

$commission_rate = intval(website_setting(27017))/100;
$players___6287 = $this->config->item('players___6287'); //APP
$gross_links = 0;
$gross_sales = 0;
$gross_revenue = 0;
$gross_paypal_fee = 0;
$gross_commission = 0;
$gross_payout = 0;
$gross_currencies = array();
$idea_query = array();
$daily_sales = array();
$origin_sales = array();
$all_e = array();



if(!isset($_GET['playerhandle']) || !strlen($_GET['playerhandle']) || !$_GET['playerhandle'] || $_GET['playerhandle']=='0'){
    
    echo '<h1>'.$players___6287[27004]['m__title'].'</h1>';
    foreach($this->Players->tree(11029, $player_active['playerid'], array(27004)) as $e){
        echo '<div><a href="'.view_app_link(27004).view_memory(42903,42902).$e['playerhandle'].'" class="main__title">'.$e['playertext'].'</a></div>';
    }

} else {


    //Show header:
    echo '<div style="padding: 0 0 0 10px; font-weight: bold; margin-bottom: -13px;"><a href="'.view_app_link(27004).'"><b>'.$players___6287[27004]['m__title'].'</b></a></div>';

    $es = $this->Players->read(array(
        'LOWER(playerhandle)' => strtolower($_GET['playerhandle']),
    ));
    echo '<h2>'.$es[0]['playertext'].' @'.$es[0]['playerhandle'].'</h2>';

    $idea_query = $this->Links->read(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___33602')) . ')' => null, //Idea/Player Links Active
        'ideatype IN (' . join(',', $this->config->item('playerids___41055')) . ')' => null, //Payment Ideas
        'linkplayerup' => $es[0]['playerid'],
    ), array('linkidearight'), 0, 0, array('linknumber' => 'ASC'));


    //List all payment Ideas and their total earnings
    $x_updated = 0;
    echo '<p>'.count($idea_query).' results found:</p>';

    $sale_type_content = '';
    foreach($idea_query as $i){

        //Total earnings:
        $link_content = '';
        $total_links = 0;
        $total_sales = 0;
        $total_revenue = 0;
        $total_paypal_fee = 0;
        $currencies = array();

        foreach($this->Links->read(array(
                    'linkplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
            'linkidealeft' => $i['ideaid'],
        ), array(), 0, 0, array('linkplayercreator' => 'ASC')) as $x){

            $linktext = unserialize($x['linktext']);
            $total_links++;
            $this_quantity = 1;//Default assumption:

            //Player for quantity?
            unset($linktext2);

            if(isset($linktext2) && $linktext2['quantity']>1){
                $this_quantity = $linktext2['quantity'];
                $linktext['mc_fee'] = $linktext2['mc_fee'] * -1;
            } elseif(isset($linktext['quantity']) && $linktext['quantity']>1){
                $this_quantity = $linktext['quantity'];
            } elseif(count($x2) && $x2['linknumber']>=2){
                $this_quantity = $x2['linknumber'];
            }

            //Count only if a TICKET idea:
            if(!in_array($x['linkplayertype'], $this->config->item('playerids___30469'))){
                $linktext['mc_gross'] = 0;
                $linktext['mc_fee'] = 0;
                $linktext['mc_currency'] = '';
                $linktext['item_number'] = '';
                $linktext['first_name'] = '';
                $linktext['last_name'] = '';
            }

            if(!isset($linktext['mc_currency'])){
                continue;
            }

            if(!isset($linktext['mc_fee'])){
                $linktext['mc_fee'] = 0;
            }

            $this_commission = $linktext['mc_gross']*$commission_rate;
            $this_payout = $linktext['mc_gross']-$linktext['mc_fee']-$this_commission;
            if($this_payout < 0){
                $this_quantity = $this_quantity * -1;
            }

            $total_sales += $this_quantity;
            $total_paypal_fee += doubleval($linktext['mc_fee']);
            $total_revenue += doubleval($linktext['mc_gross']);
            if(!in_array($linktext['mc_currency'], $currencies) && strlen($linktext['mc_currency'])>0){
                array_push($currencies, $linktext['mc_currency']);
            }
            if(!in_array($linktext['mc_currency'], $gross_currencies) && strlen($linktext['mc_currency'])>0){
                array_push($gross_currencies, $linktext['mc_currency']);
            }

            $item_parts = explode('-',$linktext['item_number']);
            $this_e = intval(isset($item_parts[3]) ? $item_parts[3] : $x['linkplayercreator'] );
            array_push($all_e, $this_e);
            $es = $this->Players->read(array(
                'playerid' => $this_e,
            ));


            $link_content .= '<tr class="link_columns links_'.$i['ideaid'].' hidden">';
            $link_content .= '<td>'.( count($es) ? '<span class="icon-block-sm e_cover_micro">'.view_cover($es[0]['playercover'],true).'</span><a href="'.view_memory(42903,42902).$es[0]['playerhandle'].'" style="font-weight:bold; display: inline-block;"><u>'.$es[0]['playertext'].'</u></a> ' : '' ).$linktext['first_name'].' '.$linktext['last_name'].'</td>';
            $link_content .= '<td style="text-align: right;" class="advance_columns hidden">'.( $linktext['mc_gross']!=0 && strlen($linktext['txn_id'])>0 ? '<a href="https://www.paypal.com/activity/payment/'.$linktext['txn_id'].'" target="_blank" data-toggle="tooltip" data-placement="top" title="View Paypal Link"><i class="fab fa-paypal" style="font-size:1em !important;"></i></a> ' : '' ).'<a href="'.view_app_link(4341).'?linkid='.$x['linkid'].'" target="_blank" style="font-size:1em !important;" data-toggle="tooltip" data-placement="top" title="View Platform Link"><i class="far fa-atlas"></i></a></td>';
            $link_content .= '<td style="text-align: right;" class="advance_columns hidden">&nbsp;</td>';
            $link_content .= '<td style="text-align: right;">'.$this_quantity.'&nbsp;x</td>';
            $link_content .= '<td class="advance_columns hidden" style="text-align: right;">$'.number_format($linktext['mc_gross'], 2).'</td>';
            $link_content .= '<td class="advance_columns hidden" style="text-align: right;" title="'.($commission_rate*100).'%">$'.number_format($this_commission, 2).'</td>';
            $link_content .= '<td class="advance_columns hidden" style="text-align: right;" title="'.( $linktext['mc_gross'] > 0 ? ($linktext['mc_fee']/$linktext['mc_gross']*100) : 0 ).'%">$'.number_format($linktext['mc_fee'], 2).'</td>';
            $link_content .= '<td style="text-align: left;"><b>&nbsp;'.( $this_quantity>1 ? '$'.number_format(($this_payout/$this_quantity), 2) : '' ).'</b></td>';
            $link_content .= '<td style="text-align: right;">$'.number_format($this_payout, 2).'</td>';
            $link_content .= '<td style="text-align: right;" class="advance_columns hidden">'.$linktext['mc_currency'].'</td>';

            $link_content .= '</tr>';

            if($this_payout > 0){
                $date = date("md", strtotime($x['linktime']));
                if(isset($daily_sales[$date])){
                    $daily_sales[$date] += $this_payout;
                } else {
                    $daily_sales[$date] = $this_payout;
                }

                $origin_e = $x['linkidearight'];
                if(isset($origin_sales[$origin_e])){
                    $origin_sales[$origin_e] += number_format($this_payout, 0, '','');
                } else {
                    $origin_sales[$origin_e] = number_format($this_payout, 0, '','');
                }

            }

        }
        $total_commission = ( $commission_rate * $total_revenue );
        $payout = $total_revenue-$total_commission-$total_paypal_fee;


        if($i['ideatype']==6183 && !$total_links){
            continue;
        }

        $gross_sales += $total_sales;
        $gross_links += $total_links;
        $gross_revenue += $total_revenue;
        $gross_paypal_fee += $total_paypal_fee;
        $gross_commission += $total_commission;
        $gross_payout += $payout;

        $max_available = $this->Links->read(array(
                    'linkplayertype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
            'linkidearight' => $i['ideaid'],
            'linkplayerup' => 26189,
        ), array(), 1);
        $available_links = (count($max_available) && is_numeric($max_available[0]['linktext']) ? intval($max_available[0]['linktext']) : '∞');

        if(fmod($total_links, 2)==1){
            $link_content .= '<tr class="link_columns hidden"></tr>';
        }

        $sale_type_content .= '<tr class="main__title">';
        $sale_type_content .= '<td>'.( $total_sales>0 ? '<a href="javascript:void(0)" onclick="$(\'.links_'.$i['ideaid'].'\').toggleClass(\'hidden\');" style="font-weight:bold;"><u>'.view_idea_title($i).'</u></a>' : view_idea_title($i) ).' <a href="'.view_memory(42903,33286).$i['ideahashtag'].'"><i class="far fa-cog" style="font-size:1em !important;"></i></a></td>';
        $sale_type_content .= '<td style="text-align: right;" class="advance_columns hidden">'.$total_links.'</td>';
        $sale_type_content .= '<td style="text-align: right;" class="advance_columns hidden">/'.$available_links.'</td>';
        $sale_type_content .= '<td style="text-align: right;">'.( $total_sales>0 ? $total_sales.'&nbsp;x' : '&nbsp;' ).'</td>';
        $sale_type_content .= '<td class="advance_columns hidden" style="text-align: right;">'.( $total_sales!=0 ? '$'.number_format($total_revenue, 2) : '&nbsp;' ).'</td>';
        $sale_type_content .= '<td class="advance_columns hidden" style="text-align: right;">'.( $total_sales!=0 ? '$'.number_format($total_commission, 2) : '&nbsp;' ).'</td>';
        $sale_type_content .= '<td class="advance_columns hidden" style="text-align: right;">'.( $total_sales!=0 ? '$'.number_format($total_paypal_fee, 2) : '&nbsp;').'</td>';
        $sale_type_content .= '<td style="text-align: left;">&nbsp;'.( $total_sales!=0 ? '$'.number_format(($payout/$total_sales), 2) : '&nbsp;' ).'</td>';
        $sale_type_content .= '<td style="text-align: right;"><b>'.( $total_sales!=0 ? '$'.number_format($payout, 2) : '' ).'</b></td>';
        $sale_type_content .= '<td style="text-align: right;" class="advance_columns hidden">'.join(', ',$currencies).'</td>';
        $sale_type_content .= '</tr>';
        $sale_type_content .= $link_content;

    }

    $otherplayer_content = '';











    $other_es = array();

    foreach($this->Players->read(array(
        'LOWER(playerhandle)' => strtolower($_GET['playerhandle']),
    )) as $e){
        $filters = array(
                    'linkplayertype IN (' . join(',', $this->list_link_sourcing) . ')' => null, //SOURCE LINKS
            'linkplayerup' => $e['playerid'], //Member
        );
        if(count($all_e)){
            $filters[ 'linkplayerdown NOT IN (' . join(',', $all_e) . ')'] = null;
        }
        $other_es = $this->Links->read($filters, array('linkplayerdown'), 0);
    }





    if(count($other_es)){

        $players___4593 = $this->config->item('players___4593');

        //Show Other Players:
        $otherplayer_content .= '<tr class="main__title">';
        $otherplayer_content .= '<td><a href="javascript:void(0)" onclick="$(\'.thr_e\').toggleClass(\'hidden\');" style="font-weight:bold;"><u>'.$players___4593[29393]['m__title'].'</u></a></td>';
        $otherplayer_content .= '<td style="text-align: right;" class="advance_columns hidden">0</td>';
        $otherplayer_content .= '<td style="text-align: right;" class="advance_columns hidden"></td>';
        $otherplayer_content .= '<td style="text-align: right;">'.count($other_es).'&nbsp;x'.'</td>';
        $otherplayer_content .= '<td class="advance_columns hidden" style="text-align: right;">&nbsp;</td>';
        $otherplayer_content .= '<td class="advance_columns hidden" style="text-align: right;">&nbsp;</td>';
        $otherplayer_content .= '<td class="advance_columns hidden" style="text-align: right;">&nbsp;</td>';
        $otherplayer_content .= '<td style="text-align: left;">&nbsp;$0.00</td>';
        $otherplayer_content .= '<td style="text-align: right;">&nbsp;$0.00</td>';
        $otherplayer_content .= '<td style="text-align: right;" class="advance_columns hidden">&nbsp;</td>';
        $otherplayer_content .= '</tr>';


        //Doo We Have other?
        foreach($other_es as $other_e){
            $otherplayer_content .= '<tr class="link_columns thr_e hidden">';
            $otherplayer_content .= '<td><span class="icon-block e_cover_micro">'.view_cover($other_e['playercover'],true).'</span><a href="'.view_memory(42903,42902).$other_e['playerhandle'].'" style="font-weight:bold; display: inline-block;"><u>'.$other_e['playertext'].'</u></a></td>';
            $otherplayer_content .= '<td style="text-align: right;" class="advance_columns hidden">&nbsp;</td>';
            $otherplayer_content .= '<td style="text-align: right;" class="advance_columns hidden">&nbsp;</td>';
            $otherplayer_content .= '<td style="text-align: right;"><a href="'.view_app_link(4341).'?linkid='.$other_e['linkid'].'" target="_blank" style="font-size:1em !important;" data-toggle="tooltip" data-placement="top" title="View Platform Link"><i class="far fa-atlas"></i></a></td>';
            $otherplayer_content .= '<td class="advance_columns hidden" style="text-align: right;">&nbsp;</td>';
            $otherplayer_content .= '<td class="advance_columns hidden" style="text-align: right;">&nbsp;</td>';
            $otherplayer_content .= '<td class="advance_columns hidden" style="text-align: right;">&nbsp;</td>';
            $otherplayer_content .= '<td style="text-align: left;">&nbsp;$0.00</td>';
            $otherplayer_content .= '<td style="text-align: right;">&nbsp;$0.00</td>';
            $otherplayer_content .= '<td style="text-align: right;" class="advance_columns hidden">&nbsp;</td>';
            $otherplayer_content .= '</tr>';
            $gross_sales++;
        }

    }

}



if(count($idea_query)){

    echo '<table id="sortable_table" class="table table-sm image-mini" style="margin: 0 5px; width:calc(100% - 10px) !important;">';
    echo '<tr style="vertical-align: baseline;" class="main__title">';
    echo '<th id="th_primary">&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onclick="$(\'.link_columns\').toggleClass(\'hidden\');" style="font-weight:bold;" data-toggle="tooltip" data-placement="top" title="Toggle Links"><i class="far fa-arrows-v"></i></a>&nbsp;&nbsp;<a href="javascript:void(0)" onclick="toggle_max_view(\'advance_columns\')" style="font-weight:bold;" data-toggle="tooltip" data-placement="top" title="Toggle Advanced Columns"><i class="far fa-arrows-h"></i></a></th>';
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

    echo $otherplayer_content;
    echo $sale_type_content;

    echo '<tr class="main__title">';
    echo '<th style="text-align: left; font-weight: bold;" id="th_primary">Totals</th>';
    echo '<th style="text-align: right; font-weight: bold;" class="advance_columns hidden">'.$gross_links.'</th>';
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
                    if(($sales/$gross_revenue)>=0.5 || count($this->Links->read(array(
                                                    'linkplayertype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
                            'linkidearight' => $origin,
                            'linkplayerup' => 30564, //None Promoter
                        )))){
                        //This item has more than 50% of sales, remove it:
                        continue;
                    }
                    if($sales > 0){
                        //Fetch this origin:
                        $is = $this->Ideas->read(array(
                            'ideaid' => $origin,
                        ));
                        echo "['".( count($is) ? '$'.number_format($sales, 0).' '.str_replace('\'','`',view_idea_title($is[0], true)) : 'Unknown' )."', ".number_format($sales, 0, '.', '')."],";
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
        border-top:1px solid #000000 !important;
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
    .link_columns td{
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