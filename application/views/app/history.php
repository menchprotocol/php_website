<?php

//Focus Source:
echo '<div class="view__12274 row justify-content">';
echo view__card_e(42287, $focus_e, null);
echo '</div>';


//Display idea info:
$recursive_down_ids = $this->Mench_ledger->tree_history($focus_i, $focus_e['e__id']);


//Main Idea:
echo '<div class="row justify-content">';
view_tree($recursive_down_ids);
echo '</div>';
