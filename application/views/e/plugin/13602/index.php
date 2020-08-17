<?php

$start_year = 2017;
$start_month = 01;

echo '<table>';

foreach($this->config->item('e___12467') as $x__type => $m) {
    echo '<tr>';
    echo '<td class="montserrat doupper"><div class="col_name">'.$m['m_icon'].' '.$m['m_title'].'</div></td>';


    if($x__type==12273){

        //IDEAS
        $unique = $this->X_model->fetch(array(
            'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___13480')) . ')' => null, //UNIQUE IDEAS
        ), array('x__right'), 0, 0, array(), 'COUNT(x__id) as totals');

    } elseif($x__type==12274){

        //SOURCE
        $unique = $this->X_model->fetch(array(
            'e__status IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___13548')) . ')' => null, //UNIQUE SOURCES
        ), array('x__down'), 0, 0, array(), 'COUNT(x__id) as totals');

    } elseif($x__type==6255){

        //DISCOVER
        $unique = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVER COIN
        ), array(), 0, 0, array(), 'COUNT(x__id) as totals');

    }

    echo '<td>'.number_format($unique[0]['totals'], 0).'</td>';

    for($i=0;$i<1000;$i++){

        $time_start = date("Y-m-d H:i:s", mktime(0, 0, 0, $start_month+$i, 1, $start_year));
        $time_end = date("Y-m-d H:i:s", mktime(0, 0, 0, $start_month+$i+1, 1, $start_year));

        if($x__type==12273){

            //IDEAS
            $query = $this->X_model->fetch(array(
                //'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                //'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___13480')) . ')' => null, //UNIQUE IDEAS
                'x__time >=' => $time_start,
                'x__time <' => $time_end,
            ), array('x__right'), 0, 0, array(), 'COUNT(x__id) as totals');

        } elseif($x__type==12274){

            //SOURCE
            $query = $this->X_model->fetch(array(
                //'e__status IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
                //'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___13548')) . ')' => null, //UNIQUE SOURCES
                'x__time >=' => $time_start,
                'x__time <' => $time_end,
            ), array('x__down'), 0, 0, array(), 'COUNT(x__id) as totals');

        } elseif($x__type==6255){

            //DISCOVER
            $query = $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVER COIN
                'x__time >=' => $time_start,
                'x__time <' => $time_end,
            ), array(), 0, 0, array(), 'COUNT(x__id) as totals');

        }

        echo '<td style="font-size: 0.8em;"><div class="col_stat">'.( $query[0]['totals'] > 0 ? number_format($query[0]['totals'], 0) : '&nbsp;' ).'</div></td>';

        if(date("Y-m", mktime(0, 0, 0, $start_month+$i, 1, $start_year))==date("Y-m")){
            break;
        }

    }
    echo '</tr>';
}


echo '<tr>';
echo '<td><div class="col_name">&nbsp;</div></td>';
echo '<td><div class="col_name">&nbsp;</div></td>';
for($i=0;$i<1000;$i++){

    $time_start = date("Y-m-d H:i:s", mktime(0, 0, 0, $start_month+$i, 1, $start_year));
    $time_end = date("Y-m-d H:i:s", mktime(0, 0, 0, $start_month+$i+1, 1, $start_year));

    echo '<td style="font-size: 0.8em;" title="'.$time_start.' - '.$time_end.'"><div class="col_stat montserrat"><b>'.date("ym", mktime(0, 0, 0, $start_month+$i, date("j"), $start_year)).'</b></div></td>';

    if(date("Y-m", mktime(0, 0, 0, $start_month+$i, 1, $start_year))==date("Y-m")){
        break;
    }
}
echo '</tr>';


echo '</table>';
