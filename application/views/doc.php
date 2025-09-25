<?php

$user_session = user_session();

//Post Tree:
echo '<div class="row justify-content">';
view_tree($this->Ideachains->flat_tree($focus_i), true, $user_session);
echo '</div>';
