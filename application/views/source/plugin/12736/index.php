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

    $is_player = count($this->DISCOVER_model->fetch(array(
        'x__up' => 4430, //MENCH PLAYERS
        'x__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
        'x__down' => $en['e__id'],
        'x__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
    ), array(), 1));
    $is_read = count($this->DISCOVER_model->fetch(array(
        'x__player' => $en['e__id'],
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
        $this->DISCOVER_model->create(array(
            'x__type' => source_link_type(),
            'x__up' => 4430, //MENCH PLAYERS
            'x__player' => $en['e__id'],
            'x__down' => $en['e__id'],
        ));
    }

}

echo nl2br(print_r($stats, true));