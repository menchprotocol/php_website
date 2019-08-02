
<script>
    var in_focus_id = <?= $in['in_id'] ?>;
    var session_en_id = <?= ( isset($session_en['en_id']) ? intval($session_en['en_id']) : 0 ) ?>;
</script>
<script src="/js/custom/intent-featured.js?v=v<?= $this->config->item('app_version') ?>"
        type="text/javascript"></script>

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


//Intent Select Publicly? If so, allow user to choose path:
if(in_array($in['in_type_entity_id'], $this->config->item('en_ids_7588'))){

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


//Check to see if this intent is part of the company training entities:
foreach($this->config->item('en_all_7593') as $company_intro){
    if($company_intro['m_desc']==$in['in_id']){
        echo '<a class="btn btn-primary tag-manager-get-started" href="/10430/signin" style="display: inline-block; padding:12px 36px; font-size: 1.3em; margin-top: 35px;">Get Started&nbsp;&nbsp;&nbsp;<i class="fas fa-angle-double-right"></i></a>';
        break;
    }
}


echo '</div>';
?>