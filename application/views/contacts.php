<?php

//Generate list & settings:
$idea_settings = idea_settings($_GET['ideahashtag'], true);
echo '<h1>' . view_idea_title($idea_settings['i']) . '</h1>';
echo count($idea_settings['query_string_filtered']) . ' Contacts<br />';
echo $idea_settings['contact_details']['email_count'] . ' Emails<br />';
echo $idea_settings['contact_details']['phone_count'] . ' Phones<br /><br />';

//Generate the contact list of the input idea:
echo '<textarea class="mono-space subscriber_data" style="background-color: #FFFFFF; color:#000 !important; padding:3px; font-size:0.8em; height:218px; width: 100%; border-radius: 0px;">'.$idea_settings['contact_details']['full_list'].'</textarea>';
echo '<textarea class="mono-space subscriber_data" style="background-color: #FFFFFF; color:#000 !important; padding:3px; font-size:0.8em; height:218px; width: 100%; border-radius: 0px;">'.$idea_settings['contact_details']['email_list'].'</textarea>';