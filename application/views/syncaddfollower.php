<?php

//Sync All Adding followers:
$counter = 0;
foreach ($this->Mench_ledger->fetch(array(
    'linkvoid' => 0, //Not Void
    'linktype' => 7545,
    'linkup NOT IN (' . join(',', $this->config->item('n___43048')) . ')' => null, //No need to add these special ones... SourceNickname
), array('linkup'), 0) as $addition_sync) {

    $is_found = false;
    //Fetch everyone who has discovered this idea:
    foreach ($this->Mench_ledger->fetch(array(
        'linkvoid' => 0, //Not Void
        'linktype IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
        'linkleft' => $addition_sync['linkright'],
    ), array('linkplayer'), 0, 0, array('linkid' => 'DESC')) as $dicovered) {

        //Make sure no previous removed link between these two sources:
        if(!count($this->Mench_ledger->fetch(array(
            'linkvoid >' => 0,
            'linktype IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'linkup' => $addition_sync['linkup'],
            'linkdown' => $dicovered['linkplayer'],
        )))){
            //We would not recreate a removed link:
            continue;
        }

        //Any responses by this user?
        $set_linktext = $dicovered['linktext'];
        foreach($this->Mench_ledger->fetch(array(
            'linkvoid' => 0, //Not Void
            'linktype' => 33532, //Private Reply
            'linkleft' => $addition_sync['linkright'],
            'linkplayer' => $dicovered['linkplayer'],
        ), array('linkright'), 0, 1, array('linkid' => 'DESC')) as $response){
            $set_linktext = $response['ideatext'];
        }

        //lets append this source:
        if (append_source($addition_sync['linkup'], $dicovered['linkplayer'], $set_linktext, $addition_sync['linkright'])) {
            $counter++;
        }
    }
}

echo $counter . ' Sources synced.';