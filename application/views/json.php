<?php

//view_json($this->Links->history($focus_i, $focus_e['playerid']));

$count = 0;
$duplicate = 0;
$previous = false;

echo '<table>';
foreach($this->Links->read(array(
    'linkplayertype IN (' . join(',', array_merge($this->list_link_sourcing,$this->list_link_contribution)) . ')' => null, //SUCCESSFUL DISCOVERIES
), array(), 0, 0, array(
    'linkplayertype' => 'ASC',
    'linkplayerup' => 'ASC',
    'linkplayerdown' => 'ASC',
    'linkidearight' => 'ASC',
    'linkidealeft' => 'ASC',
    'linktext' => 'ASC',
    'linkid' => 'DESC',
)) as $discover){

    $count++;
    if($previous && $previous['linkplayercreator']==$discover['linkplayercreator'] && $previous['linkplayertype']==$discover['linkplayertype'] && $previous['linkplayerup']==$discover['linkplayerup'] && $previous['linkplayerdown']==$discover['linkplayerdown'] && $previous['linkidearight']==$discover['linkidearight'] && $previous['linkidealeft']==$discover['linkidealeft'] && $previous['linktext']==$discover['linktext']){

        $duplicate++;
        echo '<tr><td>'.$previous['linkplayertype'].'</td><td>'.$previous['linktime'].'</td><td>'.$previous['linkplayercreator'].'</td><td>'.$previous['linkplayerup'].'</td><td>'.$previous['linkplayerdown'].'</td><td>'.$previous['linkidearight'].'</td><td>'.$previous['linkidealeft'].'</td><td>'.$previous['linktext'].'</td><td>'.$previous['linkplayertype'].'</td><td>'.$previous['linkplayertype'].'</td></tr>';
        echo '<tr style="background-color: #CCC;"><td>'.$discover['linkplayertype'].'</td><td>'.$discover['linktime'].'</td><td>'.$discover['linkplayercreator'].'</td><td>'.$discover['linkplayerup'].'</td><td>'.$discover['linkplayerdown'].'</td><td>'.$discover['linkidearight'].'</td><td>'.$discover['linkidealeft'].'</td><td>'.$discover['linktext'].'</td><td>'.$discover['linkplayertype'].'</td><td>'.$discover['linkplayertype'].'</td></tr>';

        //$this->db->query("DELETE FROM menchledger WHERE linkid=".$discover['linkid'].";");

    }

    $previous = $discover;
}

echo '</table>';
echo $duplicate.'/'.$count.' are duplicate';

/*

die('pending...');

$count_ideation = 0;
$count_sourcing = 0;
$count_contribution = 0;
$count_discovery = 0;
$count_other = 0;
$missing_ideation = 0;
$missing_sourcing = 0;
$missing_contribution = 0;
$missing_discovery = 0;
$missing_other = 0;
boost_power();

$count = 0;
$missing = 0;
foreach ($this->Links->readalt(array(
    'link_type IN (' . join(',', $list_link_contribution) . ')' => null, //Active Writes
), array(), 0, 0, array('link_id' => 'DESC')) as $x) {

    $count++;
    $is_missing = !count($this->Links->read(array(
        'linkplayertype' => $x['link_type'],
        'linkplayerup' => $x['link_up'],
        'linkplayerdown' => $x['link_down'],
        'linkidealeft' => ($x['link_left'] > 0 ? intval($x['link_left']) + 100000 : 0),
        'linkidearight' => ($x['link_right'] > 0 ? intval($x['link_right']) + 100000 : 0),
    )));
    if ($is_missing) {
        $missing++;
        echo print_r($x, true);
        echo 'WAS MISSING <hr />';
        $this->Links->create(array(
            'linktime' => $x['link_time'],
            'linkplayercreator' => $x['link_player'],
            'linkplayertype' => $x['link_type'],
            'linkplayerdomain' => $x['link_domain'],
            'linkplayerup' => $x['link_up'],
            'linkplayerdown' => $x['link_down'],
            'linkidealeft' => ($x['link_left'] > 0 ? intval($x['link_left']) + 100000 : 0),
            'linkidearight' => ($x['link_right'] > 0 ? intval($x['link_right']) + 100000 : 0),
            'linknumber' => $x['link_number'],
            'linktext' => $x['link_text'],
        ));
    }

    if (in_array($x['link_type'], $this->list_link_ideation)) {
        $count_ideation++;
        if ($is_missing) {
            $missing_ideation++;
        }
    } elseif (in_array($x['link_type'], $this->list_link_sourcing)) {
        $count_sourcing++;
        if ($is_missing) {
            $missing_sourcing++;
        }
    } elseif (in_array($x['link_type'], $this->list_link_contribution)) {
        $count_contribution++;
        if ($is_missing) {
            $missing_contribution++;
        }
    } elseif (in_array($x['link_type'], $this->list_link_discovery)) {
        $count_discovery++;
        if ($is_missing) {
            $missing_discovery++;
        }
    } else {
        $count_other++;
        if ($is_missing) {
            $missing_other++;
        }
        echo print_r($x, true);
        echo 'WAS OTHER<hr />';
    }

}

echo $missing . '/' . $count . ' Total Missing:<br /><br />';

echo $missing_ideation . '/' . $count_ideation . ' $count_ideation<br />';
echo $missing_sourcing . '/' . $count_sourcing . ' $count_sourcing<br />';
echo $missing_contribution . '/' . $count_contribution . ' $count_contribution<br />';
echo $missing_discovery . '/' . $count_discovery . ' $count_discovery<br /><br />';
echo $missing_other . '/' . $count_other . ' $count_other<br />';

*/