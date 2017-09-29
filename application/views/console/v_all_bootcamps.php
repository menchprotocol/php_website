
<h1>My <?= $this->lang->line('c_pname') ?></h1>
<?php 
//Attempt to fetch session variables:
if(count($bootcamps)>0){
    echo '<div class="list-group" style="margin-top:30px;">';
    foreach($bootcamps as $c){
        echo '<a href="/console/'.$c['c_id'].'" class="list-group-item"><span class="pull-right">'.status_bible('c',$c['c_status'],1).' <span class="label label-primary"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span>'.echo_title($c['c_objective']).'</a>'; //<span data-toggle="tooltip" title="'.$c['count_users'].' Challenge Administrators" style="width:45px; display:inline-block;"><i class="fa fa-user"></i> '.$c['count_users'].'</span> <span data-toggle="tooltip" title="'.$c['count_runs'].' Challenge Runs" style="width:45px; display:inline-block;"><i class="fa fa-code-fork"></i> '.$c['count_runs'].'</span>
    }
    echo '</div>';
} else {
    echo '<div class="alert alert-info" role="alert">No bootcamps created yet.</div>';
}

?>

<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#newBootcampModal">New</a>