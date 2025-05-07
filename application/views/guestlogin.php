<?php

$ideahashtag = (isset($_GET['ideahashtag']) && strlen($_GET['ideahashtag']) ? $_GET['ideahashtag'] : false );

//Make sure not logged in:
if($source_session['sourceid']){

    js_php_redirect(( $ideahashtag ? guestlogin . phpview_memory(42903, 33286) . $ideahashtag : view_memory(42903,42902).$source_session['sourcehandle'] ), 13);

} else {

    $sourcecover_generator = sourcecover_generator(12279);
    $source_result = $this->Sources->join(view_random_title(), null, null, $sourcecover_generator);
    js_php_redirect(( $ideahashtag ? guestlogin . phpview_memory(42903, 33286) . $ideahashtag : view_memory(42903,42902).$source_result['e']['sourcehandle'] ), 13);

}

echo '<div class="center"><span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span></div>';