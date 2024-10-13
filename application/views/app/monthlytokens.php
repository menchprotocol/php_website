<?php

$start_year = 2017;
$start_month = 01;

echo '<table>';

foreach($this->config->item('e___14874') as $x__type => $m) {

    if($x__type==12273){

        //IDEAS
        $unique = $this->X_model->fetch(array(
                    'x__type IN (' . join(',', $this->config->item('n___13480')) . ')' => null, //UNIQUE IDEAS
        ), array('x__next'), 0, 0, array(), 'COUNT(x__id) as totals');

    } elseif($x__type==12274){

        //SOURCE
        $unique = $this->X_model->fetch(array(
                    'x__type IN (' . join(',', $this->config->item('n___13548')) . ')' => null, //AUTHORED SOURCES
        ), array('x__follower'), 0, 0, array(), 'COUNT(x__id) as totals');

    } elseif(in_array($x__type, $this->config->item('n___42284'))){

        $unique = $this->X_model->fetch(array(
            'x__type IN (' . join(',', $this->config->item('n___'.$x__type)) . ')' => null,
        ), array(), 0, 0, array(), 'COUNT(x__id) as totals');

    } elseif($x__type==6255){

        //DISCOVERY
        $unique = $this->X_model->fetch(array(
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
        ), array(), 0, 0, array(), 'COUNT(x__id) as totals');

    } else {

        continue;

    }


    echo '<tr>';
    echo '<td class="main__title"><div class="col_name">'.$m['m__cover'].' '.$m['m__title'].'</div></td>';
    echo '<td>'.number_format($unique[0]['totals'], 0).'</td>';
    echo '<td>&nbsp;</td>';
    echo '<td>&nbsp;</td>';
    echo '<td>&nbsp;</td>';


    for($i=0;$i<1000;$i++){

        $time_start = date("Y-m-d H:i:s", mktime(0, 0, 0, $start_month+$i, 1, $start_year));
        $time_end = date("Y-m-d H:i:s", mktime(0, 0, 0, $start_month+$i+1, 1, $start_year));

        if($x__type==12273){

            //IDEAS
            $query = $this->X_model->fetch(array(
                    'x__type IN (' . join(',', $this->config->item('n___13480')) . ')' => null, //UNIQUE IDEAS
                'x__time >=' => $time_start,
                'x__time <' => $time_end,
            ), array('x__next'), 0, 0, array(), 'COUNT(x__id) as totals');

        } elseif($x__type==12274){

            //SOURCE
            $query = $this->X_model->fetch(array(
                    'x__type IN (' . join(',', $this->config->item('n___13548')) . ')' => null, //UNIQUE SOURCES
                'x__time >=' => $time_start,
                'x__time <' => $time_end,
            ), array('x__follower'), 0, 0, array(), 'COUNT(x__id) as totals');

        } elseif($x__type==6255){

            //DISCOVERY
            $query = $this->X_model->fetch(array(
                    'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
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
echo '<td>&nbsp;</td>';
echo '<td>&nbsp;</td>';
echo '<td>&nbsp;</td>';
echo '<td>&nbsp;</td>';
echo '<td>&nbsp;</td>';
for($i=0;$i<1000;$i++){

    $time_start = date("Y-m-d H:i:s", mktime(0, 0, 0, $start_month+$i, 1, $start_year));
    $time_end = date("Y-m-d H:i:s", mktime(0, 0, 0, $start_month+$i+1, 1, $start_year));

    echo '<td style="font-size: 0.8em;" title="'.$time_start.' - '.$time_end.'"><div class="col_stat main__title"><b>'.date("ym", mktime(0, 0, 0, $start_month+$i, date("j"), $start_year)).'</b></div></td>';

    if(date("Y-m", mktime(0, 0, 0, $start_month+$i, 1, $start_year))==date("Y-m")){
        break;
    }
}
echo '</tr>';


echo '</table>';
