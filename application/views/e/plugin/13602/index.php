<?php

$start_year = 2017;
$start_month = 01;
$total_months = 48;


echo '<table>';


echo '<tr>';
echo '<td><div style="width: 400px;">&nbsp;</div></td>';
for($i=0;$i<$total_months;$i++){
    echo '<td style="font-size: 0.8em;"><div style="width: 120px;"><b>'.date("ym", mktime(0, 0, 0, $start_month+$i, date("j"), $start_year)).'</b></div></td>';
}
echo '</tr>';


foreach($this->config->item('e___12467') as $x__type => $m) {
    echo '<tr>';
    echo '<td class="montserrat doupper"><div style="width: 400px;">'.$m['m_icon'].' '.$m['m_title'].'</div></td>';
    for($i=0;$i<$total_months;$i++){
        echo '<td style="font-size: 0.8em;"><div style="width: 120px;">'.rand(0,2000).'</div></td>';
    }
    echo '</tr>';
}


echo '</table>';
