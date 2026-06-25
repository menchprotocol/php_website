<?php

//Generate list & settings:
$post_settings = post_settings($_GET['posthashtag'], true);
echo '<h1>' . view_post_title($post_settings['i']) . '</h1>';
echo count($post_settings['query_string_filtered']) . ' Contacts<br />';