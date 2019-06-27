
<script>
    //Set global variables:
    var en_miner_id = <?= $session_en['en_id'] ?>;
</script>
<script src="/js/custom/messenger-actionplan.js?v=v<?= $this->config->item('app_version') ?>" type="text/javascript"></script>

<?php

echo '<h3 class="master-h3 primary-title" style="padding:15px 0;">🚩 Action Plan</h3>';

if(count($user_intents) > 0){

    //See if we have 2 or more intentions:
    $has_multiple_intentions = ( count($user_intents) >= 2 );

    //User has multiple Action Plans, so list all Action Plans to enable User to choose:
    echo '<div id="actionplan_steps" class="list-group '.( $has_multiple_intentions ? 'actionplan-sort' : '').'" style="margin-top: 10px;">';
    foreach ($user_intents as $priority => $ln) {

        //Calculate time:
        $time_estimate = echo_time_range($ln);

        //Display row:
        echo '<a id="ap_in_'.$ln['in_id'].'" href="/actionplan/' . $ln['in_id'] . '" sort-link-id="'.$ln['ln_id'].'" class="list-group-item actionplan_sort">';

        echo '<span class="pull-right" style="padding-right:8px; padding-left:10px;">';
        echo '<span class="actionplan_remove" in-id="'.$ln['in_id'].'" data-toggle="tooltip" title="Cancel this intention and stop receiving updates" data-placement="left"><i class="fas fa-comment-times" style="font-size:1.6em;"></i></span>';
        echo '</span>';

        $completion_rate = $this->Actionplan_model->actionplan_completion_progress($session_en['en_id'], $ln);

        echo '<span class="actionplan-title in-title-'.$ln['in_id'].'">' . $ln['in_outcome'] . '</span>';
        echo '<div class="actionplan-overview">';
        echo '<span class="results-ln-'.$ln['ln_id'].'">'.echo_ordinal_number(($priority+1)).'</span> Priority, ';
        echo ( $time_estimate ? $time_estimate.', ' : '');
        echo '<span title="'.$completion_rate['steps_completed'].'/'.$completion_rate['steps_total'].' Steps Completed">'.$completion_rate['completion_percentage'].'% Complete</span>';
        echo '</div>';
        echo '</a>';

    }

    echo '</div>';

    if($has_multiple_intentions){
        //Give sorting tip:
        echo '<div class="actionplan-tip"><i class="fas fa-lightbulb"></i> TIP: You can prioritize your intentions by holding and dragging them up or down.</div>';
    }

} else {

    //Show warning:
    echo '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> Your Action Plan has no intentions, yet.</div>';

}

//Inform users how they can add new intentions:
echo '<div class="actionplan-tip"><i class="fas fa-lightbulb"></i> TIP: '.echo_random_message('command_me').'</div>';

//Are they a miner? Give them option to clear everything:
if(count($this->Links_model->ln_fetch(array(
        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
        'ln_child_entity_id' => $session_en['en_id'],
        'ln_parent_entity_id' => 1308, //Miners
    ))) > 0){
    $timestamp = time();
    echo '<div style="text-align: right;"><a href="/messenger/actionplan_reset_progress/'.$session_en['en_id'].'/'.$timestamp.'/'.md5($session_en['en_id'] . $this->config->item('actionplan_salt') . $timestamp).'" style="font-size:0.6em; color:#CCC;"><i class="fas fa-trash-alt"></i> Clear All</a></div>';
}

?>