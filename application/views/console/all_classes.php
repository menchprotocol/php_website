<div class="help_body maxout below_h" id="content_2274"></div>

<?php
if(count($bootcamp['c__classes'])>0){
    echo '<div class="list-group maxout">';
    foreach($bootcamp['c__classes'] as $class){
        echo '<a href="/console/'.$bootcamp['b_id'].'/classes/'.$class['r_id'].'" class="list-group-item">';
            echo '<span class="pull-right"><span class="badge badge-primary"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span>';
            echo '<i class="fa fa-calendar" aria-hidden="true"></i> '.time_format($class['r_start_date'],2).' &nbsp; ';
            if(strlen($class['r_usd_price'])>0){
                echo '<i class="fa fa-usd" aria-hidden="true"></i> '.number_format($class['r_usd_price'],( fmod($class['r_usd_price'],1)==0?0:2 )).' &nbsp; ';
            }
            echo status_bible('r',$class['r_status'],0,'top');
        echo '</a>';
    }
    echo '</div>';
} else {
    echo '<div class="alert alert-info maxout" role="alert">No classes created yet.</div>';
}
?>

<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#newClassModal">New</a>
<?php /* This is distraction for now... <span>or <a href="#" data-toggle="modal" data-target="#ScheduleClasses"><u>Schedule Classes</u></a></span> */ ?>
