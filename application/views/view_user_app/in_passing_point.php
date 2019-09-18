
<script>
    var in_loaded_id = <?= $in['in_id'] ?>;
    var session_en_id = <?= ( isset($session_en['en_id']) ? intval($session_en['en_id']) : 0 ) ?>;
</script>
<script src="/js/custom/intent-featured.js?v=v<?= $this->config->item('app_version') ?>"
        type="text/javascript"></script>

<?php
echo '<div class="landing-page-intro" id="in_public_ui">';

//Intent Title:
if(in_is_clean_outcome($in)) {
    echo '<h1 style="margin-bottom:30px;" id="title-parent">' . echo_in_outcome($in['in_outcome']) . '</h1>';
}


//Fetch & Display Intent Note Messages:
foreach ($this->Links_model->ln_fetch(array(
    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    'ln_type_entity_id' => 4231, //Intent Note Messages
    'ln_child_intent_id' => $in['in_id'],
), array(), 0, 0, array('ln_order' => 'ASC')) as $ln) {
    echo $this->Communication_model->dispatch_message($ln['ln_content']);
}


//Intent Select Publicly? If so, allow user to choose path:
if(in_array($in['in_completion_method_entity_id'], $this->config->item('en_ids_7588'))){

    //Give option to choose a child path:
    echo '<div class="list-group actionplan_list grey_list" style="margin-top:40px;">';
    $in__children = $this->Links_model->ln_fetch(array(
        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        'ln_type_entity_id' => 4228, //Intent Link Regular Step
        'ln_parent_intent_id' => $in['in_id'],
    ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));
    $common_prefix = common_prefix($in__children);

    foreach ($in__children as $child_in) {
        echo echo_in_recommend($child_in, $common_prefix, null, $referrer_en_id);
    }
    echo '</div>';

} else {

    //Just show the Action Plan:
    echo '<br />'.echo_tree_actionplan($in, $autoexpand);

}


echo '</div>';
?>