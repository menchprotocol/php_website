<?php

//Make sure member:
if(!count($this->X_model->fetch(array(
    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
    'x__following IN (' . join(',', $this->config->item('n___32537')) . ')' => null, //Interested Member
    'x__follower' => $focus_e['e__id'],
)))){

    return view_json(array(
        'status' => 0,
        'message' => 'Source is not an interested member',
    ));

} else {

    session_delete();

    //Assign session & log transaction:
    $this->E_model->activate_session($focus_e);

    js_php_redirect(view_memory(42903,42902).$focus_e['e__handle'], 1597);

}