<?php

//view_json($this->Menchledger->tree_full_history($focus_i, $focus_e['playerid']));



$count = 0;
$missing = 0;
foreach ($this->Menchledger->fetchold(array(
    'link_type' => 4983,
)) as $x) {
    $count++;
    if(!count($this->Menchledger->fetch(array(
        'linktype' => 4983,
        'linkplayerup' => $x['link_up'],
        'linkplayerdown' => $x['link_down'],
        'linkidealeft' => ( $x['link_left']>0 ? intval($x['link_left'])+100000 : 0 ),
        'linkidearight' => ( $x['link_right']>0 ? intval($x['link_right'])+100000 : 0 ),
    )))){
        $missing++;
        echo print_r($x, true);
        echo '<hr />';
    }
}

echo $missing.'/'.$count.' Missing';