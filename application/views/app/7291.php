<?php
//Destroys Session
session_delete();

echo '<div class="center-info">';
echo '<div class="text-center"><img src="/img/logos/'.get_domain_setting(0).'.svg" class="fa-spin-slow platform-large" /></div>';
echo '<p style="margin-top:13px; text-align: center;">'.view_shuffle_message(12694).'</p>';
echo '</div>';

js_redirect('/', 1597);

?>