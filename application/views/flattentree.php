<?php

if(!isset($_GET['ideahashtag'])){
    die('Missing Idea ID ideahashtag');
}


//Generate list & settings:
$list_settings = list_settings($_GET['ideahashtag']);
echo '<h1>' . view_idea_title($list_settings['i']) . '</h1>';


//Display idea info:
$recursive_down_ids = $this->Nodeideas->recursive_down_ids($list_settings['i'], 'ALL');


//Main Idea:
echo '<h2><a href="'.view_memory(42903,33286).$list_settings['i']['ideahashtag'].'">'.view_idea_title($list_settings['i'], true).'</a> '.count($recursive_down_ids['recursive_idea_ids']).' IDEAS</h2>';

echo '<div class="row justify-content">';
foreach($recursive_down_ids['recursive_idea_ids'] as $recursive_down_id){
    foreach($this->Nodeideas->fetch(array(
        'ideaid' => $recursive_down_id,
    ), 0) as $this_i){
        echo view_card_i(12273, $this_i);
    }
}
echo '</div>';