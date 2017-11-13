<ul class="maxout" style="margin-bottom:20px;">
	<li>Each <b><i class="fa fa-calendar" aria-hidden="true"></i> Class</b> groups students based on their start date.</li>
	<li>All classes share the bootcamp's central <b style="display:inline-block;"><i class="fa fa-list-ol" aria-hidden="true"></i> Action Plan</b>.</li>
	<li>Improve your bootcamp iteratively after each class using student feedback.</li>
</ul>

<?php
if(count($bootcamp['c__classes'])>0){
    echo '<div class="list-group">';
    foreach($bootcamp['c__classes'] as $class){
        echo '<a href="/console/'.$bootcamp['b_id'].'/classes/'.$class['r_id'].'" class="list-group-item">';
            echo '<span class="pull-right"><span class="badge badge-primary"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span>';
            echo '<i class="fa fa-calendar" aria-hidden="true"></i> '.time_format($class['r_start_date'],2).' &nbsp; ';
            if(strlen($class['r_usd_price'])>0){
                echo '<i class="fa fa-usd" aria-hidden="true"></i> '.number_format($class['r_usd_price']).' &nbsp; ';
            }
            echo status_bible('r',$class['r_status'],0,'top');
        echo '</a>';
    }
    echo '</div>';
} else {
    echo '<div class="alert alert-info" role="alert">No classes created yet.</div>';
}

?>

<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#newClassModal">New</a>
<span>or <a href="#" data-toggle="modal" data-target="#ScheduleClasses"><u>Schedule Classes</u></a></span>
