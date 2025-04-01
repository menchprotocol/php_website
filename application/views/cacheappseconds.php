<?php

if($player_e){
    //Remove all Active Cache:
    echo '<div class="alert alert-warning" role="alert">Deleted '.reset_cache($player_e['playerid']).' active caches</div>';
}


$found_cache = 0;
echo '<div class="row justify-content margin-top-down">';
foreach($this->config->item('players___14599') as $app_playerid => $cache_apps){

    //Fetch Last Cache
    $latest_cache = $this->Menchledger->fetch(array(
        'linkdomain' => website_setting(0),
        'linktype' => 44179, //Triggered
        'linkup' => 14599, //Cache App
        'linkdown' => $app_playerid,
        'linkvoid' => 0, //Not Void
    ), array(), 1, 0, array('linktime' => 'DESC'));

    echo '<div class="col-8 main__title"><span class="icon-block">'.$cache_apps['m__cover'].'</span>'.$cache_apps['m__title'].'</div>';
    echo '<div class="col-4"><i class="far fa-history"></i> '.( count($latest_cache) ? view__time_difference($latest_cache[0]['linktime']) : 'NEVER' ).'</div>';

    if(count($latest_cache)){
        $found_cache++;
    }

}
echo '</div>';

if($found_cache){
    echo '<a href="'.view__app_link(14599).'?reset=1" class="btn btn-default">RESET ACTIVE CACHE</a>';
}
