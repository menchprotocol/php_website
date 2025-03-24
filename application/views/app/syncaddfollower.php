<?php

//Sync All Adding followers:
$counter = 0;
foreach ($this->Mench_ledger->fetch(array(
    'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'LinkType' => 7545,
    'LinkUp NOT IN (' . join(',', $this->config->item('n___43048')) . ')' => null, //No need to add these special ones... SourceNickname
), array('LinkUp'), 0) as $addition_sync) {

    $is_found = false;
    //Fetch everyone who has discovered this idea:
    foreach ($this->Mench_ledger->fetch(array(
        'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'LinkType IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
        'LinkLeft' => $addition_sync['LinkRight'],
    ), array('LinkPlayer'), 0, 0, array('LinkId' => 'DESC')) as $dicovered) {

        //Make sure no previous removed link between these two sources:
        if(!count($this->Mench_ledger->fetch(array(
            'LinkType' => 10673, //Unlink
            'LinkUp' => $addition_sync['LinkUp'],
            'LinkDown' => $dicovered['LinkPlayer'],
        )))){
            //We would not recreate a removed link:
            continue;
        }

        //Any responses by this user?
        $set_LinkText = $dicovered['LinkText'];
        foreach($this->Mench_ledger->fetch(array(
            'LinkPrivacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            'LinkType' => 33532, //Share Idea
            'LinkLeft' => $addition_sync['LinkRight'],
            'LinkPlayer' => $dicovered['LinkPlayer'],
        ), array('LinkRight'), 0, 1, array('LinkId' => 'DESC')) as $response){
            $set_LinkText = $response['i__message'];
        }

        //lets append this source:
        if (append_source($addition_sync['LinkUp'], $dicovered['LinkPlayer'], $set_LinkText, $addition_sync['LinkRight'])) {
            $counter++;
        }
    }
}

echo $counter . ' Sources synced.';