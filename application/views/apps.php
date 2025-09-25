<?php

$already_loaded = array(6287);
$users___6287 = $this->config->item('users___6287'); //APP


//Start with Featured Apps
echo '<div class="row">';
foreach ($this->Chains->read(array(
    'chainuserinput' => 30841, //Featured Apps
    'chainuseroutput NOT IN (' . join(',', $already_loaded) . ')' => null, //USER CHAINS
    'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
), array('chainuseroutput'), 0, 0, user_sort()) as $app) {

    if (!in_array($app['userid'], $this->config->item('userids___6287')) || in_array($app['userid'], $this->config->item('userids___32141'))) { //Hidden Apps?
        continue;
    }

    $superpowers_required = array_intersect($this->config->item('userids___10957'), $users___6287[$app['userid']]['m__following']);
    if (count($superpowers_required) && !user_session(end($superpowers_required))) {
        continue;
    }

    echo user_view(6287, $app);
    array_push($already_loaded, intval($app['userid']));

}
echo '</div>';


//List Regular Apps:
echo '<div class="row">';
foreach ($this->Chains->read(array(
    'chainuserinput' => 6287, //Featured Apps
    'chainuseroutput NOT IN (' . join(',', $already_loaded) . ')' => null, //USER CHAINS
    'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
), array('chainuseroutput'), 0, 0, user_sort()) as $app) {

    if (in_array($app['userid'], $this->config->item('userids___32141'))) { //Hidden Apps?
        continue;
    }

    if (isset($users___6287[$app['userid']])) {
        $superpowers_required = array_intersect($this->config->item('userids___10957'), $users___6287[$app['userid']]['m__following']);
        if (count($superpowers_required) && !user_session(end($superpowers_required))) {
            continue;
        }
    }

    echo user_view(6287, $app);
}
echo '</div>';
