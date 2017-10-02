<?php 
//Attempt to fetch session variables:
if(count($bootcamps)>0){
    echo '<div class="list-group">';
    foreach($bootcamps as $c){
        echo '<a href="/console/'.$c['c_id'].'" class="list-group-item"><span class="pull-right"><span class="label label-primary"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span>'.status_bible('c',$c['c_status'],1,'right').' <b>'.$c['c_objective'].'</b></a>'; //<span data-toggle="tooltip" title="'.$c['count_users'].' Challenge Administrators" style="width:45px; display:inline-block;"><i class="fa fa-user"></i> '.$c['count_users'].'</span> <span data-toggle="tooltip" title="'.$c['count_runs'].' Challenge Runs" style="width:45px; display:inline-block;"><i class="fa fa-code-fork"></i> '.$c['count_runs'].'</span>
    }
    echo '</div>';
} else {
    echo '<div class="alert alert-info" role="alert">No bootcamps created yet.</div>';
}

?>

<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#newBootcampModal">New</a>