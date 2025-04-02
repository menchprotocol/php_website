<?php

//Make sure member:
if(!count($this->Ledger->fetch(array(
    'linktype IN (' . join(',', $this->config->item('playerids___32292')) . ')' => null, //SOURCE LINKS
    'linkup IN (' . join(',', $this->config->item('playerids___32537')) . ')' => null, //Interested Member
    'linkdown' => $focus_e['playerid'],
)))){

    return view_json(array(
        'status' => 0,
        'message' => 'Player is not an interested member',
    ));

} else {

    session_delete();

    //Assign session & log transaction:
    $this->Nodeplayers->activate_session($focus_e);

    js_php_redirect(loginas . phpview_memory(42903, 42902) . $focus_e['playerhandle'], 1597);

}