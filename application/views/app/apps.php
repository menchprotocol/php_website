<?php

$already_loaded = array(6287);
$e___6287 = $this->config->item('e___6287'); //APP



//Start with Featured Apps
echo '<div class="row">';
foreach($this->X_model->fetch(array(
    'x__following' => 30841, //Featured Apps
    'x__follower NOT IN (' . join(',', $already_loaded) . ')' => null, //SOURCE LINKS
    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
), array('x__follower'), 0, 0, sort__e()) as $app) {

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
echo '</div>';




//List Regular Apps:
echo '<div class="extra_apps center" style="padding: 34px 0;"><a href="javascript:void(0)" onclick="$(\'.extra_apps\').toggleClass(\'hidden\');"><i class="far fa-search-plus"></i> SEE MORE</a></div>';
echo '<div class="row extra_apps hidden">';
foreach($this->X_model->fetch(array(
    'x__following' => 6287, //Featured Apps
    'x__follower NOT IN (' . join(',', $already_loaded) . ')' => null, //SOURCE LINKS
    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
), array('x__follower'), 0, 0, sort__e()) as $app) {

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
