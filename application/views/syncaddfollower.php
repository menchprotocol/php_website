<?php

//Sync All Adding followers:
$counter = 0;
foreach ($this->Ledger->fetch(array(
    'linkplayertype' => 7545,
    'linkplayerup NOT IN (' . join(',', $this->config->item('playerids___43048')) . ')' => null, //No need to add these special ones... PlayerNickname
), array('linkplayerup'), 0) as $addition_sync) {

    $is_found = false;
    //Fetch everyone who has discovered this idea:
    foreach ($this->Ledger->fetch(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
        'linkidealeft' => $addition_sync['linkidearight'],
    ), array('linkplayercreator'), 0, 0, array('linkid' => 'DESC')) as $dicovered) {

        //Make sure no previous removed link between these two Players:
        if(!count($this->Ledger->fetch(array(
            'linkvoid >' => 0,
            'linkplayertype IN (' . join(',', $this->config->item('playerids___32292')) . ')' => null, //SOURCE LINKS
            'linkplayerup' => $addition_sync['linkplayerup'],
            'linkplayerdown' => $dicovered['linkplayercreator'],
        )))){
            //We would not recreate a removed link:
            continue;
        }

        //Any responses by this user?
        $set_linktext = $dicovered['linktext'];
        foreach($this->Ledger->fetch(array(
                    'linkplayertype' => 33532, //Private Reply
            'linkidealeft' => $addition_sync['linkidearight'],
            'linkplayercreator' => $dicovered['linkplayercreator'],
        ), array('linkidearight'), 0, 1, array('linkid' => 'DESC')) as $response){
            $set_linktext = $response['ideatext'];
        }

        //lets append this Player:
        if (append_player($addition_sync['linkplayerup'], $dicovered['linkplayercreator'], $set_linktext, $addition_sync['linkidearight'])) {
            $counter++;
        }
    }
}

echo $counter . ' Players synced.';