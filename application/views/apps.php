<?php

$already_loaded = array(6287);
$handles___6287 = $this->config->item('handles___6287'); //APP


//Start with Featured Apps
echo '<div class="row">';
foreach ($this->Chains->read(array(
    'chainhandleinput' => 30841, //Featured Apps
    'chainhandleoutput NOT IN (' . join(',', $already_loaded) . ')' => null, //HANDLE CHAINS
    'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
), array('chainhandleoutput'), 0, 0, handle_sort()) as $app) {

    if (!in_array($app['handleid'], $this->config->item('handleids___6287')) || in_array($app['handleid'], $this->config->item('handleids___32141'))) { //Hidden Apps?
        continue;
    }

    $superpowers_required = array_intersect($this->config->item('handleids___10957'), $handles___6287[$app['handleid']]['m__following']);
    if (count($superpowers_required) && !handle_session(end($superpowers_required))) {
        continue;
    }

    echo handle_view(6287, $app);
    array_push($already_loaded, intval($app['handleid']));

}
echo '</div>';


//List Regular Apps:
echo '<div class="row">';
foreach ($this->Chains->read(array(
    'chainhandleinput' => 6287, //Featured Apps
    'chainhandleoutput NOT IN (' . join(',', $already_loaded) . ')' => null, //HANDLE CHAINS
    'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
), array('chainhandleoutput'), 0, 0, handle_sort()) as $app) {

    if (in_array($app['handleid'], $this->config->item('handleids___32141'))) { //Hidden Apps?
        continue;
    }

    if (isset($handles___6287[$app['handleid']])) {
        $superpowers_required = array_intersect($this->config->item('handleids___10957'), $handles___6287[$app['handleid']]['m__following']);
        if (count($superpowers_required) && !handle_session(end($superpowers_required))) {
            continue;
        }
    }

    echo handle_view(6287, $app);
}
echo '</div>';
