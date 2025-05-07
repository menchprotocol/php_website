<?php

//Sync All Adding followers:
$counter = 0;
foreach ($this->Chains->read(array(
    'chainsourcetype' => 7545,
    'chainsourceup NOT IN (' . join(',', $this->config->item('sourceids___43048')) . ')' => null, //No need to add these special ones... SourceNickname
), array('chainsourceup'), 0) as $addition_sync) {

    $is_found = false;
    //Fetch everyone who has idea_discovered this idea:
    foreach ($this->Chains->read(array(
        'chainsourcetype IN (' . join(',', $this->config->item('sourceids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
        'chainidealeft' => $addition_sync['chainidearight'],
    ), array('chainsourcecreator'), 0, 0, array('chainid' => 'DESC')) as $dicovered) {

        //Any responses by this user?
        $set_chainvalue = $dicovered['chainvalue'];
        foreach ($this->Chains->read(array(
            'chainsourcetype' => 33532, //Private Reply
            'chainidealeft' => $addition_sync['chainidearight'],
            'chainsourcecreator' => $dicovered['chainsourcecreator'],
        ), array('chainidearight'), 0, 1, array('chainid' => 'DESC')) as $response) {
            $set_chainvalue = $response['ideavalue'];
        }

        //lets append this Source:
        if (append_source($addition_sync['chainsourceup'], $dicovered['chainsourcecreator'], $set_chainvalue, $addition_sync['chainidearight'], false)) {
            $counter++;
        }
    }
}

echo $counter . ' Sources synced.';