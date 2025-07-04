<?php

if(!isset($_GET['hashtaghashtag'])){
    die('Missing Hahstag Hashtag');
}


//Define the user to fetch their discoveries for this hashtag:
if(!isset($_GET['handlehandle']) || !strlen($_GET['handlehandle'])){
    $_GET['handlehandle'] = $handle_session['handlehandle'];
}


//Generate list & settings:
$hashtag_settings = hashtag_settings($_GET['hashtaghashtag']);
echo '<h1>' . view_hashtag_title($hashtag_settings['i']) . '</h1>';


foreach($this->Handles->read(array(
    'LOWER(handlehandle)' => strtolower($_GET['handlehandle']),
)) as $e){
    //List the hashtag:
    print_r(array(
        'next_hashtags' => $this->Chains->next_hashtags($e['handleid'], $hashtag_settings['i']['hashtaghashtag'], $hashtag_settings['i'], 0, false),
        'progress' => $this->Chains->progress($e['handleid'], $hashtag_settings['i']),
    ));
}
