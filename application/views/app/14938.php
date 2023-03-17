<?php

$i__id = (isset($_GET['i__id']) && intval($_GET['i__id']) ? intval($_GET['i__id']) : 0 );

//Make sure not logged in:
if($member_e['e__id']){

    js_php_redirect(( $i__id ? '/'.$i__id : '/@'.$member_e['e__id'] ), 13);

} else {

    //Create a new account for them:
    $member_result = $this->E_model->add_member('Anonymous User');

    //Now Update to a more fun name:
    $res = $this->E_model->update($member_result['e']['e__id'], array(
        'e__title' => random_adjective().' '.str_replace('Black Widow','',ucwords(str_replace('-',' ',one_two_explode('fa-',' ',$member_result['e']['e__cover'])))).' '.$member_result['e']['e__id'],
    ), true);

    js_php_redirect(( $i__id ? '/'.$i__id : '/@'.$member_result['e']['e__id'] ), 13);

}