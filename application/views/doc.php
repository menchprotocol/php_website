<?php

//Idea Tree:
echo '<div class="row justify-content">';
view_tree($this->Links->flat($focus_i));
echo '</div>';
