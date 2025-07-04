<?php

$hashtaghashtag = (isset($_GET['hashtaghashtag']) && strlen($_GET['hashtaghashtag']) ? $_GET['hashtaghashtag'] : false );

//Make sure not logged in:
if($handle_session['handleid']){

    js_php_redirect(( $hashtaghashtag ? guestlogin . view_memory(42903, 33286) . $hashtaghashtag : view_memory(42903,42902).$handle_session['handlehandle'] ), 13);

} else {

    $handlecover_generator = handlecover_generator(12279);
    $handle_result = $this->Handles->join(view_random_title(), null, null, $handlecover_generator);
    js_php_redirect(( $hashtaghashtag ? guestlogin . view_memory(42903, 33286) . $hashtaghashtag : view_memory(42903,42902).$handle_result['e']['handlehandle'] ), 13);

}

echo '<div class="center"><span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span></div>';