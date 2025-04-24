<?php

//Idea Tree:
echo '<div class="row justify-content">';
view_tree($this->Chains->flat($focus_i));
echo '</div>';
