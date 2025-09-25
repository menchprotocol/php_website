<?php

$posthashtag = (isset($_GET['posthashtag']) && strlen($_GET['posthashtag']) ? $_GET['posthashtag'] : false );

//Make sure not logged in:
if($user_session['userid']){

    js_php_redirect(( $posthashtag ? guestlogin . view_memory(42903, 33286) . $posthashtag : view_memory(42903,42902).$user_session['userhandle'] ), 13);

} else {

    $usercover_generator = usercover_generator(12279);
    $user_result = $this->Users->join(view_random_title(), null, null, $usercover_generator);
    js_php_redirect(( $posthashtag ? guestlogin . view_memory(42903, 33286) . $posthashtag : view_memory(42903,42902).$user_result['e']['userhandle'] ), 13);

}

echo '<div class="center"><span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span></div>';