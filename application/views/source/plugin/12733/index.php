<?php

if(!isset($_GET['en_id']) || !intval($_GET['en_id'])){
    $_GET['en_id'] = $session_en['en_id'];
}

if(!isset($_GET['in_id']) || !intval($_GET['in_id'])) {

    //List this users DISCOVER LIST ideas so they can choose:
    echo '<div>Choose one of your DISCOVER LIST ideas to debug:</div><br />';

    $player_discoveries = $this->LEDGER_model->ln_fetch(array(
        'ln_creator_source_id' => $_GET['en_id'],
        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //DISCOVER LIST Idea Set
        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
        'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
    ), array('in_previous'), 0, 0, array('ln_order' => 'ASC'));

    foreach ($player_discoveries as $priority => $ln) {
        echo '<div>' . ($priority + 1) . ') <a href="?in_id=' . $ln['in_id'] . '&en_id=' . $_GET['en_id'] . '">' . echo_in_title($ln) . '</a></div>';
    }

} else {

    $ins = $this->IDEA_model->in_fetch(array(
        'in_id' => $_GET['in_id'],
        'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
    ));

    if(count($ins) < 1){

        echo 'Public Idea not found';

    } else {

        //List the idea:
        echo_json(array(
            'in_general' => array(
                'in_recursive_parents' => $this->IDEA_model->in_recursive_parents($ins[0]['in_id']),
                'in_metadata_common_base' => $this->IDEA_model->in_metadata_common_base($ins[0]),
            ),
            'in_user' => array(
                'discover_next_find' => $this->DISCOVER_model->discover_next_find($_GET['en_id'], $ins[0]),
                'discover_completion_progress' => $this->DISCOVER_model->discover_completion_progress($_GET['en_id'], $ins[0]),
                'discover_completion_marks' => $this->DISCOVER_model->discover_completion_marks($_GET['en_id'], $ins[0]),
            ),
        ));

    }
}
