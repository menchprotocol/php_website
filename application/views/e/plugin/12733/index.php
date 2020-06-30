<?php

if(!isset($_GET['e__id']) || !intval($_GET['e__id'])){
    $_GET['e__id'] = $session_source['e__id'];
}

if(!isset($_GET['i__id']) || !intval($_GET['i__id'])) {

    //List this users Discoveries so they can choose:
    echo '<div>Choose one of your Discoveries to debug:</div><br />';

    $player_discoveries = $this->X_model->fetch(array(
        'x__player' => $_GET['e__id'],
        'x__type IN (' . join(',', $this->config->item('sources_id_12969')) . ')' => null, //MY DISCOVERIES
        'x__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
        'i__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
    ), array('x__left'), 0, 0, array('x__sort' => 'ASC'));

    foreach($player_discoveries as $priority => $discovery) {
        echo '<div>' . ($priority + 1) . ') <a href="?i__id=' . $discovery['i__id'] . '&e__id=' . $_GET['e__id'] . '">' . view_i_title($discovery) . '</a></div>';
    }

} else {

    $ideas = $this->I_model->fetch(array(
        'i__id' => $_GET['i__id'],
        'i__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
    ));

    if(count($ideas) < 1){

        echo 'Public Idea not found';

    } else {

        //List the idea:
        view_json(array(
            'i_general' => array(
                'i_recursive_parents' => $this->I_model->recursive_parents($ideas[0]['i__id']),
                'i___common_base' => $this->I_model->metadata_common_base($ideas[0]),
            ),
            'i_user' => array(
                'discover_find_next' => $this->X_model->find_next($_GET['e__id'], $ideas[0], 0, false),
                'discover_completion_progress' => $this->X_model->completion_progress($_GET['e__id'], $ideas[0]),
                'discover_completion_marks' => $this->X_model->completion_marks($_GET['e__id'], $ideas[0]),
            ),
        ));

    }
}
