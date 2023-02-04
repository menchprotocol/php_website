<?php

if(!isset($_GET['e__id']) || !intval($_GET['e__id'])){
    $_GET['e__id'] = $member_e['e__id'];
}

if(!isset($_GET['i__id']) || !intval($_GET['i__id'])) {

    //List this members discoveries so they can choose:
    echo '<div>Enter i__id to begin...</div><br />';

} else {

    $is = $this->I_model->fetch(array(
        'i__id' => $_GET['i__id'],
        'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
    ));

    if(count($is) < 1){

        echo 'Public Idea not found';

    } else {

        //List the idea:
        view_json(array(
            'X_model' => array(
                'find_next' => $this->X_model->find_next($_GET['e__id'], $is[0]['i__id'], $is[0], 0, false),
                'tree_progress' => $this->X_model->tree_progress($_GET['e__id'], $is[0]),
            ),
        ));

    }
}
