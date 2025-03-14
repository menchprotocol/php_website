<?php

echo '<h3><a href="/@' . $focus_e['e__handle'] . '">' . $focus_e['e__title'] . '</a> History of <a href="/' . $focus_i['i__hashtag'] . '">' . view__i_title($focus_i) . '</a></h3>';

//Display idea info:
$recursive_down_ids = $this->Mench_ledger->tree_history($focus_i, $focus_e['e__id']);

print_r($recursive_down_ids);

//Main Idea:
echo '<div class="row justify-content">';
foreach($this->Mench_ledger->tree_history($focus_i, $focus_e['e__id']) as $next_i){
    echo '<div style="padding-left:'.(isset($next_i['i__level']) ? ((intval($next_i['i__level'])-1)*5) : '0' ).'px;">'.view__i_title($next_i).( isset($next_i['x__message']) ? ' ['.$next_i['x__message'].']' : '' ).'</div>';
}
echo '</div>';