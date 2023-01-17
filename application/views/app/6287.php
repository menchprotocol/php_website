<?php

$already_loaded = array(6287);

//Show Featured Apps
echo '<div class="row">';
foreach($this->X_model->fetch(array(
    'x__up' => 30841, //Featured Apps
    'x__down NOT IN (' . join(',', $already_loaded) . ')' => null, //SOURCE LINKS
    'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'e__privacy IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
), array('x__down'), 0, 0, array('x__spectrum' => 'ASC', 'e__title' => 'ASC')) as $app) {
    echo view_card_e(6287, $app);
    array_push($already_loaded, intval($app['e__id']));
}

//List Regular Apps:
foreach($this->X_model->fetch(array(
    'x__up' => 6287, //Featured Apps
    'x__down NOT IN (' . join(',', $already_loaded) . ')' => null, //SOURCE LINKS
    'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'e__privacy IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
), array('x__down'), 0, 0, array('x__spectrum' => 'ASC', 'e__title' => 'ASC')) as $app) {
    echo view_card_e(6287, $app);
}
echo '</div>';