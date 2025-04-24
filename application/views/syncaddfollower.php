<?php

//Sync All Adding followers:
$counter = 0;
foreach ($this->Chains->read(array(
    'chainplayertype' => 7545,
    'chainplayerup NOT IN (' . join(',', $this->config->item('playerids___43048')) . ')' => null, //No need to add these special ones... PlayerNickname
), array('chainplayerup'), 0) as $addition_sync) {

    $is_found = false;
    //Fetch everyone who has idea_discovered this idea:
    foreach ($this->Chains->read(array(
        'chainplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
        'chainidealeft' => $addition_sync['chainidearight'],
    ), array('chainplayercreator'), 0, 0, array('chainid' => 'DESC')) as $dicovered) {

        //Any responses by this user?
        $set_chaintext = $dicovered['chaintext'];
        foreach ($this->Chains->read(array(
            'chainplayertype' => 33532, //Private Reply
            'chainidealeft' => $addition_sync['chainidearight'],
            'chainplayercreator' => $dicovered['chainplayercreator'],
        ), array('chainidearight'), 0, 1, array('chainid' => 'DESC')) as $response) {
            $set_chaintext = $response['ideatext'];
        }

        //lets append this Player:
        if (append_player($addition_sync['chainplayerup'], $dicovered['chainplayercreator'], $set_chaintext, $addition_sync['chainidearight'], false)) {
            $counter++;
        }
    }
}

echo $counter . ' Players synced.';