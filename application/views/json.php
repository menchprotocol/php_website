<?php

//view__json($this->Mench_ledger->tree_full_history($focus_i, $focus_e['e__id']));


$count = 0;
foreach($this->Idea_cache->fetch(array()) as $i){
    $count++;
}

echo $count.' ideas';