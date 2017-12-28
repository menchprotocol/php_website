<?php

//Instructor has already activated their Instructor Bot
if(count($bootcamps)>0){
    echo '<div class="list-group maxout">';
    foreach($bootcamps as $bootcamp){
        
        //Calculate their progress:
        $launch_status = calculate_bootcamp_status($bootcamp);
        
        echo '<a href="/console/'.$bootcamp['b_id'].'" class="list-group-item">';
        echo '<span class="pull-right"><span class="badge badge-primary"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span>';
        echo '<i class="fa fa-dot-circle-o" aria-hidden="true"></i> <b>'.$bootcamp['c_objective'].'</b>';
        
        echo '<ul class="below_list">';
        
        echo '<li style="min-width:100px;"><i class="fa fa-flag" aria-hidden="true"></i> '.$bootcamp['c__milestone_units'].' '.ucwords($bootcamp['b_sprint_unit']).( $bootcamp['c__milestone_units']==1 ? '' : 's' ).'</li>';
        echo '<li style="min-width:115px;"><i class="fa fa-tasks" aria-hidden="true"></i> '.$launch_status['progress'].'% Ready</li>';
        echo '<li>'.status_bible('b',$bootcamp['b_status'],0,'right').'</li>';
        
        echo '</ul>';
        echo '</a>';
    }
    echo '</div>';
} else {
    echo '<div class="alert alert-info" role="alert">No bootcamps created yet.</div>';
}

//New Bootcamp Button:
echo '<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#newBootcampModal">New</a>';
if($udata['u_status']>=3){
    echo '<span>or <a href="/cockpit/all/bootcamps"><u>Browse All</u></a></span>';
}

?>