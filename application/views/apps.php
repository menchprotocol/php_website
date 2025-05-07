<?php

$already_loaded = array(6287);
$sources___6287 = $this->config->item('sources___6287'); //APP


//Start with Featured Apps
echo '<div class="row">';
foreach ($this->Chains->read(array(
    'chainsourceup' => 30841, //Featured Apps
    'chainsourcedown NOT IN (' . join(',', $already_loaded) . ')' => null, //SOURCE CHAINS
    'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
), array('chainsourcedown'), 0, 0, source_sort()) as $app) {

    if (!in_array($app['sourceid'], $this->config->item('sourceids___6287')) || in_array($app['sourceid'], $this->config->item('sourceids___32141'))) { //Hidden Apps?
        continue;
    }

    $superpowers_required = array_intersect($this->config->item('sourceids___10957'), $sources___6287[$app['sourceid']]['m__following']);
    if (count($superpowers_required) && !source_session(end($superpowers_required))) {
        continue;
    }

    echo source_view(6287, $app);
    array_push($already_loaded, intval($app['sourceid']));

}
echo '</div>';


//List Regular Apps:
echo '<div class="row">';
foreach ($this->Chains->read(array(
    'chainsourceup' => 6287, //Featured Apps
    'chainsourcedown NOT IN (' . join(',', $already_loaded) . ')' => null, //SOURCE CHAINS
    'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
), array('chainsourcedown'), 0, 0, source_sort()) as $app) {

    if (in_array($app['sourceid'], $this->config->item('sourceids___32141'))) { //Hidden Apps?
        continue;
    }

    if (isset($sources___6287[$app['sourceid']])) {
        $superpowers_required = array_intersect($this->config->item('sourceids___10957'), $sources___6287[$app['sourceid']]['m__following']);
        if (count($superpowers_required) && !source_session(end($superpowers_required))) {
            continue;
        }
    }

    echo source_view(6287, $app);
}
echo '</div>';
