<?php

$hashtagterm = (isset($_GET['hashtagterm']) && strlen($_GET['hashtagterm']) ? $_GET['hashtagterm'] : false );

//Make sure not logged in:
if($handle_session['handleid']){

    js_php_redirect(( $hashtagterm ? guestlogin . view_memory(42903, 33286) . $hashtagterm : view_memory(42903,42902).$handle_session['handleterm'] ), 13);

} else {

    $handlecover_generator = handlecover_generator(12279);
    $handle_result = $this->Handles->join(view_random_title(), null, null, $handlecover_generator);
    js_php_redirect(( $hashtagterm ? guestlogin . view_memory(42903, 33286) . $hashtagterm : view_memory(42903,42902).$handle_result['e']['handleterm'] ), 13);

}

echo '<div class="center"><span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span></div>';