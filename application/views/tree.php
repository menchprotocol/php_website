<?php

//Focus Source:
echo '<div class="view_12274 row justify-content">';
echo source_view(42287, $focus_e, null);
echo '</div>';

$_GET['view_all'] = true;

//Idea Tree:
echo '<div class="row justify-content">';
view_tree($this->Chains->historyidea_discovered($focus_i, $focus_e['sourceid']), true, $focus_e);
echo '</div>';
