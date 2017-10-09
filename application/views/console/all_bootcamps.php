<?php
//Attempt to fetch session variables:
if(count($bootcamps)>0){
    echo '<div class="list-group">';
    foreach($bootcamps as $bootcamp){
        echo '<a href="/console/'.$bootcamp['b_id'].'" class="list-group-item"><span class="pull-right"><span class="label label-primary"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span>'.status_bible('b',$bootcamp['b_status'],1,'right').' <b>'.$bootcamp['c_objective'].'</b></a>';
    }
    echo '</div>';
} else {
    echo '<div class="alert alert-info" role="alert">No bootcamps created yet.</div>';
}

?>

<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#newBootcampModal">New</a>
