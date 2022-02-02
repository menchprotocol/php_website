<?php

if(!isset($_GET['e__id']) || !intval($_GET['e__id'])){
    $_GET['e__id'] = $member_e['e__id'];
}

if(!isset($_GET['i__id']) || !intval($_GET['i__id'])) {

    //List this members discoveries so they can choose:
    echo '<div>Choose one of your discoveries to debug:</div><br />';

    $e_x = $this->X_model->fetch(array(
        'x__source' => $_GET['e__id'],
        'x__type IN (' . join(',', $this->config->item('n___12969')) . ')' => null, //STARTED
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PRIVATE
        'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PRIVATE
    ), array('x__left'), 0, 0, array('x__spectrum' => 'ASC'));

    foreach($e_x as $priority => $x) {
        echo '<div>' . ($priority + 1) . ') <a href="?i__id=' . $x['i__id'] . '&e__id=' . $_GET['e__id'] . '">' . view_i_title($x) . '</a></div>';
    }

} else {

    $is = $this->I_model->fetch(array(
        'i__id' => $_GET['i__id'],
        'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PRIVATE
    ));

    if(count($is) < 1){

        echo 'Public Idea not found';

    } else {

        //List the idea:
        view_json(array(
            'I_model' => array(
                'metadata_common_base' => $this->I_model->metadata_common_base($is[0]),
            ),
            'X_model' => array(
                'find_next' => $this->X_model->find_next($_GET['e__id'], $is[0]['i__id'], $is[0], 0, false),
                'completion_progress' => $this->X_model->completion_progress($_GET['e__id'], $is[0]),
                'completion_marks' => $this->X_model->completion_marks($_GET['e__id'], $is[0]),
            ),
        ));

    }
}
