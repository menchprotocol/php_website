<?php

$i__id = (isset($_GET['i__id']) && intval($_GET['i__id']) ? intval($_GET['i__id']) : 0 );

//Make sure not logged in:
if($member_e['e__id']){

    js_php_redirect(( $i__id ? '/'.$i__id : '/@'.$member_e['e__id'] ), 13);

} else {

    //Create a new account for them:
    $member_result = $this->E_model->add_member('Anonymous User');
    js_php_redirect(( $i__id ? '/'.$i__id : '/@'.$member_result['e']['e__id'] ), 13);

}