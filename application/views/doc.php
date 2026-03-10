<?php

$user_session = user_session();

//Post Tree:
echo '<div class="row justify-content">';
echo 'Testing agents:';
view_tree($this->Chains->post_json($focus_post), true, $user_session);
echo '</div>';
