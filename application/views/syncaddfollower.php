<?php

//Sync All Adding followers:
$counter = 0;
foreach ($this->Chains->read(array(
    'chainusertype' => 7545,
    'chainuserinput NOT IN (' . join(',', $this->config->item('userids___43048')) . ')' => null, //No need to add these special ones... UserNickname
), array('chainuserinput'), 0) as $addition_sync) {

    $is_found = false;
    //Fetch everyone who has post discovered this post:
    foreach ($this->Chains->read(array(
        'chainusertype IN (' . join(',', $this->config->item('userids___31777')) . ')' => null, //DISCOVERIES
        'chainpostinput' => $addition_sync['chainpostoutput'],
    ), array('chainusercreator'), 0, 0, array('chainid' => 'DESC')) as $dicovered) {

        //Any responses by this user?
        $set_chainvalue = $dicovered['chainvalue'];
        foreach ($this->Chains->read(array(
            'chainusertype' => 4228, //Sequence
            'chainpostoutput' => $addition_sync['chainpostoutput'],
            'chainusercreator' => $dicovered['chainusercreator'],
        ), array('chainpostinput'), 0, 1, array('chainid' => 'DESC')) as $response) {
            $set_chainvalue = $response['posttext'];
        }

        //lets append this User:
        if (append_user($addition_sync['chainuserinput'], $dicovered['chainusercreator'], $set_chainvalue, $addition_sync['chainpostoutput'], false)) {
            $counter++;
        }
    }
}

echo $counter . ' Users synced.';