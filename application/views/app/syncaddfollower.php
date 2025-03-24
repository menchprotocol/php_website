<?php

//Sync All Adding followers:
$counter = 0;
foreach ($this->Mench_ledger->fetch(array(
    'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'link_type' => 7545,
    'link_up NOT IN (' . join(',', $this->config->item('n___43048')) . ')' => null, //No need to add these special ones... SourceNickname
), array('link_up'), 0) as $addition_sync) {

    $is_found = false;
    //Fetch everyone who has discovered this idea:
    foreach ($this->Mench_ledger->fetch(array(
        'link_privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'link_type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
        'link_left' => $addition_sync['link_right'],
    ), array('link_player'), 0, 0, array('link_id' => 'DESC')) as $dicovered) {

        //Make sure no previous removed link between these two sources:
        if(!count($this->Mench_ledger->fetch(array(
            'link_type' => 10673, //Unlink
            'link_up' => $addition_sync['link_up'],
            'link_down' => $dicovered['link_player'],
        )))){
            //We would not recreate a removed link:
            continue;
        }

        //Any responses by this user?
        $set_link_text = $dicovered['link_text'];
        foreach($this->Mench_ledger->fetch(array(
            'link_privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            'link_type' => 33532, //Share Idea
            'link_left' => $addition_sync['link_right'],
            'link_player' => $dicovered['link_player'],
        ), array('link_right'), 0, 1, array('link_id' => 'DESC')) as $response){
            $set_link_text = $response['i__message'];
        }

        //lets append this source:
        if (append_source($addition_sync['link_up'], $dicovered['link_player'], $set_link_text, $addition_sync['link_right'])) {
            $counter++;
        }
    }
}

echo $counter . ' Sources synced.';