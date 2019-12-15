<script>
    var in_loaded_id = <?= $in['in_id'] ?>;
    var session_en_id = <?= ( isset($session_en['en_id']) ? intval($session_en['en_id']) : 0 ) ?>;
</script>
<script src="/js/custom/in_landing_page.js?v=v<?= config_var(11060) ?>"
        type="text/javascript"></script>


<div class="container">
<?php

echo '<h1>' . echo_in_outcome($in['in_outcome']) . '</h1>';

//Overview:
/*
$step_info = echo_tree_steps($in, false);
$source_info = echo_tree_experts($in, false);

if($step_info || $source_info){
    echo '<div style="margin:5px 0;">';
    echo $step_info;
    echo $source_info;
    echo '</div>';
}
*/


//MESSAGES:
foreach ($this->READ_model->ln_fetch(array(
    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    'ln_type_entity_id' => 4231, //Intent Note Messages
    'ln_child_intent_id' => $in['in_id'],
), array(), 0, 0, array('ln_order' => 'ASC')) as $ln) {
    echo $this->READ_model->dispatch_message($ln['ln_content']);
}



if(in_array($in['in_completion_method_entity_id'], $this->config->item('en_ids_12107'))){

    //Give option to choose a child path:
    echo '<div class="list-group" style="margin-top:30px;">';
    $in__children = $this->READ_model->ln_fetch(array(
        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        'ln_type_entity_id' => 4228, //Intent Link Regular Step
        'ln_parent_intent_id' => $in['in_id'],
    ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));
    foreach ($in__children as $child_in) {
        echo echo_in_read($child_in, null, $in['in_id']);
    }
    echo '</div>';

} else {

    echo '<div style="padding-bottom:40px;"><a class="btn btn-read" href="/'.$in['in_id'].'/next">'.( isset($session_en['en_id']) ? 'NEXT' : 'START READING' ).' <i class="fas fa-angle-right"></i></a></div>';

}

?>

</div>
