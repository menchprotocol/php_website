<?php

$handle_session = handle_session();

//Hashtag Tree:
echo '<div class="row justify-content">';
view_tree($this->Chains->flat_tree($focus_i), true, $handle_session);
echo '</div>';
