<?php

//Generate list & settings:
$post_settings = post_settings($_GET['posthashtag'], true);
echo '<h1>' . view_post_title($post_settings['i']) . '</h1>';
echo count($post_settings['query_string_filtered']) . ' Contacts<br />';
echo $post_settings['contact_details']['email_count'] . ' Emails<br />';
echo $post_settings['contact_details']['phone_count'] . ' Phones<br /><br />';

//Generate the contact list of the input post:
echo '<textarea class="mono-space subscriber_data" style="background-color: #FFFFFF; color:#000 !important; padding:3px; font-size:0.8em; height:218px; width: 100%; border-radius: 0px;">'.$post_settings['contact_details']['full_list'].'</textarea>';
echo '<textarea class="mono-space subscriber_data" style="background-color: #FFFFFF; color:#000 !important; padding:3px; font-size:0.8em; height:218px; width: 100%; border-radius: 0px;">'.$post_settings['contact_details']['email_list'].'</textarea>';