<?php
$mench_support_team = $this->config->item('mench_support_team');
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

$total_goals = count($b['c__active_intents']) + echo__s($b['c__child_child_count']) + $b['c__child_child_count'];

echo '<div id="marketplace_b_url" style="display:none;">'.$website['url'].$b['b_url_key'].'</div>';
?>
<div class="title"><h4><a href="/console/<?= $b['b_id'] ?>/actionplan" class="badge badge-primary badge-msg"><i class="fas fa-flag"></i> Action Plan <i class="fas fa-arrow-right"></i></a> <span id="hb_2272" class="help_button" intent-id="2272"></span></h4></div>
<div class="help_body maxout" id="content_2272"></div>

<?php

echo '<div class="dash-label"><span class="icon-left"><i class="fas fa-clock"></i></span> '.$b['b__week_count'].' Week'.echo__s($b['b__week_count']).' @ '.echo_hours($b['c__estimated_hours']/(( $b['b_is_parent'] && count($b['c__child_intents'])>0 ? count($b['c__child_intents']) : 1 ))).'/Week</div>';

if($b['b_is_parent']) {

    //Show total tasks:
    echo '<div class="dash-label"><span class="icon-left"><i class="fas fa-clipboard-check"></i></span> ' . $b['c__child_child_count'] . ' Task' . echo__s($b['c__child_child_count']) . '</div>';

} else {
    //Total Tasks for weekly Bootcamps:
    echo '<div class="dash-label"><span class="icon-left"><i class="fas fa-clipboard-check"></i></span> '.$b['c__child_count'].' Task'.echo__s($b['c__child_count']) .'</div>';
    if($b['c__child_child_count']>0){
        echo '<div class="dash-label"><span class="icon-left"><i class="fal fa-clipboard-check"></i></span> '.$b['c__child_child_count'].' Step'.echo__s($b['c__child_child_count']).'</div>';
    }
}

echo ' <div class="dash-label"><span class="icon-left"><i class="fas fa-comment-dots"></i></span> '.$b['c__message_tree_count'].' Message'. echo__s($b['c__message_tree_count']).'</div>';

?>






<?php

echo '<div class="title" style="margin-top:40px;"><h4><a href="/console/'.$b['b_id'].'/classes" class="badge badge-primary badge-msg"><b><i class="fas fa-users"></i> Classes <i class="fas fa-arrow-right"></i></b></a> <span id="hb_2274" class="help_button" intent-id="2274"></span></h4></div><div class="help_body maxout" id="content_2274"></div>';

//Fetch admission stats:
$student_funnel = array(
    0 => count($this->Db_model->ru_fetch(array(
        'ru.ru_b_id'	   => $b['b_id'],
        'ru.ru_status'     => 0,
    ))),
    4 => count($this->Db_model->ru_fetch(array(
        'ru.ru_b_id'	   => $b['b_id'],
        'ru.ru_status'     => 4,
    ))),
    6 => count($this->Db_model->ru_fetch(array(
        'ru.ru_b_id'       => $b['b_id'],
        'ru.ru_status'     => 6,
    ))),
    7 => count($this->Db_model->ru_fetch(array(
        'ru.ru_b_id'	   => $b['b_id'],
        'ru.ru_status'     => 7,
    ))),
);

//Show current funnel
foreach($student_funnel as $ru_status=>$count){
    echo '<div><span class="stat-num">'.$count.'</span>'.echo_status('ru',$ru_status).'</div>';
}
?>



<div class="title" style="margin-top:40px;"><h4><a href="/console/<?= $b['b_id'] ?>/settings" class="badge badge-primary badge-msg"><b><i class="fas fa-cog"></i> Settings <i class="fas fa-arrow-right"></i></b></a></h4></div>

<?php
echo '<div>Coaches: ';
foreach($b['b__admins'] as $key=>$instructor){
    if($key>0){
        echo ', ';
    }
    echo $instructor['u_full_name'];
}
echo '</div>';
?>
<div style="margin-top:-5px;">Landing Page: <a href="/<?= $b['b_url_key'] ?>"><u><?= $website['url'] . $b['b_url_key'] ?></u></a> <a href="#" class="btn btn-sm btn-default marketplace_b_url copy-btn">Copy&nbsp;<i class="fas fa-clone" style="font-size:1em;"></i></a></div>
<div style="margin-top:-5px;">Publish Status: <?= echo_status('b',$b['b_status'],0,'right') ?></div>






<?php
if(!$b['b_is_parent']){


    //Show Potential parent Bootcamps:
    $parent_bs = $this->Db_model->cr_inbound_fetch(array(
        'cr.cr_outbound_b_id' => $b['b_id'],
        'cr.cr_status >=' => 1,
        'b.b_status >=' => 2, //Published in some way
    ),array('b'));


    //Did we find anything?
    if(count($parent_bs)>0){
        echo '<div class="title" style="margin-top:30px;"><h4><b><i class="fas fa-cubes"></i> Parent Bootcamps</b></a></h4></div>';
        echo '<div class="list-group maxout">';
        foreach ($parent_bs as $parent_b){
            echo '<a href="/console/'.$parent_b['b_id'].'/actionplan" class="list-group-item">';
            echo '<span class="pull-right"><span class="badge badge-primary" style="margin-top:-5px;"><i class="fas fa-chevron-right"></i></span></span>';
            echo '<i class="fas fa-cubes"></i> ';
            echo $parent_b['c_outcome'];
            echo '</a>';
        }
        echo '</div>';
    }
}
?>



<?php $launch_status = b_progress($b); ?>
<div class="title" style="margin-top:40px;"><h4><?= $launch_status['stage'] ?> <span id="hb_1511" class="help_button" intent-id="1511"></span></h4></div>
<div class="help_body maxout" id="content_1511"></div>
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
    $check_list .= echo_checklist($item['href'],$item['anchor'],$item['e_status'],$item['time_min']);
    if($item['e_status'] /*Auto Verified*/){
        $count_done++;
    }
}
echo '<div id="list-checklist" class="list-group maxout">';
echo $check_list;
echo '</div>';
if($count_done>0){
    echo '<div class="toggle-done"><a href="javascript:void(0)" onclick="$(\'.checklist-done\').toggleClass(\'checklist-done-see\')"><i class="fas fa-check-circle initial"></i> &nbsp;Toggle '.$count_done.' Completed Steps</a></div>';
}
?>
