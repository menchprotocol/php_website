<?php

$already_loaded = array(6287);
$e___6287 = $this->config->item('e___6287'); //APP

//Show Featured Apps
echo '<div class="row">';
foreach($this->X_model->fetch(array(
    'x__up' => 30841, //Featured Apps
    'x__down NOT IN (' . join(',', $already_loaded) . ')' => null, //SOURCE LINKS
    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
), array('x__down'), 0, 0, sort__e()) as $app) {

    if(!in_array($app['e__id'], $this->config->item('n___6287')) || in_array($app['e__id'], $this->config->item('n___32141'))){ //Hidden Apps?
        continue;
    }

    $superpowers_required = array_intersect($this->config->item('n___10957'), $e___6287[$app['e__id']]['m__following']);
    if(count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
        continue;
    }

    echo view_card_e(6287, $app);
    array_push($already_loaded, intval($app['e__id']));

}



//List Regular Apps:
foreach($this->X_model->fetch(array(
    'x__up' => 6287, //Featured Apps
    'x__down NOT IN (' . join(',', $already_loaded) . ')' => null, //SOURCE LINKS
    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
), array('x__down'), 0, 0, sort__e()) as $app) {

    if(in_array($app['e__id'] , $this->config->item('n___32141'))){ //Hidden Apps?
        continue;
    }

    if(isset($e___6287[$app['e__id']])){
        $superpowers_required = array_intersect($this->config->item('n___10957'), $e___6287[$app['e__id']]['m__following']);
        if(count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
            continue;
        }
    }

    echo view_card_e(6287, $app);
}
echo '</div>';