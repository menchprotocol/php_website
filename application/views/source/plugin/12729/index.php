<?php

echo '<table class="table table-sm table-striped stats-table mini-stats-table">';

echo '<tr class="panel-title down-border">';
echo '<td style="text-align: left;">Read Type</td>';
echo '<td style="text-align: left;">Coins</td>';
echo '</tr>';


//Count them all:
$sources__12140 = $this->config->item('sources__12140');

$full_coins = $this->DISCOVER_model->fetch(array(
    'x__type IN (' . join(',', $this->config->item('sources_id_12141')) . ')' => null, //Full
    'x__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
), array(), 0, 0, array(), 'COUNT(x__id) as total_reads');
echo '<tr class="panel-title down-border" style="font-weight: bold;">';
echo '<td style="text-align: left;" class="montserrat doupper">'.$sources__12140[12141]['m_icon'].' '.$sources__12140[12141]['m_name'].'</td>';
echo '<td style="text-align: left;">'.number_format($full_coins[0]['total_reads'], 0).'</td>';
echo '</tr>';


//Add some empty space:
echo '<tr class="panel-title down-border"><td style="text-align: left;" colspan="4">&nbsp;</td></tr>';

//Show each link type:
foreach($this->DISCOVER_model->fetch(array(
    'x__type IN (' . join(',', $this->config->item('sources_id_12141')) . ')' => null, //Full
    'x__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
), array('x__type'), 0, 0, array('total_reads' => 'DESC'), 'COUNT(x__id) as total_reads, e__title, e__icon, e__id, x__type', 'e__id, e__title, e__icon, x__type') as $read) {

    //Determine which weight group this belongs to:
    $direction = filter_cache_group($read['e__id'], 2738);

    echo '<tr class="panel-title down-border">';
    echo '<td style="text-align: left;"><span class="icon-block">'.$read['e__icon'].'</span><a href="/@'.$read['e__id'].'" class="montserrat doupper">'.$read['e__title'].'</a></td>';
    echo '<td style="text-align: left;"><span class="icon-block">'.$direction['m_icon'].'</span>'.number_format($read['total_reads'], 0).'</td>';
    echo '</tr>';

}

echo '</table>';
