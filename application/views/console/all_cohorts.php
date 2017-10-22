<?php
if(count($bootcamp['c__cohorts'])>0){
    echo '<div class="list-group">';
    foreach($bootcamp['c__cohorts'] as $cohort){
        echo '<a href="/console/'.$bootcamp['b_id'].'/cohorts/'.$cohort['r_id'].'" class="list-group-item">';
            echo '<span class="pull-right">'.status_bible('r',$cohort['r_status'],1,'left').'</span>';
            echo '<i class="fa fa-calendar" aria-hidden="true"></i> '.time_format($cohort['r_start_date'],1).' &nbsp; ';
            echo '<i class="fa fa-usd" aria-hidden="true"></i> '.number_format($cohort['r_usd_price']);
        echo '</a>';
    }
    echo '</div>';
} else {
    echo '<div class="alert alert-info" role="alert">No cohorts created yet.</div>';
}

?>

<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#newCohortModal">New</a>