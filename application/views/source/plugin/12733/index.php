<?php

if(!isset($_GET['source__id']) || !intval($_GET['source__id'])){
    $_GET['source__id'] = $session_en['source__id'];
}

if(!isset($_GET['idea__id']) || !intval($_GET['idea__id'])) {

    //List this users Reads ideas so they can choose:
    echo '<div>Choose one of your Reads reads to debug:</div><br />';

    $player_reads = $this->READ_model->fetch(array(
        'read__source' => $_GET['source__id'],
        'read__type IN (' . join(',', $this->config->item('sources_id_12969')) . ')' => null, //Reads Idea Set
        'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
        'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
    ), array('idea_previous'), 0, 0, array('read__sort' => 'ASC'));

    foreach($player_reads as $priority => $ln) {
        echo '<div>' . ($priority + 1) . ') <a href="?idea__id=' . $ln['idea__id'] . '&source__id=' . $_GET['source__id'] . '">' . view_idea__title($ln) . '</a></div>';
    }

} else {

    $ins = $this->IDEA_model->fetch(array(
        'idea__id' => $_GET['idea__id'],
        'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
    ));

    if(count($ins) < 1){

        echo 'Public Idea not found';

    } else {

        //List the idea:
        view_json(array(
            'in_general' => array(
                'in_recursive_parents' => $this->IDEA_model->recursive_parents($ins[0]['idea__id']),
                'idea__metadata_common_base' => $this->IDEA_model->metadata_common_base($ins[0]),
            ),
            'in_user' => array(
                'read_find_next' => $this->READ_model->find_next($_GET['source__id'], $ins[0]),
                'read_completion_progress' => $this->READ_model->completion_progress($_GET['source__id'], $ins[0]),
                'read_completion_marks' => $this->READ_model->completion_marks($_GET['source__id'], $ins[0]),
            ),
        ));

    }
}
