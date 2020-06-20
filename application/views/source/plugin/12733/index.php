<?php

if(!isset($_GET['e__id']) || !intval($_GET['e__id'])){
    $_GET['e__id'] = $session_source['e__id'];
}

if(!isset($_GET['i__id']) || !intval($_GET['i__id'])) {

    //List this users Discoveries so they can choose:
    echo '<div>Choose one of your Discoveries to debug:</div><br />';

    $player_reads = $this->DISCOVER_model->fetch(array(
        'x__player' => $_GET['e__id'],
        'x__type IN (' . join(',', $this->config->item('sources_id_12969')) . ')' => null, //MY DISCOVERIES
        'x__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
        'i__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
    ), array('x__left'), 0, 0, array('x__sort' => 'ASC'));

    foreach($player_reads as $priority => $read) {
        echo '<div>' . ($priority + 1) . ') <a href="?i__id=' . $read['i__id'] . '&e__id=' . $_GET['e__id'] . '">' . view_i__title($read) . '</a></div>';
    }

} else {

    $ideas = $this->MAP_model->fetch(array(
        'i__id' => $_GET['i__id'],
        'i__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
    ));

    if(count($ideas) < 1){

        echo 'Public Idea not found';

    } else {

        //List the idea:
        view_json(array(
            'idea_general' => array(
                'idea_recursive_parents' => $this->MAP_model->recursive_parents($ideas[0]['i__id']),
                'i___common_base' => $this->MAP_model->metadata_common_base($ideas[0]),
            ),
            'idea_user' => array(
                'read_find_next' => $this->DISCOVER_model->find_next($_GET['e__id'], $ideas[0], 0, false),
                'read_completion_progress' => $this->DISCOVER_model->completion_progress($_GET['e__id'], $ideas[0]),
                'read_completion_marks' => $this->DISCOVER_model->completion_marks($_GET['e__id'], $ideas[0]),
            ),
        ));

    }
}
