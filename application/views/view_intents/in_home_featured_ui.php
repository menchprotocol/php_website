<?php

//Header:
echo '<h1>'.$in['in_outcome'].'</h1>';


//Display intent messages:
echo '<div class="home-page-intro">';
foreach ($this->Database_model->fn___tr_fetch(array(
    'tr_status >=' => 2, //Published+
    'tr_type_entity_id' => 4231, //Intent Note Messages
    'tr_child_intent_id' => $in['in_id'],
), array(), 0, 0, array('tr_order' => 'ASC')) as $tr) {
    echo $this->Chat_model->fn___dispatch_message($tr['tr_content']);
}
echo '</div>';


//Featured intents:
echo '<div class="list-group actionplan_list grey_list maxout" style="margin-top:20px;">';
foreach ($featured_ins as $featured_c) {
    echo fn___echo_in_featured($featured_c);
}
echo '</div>';

?>