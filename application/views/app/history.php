<?php

//Generate list & settings:
foreach($this->Idea_cache->fetch(array(
    'LOWER(i__hashtag)' => strtolower($_GET['i__hashtag']),
)) as $i){

    foreach($this->Source_cache->fetch(array(
        'LOWER(e__handle)' => strtolower($_GET['e__handle']),
    )) as $e){

        echo '<h3><a href="/@' . $e['e__handle'] . '">' . $e['e__title'] . '</a> History of <a href="/' . $i['i__hashtag'] . '">' . view__i_title($i) . '</a></h3>';

        //Display idea info:
        $recursive_down_ids = $this->Idea_cache->recursive_down_ids($i, 'ALL');

        //Main Idea:
        echo '<h2><a href="'.view__memory(42903,33286).$i['i__hashtag'].'">'.view__i_title($i, true).'</a> '.count($recursive_down_ids['recursive_i_ids']).' IDEAS</h2>';

        echo '<div class="row justify-content">';
        foreach($recursive_down_ids['recursive_i_ids'] as $recursive_down_id){
            foreach($this->Idea_cache->fetch(array(
                'i__id' => $recursive_down_id,
            ), 0) as $this_i){
                echo view__card_i(12273, $this_i);
            }
        }
        echo '</div>';

    }
}
