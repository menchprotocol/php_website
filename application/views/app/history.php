<?php

echo '<h3><a href="/@' . $focus_e['e__handle'] . '"><u>@' . $focus_e['e__handle'] . ' ' . $focus_e['e__title'] . '</u></a> History:</h3>';

//Display idea info:
$recursive_down_ids = $this->Mench_ledger->tree_history($focus_i, $focus_e['e__id']);


//Main Idea:
echo '<div class="row justify-content">';
view_tree($recursive_down_ids);
echo '</div>';
