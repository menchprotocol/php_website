<?php

//view_json($this->Menchledger->tree_full_history($focus_i, $focus_e['playerid']));



$count = 0;
foreach ($this->Nodeideas->fetch(array()) as $i) {
    $count++;
    //$content_message = view_idea_links($i, $x['playerid'], true); //Hide the show more content if any
}

echo $count;