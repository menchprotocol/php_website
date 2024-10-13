<?php

$deleted_i = 0;
$deleted_links = 0;

if ($player_e) {
    foreach ($this->Ideas->read(array(
        'i__privacy NOT IN (' . njoin(31871) . ')' => null, //ACTIVE
    )) as $deleted_i) {
        $deleted_i++;
        $deleted_links += $this->Ideas->remove($deleted_i['i__id'], $player_e['e__id'], 0);
    }
}


echo 'Deleted ' . $deleted_links . ' Links from ' . $deleted_i . ' deleted nodes';