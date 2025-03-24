<?php

//Focus Source:
echo '<div class="view__12274 row justify-content">';
echo view__card_e(42287, $focus_e, null);
echo '</div>';

//Idea Tree:
echo '<div class="row justify-content">';
view_tree($this->Mench_ledger->tree_discovered_history($focus_i, $focus_e['e__id']));
echo '</div>';
