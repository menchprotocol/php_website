<div class="help_body maxout below_h" id="content_594"></div>

<?php 
$submissions = $this->Db_model->us_fetch_fancy(array(
    'us_b_id' => $bootcamp['b_id'],
));
if(count($submissions)>0){
    $focus_u_id = 0;
    foreach($submissions as $s){
        if($focus_u_id!==$s['u_id']){
            //Print student:
            echo '<h3><b class="userheader"><img src="'.$s['u_image_url'].'" style="margin-top:-19px;" /> '.$s['u_fname'].' '.$s['u_lname'].':</b></h3><br />';
            $focus_u_id = $s['u_id'];
        }
        
        //Print their submission:
        echo '<div class="row">';
        echo '<div class="col-sm-4"><a href="/console/'.$bootcamp['b_id'].'/actionplan/'.$s['c_id'].'"><b><u>'.$s['c_objective'].'</u></b></a><br />Submitted '.( $s['us_on_time_score']==0 ? '<b style="color:#FF0000">really late</b>' : ( $s['us_on_time_score']==1 ? '<b style="color:#00CC00">on-time</b>' : '<b style="color:#FF8C00">a little late</b>' ) ).'<br />'.time_format($s['us_timestamp']).'</div>
        <div class="col-sm-8">'.( strlen($s['us_student_notes'])>0 ? nl2br(make_links_clickable($s['us_student_notes'])) : 'No Comments' ).'</div>';
        echo '</div>';
        echo '<hr />';
    }
} else {
    echo '<div class="alert alert-info" role="alert"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> No students admitted yet.</div>';
}

?>