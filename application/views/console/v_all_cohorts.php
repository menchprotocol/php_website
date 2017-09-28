<ol class="breadcrumb"><li>Cohorts</li></ol>
<h1>Cohorts</h1>

<?php
if(isset($bootcamp['runs']) && count($bootcamp['runs'])>0){
    foreach($bootcamp['runs'] as $cohort){
        echo $cohort['r_start_time'].'kjhkjh<br />';
    }
} else {
    echo 'No cohorts created yet.';
}

?>