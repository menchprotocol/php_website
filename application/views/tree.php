<?php

//Focus Handle:
echo '<div class="view_12274 row justify-content">';
echo handle_view(42287, $focus_e, null);
echo '</div>';

$_GET['view_all'] = true;

//Hashtag Tree:
echo '<div class="row justify-content">';
view_tree($this->Chains->history_discovered($focus_i, $focus_e['handleid']), true, $focus_e);
echo '</div>';