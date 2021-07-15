<?php

$commission_rate = doubleval(view_memory(6404,27017))/100;

if(!isset($_GET['i__id']) || !intval($_GET['i__id'])){


    //List all payment Ideas and their total earnings
    $body_content = '';
    foreach($this->I_model->fetch(array(
        'i__type IN (' . join(',', $this->config->item('n___27005')) . ')' => null, //Payment Idea
    )) as $i){

        //Total earnings:
        $total_units = 0;
        $total_revenue = 0;
        $total_paypal_fee = 0;
        $total_instant = 0;
        $currencies = array();

        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERY COIN
            'x__left' => $i['i__id'],
        ), array(), 0) as $x){
            $x__metadata = unserialize($x['x__metadata']);
            $total_units++;
            $total_paypal_fee += doubleval($x__metadata['mc_fee']);
            $total_instant += ( $x__metadata['payment_type']=='instant' ? 1 : 0 );
            $total_revenue += doubleval($x__metadata['mc_gross']);
            if(!in_array($x__metadata['mc_currency'], $currencies)){
                array_push($currencies, $x__metadata['mc_currency']);
            }
        }
        $total_commission = ( $commission_rate * $total_revenue );

        $body_content .= '<tr>';

        $body_content .= '<td><a href="/~'.$i['i__id'].'" style="font-weight:bold;"><u>'.$i['i__title'].'</u></a></td>';
        $body_content .= '<td>'.$total_instant.'/'.$total_units.'</td>';
        $body_content .= '<td>'.join(', ',$currencies).'</td>';
        $body_content .= '<td>$'.number_format($total_revenue, 2).'</td>';
        if($total_revenue > 0 && $total_units > 0){
            $body_content .= '<td>$'.number_format(( $total_revenue / $total_units ), 2).'</td>';
            $body_content .= '<td title="Commission of $'.$total_commission.' ('.($commission_rate*100).'%) and Paypal Fee of $'.$total_paypal_fee.' ('.($total_paypal_fee/$total_revenue*100).'%)">$'.number_format(($total_revenue-$total_commission), 2).'</td>';
        } else {
            $body_content .= '<td>$0</td>';
            $body_content .= '<td>$0</td>';
        }

        $body_content .= '</tr>';

    }


    $table_sortable = array('#th_primary','#th_average','#th_rev');

    echo '<table style="font-size:0.8em;" id="sortable_table" class="table table-sm table-striped image-mini">';
    echo '<tr style="font-weight:bold; vertical-align: baseline;">';
    echo '<th id="th_primary">Paid Ideas</th>';
    echo '<th id="th_currency">Currency</th>';
    echo '<th id="th_paid">Instant Payments</th>';
    echo '<th id="th_average">Average Payment</th>';
    echo '<th id="th_rev">Total Revenue</th>';
    echo '<th id="th_payout">Net Payout</th>';
    echo '</tr>';
    echo $body_content;
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


