<?php

if(!isset($_GET['hashtagstring'])){
    die('Missing Hashtag ID hashtagstring');
}


//Generate list & settings:
$hashtag_settings = hashtag_settings($_GET['hashtagstring']);
echo '<h1>' . view_hashtag_title($hashtag_settings['i']) . '</h1>';


//Display hashtag info:
$copy = $this->Hashtags->ids($hashtag_settings['i'], 'ALL');


//Main Hashtag:
echo '<h2><a href="'.view_memory(42903,33286).$hashtag_settings['i']['hashtagstring'].'">'.view_hashtag_title($hashtag_settings['i'], true).'</a> '.count($copy['recursive_hashtag_ids']).' HASHTAGS</h2>';

echo '<div class="row justify-content">';
foreach($copy['recursive_hashtag_ids'] as $recursive_down_id){
    foreach($this->Hashtags->read(array(
        'hashtagid' => $recursive_down_id,
    ), 0) as $this_i){
        echo hashtag_view(42288, $this_i);
    }
}
echo '</div>';