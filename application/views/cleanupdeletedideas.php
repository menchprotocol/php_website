<?php

die('Needs to be redone ro read VOID ideas from ledger and remove from cache');

$deleted_i = 0;
$links_removed = 0;

if($player_e){
    foreach($this->Idea_cache->fetch(array(
    )) as $deleted_i){
        $deleted_i++;
        $links_removed += $this->Idea_cache->remove($deleted_i['ideaid'], $player_e['playerid']);
    }
}


echo 'Deleted '.$links_removed.' Links from '.$deleted_i.' deleted nodes';