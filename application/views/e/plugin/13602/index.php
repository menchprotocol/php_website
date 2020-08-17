<?php

$start_year = 2017;
$start_month = 01;
$total_months = 48;


echo '<table>';



echo '<tr>';
echo '<td><div class="col_name">&nbsp;</div></td>';
for($i=0;$i<$total_months;$i++){

    $last_week_start = date("Y-m-d H:i:s", mktime(0, 0, 0, $start_month+$i, 1, $start_year));
    $last_week_end = date("Y-m-d H:i:s", mktime(0, 0, 0, $start_month+$i+1, 1, $start_year));

    echo '<td style="font-size: 0.8em;" title="'.$last_week_start.' - '.$last_week_end.'"><div class="col_stat montserrat"><b>'.date("ym", mktime(0, 0, 0, $start_month+$i, date("j"), $start_year)).'</b></div></td>';
}
echo '</tr>';


foreach($this->config->item('e___12467') as $x__type => $m) {
    echo '<tr>';
    echo '<td class="montserrat doupper"><div class="col_name">'.$m['m_icon'].' '.$m['m_title'].'</div></td>';
    for($i=0;$i<$total_months;$i++){

        $last_week_start = date("Y-m-d H:i:s", mktime(0, 0, 0, $start_month+$i, 1, $start_year));
        $last_week_end = date("Y-m-d H:i:s", mktime(0, 0, 0, $start_month+$i+1, 1, $start_year));

        if($x__type==12273){

            //SOURCE
            $query = $this->X_model->fetch(array(
                'e__status IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___13548')) . ')' => null, //UNIQUE SOURCES
                'x__time >=' => $last_week_start,
                'x__time <' => $last_week_end,
            ), array('x__down'), 0, 0, array(), 'COUNT(x__id) as totals');

        } elseif($x__type==12274){

            //IDEAS
            $query = $this->X_model->fetch(array(
                'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___13480')) . ')' => null, //UNIQUE IDEAS
                'x__time >=' => $last_week_start,
                'x__time <' => $last_week_end,
            ), array('x__right'), 0, 0, array(), 'COUNT(x__id) as totals');

        } elseif($x__type==6255){

            //DISCOVER
            $query = $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVER COIN
                'x__time >=' => $last_week_start,
                'x__time <' => $last_week_end,
            ), array(), 0, 0, array(), 'COUNT(x__id) as totals');

        }

        echo '<td style="font-size: 0.8em;"><div class="col_stat">'.number_format($query[0]['totals'], 0).'</div></td>';
    }
    echo '</tr>';
}


echo '</table>';
