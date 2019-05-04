<?php

//Header:
echo '<h1>Land Your Dream Programming Job</h1>';

//Display intent messages:
echo '<div class="home-page-intro">';

echo '<div class="i_content"><div class="msg">Hi, I\'m Mench, a human-trained personal assistant designed to help you get hired at your dream programming job. Our community of "Miners" aggregate key ideas and actionable tasks from top industry experts, and I will communicate them to you via Messenger.</div></div>';

echo '<div class="i_content"><div class="msg">I\'m open-source, free and on a mission to expand your potential.</div></div>';

echo '<div class="i_content"><div class="msg">Get started by choosing an intention:</div></div>';

echo '</div>';


//Featured intents:
echo '<div class="list-group actionplan_list grey_list maxout" style="margin-top:20px;">';
foreach ($this->Database_model->ln_fetch(array(
    'ln_status' => 2, //Published
    'in_status' => 2, //Published
    'ln_type_entity_id' => 4228, //Fixed Intent Links
    'ln_parent_intent_id' => $this->config->item('in_featured'), //Feature Mench Intentions
), array('in_child'), 0, 0, array('ln_order' => 'ASC')) as $featured_in) {
    echo echo_in_featured($featured_in);
}
echo '</div>';

?>