<?php

echo '<h3><a href="/@' . $focus_e['e__handle'] . '">' . $focus_e['e__title'] . '</a> History of <a href="/' . $focus_i['i__hashtag'] . '">' . view__i_title($focus_i) . '</a></h3>';

//Display idea info:
$recursive_down_ids = $this->Mench_ledger->tree_history($focus_i, $focus_e['e__id']);


//Main Idea:
echo '<div class="row justify-content">';
view_tree($recursive_down_ids);
echo '</div>';



//print_r($recursive_down_ids);
