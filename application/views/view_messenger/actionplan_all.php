
<script>
    //Set global variables:
    var en_miner_id = <?= $session_en['en_id'] ?>;
</script>
<script src="/js/custom/messenger-actionplan.js?v=v<?= $this->config->item('app_version') ?>" type="text/javascript"></script>

<?php

//Student has multiple Action Plans, so list all Action Plans to enable Student to choose:
echo '<div id="actionplan_intents" class="list-group actionplan-sort" style="margin-top: 10px;">';
foreach ($student_intents as $ln) {

    //Display row:
    echo '<a href="/messenger/actionplan/' . $ln['ln_child_intent_id'] . '" intent-id="'.$ln['ln_child_intent_id'].'" class="list-group-item actionplan_sort">';

    //Right:
    echo '<span class="pull-right">';
        $time_estimate = echo_time_range($ln, true);
        if ($time_estimate) {
            echo $time_estimate . ' <i class="fal fa-alarm-clock"></i> ';
        }
        echo '<span class="badge badge-primary" style="margin-top: -8px;"><i class="fas fa-angle-right"></i></span>';
    echo '</span>';

    //Left:
    echo '<i class="fas fa-sort"></i>'; //For sorting Action Plan
    //echo echo_fixed_fields('ln_status', $ln['ln_status'], 1, 'right');
    echo '<span class="actionplan-title">' . $ln['in_outcome'] . '</span>';


    echo '</a>';
}


//Input to add new parents:
echo '<div id="new-actionplan" class="list-group-item list_input grey-input">
                    <div class="form-group is-empty"><input type="text" class="form-control new-input algolia_search actionplanadder" data-lpignore="true" placeholder="Search/Add New Intention..."></div>
                    <div class="algolia_search_pad hidden"><span>Search published intention (or browse them on <a href="https://mench.com">mench.com</a>)</span></div>
            </div>';

echo '</div>';

?>