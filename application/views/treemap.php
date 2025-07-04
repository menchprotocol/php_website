<?php

//Focus Handle:
echo '<div class="view_12274 row justify-content">';
echo handle_view(42287, $focus_e, null);
echo '</div>';

//Hashtag Tree:
echo '<div class="row justify-content">';
view_tree($this->Chains->historyhashtag_discovered($focus_i, $focus_e['handleid']));
echo '</div>';
