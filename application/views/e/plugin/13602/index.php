<?php

$start_year = 2017;
$start_month = 01;
$total_months = 48;


echo '<table>';


echo '<tr>';
echo '<td>Name</td>';
for($i=0;$i<$total_months;$i++){
    echo '<td>'.date("Y-m-d H:i:s", mktime(0, 0, 0, $start_month+$i, date("j"), $start_year)).'</td>';
}
echo '</tr>';


foreach($this->config->item('e___12467') as $x__type => $m) {
    echo '<tr>';
    echo '<td class="montserrat doupper">'.$m['m_icon'].' '.$m['m_title'].'</td>';
    for($i=0;$i<$total_months;$i++){
        echo '<td>'.rand(0,2000).'</td>';
    }
    echo '</tr>';
}


echo '</table>';
