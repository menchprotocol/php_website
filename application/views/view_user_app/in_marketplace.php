<?php

$in_published_trees = $this->Intents_model->in_fetch(array(
    'in_level_entity_id' => 7598, //Tree
    'in_status_entity_id' => 6184, //Published
), array(), 0, 0, array(
    'in_outcome' => 'ASC',
));


echo '<p style="margin:25px 0 15px;">So far I\'m trained on '.count($in_published_trees).' intentions:</p>';


echo '<div class="list-group actionplan_list grey_list" style="margin-top:40px;">';
foreach($in_published_trees as $in_published_tree){
    echo echo_in_recommend($in_published_tree, null, null);
}

echo '</div>';

?>