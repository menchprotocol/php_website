<?php $this->load->view('console/inputs/g_cohort_intro'); ?>

<?php
if(count($bootcamp['c__cohorts'])>0){
    echo '<div class="list-group">';
    foreach($bootcamp['c__cohorts'] as $cohort){
        echo '<a href="/console/'.$bootcamp['b_id'].'/cohorts/'.$cohort['r_id'].'" class="list-group-item">';
            echo '<span class="pull-right"><span class="badge badge-primary"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span>';
            echo '<i class="fa fa-calendar" aria-hidden="true"></i> '.time_format($cohort['r_start_date'],2).' &nbsp; ';
            if(strlen($cohort['r_usd_price'])>0){
                echo '<i class="fa fa-usd" aria-hidden="true"></i> '.number_format($cohort['r_usd_price']).' &nbsp; ';
            }
            echo status_bible('r',$cohort['r_status'],0,'top');
        echo '</a>';
    }
    echo '</div>';
} else {
    echo '<div class="alert alert-info" role="alert">No cohorts created yet.</div>';
}

?>

<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#newCohortModal">New</a>
<span>or <a href="#" data-toggle="modal" data-target="#ScheduleCohorts"><u>Schedule Cohorts</u></a></span>

