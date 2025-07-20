<?php

//Generate list & settings:
$hashtag_settings = hashtag_settings($_GET['hashtagstring'], true);
echo '<h1>' . view_hashtag_title($hashtag_settings['i']) . '</h1>';
echo count($hashtag_settings['query_string_filtered']) . ' Contacts<br />';
echo $hashtag_settings['contact_details']['email_count'] . ' Emails<br />';
echo $hashtag_settings['contact_details']['phone_count'] . ' Phones<br /><br />';

//Generate the contact list of the input hashtag:
echo '<textarea class="mono-space subscriber_data" style="background-color: #FFFFFF; color:#000 !important; padding:3px; font-size:0.8em; height:218px; width: 100%; border-radius: 0px;">'.$hashtag_settings['contact_details']['full_list'].'</textarea>';
echo '<textarea class="mono-space subscriber_data" style="background-color: #FFFFFF; color:#000 !important; padding:3px; font-size:0.8em; height:218px; width: 100%; border-radius: 0px;">'.$hashtag_settings['contact_details']['email_list'].'</textarea>';