<?php

//Sync All Adding followers:
$counter = 0;
foreach ($this->Chains->read(array(
    'chainhandletype' => 7545,
    'chainhandleinput NOT IN (' . join(',', $this->config->item('handleids___43048')) . ')' => null, //No need to add these special ones... HandleNickname
), array('chainhandleinput'), 0) as $addition_sync) {

    $is_found = false;
    //Fetch everyone who has hashtag_discovered this hashtag:
    foreach ($this->Chains->read(array(
        'chainhandletype IN (' . join(',', $this->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
        'chainhashtaginput' => $addition_sync['chainhashtagoutput'],
    ), array('chainhandlecreator'), 0, 0, array('chainid' => 'DESC')) as $dicovered) {

        //Any responses by this user?
        $set_chainvalue = $dicovered['chainvalue'];
        foreach ($this->Chains->read(array(
            'chainhandletype' => 4228, //Sequence
            'chainhashtagoutput' => $addition_sync['chainhashtagoutput'],
            'chainhandlecreator' => $dicovered['chainhandlecreator'],
        ), array('chainhashtaginput'), 0, 1, array('chainid' => 'DESC')) as $response) {
            $set_chainvalue = $response['hashtagtext'];
        }

        //lets append this Handle:
        if (append_handle($addition_sync['chainhandleinput'], $dicovered['chainhandlecreator'], $set_chainvalue, $addition_sync['chainhashtagoutput'], false)) {
            $counter++;
        }
    }
}

echo $counter . ' Handles synced.';