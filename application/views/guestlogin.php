<?php

$ideahashtag = (isset($_GET['ideahashtag']) && strlen($_GET['ideahashtag']) ? $_GET['ideahashtag'] : false );

//Make sure not logged in:
if($player_active['playerid']){

    js_php_redirect(( $ideahashtag ? guestlogin . phpview_memory(42903, 33286) . $ideahashtag : view_memory(42903,42902).$player_active['playerhandle'] ), 13);

} else {

    $playercover_generator = playercover_generator(12279);
    $player_result = $this->Players->join(view_random_title(), null, null, $playercover_generator);
    js_php_redirect(( $ideahashtag ? guestlogin . phpview_memory(42903, 33286) . $ideahashtag : view_memory(42903,42902).$player_result['e']['playerhandle'] ), 13);

}

echo '<div class="center"><span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span></div>';