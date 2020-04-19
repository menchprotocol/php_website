<?php

if(!isset($_GET['en_id']) || !intval($_GET['en_id'])){
    $_GET['en_id'] = $session_en['en_id'];
}

if(!isset($_GET['in_id']) || !intval($_GET['in_id'])) {

    //List this users 🔴 READING LIST ideas so they can choose:
    echo '<div>Choose one of your 🔴 READING LIST ideas to debug:</div><br />';

    $player_reads = $this->READ_model->ln_fetch(array(
        'ln_creator_source_id' => $_GET['en_id'],
        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //🔴 READING LIST Idea Set
        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
        'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
    ), array('in_parent'), 0, 0, array('ln_order' => 'ASC'));

    foreach ($player_reads as $priority => $ln) {
        echo '<div>' . ($priority + 1) . ') <a href="?in_id=' . $ln['in_id'] . '&en_id=' . $_GET['en_id'] . '">' . echo_in_title($ln) . '</a></div>';
    }

} else {

    $ins = $this->IDEA_model->in_fetch(array(
        'in_id' => $_GET['in_id'],
        'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
    ));

    if(count($ins) < 1){

        echo 'ERROR: Public Idea not found';

    } else {

        //List the idea:
        echo_json(array(
            'in_user' => array(
                'next_in_id' => $this->READ_model->read_next_find($_GET['en_id'], $ins[0]),
                'progress' => $this->READ_model->read__completion_progress($_GET['en_id'], $ins[0]),
                'marks' => $this->READ_model->read__completion_marks($_GET['en_id'], $ins[0]),
            ),
            'in_general' => array(
                'recursive_parents' => $this->IDEA_model->in_fetch_recursive_parents($ins[0]['in_id']),
                'common_base' => $this->IDEA_model->in_metadata_common_base($ins[0]),
            ),
        ));

    }
}
