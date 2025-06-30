<?php

$start_year = 2017;
$start_month = 01;

echo '<table>';

foreach ($this->config->item('sources___14874') as $chainsourcetype => $m) {

    if ($chainsourcetype == 12273) {

        //IDEAS
        $unique = $this->Chains->read(array(
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13480')) . ')' => null, //UNIQUE IDEAS
        ), array('chainidearight'), 0, 0, array(), 'COUNT(chainid) as totals');

    } elseif ($chainsourcetype == 12274) {

        //SOURCE
        $unique = $this->Chains->read(array(
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //AUTHORED SOURCES
        ), array('chainsourcedown'), 0, 0, array(), 'COUNT(chainid) as totals');

    } elseif ($chainsourcetype==31777) {

        $unique = $this->Chains->read(array(
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___' . $chainsourcetype)) . ')' => null,
        ), array(), 0, 0, array(), 'COUNT(chainid) as totals');

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

        if ($chainsourcetype == 12273) {

            //IDEAS
            $query = $this->Chains->read(array(
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13480')) . ')' => null, //UNIQUE IDEAS
                'chaintime >=' => $time_start,
                'chaintime <' => $time_end,
            ), array('chainidearight'), 0, 0, array(), 'COUNT(chainid) as totals');

        } elseif ($chainsourcetype == 12274) {

            //SOURCE
            $query = $this->Chains->read(array(
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //UNIQUE SOURCES
                'chaintime >=' => $time_start,
                'chaintime <' => $time_end,
            ), array('chainsourcedown'), 0, 0, array(), 'COUNT(chainid) as totals');

        } elseif ($chainsourcetype == 31777) {

            $query = $this->Chains->read(array(
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___31777')) . ')' => null, //DISCOVERIES
                'chaintime >=' => $time_start,
                'chaintime <' => $time_end,
            ), array(), 0, 0, array(), 'COUNT(chainid) as totals');

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
