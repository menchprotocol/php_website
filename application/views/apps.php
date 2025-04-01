<?php

$already_loaded = array(6287);
$e___6287 = $this->config->item('e___6287'); //APP



//Start with Featured Apps
echo '<div class="row">';
foreach($this->Mench_ledger->fetch(array(
    'linkup' => 30841, //Featured Apps
    'linkdown NOT IN (' . join(',', $already_loaded) . ')' => null, //SOURCE LINKS
    'linktype IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
    'linkvoid' => 0, //Not Void
), array('linkdown'), 0, 0, sort__e()) as $app) {

    if(!in_array($app['playerid'], $this->config->item('n___6287')) || in_array($app['playerid'], $this->config->item('n___32141'))){ //Hidden Apps?
        continue;
    }

    $superpowers_required = array_intersect($this->config->item('n___10957'), $e___6287[$app['playerid']]['m__following']);
    if(count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
        continue;
    }

    echo view__card_e(6287, $app);
    array_push($already_loaded, intval($app['playerid']));

}
echo '</div>';




//List Regular Apps:
echo '<div class="row">';
foreach($this->Mench_ledger->fetch(array(
    'linkup' => 6287, //Featured Apps
    'linkdown NOT IN (' . join(',', $already_loaded) . ')' => null, //SOURCE LINKS
    'linktype IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
    'linkvoid' => 0, //Not Void
), array('linkdown'), 0, 0, sort__e()) as $app) {

    if(in_array($app['playerid'] , $this->config->item('n___32141'))){ //Hidden Apps?
        continue;
    }

    if(isset($e___6287[$app['playerid']])){
        $superpowers_required = array_intersect($this->config->item('n___10957'), $e___6287[$app['playerid']]['m__following']);
        if(count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
            continue;
        }
    }

    echo view__card_e(6287, $app);
}
echo '</div>';
