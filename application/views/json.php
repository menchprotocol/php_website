<?php

//view_json($this->Menchledger->tree_full_history($focus_i, $focus_e['playerid']));



$count = 0;
foreach ($this->Nodeideas->fetch(array()) as $i) {
    $count++;
    view_sync_links($i['ideatext'], true, $i['ideaid']);
}

echo $count;