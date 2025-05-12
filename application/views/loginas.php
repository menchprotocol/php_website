<?php

//Make sure member:
if(!count($this->Chains->read(array(
    'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
    'chainsourceup IN (' . join(',', $this->config->item('sourceids___32537')) . ')' => null, //Interested Member
    'chainsourcedown' => $focus_e['sourceid'],
)))){

    return view_json(array(
        'status' => 0,
        'message' => 'Source is not an interested member',
    ));

} else {

    session_delete();

    //Assign session & log Chain:
    $this->Sources->activate($focus_e);

    js_php_redirect( view_memory(42903, 42902) . $focus_e['sourcehandle'], 1597);

}