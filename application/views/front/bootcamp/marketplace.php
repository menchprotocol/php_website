
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


<h1><?= $title ?></h1>
<p class="home_line_2">Learn in-demand skills from industry experts by completing weekly Bootcamps. Tuition ranges from <span style="display:inline-block;">$0-163 per week</span> based on <?= strtolower($this->lang->line('obj_rs_name')) ?> you choose. We <a href="https://support.mench.com/hc/en-us/articles/115002080031"><b>guarantee results</b></a> by refunding students who do the work but don't accomplish the Bootcamp outcome by the end of each week. New classes start every Monday at 00:00 PST. <span style="display:inline-block;">(in <span id="classes_start"></span>)</span></p>
<br />

<!-- <div class="col-sm-4"><?php // echo tree_menu(4793,array(4793)) ?></div> -->
<?php

//Fetch bs:
$bs = $this->Db_model->remix_bs(array(
    'b.b_status' => 3,
    'b.b_fp_id >' => 0,
    'b.b_old_format' => 0,
),array('ba','ihm'));

echo '<div class="row">';
foreach($bs as $count=>$b){

    if($count>0 && fmod($count,3)==0){
        echo '</div><div class="row">';
    }

    if($b['b_is_parent']){
        //Aggregate the data for all children:
        $b = b_aggregate($b);
    }

    echo '<div class="col-md-4">
<div class="card card-product">
<div class="card-image"><a href="/'.$b['b_url_key'].'">'.$b['c__header_media'].'</a></div>
<div class="card-content">';


    echo '<h6 class="category text-muted">';

        if($b['b_difficulty_level']>0){
            echo status_bible('df',$b['b_difficulty_level'],0,'top').' ';
        }

        echo '<span class="line_1" data-toggle="tooltip" data-placement="top" title="Complete '.$b['c__child_count'].' Task'.show_s($b['c__child_count']).' totalling '.format_hours($b['c__estimated_hours'],false).' anytime during this '.$b['b__week_count'].' week'.show_s($b['b__week_count']).' Bootcamp"><i class="fa fa-clock-o" aria-hidden="true"></i> '.format_hours($b['c__estimated_hours'],true).' IN '.$b['b__week_count'].' Week'.show_s($b['b__week_count']).'</span>';

    echo '</h6>';

    echo '<h4 class="card-title"><a href="/'.$b['b_url_key'].'">'.$b['c_objective'].'</a></h4>';
    echo '<div class="card-description">';
    //Print lead admin:
    foreach($b['b__admins'] as $admin){
        if($admin['ba_status']==3){
            echo '<span style="display:inline-block; width:100%;">By <img src="'.$admin['u_image_url'].'" style="display:inline-block;" /> '.$admin['u_fname'].' '.$admin['u_lname'].'</span>';
        }
    }
    echo '</div>';
    echo '</div>
</div>
</div>';
}

echo '</div>';
?>
</div>
</div>




</div>
</div>

<div>
    <div class="container">

        <?php $this->load->view('front/shared/bootcamps_include'); ?>
        <br /><br />
