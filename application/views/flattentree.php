<?php

if(!isset($_GET['ideahashtag'])){
    die('Missing Idea ID ideahashtag');
}


//Generate list & settings:
$idea_settings = idea_settings($_GET['ideahashtag']);
echo '<h1>' . view_idea_title($idea_settings['i']) . '</h1>';


//Display idea info:
$copy = $this->Ideas->ids($idea_settings['i'], 'ALL');


//Main Idea:
echo '<h2><a href="'.view_memory(42903,33286).$idea_settings['i']['ideahashtag'].'">'.view_idea_title($idea_settings['i'], true).'</a> '.count($copy['recursive_idea_ids']).' IDEAS</h2>';

echo '<div class="row justify-content">';
foreach($copy['recursive_idea_ids'] as $recursive_down_id){
    foreach($this->Ideas->read(array(
        'ideaid' => $recursive_down_id,
    ), 0) as $this_i){
        echo idea_view(42288, $this_i);
    }
}
echo '</div>';