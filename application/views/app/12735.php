<?php

$stats = array(
    'ideas' => 0,
    'e_missing' => 0,
    'note_deleted' => 0,
    'note_ref_deleted' => 0,
    'is_deleted' => 0,
    'creator_missing' => 0,
    'creator_extra' => 0,
    'creator_fixed' => 0,
    'e_duplicate' => 0,
);

//FInd and delete duplicate sources:
foreach($this->I_model->fetch() as $in) {

    $stats['ideas']++;

    $is_deleted = !in_array($in['i__type'], $this->config->item('n___7356'));

    //Scan sources:
    $i_e = $this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
        'x__right' => $in['i__id'],
        'x__up >' => 0, //MESSAGES MUST HAVE A SOURCE REFERENCE TO ISSUE IDEA COINS
    ));

    $i_creators = $this->X_model->fetch(array(
        'x__type' => 4250, //New Idea Created
        'x__right' => $in['i__id'],
    ), array(), 0, 0, array('x__id' => 'ASC')); //Order in case we have extra & need to remove

    if(!count($i_creators)) {
        $stats['creator_missing']++;
        $this->X_model->create(array(
            'x__source' => $member_e['e__id'],
            'x__right' => $in['i__id'],
            'x__message' => $in['i__title'],
            'x__type' => 4250, //New Idea Created
        ));
    }


    $all_messages = array();
    foreach($this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type' => 4231, //IDEA NOTES Messages
        'x__right' => $in['i__id'],
    ), array(), 0, 0, array('x__spectrum' => 'ASC')) as $msg){
        array_push($all_messages, $msg['x__reference']);
    }
    $delete_filters = array(
        'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        'x__type' => 14947, //Note Extra Sources
        'x__right' => $in['i__id'],
    );
    if(count($all_messages)){
        $delete_filters['x__reference NOT IN (' . join(',', $all_messages) . ')'] = null;
    }
    foreach($this->X_model->fetch($delete_filters, array(), 0) as $delete_x) {
        $stats['note_ref_deleted']++;
        $this->X_model->update($delete_x['x__id'], array(
            'x__status' => 6173,
        ));
    }


    if(!$is_deleted && !count($i_e)){

        //Missing SOURCE
        $stats['e_missing']++;
        $creator_id = ( count($i_e) ? $i_e[0]['x__source'] : $member_e['x__up'] );
        $this->X_model->create(array(
            'x__type' => 4983, //IDEA SOURCES
            'x__source' => $creator_id,
            'x__up' => $creator_id,
            'x__right' => $in['i__id'],
        ));

    }
}

echo nl2br(print_r($stats, true));