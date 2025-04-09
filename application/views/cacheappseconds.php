<?php

if($player_session){
    //Remove all Active Cache:
    echo '<div class="alert alert-warning" role="alert">Deleted '.reset_cache($player_session['playerid']).' active caches</div>';
}


$found_cache = 0;
echo '<div class="row justify-content margin-top-down">';
foreach($this->config->item('players___14599') as $app_playerid => $cache_apps){

    //Fetch Last Cache
    $latest_cache = $this->Links->read(array(
        'linkplayerdomain' => website_setting(0),
        'linkplayertype' => 44179, //Triggered
        'linkplayerup' => 14599, //Cache App
        'linkplayerdown' => $app_playerid,
        ), array(), 1, 0, array('linktime' => 'DESC'));

    echo '<div class="col-8 main__title"><span class="icon-block">'.$cache_apps['m__cover'].'</span>'.$cache_apps['m__title'].'</div>';
    echo '<div class="col-4"><i class="far fa-history"></i> '.( count($latest_cache) ? view_time_difference($latest_cache[0]['linktime']) : 'NEVER' ).'</div>';

    if(count($latest_cache)){
        $found_cache++;
    }

}
echo '</div>';

if($found_cache){
    echo '<a href="'.view_app_link(14599).'?reset=1" class="btn btn-default">RESET ACTIVE CACHE</a>';
}
