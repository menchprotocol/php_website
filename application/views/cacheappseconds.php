<?php

if($user_session){
    //Remove all Active Cache:
    echo '<div class="alert alert-warning" role="alert">Deleted '.reset_cache($user_session['userid']).' active caches</div>';
}


$found_cache = 0;
echo '<div class="row justify-content margin-top-down">';
foreach($this->config->item('users___14599') as $app_userid => $cache_apps){

    //Fetch Last Cache
    $latest_cache = $this->Chains->read(array(
        'chainuserdomain' => website_setting(0),
        'chainusertype' => 44176, //User View
        'chainuserinput' => 14599, //Cache App
        'chainuseroutput' => $app_userid,
        ), array(), 1, 0, array('chaintime' => 'DESC'));

    echo '<div class="col-8 main__title"><span class="icon-block">'.$cache_apps['m__cover'].'</span>'.$cache_apps['m__title'].'</div>';
    echo '<div class="col-4"><i class="far fa-history"></i> '.( count($latest_cache) ? view_time_difference($latest_cache[0]['chaintime']) : 'NEVER' ).'</div>';

    if(count($latest_cache)){
        $found_cache++;
    }

}
echo '</div>';

if($found_cache){
    echo '<a href="'.view_app_chain(14599).'?reset=1" class="btn btn-default">RESET ACTIVE CACHE</a>';
}
