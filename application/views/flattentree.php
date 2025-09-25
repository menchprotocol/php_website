<?php

if(!isset($_GET['posthashtag'])){
    die('Missing Post ID posthashtag');
}


//Generate list & settings:
$post_settings = post_settings($_GET['posthashtag']);
echo '<h1>' . view_post_title($post_settings['i']) . '</h1>';


//Display post info:
$copy = $this->Posts->ids($post_settings['i'], 'ALL');


//Main Post:
echo '<h2><a href="'.view_memory(42903,33286).$post_settings['i']['posthashtag'].'">'.view_post_title($post_settings['i'], true).'</a> '.count($copy['recursive_post_ids']).' POSTS</h2>';

echo '<div class="row justify-content">';
foreach($copy['recursive_post_ids'] as $recursive_down_id){
    foreach($this->Posts->read(array(
        'postid' => $recursive_down_id,
    ), 0) as $this_i){
        echo post_view(42288, $this_i);
    }
}
echo '</div>';