<h1>Cohorts</h1>
<?php
if(isset($bootcamp['runs']) && count($bootcamp['runs'])>0){
    $r_pace_options = $this->config->item('r_pace_options');
    echo '<div class="list-group" style="margin-top:30px;">';
    foreach($bootcamp['runs'] as $cohort){
        echo '<a href="/console/'.$bootcamp['c_id'].'/cohorts/'.$cohort['r_id'].'" class="list-group-item"><span class="pull-right">'.status_bible('r',$cohort['r_status'],1).' <span class="label label-primary"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span>';
            echo '<i class="fa fa-calendar" aria-hidden="true"></i> '.time_format($cohort['r_start_date'],1).' &nbsp; ';
            echo '<i class="fa fa-clock-o" aria-hidden="true"></i> '.$r_pace_options[$cohort['r_pace_id']]['p_name'].': '.$r_pace_options[$cohort['r_pace_id']]['p_hours'].' &nbsp; ';
            echo '<i class="fa fa-usd" aria-hidden="true"></i> '.number_format($cohort['r_usd_price']);
            echo '</a>';
    }
    echo '</div>';
} else {
    echo '<div class="alert alert-info" role="alert">No cohorts created yet.</div>';
}

?>

<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#newCohortModal">New</a>