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
        'ln_profile_source_id' => 4430, //MENCH PLAYERS
        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
        'ln_portfolio_source_id' => $en['en_id'],
        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
    ), array(), 1));
    $is_ledger = count($this->READ_model->fetch(array(
        'ln_creator_source_id' => $en['en_id'],
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
            'ln_type_source_id' => en_link_type_id(),
            'ln_profile_source_id' => 4430, //MENCH PLAYERS
            'ln_creator_source_id' => $en['en_id'],
            'ln_portfolio_source_id' => $en['en_id'],
        ));
    }

}

echo nl2br(print_r($stats, true));