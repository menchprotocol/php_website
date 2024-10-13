<?php

if ($player_e) {
    //Remove all Active Cache:
    echo '<div class="alert alert-warning" role="alert">Deleted ' . reset_cache($player_e['e__id']) . ' active caches</div>';
}


$found_cache = 0;
echo '<div class="row justify-content margin-top-down">';
foreach ($this->config->item('e___14599') as $app_e__id => $cache_mem) {

    //Fetch Last Cache
    $latest_cache = $this->Ledger->read(array(
        'x__type' => 14599, //Cache App
        'x__following' => $app_e__id,
    ), array(), 1, 0, array('x__time' => 'DESC'));

    echo '<div class="col-8 main__title"><span class="icon-block">' . $cache_mem['m__cover'] . '</span>' . $cache_mem['m__title'] . '</div>';
    echo '<div class="col-4"><i class="far fa-history"></i> ' . (count($latest_cache) ? view_time_difference($latest_cache[0]['x__time']) : 'NEVER') . '</div>';

    if (count($latest_cache)) {
        $found_cache++;
    }

}
echo '</div>';

if ($found_cache) {
    echo '<a href="' . view_app_link(14599) . '?reset=1" class="btn btn-default">RESET ACTIVE CACHE</a>';
}
