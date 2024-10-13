<?php

$i__hashtag = (isset($_GET['i__hashtag']) && strlen($_GET['i__hashtag']) ? $_GET['i__hashtag'] : false );

//Make sure not logged in:
if($player_e['e__id']){

    js_php_redirect(( $i__hashtag ? view_memory(42903,33286).$i__hashtag : view_memory(42903,42902).$player_e['e__handle'] ), 13);

} else {

    $random_cover = random_cover(12279);
    $player_result = $this->Sources->add_member(view_random_title(), null, null, $random_cover);
    js_php_redirect(( $i__hashtag ? view_memory(42903,33286).$i__hashtag : view_memory(42903,42902).$player_result['e']['e__handle'] ), 13);

}

echo '<div class="center"><span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span></div>';