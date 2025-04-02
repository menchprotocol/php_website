<?php

$start_year = 2017;
$start_month = 01;

echo '<table>';

foreach ($this->config->item('players___14874') as $linkplayertype => $m) {

    if ($linkplayertype == 12273) {

        //IDEAS
        $unique = $this->Menchledger->fetch(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___13480')) . ')' => null, //UNIQUE IDEAS
        ), array('linkidearight'), 0, 0, array(), 'COUNT(linkid) as totals');

    } elseif ($linkplayertype == 12274) {

        //SOURCE
        $unique = $this->Menchledger->fetch(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //AUTHORED SOURCES
        ), array('linkplayerdown'), 0, 0, array(), 'COUNT(linkid) as totals');

    } elseif (in_array($linkplayertype, $this->config->item('playerids___42284'))) {

        $unique = $this->Menchledger->fetch(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___' . $linkplayertype)) . ')' => null,
        ), array(), 0, 0, array(), 'COUNT(linkid) as totals');

    } elseif ($linkplayertype == 6255) {

        $unique = $this->Menchledger->fetch(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
        ), array(), 0, 0, array(), 'COUNT(linkid) as totals');

    } else {

        continue;

    }


    echo '<tr>';
    echo '<td class="main__title"><div class="col_name">' . $m['m__cover'] . ' ' . $m['m__title'] . '</div></td>';
    echo '<td>' . number_format($unique[0]['totals'], 0) . '</td>';
    echo '<td>&nbsp;</td>';
    echo '<td>&nbsp;</td>';
    echo '<td>&nbsp;</td>';


    for ($i = 0; $i < 1000; $i++) {

        $time_start = date("Y-m-d H:i:s", mktime(0, 0, 0, $start_month + $i, 1, $start_year));
        $time_end = date("Y-m-d H:i:s", mktime(0, 0, 0, $start_month + $i + 1, 1, $start_year));

        if ($linkplayertype == 12273) {

            //IDEAS
            $query = $this->Menchledger->fetch(array(
                'linkplayertype IN (' . join(',', $this->config->item('playerids___13480')) . ')' => null, //UNIQUE IDEAS
                'linktime >=' => $time_start,
                'linktime <' => $time_end,
            ), array('linkidearight'), 0, 0, array(), 'COUNT(linkid) as totals');

        } elseif ($linkplayertype == 12274) {

            //SOURCE
            $query = $this->Menchledger->fetch(array(
                'linkplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //UNIQUE SOURCES
                'linktime >=' => $time_start,
                'linktime <' => $time_end,
            ), array('linkplayerdown'), 0, 0, array(), 'COUNT(linkid) as totals');

        } elseif ($linkplayertype == 6255) {

            $query = $this->Menchledger->fetch(array(
                'linkplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                'linktime >=' => $time_start,
                'linktime <' => $time_end,
            ), array(), 0, 0, array(), 'COUNT(linkid) as totals');

        }

        echo '<td style="font-size: 0.8em;"><div class="col_stat">' . ($query[0]['totals'] > 0 ? number_format($query[0]['totals'], 0) : '&nbsp;') . '</div></td>';

        if (date("Y-m", mktime(0, 0, 0, $start_month + $i, 1, $start_year)) == date("Y-m")) {
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
for ($i = 0; $i < 1000; $i++) {

    $time_start = date("Y-m-d H:i:s", mktime(0, 0, 0, $start_month + $i, 1, $start_year));
    $time_end = date("Y-m-d H:i:s", mktime(0, 0, 0, $start_month + $i + 1, 1, $start_year));

    echo '<td style="font-size: 0.8em;" title="' . $time_start . ' - ' . $time_end . '"><div class="col_stat main__title"><b>' . date("ym", mktime(0, 0, 0, $start_month + $i, date("j"), $start_year)) . '</b></div></td>';

    if (date("Y-m", mktime(0, 0, 0, $start_month + $i, 1, $start_year)) == date("Y-m")) {
        break;
    }
}
echo '</tr>';


echo '</table>';
