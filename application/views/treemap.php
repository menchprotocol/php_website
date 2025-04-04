<?php

//Focus Player:
echo '<div class="view_12274 row justify-content">';
echo view_player(42287, $focus_e, null);
echo '</div>';

//Idea Tree:
echo '<div class="row justify-content">';
view_tree($this->Links->historydiscovered($focus_i, $focus_e['playerid']));
echo '</div>';
