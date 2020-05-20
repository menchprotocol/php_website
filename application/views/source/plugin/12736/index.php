<?php

$stats = array(
    'source' => 0,
    'player' => 0,
    'read' => 0,
    'read_not_source_count' => 0,
    'source_not_read_count' => 0,
    'source_not_read_home' => array(),
);

foreach($this->SOURCE_model->fetch() as $en) {

    $stats['source']++;

    $is_player = count($this->READ_model->fetch(array(
        'read__up' => 4430, //MENCH PLAYERS
        'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
        'read__down' => $en['source__id'],
        'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
    ), array(), 1));
    $is_read = count($this->READ_model->fetch(array(
        'read__source' => $en['source__id'],
    ), array(), 1));

    if($is_player){
        $stats['player']++;
    }
    if($is_read){
        $stats['read']++;
    }
    if($is_player && !$is_read){
        $stats['source_not_read_count']++;
        array_push($stats['source_not_read_home'], $en);
    }
    if($is_read && !$is_player){
        $stats['read_not_source_count']++;
        $this->READ_model->create(array(
            'read__type' => en_link_type_id(),
            'read__up' => 4430, //MENCH PLAYERS
            'read__source' => $en['source__id'],
            'read__down' => $en['source__id'],
        ));
    }

}

echo nl2br(print_r($stats, true));