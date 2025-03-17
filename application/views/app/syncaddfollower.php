<?php

//Sync All Adding followers:
$counter = 0;
foreach ($this->Mench_ledger->fetch(array(
    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type' => 7545,
    'x__following NOT IN (' . join(',', $this->config->item('n___43048')) . ')' => null, //No need to add these special ones... SourceNickname
), array('x__following'), 0) as $addition_sync) {

    $is_found = false;
    //Fetch everyone who has discovered this idea:
    foreach ($this->Mench_ledger->fetch(array(
        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
        'x__previous' => $addition_sync['x__next'],
    ), array('x__player'), 0, 0, array('x__id' => 'DESC')) as $dicovered) {

        //Make sure no previous removed link between these two sources:
        if(!count($this->Mench_ledger->fetch(array(
            'x__type' => 10673, //Unlink
            'x__following' => $addition_sync['x__following'],
            'x__follower' => $dicovered['x__player'],
        )))){
            //We would not recreate a removed link:
            continue;
        }

        //Any responses by this user?
        $set_x__message = $dicovered['x__message'];
        foreach($this->Mench_ledger->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            'x__type' => 33532, //Share Idea
            'x__previous' => $addition_sync['x__next'],
            'x__player' => $dicovered['x__player'],
        ), array('x__next'), 0, 1, array('x__id' => 'DESC')) as $response){
            $set_x__message = $response['i__message'];
        }

        //lets append this source:
        if (append_source($addition_sync['x__following'], $dicovered['x__player'], $set_x__message, $addition_sync['x__next'])) {
            $counter++;
        }
    }
}

echo $counter . ' Sources synced.';