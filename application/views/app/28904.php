<?php

//Notification Settings
echo '<div style="max-width: 540px; margin: 0 auto;">'.view_radio_e(28904, ( isset($_GET['e__id']) && isset($_GET['e__hash']) && md5($_GET['e__id'].$this->config->item('cred_password_salt'))==$_GET['e__hash'] ? $_GET['e__id'] : $member_e['e__id'] ), 0).'</div>';
