<?php

$already_loaded = array(6287);
$e___6287 = $this->config->item('e___6287'); //APP

//Show Featured Apps
echo '<div class="row">';
foreach($this->X_model->fetch(array(
    'x__up' => 30841, //Featured Apps
    'x__down NOT IN (' . join(',', $already_loaded) . ')' => null, //SOURCE LINKS
    'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
), array('x__down'), 0, 0, array('x__weight' => 'ASC', 'e__title' => 'ASC')) as $app) {

    if(!in_array($app['e__id'], $this->config->item('n___6287')) || in_array($app['e__id'], $this->config->item('n___32141'))){ //Hidden Apps?
        continue;
    }

    $superpower_actives = array_intersect($this->config->item('n___10957'), $e___6287[$app['e__id']]['m__following']);
    if(count($superpower_actives) && !superpower_active(end($superpower_actives), true)){
        continue;
    }

    echo view_card_e(6287, $app);
    array_push($already_loaded, intval($app['e__id']));

}



//List Regular Apps:
foreach($this->X_model->fetch(array(
    'x__up' => 6287, //Featured Apps
    'x__down NOT IN (' . join(',', $already_loaded) . ')' => null, //SOURCE LINKS
    'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
), array('x__down'), 0, 0, array('x__weight' => 'ASC', 'e__title' => 'ASC')) as $app) {

    if(in_array($app['e__id'] , $this->config->item('n___32141'))){ //Hidden Apps?
        continue;
    }

    $superpower_actives = array_intersect($this->config->item('n___10957'), $e___6287[$app['e__id']]['m__following']);
    if(count($superpower_actives) && !superpower_active(end($superpower_actives), true)){
        continue;
    }

    echo view_card_e(6287, $app);
}
echo '</div>';