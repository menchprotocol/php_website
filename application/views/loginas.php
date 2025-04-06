<?php

//Make sure member:
if(!count($this->Links->read(array(
    'linkplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
    'linkplayerup IN (' . join(',', $this->config->item('playerids___32537')) . ')' => null, //Interested Member
    'linkplayerdown' => $focus_e['playerid'],
)))){

    return view_json(array(
        'status' => 0,
        'message' => 'Player is not an interested member',
    ));

} else {

    session_delete();

    //Assign session & log Link:
    $this->Players->activate($focus_e);

    js_php_redirect(loginas . phpview_memory(42903, 42902) . $focus_e['playerhandle'], 1597);

}