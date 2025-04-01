<?php

$already_loaded = array(6287);
$players___6287 = $this->config->item('players___6287'); //APP



//Start with Featured Apps
echo '<div class="row">';
foreach($this->Menchledger->fetch(array(
    'linkup' => 30841, //Featured Apps
    'linkdown NOT IN (' . join(',', $already_loaded) . ')' => null, //SOURCE LINKS
    'linktype IN (' . join(',', $this->config->item('playerids___32292')) . ')' => null, //SOURCE LINKS
    'linkvoid' => 0, //Not Void
), array('linkdown'), 0, 0, sort__player()) as $app) {

    if(!in_array($app['playerid'], $this->config->item('playerids___6287')) || in_array($app['playerid'], $this->config->item('playerids___32141'))){ //Hidden Apps?
        continue;
    }

    $superpowers_required = array_intersect($this->config->item('playerids___10957'), $players___6287[$app['playerid']]['m__following']);
    if(count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
        continue;
    }

    echo view__card_player(6287, $app);
    array_push($already_loaded, intval($app['playerid']));

}
echo '</div>';




//List Regular Apps:
echo '<div class="row">';
foreach($this->Menchledger->fetch(array(
    'linkup' => 6287, //Featured Apps
    'linkdown NOT IN (' . join(',', $already_loaded) . ')' => null, //SOURCE LINKS
    'linktype IN (' . join(',', $this->config->item('playerids___32292')) . ')' => null, //SOURCE LINKS
    'linkvoid' => 0, //Not Void
), array('linkdown'), 0, 0, sort__player()) as $app) {

    if(in_array($app['playerid'] , $this->config->item('playerids___32141'))){ //Hidden Apps?
        continue;
    }

    if(isset($players___6287[$app['playerid']])){
        $superpowers_required = array_intersect($this->config->item('playerids___10957'), $players___6287[$app['playerid']]['m__following']);
        if(count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
            continue;
        }
    }

    echo view__card_player(6287, $app);
}
echo '</div>';
