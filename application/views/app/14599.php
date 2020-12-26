<?php

$user_e = superpower_unlocked();
if(isset($_GET['reset'])){
    //Remove all Active Cache:
    $count = 0;
    foreach($this->X_model->fetch(array(
        'x__type' => 14599, //Cache App
        'x__up IN (' . join(',', $this->config->item('n___14599')) . ')' => null, //Cache Apps
        'x__time >' => date("Y-m-d H:i:s", (time() - view_memory(6404,14599))),
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    )) as $delete_cahce){
        //Delete email:
        $count += $this->X_model->update($delete_cahce['x__id'], array(
            'x__status' => 6173, //Transaction Removed
        ), $user_e['e__id'], 14600 /* Delete Cache */);
    }

    echo '<div class="msg alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span> Deleted '.$count.' active caches</div>';

}


$found_cache = 0;
echo '<div class="row">';
foreach($this->config->item('e___14599') as $app_e__id => $cache_apps){

    //Fetch Last Cache
    $latest_cache = $this->X_model->fetch(array(
        'x__type' => 14599, //Cache App
        'x__up' => $app_e__id,
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    ), array(), 1, 0, array('x__time' => 'DESC'));

    echo '<div class="col-8"><span class="icon-block">'.$cache_apps['m__icon'].'</span>'.$cache_apps['m__title'].'</div>';
    echo '<div class="col-4"><i class="far fa-history"></i> '.( count($latest_cache) ? view_time_difference(strtotime($latest_cache[0]['x__time'])) : 'NEVER' ).'</div>';

    if(count($latest_cache)){
        $found_cache++;
    }

}
echo '</div>';

if($found_cache){
    echo '<a href="/app/14599?reset=1">RESET ACTIVE CACHE</a>';
}
