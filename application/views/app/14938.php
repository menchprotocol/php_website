<?php

$i__id = (isset($_GET['i__id']) && intval($_GET['i__id']) ? intval($_GET['i__id']) : 0 );

//Make sure not logged in:
if($member_e['e__id']){

    js_php_redirect(( $i__id ? '/'.$i__id : '/@'.$member_e['e__id'] ), 13);

} else {

    //Create a new account for them:
    $random_cover = random_cover(12279);
    $random_title = random_adjective().' '.str_replace('Black Widow','',ucwords(str_replace('-',' ',one_two_explode('fa-',' ',$random_cover))));
    $member_result = $this->E_model->add_member($random_title, null, null, $random_cover);
    js_php_redirect(( $i__id ? '/'.$i__id : '/@'.$member_result['e']['e__id'] ), 13);

}

echo '<div class="center"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span></div>';