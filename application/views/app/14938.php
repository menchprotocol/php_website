<?php

$i__hashtag = (isset($_GET['i__hashtag']) && strlen($_GET['i__hashtag']) ? $_GET['i__hashtag'] : false );

//Make sure not logged in:
if($member_e['e__id']){

    js_php_redirect(( $i__hashtag ? '/'.$i__hashtag : '/@'.$member_e['e__handle'] ), 13);

} else {

    $random_cover = random_cover(12279);
    $member_result = $this->E_model->add_member(view_random_title(), null, null, $random_cover);
    js_php_redirect(( $i__hashtag ? '/'.$i__hashtag : '/@'.$member_result['e']['e__handle'] ), 13);

}

echo '<div class="center"><span class="icon-block-xs"><i class="far fa-yin-yang fa-spin"></i></span></div>';