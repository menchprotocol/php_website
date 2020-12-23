<?php
//Destroys Session
session_delete();

echo '<div class="text-center"><img src="/img/mench.png" class="mench-spin mench-large" /></div>';
echo '<p style="margin-top:13px; text-align: center;">'.view_shuffle_message(12694).'</p>';
js_redirect('/', 1597);

?>