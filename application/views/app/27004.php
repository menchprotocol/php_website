<?php

$commission_rate = doubleval(view_memory(6404,27017))/100;

if(!isset($_GET['i__id']) || !intval($_GET['i__id'])){


    $gross_units = 0;
    $gross_revenue = 0;
    $gross_paypal_fee = 0;
    $gross_commission = 0;
    $gross_payout = 0;
    $gross_currencies = array();

    //List all payment Ideas and their total earnings
    $body_content = '';
    foreach($this->I_model->fetch(array(
        'i__type IN (' . join(',', $this->config->item('n___27005')) . ')' => null, //Payment Idea
    )) as $i){

        //Total earnings:
        $total_units = 0;
        $total_revenue = 0;
        $total_paypal_fee = 0;
        $currencies = array();

        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERY COIN
            'x__left' => $i['i__id'],
        ), array(), 0) as $x){
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

        }
        $total_commission = ( $commission_rate * $total_revenue );
        $payout = $total_revenue-$total_commission-$total_paypal_fee;


        $gross_units += $total_units;
        $gross_revenue += $total_revenue;
        $gross_paypal_fee += $total_paypal_fee;
        $gross_commission += $total_commission;
        $gross_payout += $payout;


        $body_content .= '<tr>';

        $body_content .= '<td><a href="/~'.$i['i__id'].'" style="font-weight:bold;"><u>'.$i['i__title'].'</u></a></td>';
        $body_content .= '<td style="text-align: right;">'.$total_units.'x</td>';
        $body_content .= '<td style="text-align: right;">$'.number_format(( $total_units > 0 ? $total_revenue / $total_units : 0 ), 2).'</td>';
        $body_content .= '<td style="text-align: right;">+$'.number_format($total_revenue, 2).'</td>';
        $body_content .= '<td style="text-align: right;" title="'.($commission_rate*100).'%">-$'.number_format($total_commission, 2).'</td>';
        $body_content .= '<td style="text-align: right;" title="'.(( $total_revenue>0 ? $total_paypal_fee/$total_revenue : 0 )*100).'%">-$'.number_format($total_paypal_fee, 2).'</td>';
        $body_content .= '<td style="text-align: right;" title="'.(( $total_revenue>0 ? $payout/$total_revenue : 0 )*100).'%"><b>$'.number_format($payout, 2).'</b></td>';
        $body_content .= '<td>'.join(', ',$currencies).'</td>';
        $body_content .= '</tr>';

    }


    $table_sortable = array('#th_primary','#th_average','#th_rev');

    echo '<table style="font-size:0.8em;" id="sortable_table" class="table table-sm table-striped image-mini">';
    echo '<tr style="font-weight:bold; vertical-align: baseline;">';
    echo '<th id="th_primary">Ideas</th>';
    echo '<th style="text-align: right;" id="th_paid">Unit</th>';
    echo '<th style="text-align: right;" id="th_average">Average</th>';
    echo '<th style="text-align: right;" id="th_rev">Revenue</th>';
    echo '<th style="text-align: right;" id="th_payout">Commission</th>';
    echo '<th style="text-align: right;" id="th_payout">Paypal Fee</th>';
    echo '<th style="text-align: right;" id="th_payout">Payout</th>';
    echo '<th id="th_currency">Currency</th>';
    echo '</tr>';
    echo $body_content;
    echo '<tr style="font-size: 1.2em; font-weight: bold; padding: 5px 0;">';
    echo '<th id="th_primary">Totals</th>';
    echo '<th>'.$gross_units.'x</th>';
    echo '<th>$'.number_format(( $gross_units > 0 ? $gross_revenue / $gross_units : 0 ), 2).'</th>';
    echo '<th>+$'.number_format($gross_revenue, 2).'</th>';
    echo '<th title="'.($commission_rate*100).'%">-$'.number_format($gross_commission, 2).'</th>';
    echo '<th title="'.(( $gross_revenue>0 ? $gross_paypal_fee/$gross_revenue : 0 )*100).'%">-$'.number_format($gross_paypal_fee, 2).'</th>';
    echo '<th title="'.(( $gross_revenue>0 ? $gross_payout/$gross_revenue : 0 )*100).'%"><b>$'.number_format($gross_payout, 2).'</b></th>';
    echo '<th>'.join(', ',$gross_currencies).'</th>';
    echo '</tr>';
    echo '</table>';

} else {

    //Make sure right idea type:
    if(!count($this->I_model->fetch(array(
        'i__id' => $_GET['i__id'],
        'i__type IN (' . join(',', $this->config->item('n___27005')) . ')' => null, //Payment Idea
    )))){
        echo 'Error: Idea must be a payment type';
    } else {

        //All good, load transactions:
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERY COIN
            'x__left' => $_GET['i__id'],
        ), array('x__source'), 0) as $x_progress){

        }

    }

    //List a specific idea with its discovery transactions

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
        background-color: #f0f0f0 !important;
        -webkit-print-color-adjust:exact;
    }
    .table-striped td {
        border-bottom: 1px dotted #f0f0f0 !important;
        font-size: 1.3em;
    }
    .fa-filter, .fa-sort{
        font-size: 1.1em !important;
        margin-bottom: 3px;
    }
    th{
        cursor: ns-resize !important;
        border: 0 !important;
    }
    th:hover, th:active{
        background-color: #FFF;
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

    $(document).ready(function () {
        var table = $('#sortable_table');
        $('<?= join(', ', $table_sortable) ?>')
            .each(function(){

                var th = $(this),
                    thIndex = th.index(),
                    inverse = false;

                th.click(function(){

                    table.find('td').filter(function(){

                        return $(this).index() === thIndex;

                    }).sortElements(function(a, b){

                        return $.text([a]) < $.text([b]) ?
                            inverse ? -1 : 1
                            : inverse ? 1 : -1;

                    }, function(){

                        return this.parentNode;

                    });

                    inverse = !inverse;

                });

            });

    });
</script>


