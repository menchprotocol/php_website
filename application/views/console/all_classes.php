<div class="help_body maxout below_h" id="content_2274"></div>

<?php
if(count($bootcamp['c__classes'])>0){
    echo '<div class="list-group maxout">';
    foreach($bootcamp['c__classes'] as $class){


        echo '<a href="/console/'.$bootcamp['b_id'].'/classes/'.$class['r_id'].'" class="list-group-item">';
            echo '<span class="pull-right"><span class="badge badge-primary"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span>';
            echo '<span style="min-width:125px; display:inline-block;"><i class="fa fa-calendar" aria-hidden="true"></i> '.time_format($class['r_start_date'],2).'</span>';
            if(strlen($class['r_usd_price'])>0){
                echo '<span style="min-width:70px; display:inline-block;"> <i class="fa fa-usd" aria-hidden="true"></i> '.( strlen($class['r_usd_price'])>0 ? ( $class['r_usd_price']==0 ? 'FREE' : number_format($class['r_usd_price'],( fmod($class['r_usd_price'],1)==0?0:2 )) ) : '???' ).'</span>';
            }

            //Show Funnel:
            echo '<span style="min-width:140px; display:inline-block;" data-toggle="tooltip" data-placement="left" title="'.( $class['r_status']>=2 ? 'Student Admission Overview: [Admitted] / [Max Admissions]' : 'Student Funnel Overview: [Started Application] -> [Completed Application] -> [Admitted] / [Max Admissions]' ).'"><i class="fa fa-user" aria-hidden="true"></i> ';
            $student_funnel = array(
                0 => count($this->Db_model->ru_fetch(array(
                    'r.r_id'	       => $class['r_id'],
                    'ru.ru_status'     => 0,
                ))),
                2 => count($this->Db_model->ru_fetch(array(
                    'r.r_id'	       => $class['r_id'],
                    'ru.ru_status'     => 2,
                ))),
                4 => count($this->Db_model->ru_fetch(array(
                    'r.r_id'	       => $class['r_id'],
                    'ru.ru_status'     => 4,
                ))),
            );
            echo ( $class['r_status']>=2 ? '' : $student_funnel[0].' &raquo; '.$student_funnel[2].' &raquo; ' ).'<b>'.$student_funnel[4].'</b>/'.( strlen($class['r_max_students'])>0 ? ( $class['r_max_students']==0 ? 'Infinity' : $class['r_max_students'] ) : '???' ).' ';
            echo '</span>';


            echo status_bible('r',$class['r_status'],0,'top');
        echo '</a>';


    }
    echo '</div>';
} else {
    echo '<div class="alert alert-info maxout" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> No classes yet.</div>';
}
?>

<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#newClassModal">New</a>
<?php /* This is distraction for now... <span>or <a href="#" data-toggle="modal" data-target="#ScheduleClasses"><u>Schedule Classes</u></a></span> */ ?>
