<?php

if(!isset($_GET['i__id'])){
    die('Missing Idea ID i__id');
}


//Generate list & settings:
$list_settings = list_settings($_GET['i__id'], true);
echo '<h1>' . view_first_line($list_settings['i']['i__message']) . '</h1>';
echo count($list_settings['query_string']) . ' Contacts<br />';
echo $list_settings['contact_details']['email_count'] . ' Emails<br />';
echo $list_settings['contact_details']['phone_count'] . ' Phones<br /><br />';

//Generate the contact list of the input idea:
echo '<textarea class="mono-space subscriber_data" style="background-color:#FFFFFF; color:#000 !important; padding:3px; font-size:0.8em; height:218px; width: 100%; border-radius: 21px;">'.$list_settings['contact_details']['full_list'].'</textarea>';
echo '<textarea class="mono-space subscriber_data" style="background-color:#FFFFFF; color:#000 !important; padding:3px; font-size:0.8em; height:218px; width: 100%; border-radius: 21px;">'.$list_settings['contact_details']['email_list'].'</textarea>';