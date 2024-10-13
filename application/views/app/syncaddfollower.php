<?php

//Sync All Adding followers:
$counter = 0;
foreach ($this->Ledger->read(array(
    'x__type' => 7545,
    'x__following NOT IN (' . njoin(43048) . ')' => null, //No need to add these special ones... SourceNickname
), array('x__following'), 0) as $addition_sync) {

    $is_found = false;
    //Fetch everyone who has discovered this idea:
    foreach ($this->Ledger->read(array(
            'x__type IN (' . njoin(6255) . ')' => null, //DISCOVERIES
        'x__previous' => $addition_sync['x__next'],
    ), array('x__player'), 0, 0, array('x__id' => 'DESC')) as $dicovered) {

        //Any responses by this user?
        $set_x__message = $dicovered['x__message'];
        foreach($this->Ledger->read(array(
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

echo $counter . ' sources synced.';