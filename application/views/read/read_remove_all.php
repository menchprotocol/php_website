<?php

$timestamp = time();

echo '<div class="landing-page-intro">';

echo '<h1 style="color:#FF0000; margin-bottom:30px;"><i class="fas fa-trash-alt"></i> Clear my 🔴 READING LIST</h1>';
echo '<p  style="color:#FF0000;"><b>WARNING:</b> You are about to remove all blogs from your reading list.</p>';
echo '<p  style="color:#FF0000;">Choose an option to continue:</p>';
echo '<p  style="margin-top:20px;"><a href="/read/actionplan_reset_progress/'.$session_en['en_id'].'/'.$timestamp.'/'.md5($session_en['en_id'] . $this->config->item('cred_password_salt') . $timestamp).'" class="btn btn-blog" style="background-color: #FF0000; color: #FFF;"><i class="fas fa-trash-alt"></i> Clear 🔴 READING LIST</a> or <a href="/read" class="btn btn-blog grey"><i class="fas fa-undo-alt"></i> Cancel & Go Back</a></p>';

echo '</div>';

?>