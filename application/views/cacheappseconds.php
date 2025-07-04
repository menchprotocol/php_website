<?php

if($handle_session){
    //Remove all Active Cache:
    echo '<div class="alert alert-warning" role="alert">Deleted '.reset_cache($handle_session['handleid']).' active caches</div>';
}


$found_cache = 0;
echo '<div class="row justify-content margin-top-down">';
foreach($this->config->item('handles___14599') as $app_handleid => $cache_apps){

    //Fetch Last Cache
    $latest_cache = $this->Chains->read(array(
        'chainhandledomain' => website_setting(0),
        'chainhandletype' => 44179, //Triggered
        'chainhandleinput' => 14599, //Cache App
        'chainhandleoutput' => $app_handleid,
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
