<?php

//Header:
echo '<h1>'.fn___echo_in_outcome($in['in_outcome'], true).'</h1>';


//Display intent messages:
echo '<div class="home-page-intro">';
foreach ($this->Database_model->fn___tr_fetch(array(
    'tr_status' => 2, //Published
    'tr_type_entity_id' => 4231, //Intent Note Messages
    'tr_child_intent_id' => $in['in_id'],
), array(), 0, 0, array('tr_order' => 'ASC')) as $tr) {
    //Echo HTML format of this message:
    echo $this->Chat_model->fn___dispatch_message($tr['tr_content']);
}
echo '</div>';



//Featured intents:
echo '<div class="list-group actionplan_list grey_list maxout" style="margin-top:20px;">';
foreach ($featured_ins as $featured_in) {
    echo fn___echo_in_featured($featured_in);
}
echo '</div>';

?>