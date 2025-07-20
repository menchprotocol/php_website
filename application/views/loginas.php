<?php

//Make sure member:
if(!count($this->Chains->read(array(
    'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
    'chainhandleinput IN (' . join(',', $this->config->item('handleids___32537')) . ')' => null, //Interested Member
    'chainhandleoutput' => $focus_e['handleid'],
)))){

    return view_json(array(
        'status' => 0,
        'message' => 'Handle is not an interested member',
    ));

} else {

    session_delete();

    //Assign session & log Chain:
    $this->Handles->activate($focus_e);

    js_php_redirect( view_memory(42903, 42902) . $focus_e['handleterm'], 1597);

}