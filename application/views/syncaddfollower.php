<?php

//Sync All Adding followers:
$counter = 0;
foreach ($this->Menchledger->fetch(array(
    'linktype' => 7545,
    'linkup NOT IN (' . join(',', $this->config->item('playerids___43048')) . ')' => null, //No need to add these special ones... PlayerNickname
), array('linkup'), 0) as $addition_sync) {

    $is_found = false;
    //Fetch everyone who has discovered this idea:
    foreach ($this->Menchledger->fetch(array(
            'linktype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
        'linkleft' => $addition_sync['linkright'],
    ), array('linkcreator'), 0, 0, array('linkid' => 'DESC')) as $dicovered) {

        //Make sure no previous removed link between these two Players:
        if(!count($this->Menchledger->fetch(array(
            'linkvoid >' => 0,
            'linktype IN (' . join(',', $this->config->item('playerids___32292')) . ')' => null, //SOURCE LINKS
            'linkup' => $addition_sync['linkup'],
            'linkdown' => $dicovered['linkcreator'],
        )))){
            //We would not recreate a removed link:
            continue;
        }

        //Any responses by this user?
        $set_linktext = $dicovered['linktext'];
        foreach($this->Menchledger->fetch(array(
                    'linktype' => 33532, //Private Reply
            'linkleft' => $addition_sync['linkright'],
            'linkcreator' => $dicovered['linkcreator'],
        ), array('linkright'), 0, 1, array('linkid' => 'DESC')) as $response){
            $set_linktext = $response['ideatext'];
        }

        //lets append this Player:
        if (append_player($addition_sync['linkup'], $dicovered['linkcreator'], $set_linktext, $addition_sync['linkright'])) {
            $counter++;
        }
    }
}

echo $counter . ' Players synced.';