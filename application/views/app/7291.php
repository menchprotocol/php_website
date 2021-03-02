<?php
//Destroys Session
session_delete();

echo '<div class="center-info">';
echo '<div class="text-center platform-large">'.get_domain('m__cover').'</div>';
echo '<p style="margin-top:13px; text-align: center;">'.view_shuffle_message(12694).'</p>';
echo '</div>';

js_redirect('/', 1597);

?>