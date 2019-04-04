
<script src="/js/custom/messenger-actionplan.js?v=v<?= $this->config->item('app_version') ?>" type="text/javascript"></script>

<?php

//Student has multiple Action Plans, so list all Action Plans to enable Student to choose:
echo '<div class="list-group actionplan-sort" style="margin-top: 10px;">';
foreach ($student_intents as $tr) {

    //Display row:
    echo '<a href="/messenger/actionplan/' . $tr['ln_child_intent_id'] . '" class="list-group-item">';

    //Right:
    echo '<span class="pull-right">';
        $time_estimate = echo_time_range($tr, true);
        if ($time_estimate) {
            echo $time_estimate . ' <i class="fal fa-alarm-clock"></i> ';
        }
        echo '<span class="badge badge-primary" style="margin-top: -8px;"><i class="fas fa-angle-right"></i></span>';
    echo '</span>';

    //Left:
    echo '<i class="fas fa-sort"></i>'; //For sorting Action Plan
    //echo echo_fixed_fields('ln_status', $tr['ln_status'], 1, 'right');
    echo '<span class="actionplan-title">' . $tr['in_outcome'] . '</span>';


    echo '</a>';
}


//Input to add new parents:
echo '<div id="new-actionplan" class="list-group-item list_input grey-input">
                    <div class="form-group is-empty"><input type="text" class="form-control new-input algolia_search actionplanadder" data-lpignore="true" placeholder="Search/Add New Intention..."></div>
                    <div class="algolia_search_pad hidden"><span>Search published intention (or browse them on <a href="https://mench.com">mench.com</a>)</span></div>
            </div>';

echo '</div>';

?>