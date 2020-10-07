<?php

if(!isset($_GET['e__id']) || !intval($_GET['e__id'])){
    $_GET['e__id'] = $user_e['e__id'];
}

if(!isset($_GET['i__id']) || !intval($_GET['i__id'])) {

    //List this users Discoveries so they can choose:
    echo '<div>Choose one of your Discoveries to debug:</div><br />';

    $u_x = $this->X_model->fetch(array(
        'x__source' => $_GET['e__id'],
        'x__type IN (' . join(',', $this->config->item('n___12969')) . ')' => null, //MY DISCOVERIES
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
    ), array('x__left'), 0, 0, array('x__sort' => 'ASC'));

    foreach($u_x as $priority => $x) {
        echo '<div>' . ($priority + 1) . ') <a href="?i__id=' . $x['i__id'] . '&e__id=' . $_GET['e__id'] . '">' . view_i_title($x) . '</a></div>';
    }

} else {

    $is = $this->I_model->fetch(array(
        'i__id' => $_GET['i__id'],
        'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
    ));

    if(count($is) < 1){

        echo 'Public Idea not found';

    } else {

        //List the idea:
        view_json(array(
            'I_model' => array(
                'recursive_parents' => $this->I_model->recursive_parents($is[0]['i__id']),
                'metadata_common_base' => $this->I_model->metadata_common_base($is[0]),
            ),
            'X_model' => array(
                'find_next' => $this->X_model->find_next($_GET['e__id'], $is[0], 0, false),
                'completion_progress' => $this->X_model->completion_progress($_GET['e__id'], $is[0]),
                'completion_marks' => $this->X_model->completion_marks($_GET['e__id'], $is[0]),
            ),
        ));

    }
}
