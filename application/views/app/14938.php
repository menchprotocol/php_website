<?php

$i__id = (isset($_GET['i__id']) && intval($_GET['i__id']) ? intval($_GET['i__id']) : 0 );

//Make sure not logged in:
if($member_e['e__id']){

    js_php_redirect(( $i__id ? '/'.$i__id : '/@'.$member_e['e__id'] ), 13);

} else {

    $member_result = $this->E_model->add_member(view_random_title(), null, null, $random_cover);
    js_php_redirect(( $i__id ? '/'.$i__id : '/@'.$member_result['e']['e__id'] ), 13);

}

echo '<div class="center"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span></div>';