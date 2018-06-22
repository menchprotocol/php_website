
<script>
    $( document ).ready(function() {
        $("#classes_start").countdowntimer({
            startDate : "<?= date('Y/m/d H:i:s'); ?>",
            dateAndTime : "<?= date('Y/m/d H:i:s' , strtotime('next monday')); ?>",
            size : "lg",
            regexpMatchFormat: "([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})",
            regexpReplaceWith: "<b>$1</b><sup>Days</sup><b>$2</b><sup>H</sup><b>$3</b><sup>M</sup><b>$4</b><sup>S</sup>"
        });
    });
</script>






<!-- <div class="col-sm-4"><?php // echo tree_menu(4793,array(4793)) ?></div> -->
<?php

echo '<h1 class="center">'.$title.'</h1>';
echo '<p class="home_line_2 center">';
    echo 'Land your dream job by completing weekly Bootcamps from industry experts.';
    //echo '<br />New classes start every Monday. <span style="display:inline-block;">(in <span id="classes_start"></span>)</span>';
echo '</p>';
echo '<br />';

//Fetch bs:
$bs = $this->Db_model->remix_bs(array(
    'b.b_status' => 3,
    'b.b_fp_id >' => 0,
    'b.b_old_format' => 0,
),array('ba','ihm'));

if(count($bs)>0){
    echo '<div class="row">';
    foreach($bs as $count=>$b){

        if($count>0 && fmod($count,3)==0){
            echo '</div><div class="row">';
        }

        echo '<div class="col-md-4 '.( count($bs)==1 ? 'col-md-offset-4' : ( count($bs)==2 && $count==0 ? 'col-md-offset-2' : '' ) ).'">
<div class="card card-product">
<div class="card-image"><a href="/'.$b['b_url_key'].'">'.$b['c__header_media'].'</a></div>
<div class="card-content">';


        echo '<h6 class="category text-muted">';

        echo '<span class="line_1" data-toggle="tooltip" data-placement="top" title="Complete '.( $b['c_level'] ? $b['c__child_child_count'] : $b['c__child_count'] ).' tasks totalling '.echo_hours($b['c__estimated_hours'],false).' anytime during this '.$b['b__week_count'].' week'.echo__s($b['b__week_count']).' Bootcamp"><i class="fas fa-clock"></i> '.$b['b__week_count'].' Week'.echo__s($b['b__week_count']).' @ '.echo_hours(($b['c__estimated_hours']/$b['b__week_count']),false).'/Week</span>';

        echo '</h6>';

        echo '<h4 class="card-title"><a href="/'.$b['b_url_key'].'">'.$b['c_outcome'].'</a></h4>';

        /*
        echo '<div class="card-description">';
        //Print lead admin:
        foreach($b['b__coaches'] as $admin){
            if($admin['ba_status']==3){
                echo '<span style="display:inline-block; width:100%;">By '.echo_cover($admin,'inline_block').' '.$admin['u_full_name'].'</span>';
            }
        }
        echo '</div>';
        */
        echo '</div>
</div>
</div>';
    }

    echo '</div>';
} else {
    //No Bootcamps, show message:
    echo '<div class="alert alert-info" style="margin:30px 0 100px; font-size:1.3em;"><i class="fas fa-bullhorn"></i> Bootcamps are cooking. If hungry you can <a href="https://m.me/askmench">connect to MenchBot</a> to be notified when food is ready.</div>';
}
?>
</div>
</div>




</div>
</div>

<div class="main main-raised main-plain main-footer">
    <div class="container">

        <?php $this->load->view('front/b/bs_include'); ?>
