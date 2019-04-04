<?php

//Header:
echo '<h1>'.echo_in_outcome($in['in_outcome'], true).'</h1>';


//Display intent messages:
echo '<div class="home-page-intro">';
foreach ($this->Database_model->ln_fetch(array(
    'ln_status' => 2, //Published
    'ln_type_entity_id' => 4231, //Intent Note Messages
    'ln_child_intent_id' => $in['in_id'],
), array(), 0, 0, array('ln_order' => 'ASC')) as $tr) {
    //Echo HTML format of this message:
    echo $this->Chat_model->dispatch_message($tr['ln_content']);
}
echo '</div>';



//Featured intents:
echo '<div class="list-group actionplan_list grey_list maxout" style="margin-top:20px;">';
foreach ($featured_ins as $featured_in) {
    echo echo_in_featured($featured_in);
}
echo '</div>';

?>