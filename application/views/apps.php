<?php

$already_loaded = array(6287);
$e___6287 = $this->config->item('e___6287'); //APP



//Start with Featured Apps
echo '<div class="row">';
foreach($this->Mench_ledger->fetch(array(
    'link_up' => 30841, //Featured Apps
    'link_down NOT IN (' . join(',', $already_loaded) . ')' => null, //SOURCE LINKS
    'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
    'link_void' => 0, //Not Void
), array('link_down'), 0, 0, sort__e()) as $app) {

    if(!in_array($app['e__id'], $this->config->item('n___6287')) || in_array($app['e__id'], $this->config->item('n___32141'))){ //Hidden Apps?
        continue;
    }

    $superpowers_required = array_intersect($this->config->item('n___10957'), $e___6287[$app['e__id']]['m__following']);
    if(count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
        continue;
    }

    echo view__card_e(6287, $app);
    array_push($already_loaded, intval($app['e__id']));

}
echo '</div>';




//List Regular Apps:
echo '<div class="row">';
foreach($this->Mench_ledger->fetch(array(
    'link_up' => 6287, //Featured Apps
    'link_down NOT IN (' . join(',', $already_loaded) . ')' => null, //SOURCE LINKS
    'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
    'link_void' => 0, //Not Void
), array('link_down'), 0, 0, sort__e()) as $app) {

    if(in_array($app['e__id'] , $this->config->item('n___32141'))){ //Hidden Apps?
        continue;
    }

    if(isset($e___6287[$app['e__id']])){
        $superpowers_required = array_intersect($this->config->item('n___10957'), $e___6287[$app['e__id']]['m__following']);
        if(count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
            continue;
        }
    }

    echo view__card_e(6287, $app);
}
echo '</div>';
