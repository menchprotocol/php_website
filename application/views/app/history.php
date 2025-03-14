<?php

echo '<h3><a href="/@' . $focus_e['e__handle'] . '">' . $focus_e['e__title'] . '</a> History of <a href="/' . $focus_i['i__hashtag'] . '">' . view__i_title($focus_i) . '</a></h3>';

//Display idea info:
$recursive_down_ids = $this->Mench_ledger->tree_history($focus_i, $focus_e['e__id']);

//Main Idea:

echo '<div class="row justify-content">';
foreach($recursive_down_ids['recursive_i_ids'] as $recursive_down_id){
    foreach($this->Idea_cache->fetch(array(
        'i__id' => $recursive_down_id,
    ), 0) as $this_i){
        echo view__card_i(12273, $this_i);
    }
}
echo '</div>';