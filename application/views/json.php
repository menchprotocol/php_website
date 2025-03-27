<?php

//view__json($this->Mench_ledger->tree_full_history($focus_i, $focus_e['e__id']));

foreach($this->Mench_ledger->fetch(array(
    'link_id >' => '0',
), array(), 10) as $x){
    echo $x['link_id']."<hr />";
}