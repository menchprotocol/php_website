<?php

$timestamp = time();

echo '<div class="landing-page-intro">';

echo '<h1 style="margin-bottom:30px;" id="title-parent"><i class="fas fa-radiation-alt"></i> Clear Action Plan</h1>';
echo '<p style="color:#FF0000;"><b>WARNING:</b> You are about to irreversibly delete your ENTIRE Action Plan data.</p>';
echo '<p style="color:#FF0000;">Choose an option to continue:</p>';
echo '<p style="margin-top:20px;"><a href="/user_app/actionplan_reset_progress/'.$session_en['en_id'].'/'.$timestamp.'/'.md5($session_en['en_id'] . $this->config->item('actionplan_salt') . $timestamp).'" class="btn btn-primary" style="background-color: #FF0000; color: #FFF;"><i class="fas fa-radiation-alt"></i> Delete Permanently</a> or <a href="/actionplan" class="btn btn-primary grey"><i class="fas fa-undo-alt"></i> Cancel & Go Back</a></p>';

echo '</div>';

?>