
<script>
    //Set global variables:
    var en_miner_id = <?= $session_en['en_id'] ?>;
</script>
<script src="/js/custom/messenger-actionplan.js?v=v<?= $this->config->item('app_version') ?>" type="text/javascript"></script>

<?php

if(count($student_intents) > 0){

    //See if we have 2 or more intentions:
    $has_multiple_intentions = ( count($student_intents) >= 2 );

    //Student has multiple Action Plans, so list all Action Plans to enable Student to choose:
    echo '<div id="actionplan_intents" class="list-group '.( $has_multiple_intentions ? 'actionplan-sort' : '').'" style="margin-top: 10px;">';
    foreach ($student_intents as $priority => $ln) {

        //Calculate time:
        $time_estimate = echo_time_range($ln);

        //Display row:
        echo '<a id="ap_in_'.$ln['in_id'].'" href="/messenger/actionplan/' . $ln['in_id'] . '" sort-link-id="'.$ln['ln_id'].'" class="list-group-item actionplan_sort">';


        if($has_multiple_intentions){
            echo '<div class="left-sorting">';
            echo '<span class="results-ln-'.$ln['ln_id'].'">'.echo_ordinal_number(($priority+1)).'</span>';
            echo '<i class="fas fa-sort"></i>'; //For sorting Action Plan
            echo '</div>';
        }

        $completion_rate = $this->Platform_model->actionplan_completion_rate($ln, $session_en['en_id']);

        echo '<span class="actionplan-title in-title-'.$ln['in_id'].'">' . $ln['in_outcome'] . '</span>';
        echo '<div class="actionplan-overview">'.( $time_estimate ? $time_estimate.', ' : '').'<span title="'.$completion_rate['steps_completed'].'/'.$completion_rate['steps_total'].' Steps Completed">'.$completion_rate['completion_percentage'].'% Complete</span> [<span class="actionplan_remove" in-id="'.$ln['in_id'].'"><i class="fas fa-hand-paper"></i> Stop</span>]</div>';
        echo '</a>';

    }

    echo '</div>';

    if($has_multiple_intentions){
        //Give sorting tip:
        echo '<div class="actionplan-tip"><i class="fas fa-lightbulb"></i> TIP: You can prioritize your intentions by holding and dragging them up or down.</div>';
    }

} else {

    //Show warning:
    echo '<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> Your Action Plan has no intentions.</div>';

}

//Inform students how they can add new intentions:
echo '<div class="actionplan-tip"><i class="fas fa-lightbulb"></i> TIP: '.echo_random_message('command_me').'</div>';

?>