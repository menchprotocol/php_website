<?php

$start_year = 2017;
$start_month = 01;

echo '<table>';

foreach($this->config->item('e___14874') as $linktype => $m) {

    if($linktype==12273){

        //IDEAS
        $unique = $this->Menchledger->fetch(array(
            'linkvoid' => 0, //Not Void
            'linktype IN (' . join(',', $this->config->item('n___13480')) . ')' => null, //UNIQUE IDEAS
        ), array('linkright'), 0, 0, array(), 'COUNT(linkid) as totals');

    } elseif($linktype==12274){

        //SOURCE
        $unique = $this->Menchledger->fetch(array(
            'linkvoid' => 0, //Not Void
            'linktype IN (' . join(',', $this->config->item('n___13548')) . ')' => null, //AUTHORED SOURCES
        ), array('linkdown'), 0, 0, array(), 'COUNT(linkid) as totals');

    } elseif(in_array($linktype, $this->config->item('n___42284'))){

        $unique = $this->Menchledger->fetch(array(
            'linkvoid' => 0, //Not Void
            'linktype IN (' . join(',', $this->config->item('n___'.$linktype)) . ')' => null,
        ), array(), 0, 0, array(), 'COUNT(linkid) as totals');

    } elseif($linktype==6255){

        $unique = $this->Menchledger->fetch(array(
            'linkvoid' => 0, //Not Void
            'linktype IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
        ), array(), 0, 0, array(), 'COUNT(linkid) as totals');

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

        if($linktype==12273){

            //IDEAS
            $query = $this->Menchledger->fetch(array(
                'linkvoid' => 0, //Not Void
                'linktype IN (' . join(',', $this->config->item('n___13480')) . ')' => null, //UNIQUE IDEAS
                'linktime >=' => $time_start,
                'linktime <' => $time_end,
            ), array('linkright'), 0, 0, array(), 'COUNT(linkid) as totals');

        } elseif($linktype==12274){

            //SOURCE
            $query = $this->Menchledger->fetch(array(
                'linkvoid' => 0, //Not Void
                'linktype IN (' . join(',', $this->config->item('n___13548')) . ')' => null, //UNIQUE SOURCES
                'linktime >=' => $time_start,
                'linktime <' => $time_end,
            ), array('linkdown'), 0, 0, array(), 'COUNT(linkid) as totals');

        } elseif($linktype==6255){

            $query = $this->Menchledger->fetch(array(
                'linkvoid' => 0, //Not Void
                'linktype IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                'linktime >=' => $time_start,
                'linktime <' => $time_end,
            ), array(), 0, 0, array(), 'COUNT(linkid) as totals');

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
