<?php

//Make sure member:
if(!count($this->Chains->read(array(
    'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
    'chainplayerup IN (' . join(',', $this->config->item('playerids___32537')) . ')' => null, //Interested Member
    'chainplayerdown' => $focus_e['playerid'],
)))){

    return view_json(array(
        'status' => 0,
        'message' => 'Player is not an interested member',
    ));

} else {

    session_delete();

    //Assign session & log Chain:
    $this->Players->activate($focus_e);

    js_php_redirect( phpview_memory(42903, 42902) . $focus_e['playerhandle'], 1597);

}