<?php

//Idea Tree:
echo '<div class="row justify-content">';
view_tree($this->Ledger->tree_doc($focus_i));
echo '</div>';
