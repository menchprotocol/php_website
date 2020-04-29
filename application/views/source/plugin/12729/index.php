<?php

echo '<table class="table table-sm table-striped stats-table mini-stats-table">';

echo '<tr class="panel-title down-border">';
echo '<td style="text-align: left;">Transaction Type</td>';
echo '<td style="text-align: left;">Coins</td>';
echo '</tr>';


//Count them all:
$en_all_12140 = $this->config->item('en_all_12140');

$full_coins = $this->LEDGER_model->ln_fetch(array(
    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12141')) . ')' => null, //Full
    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
), array(), 0, 0, array(), 'COUNT(ln_id) as total_transactions');
echo '<tr class="panel-title down-border" style="font-weight: bold;">';
echo '<td style="text-align: left;" class="montserrat doupper">'.$en_all_12140[12141]['m_icon'].' '.$en_all_12140[12141]['m_name'].'</td>';
echo '<td style="text-align: left;">'.number_format($full_coins[0]['total_transactions'], 0).'</td>';
echo '</tr>';


//Add some empty space:
echo '<tr class="panel-title down-border"><td style="text-align: left;" colspan="4">&nbsp;</td></tr>';

//Show each link type:
foreach ($this->LEDGER_model->ln_fetch(array(
    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12141')) . ')' => null, //Full
    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
), array('en_type'), 0, 0, array('total_transactions' => 'DESC'), 'COUNT(ln_id) as total_transactions, en_name, en_icon, en_id, ln_type_source_id', 'en_id, en_name, en_icon, ln_type_source_id') as $ln) {

    //Determine which weight group this belongs to:
    $direction = filter_cache_group($ln['en_id'], 2738);

    echo '<tr class="panel-title down-border">';
    echo '<td style="text-align: left;"><span class="icon-block">'.$ln['en_icon'].'</span><a href="/source/'.$ln['en_id'].'" class="montserrat doupper">'.$ln['en_name'].'</a></td>';
    echo '<td style="text-align: left;"><span class="icon-block">'.$direction['m_icon'].'</span>'.number_format($ln['total_transactions'], 0).'</td>';
    echo '</tr>';

}

echo '</table>';
