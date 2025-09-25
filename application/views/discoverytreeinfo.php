<?php

if(!isset($_GET['posthashtag'])){
    die('Missing Post Post');
}


//Define the user to fetch their discoveries for this post:
if(!isset($_GET['userhandle']) || !strlen($_GET['userhandle'])){
    $_GET['userhandle'] = $user_session['userhandle'];
}


//Generate list & settings:
$post_settings = post_settings($_GET['posthashtag']);
echo '<h1>' . view_post_title($post_settings['i']) . '</h1>';


foreach($this->Users->read(array(
    'LOWER(userhandle)' => strtolower($_GET['userhandle']),
)) as $e){
    //List the post:
    print_r(array(
        'next_posts' => $this->Ideachains->next_posts($e['userid'], $post_settings['i']['posthashtag'], $post_settings['i'], 0, false),
        'progress' => $this->Ideachains->progress($e['userid'], $post_settings['i']),
    ));
}
