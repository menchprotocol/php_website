<p class="maxout" style="margin-top:-10px;">Each cohort is a grouping of students based on their start date:</p>
<ul class="maxout" style="margin-bottom:20px;">
	<li>Enable you to run your bootcamp multiple times for different student groups.</li>
	<li>Improve your bootcamp iteratively on each cohort using student feedbacks.</li>
	<li>All cohorts share the bootcamp's central <b style="display:inline-block;"><i class="fa fa-flag" aria-hidden="true"></i> Milestones</b>.</li>
</ul>

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
