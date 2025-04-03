<?php

$playersids_joined = array_merge($this->config->item('playerids___32292'), $this->config->item('playerids___31777'),$this->config->item('playerids___13550'),$this->config->item('playerids___4486'));
$list_links_joined = array_merge($this->list_link_sourcing,$this->list_link_discovery,$this->list_link_contribution,$this->list_link_ideation);

$var = array(

    'count_list_link_sourcing' => count($this->list_link_sourcing),
    'count_playerids___32292' => count($this->config->item('playerids___32292')),
    'count_list_link_discovery' => count($this->list_link_discovery),
    'count_playerids___31777' => count($this->config->item('playerids___31777')),
    'count_list_link_contribution' => count($this->list_link_contribution),
    'count_playerids___13550' => count($this->config->item('playerids___13550')),
    'count_list_link_ideation' => count($this->list_link_ideation),
    'count_playerids___4486' => count($this->config->item('playerids___4486')),
    'count_playersids_joined' => count($playersids_joined),
    'count_list_links_joined' => count($list_links_joined),

    /*
    'list_link_sourcing' => $this->list_link_sourcing,
    'playerids___32292' => $this->config->item('playerids___32292'),
    'list_link_discovery' => $this->list_link_discovery,
    'playerids___31777' => $this->config->item('playerids___31777'),
    'list_link_contribution' => $this->list_link_contribution,
    'playerids___13550' => $this->config->item('playerids___13550'),
    'list_link_ideation' => $this->list_link_ideation,
    'playerids___4486' => $this->config->item('playerids___4486'),


    'list_links_joined' => $list_links_joined,
    'playersids_joined' => $playersids_joined,
    */
);

$var['stats_sourcing'] = $this->Menchledger->fetch(array(
    'linkplayertype IN (' . join(',', $this->list_link_sourcing) . ')' => null,
), array(), 0, 0, array(), 'COUNT(linkid) as totals');
$var['stats_discovery'] = $this->Menchledger->fetch(array(
    'linkplayertype IN (' . join(',', $this->list_link_discovery) . ')' => null,
), array(), 0, 0, array(), 'COUNT(linkid) as totals');
$var['stats_contribution'] = $this->Menchledger->fetch(array(
    'linkplayertype IN (' . join(',', $this->list_link_contribution) . ')' => null,
), array(), 0, 0, array(), 'COUNT(linkid) as totals');
$var['stats_ideation'] = $this->Menchledger->fetch(array(
    'linkplayertype IN (' . join(',', $this->list_link_ideation) . ')' => null,
), array(), 0, 0, array(), 'COUNT(linkid) as totals');

$var['stats_void'] = $this->Menchledger->fetch(array(
    'linkvoid > IN' => 0,
), array(), 0, 0, array(), 'COUNT(linkid) as totals');


$var['playersids_count'] = $this->Menchledger->fetch(array(
    'linkplayertype IN (' . join(',', $playersids_joined) . ')' => null,
), array(), 0, 0, array(), 'COUNT(linkid) as totals');
$var['playersids_count_reverse'] = $this->Menchledger->fetch(array(
    'linkplayertype NOT IN (' . join(',', $playersids_joined) . ')' => null,
), array(), 0, 0, array(), 'COUNT(linkid) as totals');

$var['list_links_count'] = $this->Menchledger->fetch(array(
    'linkplayertype IN (' . join(',', $list_links_joined) . ')' => null,
), array(), 0, 0, array(), 'COUNT(linkid) as totals');
$var['list_links_count_reverse'] = $this->Menchledger->fetch(array(
    'linkplayertype NOT IN (' . join(',', $list_links_joined) . ')' => null,
), array(), 0, 0, array(), 'COUNT(linkid) as totals');



$var['playersids_MISSING'] = $this->Menchledger->fetch(array(
    'linkplayertype NOT IN (' . join(',', $playersids_joined) . ')' => null,
), array(), 0, 0, array(), 'linkplayertype, COUNT(*) as total', 'linkplayertype');

$var['list_links_MISSING'] = $this->Menchledger->fetch(array(
    'linkplayertype NOT IN (' . join(',', $list_links_joined) . ')' => null,
), array(), 0, 0, array(), 'linkplayertype, COUNT(*) as total', 'linkplayertype');


view_json($var);


/*
view_json($this->Menchledger->tree_full_history($focus_i, $focus_e['playerid']));

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
foreach ($this->Menchledger->fetchold(array(
    'link_type IN (' . join(',', $list_link_contribution) . ')' => null, //Active Writes
), array(), 0, 0, array('link_id' => 'DESC')) as $x) {

    $count++;
    $is_missing = !count($this->Menchledger->fetch(array(
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
        $this->Menchledger->create(array(
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