<?php

//Make sure member:
if(!count($this->Chains->read(array(
    'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
    'chainuserinput IN (' . join(',', $this->config->item('userids___32537')) . ')' => null, //Interested Member
    'chainuseroutput' => $focus_e['userid'],
)))){

    return view_json(array(
        'status' => 0,
        'message' => 'User is not an interested member',
    ));

} else {

    session_delete();

    //Assign session & log Chain:
    $this->Users->activate($focus_e);

    js_php_redirect( view_memory(42903, 42902) . $focus_e['userhandle'], 1597);

}