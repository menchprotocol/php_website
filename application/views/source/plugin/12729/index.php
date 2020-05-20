<?php

echo '<table class="table table-sm table-striped stats-table mini-stats-table">';

echo '<tr class="panel-title down-border">';
echo '<td style="text-align: left;">Read Type</td>';
echo '<td style="text-align: left;">Coins</td>';
echo '</tr>';


//Count them all:
$sources__12140 = $this->config->item('sources__12140');

$full_coins = $this->READ_model->fetch(array(
    'read__type IN (' . join(',', $this->config->item('sources_id_12141')) . ')' => null, //Full
    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
), array(), 0, 0, array(), 'COUNT(read__id) as total_reads');
echo '<tr class="panel-title down-border" style="font-weight: bold;">';
echo '<td style="text-align: left;" class="montserrat doupper">'.$sources__12140[12141]['m_icon'].' '.$sources__12140[12141]['m_name'].'</td>';
echo '<td style="text-align: left;">'.number_format($full_coins[0]['total_reads'], 0).'</td>';
echo '</tr>';


//Add some empty space:
echo '<tr class="panel-title down-border"><td style="text-align: left;" colspan="4">&nbsp;</td></tr>';

//Show each link type:
foreach($this->READ_model->fetch(array(
    'read__type IN (' . join(',', $this->config->item('sources_id_12141')) . ')' => null, //Full
    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
), array('source_type'), 0, 0, array('total_reads' => 'DESC'), 'COUNT(read__id) as total_reads, source__title, source__icon, source__id, read__type', 'source__id, source__title, source__icon, read__type') as $ln) {

    //Determine which weight group this belongs to:
    $direction = filter_cache_group($ln['source__id'], 2738);

    echo '<tr class="panel-title down-border">';
    echo '<td style="text-align: left;"><span class="icon-block">'.$ln['source__icon'].'</span><a href="/source/'.$ln['source__id'].'" class="montserrat doupper">'.$ln['source__title'].'</a></td>';
    echo '<td style="text-align: left;"><span class="icon-block">'.$direction['m_icon'].'</span>'.number_format($ln['total_reads'], 0).'</td>';
    echo '</tr>';

}

echo '</table>';
