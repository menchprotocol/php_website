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
$rounded_hours = round($b['c__estimated_hours']/7 , ( $b['c__estimated_hours']<=18 ? 1 : 0 ));

echo '<div id="marketplace_b_url" style="display:none;">'.$website['url'].$b['b_url_key'].'</div>';
?>
<div class="title"><h4><a href="/console/<?= $b['b_id'] ?>/actionplan" class="badge badge-primary badge-msg"><i class="fa fa-list-ol" aria-hidden="true"></i> Action Plan <i class="fa fa-arrow-right" aria-hidden="true"></i></a> <span id="hb_2272" class="help_button" intent-id="2272"></span></h4></div>
<div class="help_body maxout" id="content_2272"></div>
<div><i class="fa fa-dot-circle-o" aria-hidden="true"></i> <b id="b_objective"><?= $b['c_objective'] ?></b></div>
<div><?= count($b['c__active_intents']) .' Task'.show_s(count($b['c__active_intents'])) ?></div>

<?php if($b['c__steps_count']>0){ ?>
    <div><?= $b['c__steps_count'] .' Step'.show_s($b['c__steps_count']) ?></div>
<?php } ?>

<div><?= $b['c__message_tree_count'] .' Message'. show_s($b['c__message_tree_count']) ?></div>
<div><?= round($b['c__estimated_hours'],1) .' Hours in 7 Days ('.$rounded_hours.' Hour'.show_s($rounded_hours).'/Day)' ?></div>





<div class="title" style="margin-top:40px;"><h4><a href="/console/<?= $b['b_id'] ?>/classes" class="badge badge-primary badge-msg"><b><i class="fa fa-users" aria-hidden="true"></i> Classes <i class="fa fa-arrow-right" aria-hidden="true"></i></b></a> <span id="hb_2274" class="help_button" intent-id="2274"></span></h4></div>
<div class="help_body maxout" id="content_2274"></div>
<?php
//Fetch admission stats:
$student_funnel = array(
    0 => count($this->Db_model->ru_fetch(array(
        'r.r_b_id'	       => $b['b_id'],
        'ru.ru_status'     => 0,
    ))),
    4 => count($this->Db_model->ru_fetch(array(
        'r.r_b_id'	       => $b['b_id'],
        'ru.ru_status'     => 4,
    ))),
    6 => count($this->Db_model->ru_fetch(array(
        'r.r_b_id'	       => $b['b_id'],
        'ru.ru_status'     => 6,
    ))),
    7 => count($this->Db_model->ru_fetch(array(
        'r.r_b_id'	       => $b['b_id'],
        'ru.ru_status'     => 7,
    ))),
);

foreach($student_funnel as $ru_status=>$count){
    echo '<div><span style="width:40px; display:inline-block">'.$count.'</span>'.status_bible('ru',$ru_status).'</div>';
}

//Fetch class:
$focus_class = filter_class($b['c__classes'],null);
if($focus_class){
    echo '<div>Next Class: '.time_format($focus_class['r_start_date'],2).'</div>';
}

?>



<div class="title" style="margin-top:40px;"><h4><a href="/console/<?= $b['b_id'] ?>/settings" class="badge badge-primary badge-msg"><b><i class="fa fa-cog" aria-hidden="true"></i> Settings <i class="fa fa-arrow-right" aria-hidden="true"></i></b></a></h4></div>

<?php
echo '<div>Team: ';
$mench_support_team = $this->config->item('mench_support_team');
$total_advisers = count($mench_support_team);
foreach($b['b__admins'] as $admin){
    if(in_array($admin['u_id'],$mench_support_team)){
        $total_advisers--;
    }
}

foreach($b['b__admins'] as $key=>$instructor){
    if($key>0){
        echo ', ';
    }
    echo $instructor['u_fname'].' '.$instructor['u_lname'];
}
if($total_advisers>0){
    echo ' + '.$total_advisers.' Adviser'.( $total_advisers ==1? '' : 's');
}
echo '</div>';
?>
<div style="margin-top:-5px;">Landing Page URL: <a href="/<?= $b['b_url_key'] ?>"><u><?= $website['url'] . $b['b_url_key'] ?></u></a> <a href="#" class="btn btn-sm btn-default marketplace_b_url copy-btn">Copy&nbsp;<i class="fa fa-clone" style="font-size:1em;" aria-hidden="true"></i></a></div>
<div style="margin-top:-5px;">Bootcamp Status: <?= status_bible('b',$b['b_status'],0,'right') ?></div>
<div style="margin-top:5px;">Facebook Page: <?= ( $b['b_fp_id']>0 ? '<a href="https://www.facebook.com/'.$b['fp_fb_id'].'"><u>'.$b['fp_name'].'</u></a>' : 'None! <a href="/console/'.$b['b_id'].'/settings#pages"><u>Connected Now &raquo;</u></a>' ) ?></div>













<?php $launch_status = calculate_project_status($b); ?>
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
    echo '<div class="toggle-done"><a href="javascript:void(0)" onclick="$(\'.checklist-done\').toggleClass(\'checklist-done-see\')"><i class="fa fa-check-square initial"></i> &nbsp;Toggle '.$count_done.' Completed Steps</a></div>';
}
if($launch_status['progress']==100){
    echo '<p>'.$launch_status['completion_message'].'</p>';
}
?>
