<?php

if(!isset($_GET['source__id']) || !intval($_GET['source__id'])){
    $_GET['source__id'] = $session_source['source__id'];
}

if(!isset($_GET['idea__id']) || !intval($_GET['idea__id'])) {

    //List this users Reads ideas so they can choose:
    echo '<div>Choose one of your Reads reads to debug:</div><br />';

    $player_reads = $this->READ_model->fetch(array(
        'read__source' => $_GET['source__id'],
        'read__type IN (' . join(',', $this->config->item('sources_id_12969')) . ')' => null, //Reads Idea Set
        'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
        'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
    ), array('read__left'), 0, 0, array('read__sort' => 'ASC'));

    foreach($player_reads as $priority => $read) {
        echo '<div>' . ($priority + 1) . ') <a href="?idea__id=' . $read['idea__id'] . '&source__id=' . $_GET['source__id'] . '">' . view_idea__title($read) . '</a></div>';
    }

} else {

    $ideas = $this->IDEA_model->fetch(array(
        'idea__id' => $_GET['idea__id'],
        'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
    ));

    if(count($ideas) < 1){

        echo 'Public Idea not found';

    } else {

        //List the idea:
        view_json(array(
            'idea_general' => array(
                'idea_recursive_parents' => $this->IDEA_model->recursive_parents($ideas[0]['idea__id']),
                'idea___common_base' => $this->IDEA_model->metadata_common_base($ideas[0]),
            ),
            'idea_user' => array(
                'read_find_next' => $this->READ_model->find_next($_GET['source__id'], $ideas[0], 0, false),
                'read_completion_progress' => $this->READ_model->completion_progress($_GET['source__id'], $ideas[0]),
                'read_completion_marks' => $this->READ_model->completion_marks($_GET['source__id'], $ideas[0]),
            ),
        ));

    }
}
