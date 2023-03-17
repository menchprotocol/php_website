<?php

$i__id = (isset($_GET['i__id']) && intval($_GET['i__id']) ? intval($_GET['i__id']) : 0 );

//Make sure not logged in:
if($member_e['e__id']){

    js_php_redirect(( $i__id ? '/'.$i__id : '/@'.$member_e['e__id'] ), 13);

} else {

    //Create a new account for them:
    $member_result = $this->E_model->add_member('Anonymous User');

    //Update to a more fun name:
    $member_result['e']['e__title'] = random_adjective().' '.str_replace('Black Widow','',ucwords(str_replace('-',' ',one_two_explode('fa-',' ',$member_result['e']['e__cover'])))).' '.$member_result['e']['e__id'];
    $res = $this->E_model->update($member_result['e']['e__id'], array(
        'e__title' => $member_result['e']['e__title'],
    ), true, $member_result['e']['e__id']);

    //Assign session & log transaction:
    $this->E_model->activate_session($member_result['e']);

    js_php_redirect(( $i__id ? '/'.$i__id : '/@'.$member_result['e']['e__id'] ), 13);

}

echo '<div class="center"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span></div>';