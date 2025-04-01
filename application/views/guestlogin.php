<?php

$ideahashtag = (isset($_GET['ideahashtag']) && strlen($_GET['ideahashtag']) ? $_GET['ideahashtag'] : false );

//Make sure not logged in:
if($player_e['playerid']){

    js_php_redirect(( $ideahashtag ? guestlogin . phpview__memory(42903, 33286) . $ideahashtag : view__memory(42903,42902).$player_e['playerhandle'] ), 13);

} else {

    $random_cover = random_cover(12279);
    $player_result = $this->Source_cache->add_member(view__random_title(), null, null, $random_cover);
    js_php_redirect(( $ideahashtag ? guestlogin . phpview__memory(42903, 33286) . $ideahashtag : view__memory(42903,42902).$player_result['e']['playerhandle'] ), 13);

}

echo '<div class="center"><span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span></div>';