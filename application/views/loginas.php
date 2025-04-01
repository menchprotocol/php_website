<?php

//Make sure member:
if(!count($this->Menchledger->fetch(array(
    'linkvoid' => 0, //Not Void
    'linktype IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
    'linkup IN (' . join(',', $this->config->item('n___32537')) . ')' => null, //Interested Member
    'linkdown' => $focus_e['playerid'],
)))){

    return view__json(array(
        'status' => 0,
        'message' => 'Source is not an interested member',
    ));

} else {

    session_delete();

    //Assign session & log transaction:
    $this->Cacheplayers->activate_session($focus_e);

    js_php_redirect(loginas . phpview__memory(42903, 42902) . $focus_e['playerhandle'], 1597);

}