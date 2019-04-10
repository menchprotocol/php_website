
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
    foreach ($student_intents as $ln) {

        //Calculate time:
        $time_estimate = echo_time_range($ln);

        //Display row:
        echo '<a href="/messenger/actionplan/' . $ln['ln_child_intent_id'] . '" link-id="'.$ln['ln_id'].'" class="list-group-item actionplan_sort">';

        //Right:
        echo '<span class="pull-right">';
        echo '<span class="badge badge-primary" style="margin:0 5px 0 0;"><i class="fas fa-angle-right"></i></span>';
        echo '<span class="badge badge-primary actionplan_remove" style="margin:0 5px 0 0;"><i class="far fa-times"></i></span>';
        echo '</span>';


        //Left:
        if($has_multiple_intentions){
            echo '<i class="fas fa-bars"></i>'; //For sorting Action Plan
        }

        //echo echo_fixed_fields('ln_status', $ln['ln_status'], 1, 'right');
        echo '<span class="actionplan-title ap-title-'.$ln['ln_id'].'">' . $ln['in_outcome'] . '</span>';
        echo '<div class="actionplan-overview"><span class="results-ln-'.$ln['ln_id'].'">'.echo_ordinal_number($ln['ln_order']).'</span> Priority, '.( $time_estimate ? $time_estimate.', ' : '').$this->Matrix_model->actionplan_completion_rate($ln, $session_en['en_id']).'% Complete</div>';
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
echo '<div class="actionplan-tip"><i class="fas fa-lightbulb"></i> TIP: You can add a new intention by sending a message starting with <span style="display:inline-block;">"<b>I want to</b>"</span> for example "I want to create a resume" or "I want to get hired".</div>';

?>