<?php
echo '<div class="landing-page-intro" id="in_public_ui">';

//Intent Title:
echo '<h1 style="margin-bottom:30px;" id="title-parent">' . echo_in_outcome($in['in_outcome']) . '</h1>';


//Fetch & Display Intent Note Messages:
foreach ($this->Links_model->ln_fetch(array(
    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    'ln_type_entity_id' => 4231, //Intent Note Messages
    'ln_child_intent_id' => $in['in_id'],
), array(), 0, 0, array('ln_order' => 'ASC')) as $ln) {
    echo $this->Communication_model->dispatch_message($ln['ln_content']);
}


//List intent children based on intent type:
if(in_is_or($in['in_type_entity_id'])){

    //Give option to choose a child path:
    echo '<div class="list-group actionplan_list grey_list maxout" style="margin-top:20px;">';
    $in__children = $this->Links_model->ln_fetch(array(
        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        'ln_type_entity_id' => 4228, //Fixed Intent Links
        'ln_parent_intent_id' => $in['in_id'],
    ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));
    $common_prefix = common_prefix($in__children);

    foreach ($in__children as $child_in) {
        echo echo_in_recommend($child_in, true, $common_prefix);
    }
    echo '</div>';

} else {

    //Just show the Action Plan:
    echo '<br />'.echo_public_actionplan($in, $autoexpand);

}

echo '</div>';
?>