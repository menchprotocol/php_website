<?php

$stats = array(
    'source' => 0,
    'player' => 0,
    'ledger' => 0,
    'ledger_not_source_count' => 0,
    'source_not_ledger_count' => 0,
    'source_not_ledger_home' => array(),
);

foreach($this->SOURCE_model->fetch() as $en) {

    $stats['source']++;

    $is_player = count($this->READ_model->fetch(array(
        'read__up' => 4430, //MENCH PLAYERS
        'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
        'read__down' => $en['source__id'],
        'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
    ), array(), 1));
    $is_ledger = count($this->READ_model->fetch(array(
        'read__source' => $en['source__id'],
    ), array(), 1));

    if($is_player){
        $stats['player']++;
    }
    if($is_ledger){
        $stats['ledger']++;
    }
    if($is_player && !$is_ledger){
        $stats['source_not_ledger_count']++;
        array_push($stats['source_not_ledger_home'], $en);
    }
    if($is_ledger && !$is_player){
        $stats['ledger_not_source_count']++;
        $this->READ_model->create(array(
            'read__type' => en_link_type_id(),
            'read__up' => 4430, //MENCH PLAYERS
            'read__source' => $en['source__id'],
            'read__down' => $en['source__id'],
        ));
    }

}

echo nl2br(print_r($stats, true));