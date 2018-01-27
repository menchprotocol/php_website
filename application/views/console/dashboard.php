<?php
$sprint_units = $this->config->item('sprint_units');
$mench_advisers = $this->config->item('mench_advisers');
?>

<script>
$(document).ready(function() {
    $(".marketplace_b_url").click(function () {
        copyToClipboard(document.getElementById("marketplace_b_url"));
        $(".marketplace_b_url").addClass('copy-btn-done');
    });
});
</script>

<div class="maxout">

<div class="help_body below_h" id="content_2273"></div>


<?php
$website = $this->config->item('website');
if(isset($bootcamp['c__milestone_units']) && $bootcamp['c__milestone_units'] > 0){
	$rounded_hours = round($bootcamp['c__estimated_hours']/$bootcamp['c__milestone_units'] , 1);
} else {
	$rounded_hours = 0;
}
echo '<div id="marketplace_b_url" style="display:none;">'.$website['url'].$bootcamp['b_url_key'].'</div>';
?>
<div class="title"><h4><a href="/console/<?= $bootcamp['b_id'] ?>/actionplan" class="badge badge-primary badge-msg"><i class="fa fa-list-ol" aria-hidden="true"></i> Action Plan <i class="fa fa-arrow-right" aria-hidden="true"></i></a> <span id="hb_2272" class="help_button" intent-id="2272"></span></h4></div>
<div class="help_body maxout" id="content_2272"></div>
<div><i class="fa fa-dot-circle-o" aria-hidden="true"></i> <b id="b_objective"><?= $bootcamp['c_objective'] ?></b></div>
<div><?= count($bootcamp['c__active_intents']) .' Milestone'.( count($bootcamp['c__active_intents'])==1 ? '' : 's' ).' in '.$bootcamp['c__milestone_units'].' '.ucwords($bootcamp['b_sprint_unit']).($bootcamp['c__milestone_units']==1?'':'s') ?></div>
<div>
    <?php
    echo $bootcamp['c__task_count'] .' Task'.($bootcamp['c__task_count']==1?'':'s');
    echo ' = '. round($bootcamp['c__estimated_hours'],1) .' Hours';
    echo ( $bootcamp['c__milestone_units']>0 ? '<div>'.$rounded_hours.' Hour'.($rounded_hours==1?'':'s').'/'.ucwords($bootcamp['b_sprint_unit']).'</div>' : '' );
    ?>
</div>
    <div><?= $bootcamp['c__message_tree_count'] ?> Message<?= ($bootcamp['c__message_tree_count']==1?'':'s') ?></div>

<div style="margin-left:-5px"><?= status_bible('b',$bootcamp['b_status'],0,'right') ?></div>
    <div><i class="fa fa-link" style="font-size:15px;" aria-hidden="true"></i> <a href="/<?= $bootcamp['b_url_key'] ?>"><u><?= $website['url'] ?><b><?= $bootcamp['b_url_key'] ?></b></u></a> <a href="#" style="margin-top: 10px;" class="btn btn-sm btn-default marketplace_b_url copy-btn">Copy&nbsp;<i class="fa fa-clone" style="font-size:1em;" aria-hidden="true"></i></a> <span id="hb_725" class="help_button" intent-id="725"></span></div>
    <div class="help_body maxout" id="content_725"></div>
    <?php /* if(count($bootcamp['c__child_intents'])>0){ ?>
<div><a href="https://support.mench.co/hc/en-us/articles/115002372531" target="_blank"><u><?= number_format($bootcamp['c__estimated_hours']*60) ?> Points <i class="fa fa-external-link-square" style="font-size: 0.8em;" aria-hidden="true"></i></u></a></div>
<div></div>
<?php } */ ?>










<div class="title" style="margin-top:40px;"><h4><a href="/console/<?= $bootcamp['b_id'] ?>/classes" class="badge badge-primary badge-msg"><b><i class="fa fa-calendar" aria-hidden="true"></i> Classes <i class="fa fa-arrow-right" aria-hidden="true"></i></b></a> <span id="hb_2274" class="help_button" intent-id="2274"></span></h4></div>
<div class="help_body maxout" id="content_2274"></div>
<div><?= count($bootcamp['c__classes']) ?> Class<?= (count($bootcamp['c__classes'])==1?'':'es') ?></div>
<?php 
//Fetch class:
$focus_class = filter_class($bootcamp['c__classes'],null);
if($focus_class){
    echo '<div>Next Starting Date: '.time_format($focus_class['r_start_date'],2).'</div>';
} elseif(count($bootcamp['c__classes'])>0){
    echo '<div>None Open for Admission Yet</div>';
}
?>







<div class="title" style="margin-top:40px;"><h4><a href="/console/<?= $bootcamp['b_id'] ?>/students" class="badge badge-primary badge-msg"><b><i class="fa fa-users" aria-hidden="true"></i> Students <i class="fa fa-arrow-right" aria-hidden="true"></i></b></a> <span id="hb_2275" class="help_button" intent-id="2275"></span></h4></div>
    <div class="help_body maxout" id="content_2275"></div>
<?php 
//Fetch admission stats:

$student_funnel = array(
    0 => count($this->Db_model->ru_fetch(array(
        'r.r_b_id'	       => $bootcamp['b_id'],
        'r.r_status >='	   => 1, //Open for admission and up
        'ru.ru_status'     => 0,
    ))),
    2 => count($this->Db_model->ru_fetch(array(
        'r.r_b_id'	       => $bootcamp['b_id'],
        'r.r_status >='	   => 1, //Open for admission and up
        'ru.ru_status'     => 2,
    ))),
    -1 => count($this->Db_model->ru_fetch(array(
        'r.r_b_id'	       => $bootcamp['b_id'],
        'r.r_status >='	   => 1, //Open for admission and up
        'ru.ru_status <'   => 0, //Anyone rejected/withdrew/dispelled
    ))),
    4 => count($this->Db_model->ru_fetch(array(
        'r.r_b_id'	       => $bootcamp['b_id'],
        'r.r_status >='	   => 1, //Open for admission and up
        'ru.ru_status'     => 4,
    ))),
    7 => count($this->Db_model->ru_fetch(array(
        'r.r_b_id'	       => $bootcamp['b_id'],
        'r.r_status >='	   => 1, //Open for admission and up
        'ru.ru_status'     => 7,
    ))),
);

foreach($student_funnel as $ru_status=>$count){
    echo '<div><span style="width:40px; display:inline-block">'.$count.'</span>'.status_bible('ru',$ru_status).'</div>';
}
?>



<div class="title" style="margin-top:40px;"><h4><a href="/console/<?= $bootcamp['b_id'] ?>/team" class="badge badge-primary badge-msg"><b><i class="fa fa-user-plus" aria-hidden="true"></i> Team <i class="fa fa-arrow-right" aria-hidden="true"></i></b></a> <span id="hb_629" class="help_button" intent-id="629"></span></h4></div>
    <div class="help_body maxout" id="content_629"></div>
<div>
	<?php
    $mench_advisers = $this->config->item('mench_advisers');
    $total_advisers = count($mench_advisers);
    foreach($bootcamp['b__admins'] as $admin){
        if(in_array($admin['u_id'],$mench_advisers)){
            $total_advisers--;
        }
    }




	foreach($bootcamp['b__admins'] as $key=>$instructor){
	    if($key>0){
	        echo ', ';
	    }
	    echo $instructor['u_fname'].' '.$instructor['u_lname'];
	}
	if($total_advisers>0){
        echo ' + '.$total_advisers.' Adviser'.( $total_advisers ==1? '' : 's');
    }
	?></div>
</div>














<?php $launch_status = calculate_bootcamp_status($bootcamp); ?>
<div class="title" style="margin-top:40px;"><h4><?= $launch_status['stage'] ?> <span id="hb_1511" class="help_button" intent-id="1511"></span></h4></div>
<div class="help_body maxout" id="content_1511"></div>
<div>Complete this checklist to prepare your Bootcamp for launch:</div>
<div class="progress maxout">
    <div class="progress-bar" role="progressbar" aria-valuenow="<?= $launch_status['progress'] ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $launch_status['progress'] ?>%;">
        <span class="progress-value"><?= $launch_status['progress'] ?>% Complete</span>
    </div>
</div>
<?php
//Display the checklist:
$count_done = 0;
$check_list = '';
foreach($launch_status['check_list'] as $item){
    $check_list .= echo_checklist($item['href'],$item['anchor'],$item['us_status'],$item['time_min']);
    if($item['us_status']){
        $count_done++;
    }
}
echo '<div id="list-checklist" class="list-group maxout">';
echo $check_list;
echo '</div>';
if($count_done>0){
    echo '<div class="toggle-done"><a href="javascript:void(0)" onclick="$(\'.checklist-done\').toggleClass(\'checklist-done-see\')"><i class="fa fa-check-square initial"></i> &nbsp;Toggle '.$count_done.' Completed Tasks</a></div>';
}
if($launch_status['progress']==100){
    echo '<p>'.$launch_status['completion_message'].'</p>';
}
?>
