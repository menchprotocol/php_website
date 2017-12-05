<?php $sprint_units = $this->config->item('sprint_units'); ?>

<div class="maxout">

<div class="help_body below_h" id="content_597"></div>


<?php $launch_status = calculate_bootcamp_status($bootcamp); ?>
<div class="title" style="margin-top:30px;"><h4><?= $launch_status['stage'] ?></h4></div>
<div><b><?= $launch_status['progress'] ?>% Ready</b>, Complete the following checklist to make your Bootcamp ready for launch:</div>
<div class="progress">
	<div class="progress-bar" role="progressbar" aria-valuenow="<?= $launch_status['progress'] ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $launch_status['progress'] ?>%;">
	<span class="sr-only"><?= $launch_status['progress'] ?>% Complete</span>
	</div>
</div>
<ul style="margin:-10px 0 0 -10px; list-style:decimal;">
	<?php 
	foreach($launch_status['call_to_action'] as $action){
	    echo '<li>'.$action.'</li>';
	}
	?>
</ul>
<hr />

<div class="title" style="margin-top:30px;"><h4><a href="/console/<?= $bootcamp['b_id'] ?>/actionplan"><i class="fa fa-list-ol" aria-hidden="true"></i> Action Plan <i class="fa fa-angle-right" aria-hidden="true"></i></a></h4></div>
<div><i class="fa fa-dot-circle-o" aria-hidden="true"></i> <?= $bootcamp['c_objective'] ?></div>
<div><?= count($bootcamp['c__child_intents']) ?> <?= $sprint_units[$bootcamp['b_sprint_unit']]['name'].' Milestone'.( count($bootcamp['c__child_intents'])==1 ? '' : 's' ) ?></div>
<div><?= $bootcamp['c__task_count'] ?> Task<?= ($bootcamp['c__task_count']==1?'':'s') ?> = <?= round($bootcamp['c__estimated_hours'],1) ?> Hours<?= ( count($bootcamp['c__child_intents'])>0 ? ' = '.round($bootcamp['c__estimated_hours']/count($bootcamp['c__child_intents'])).' Hours/'.ucwords($bootcamp['b_sprint_unit']) : '' ) ?></div>
<div><?= $bootcamp['c__message_tree_count'] ?> Message<?= ($bootcamp['c__message_tree_count']==1?'':'s') ?></div>
<?php /* if(count($bootcamp['c__child_intents'])>0){ ?>
<div><a href="https://support.mench.co/hc/en-us/articles/115002372531" target="_blank"><u><?= number_format($bootcamp['c__estimated_hours']*60) ?> Points <i class="fa fa-external-link-square" style="font-size: 0.8em;" aria-hidden="true"></i></u></a></div>
<div></div>
<?php } */ ?>
<hr />


<div class="title" style="margin-top:30px;"><h4><a href="/console/<?= $bootcamp['b_id'] ?>/classes"><b><i class="fa fa-calendar" aria-hidden="true"></i> Classes <i class="fa fa-angle-right" aria-hidden="true"></i></b></a></h4></div>
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
<hr />



<div class="title" style="margin-top:30px;"><h4><a href="/console/<?= $bootcamp['b_id'] ?>/students"><b><i class="fa fa-users" aria-hidden="true"></i> Students <i class="fa fa-angle-right" aria-hidden="true"></i></b></a></h4></div>
<?php 
//Fetch admission stats:
$admissions_accepted = $this->Db_model->ru_fetch(array(
  	'r.r_b_id'	       => $bootcamp['b_id'],
  	'r.r_status >='	   => 1, //Open for admission and up
  	'ru.ru_status >='  => 4, //Enrolled & Graduated
));
$admissions_pending = $this->Db_model->ru_fetch(array(
    'r.r_b_id'	       => $bootcamp['b_id'],
    'r.r_status >='	   => 1, //Open for admission and up
    'ru.ru_status <'   => 4,
    'ru.ru_status >='  => 0,
));
?>
<div><?= count($admissions_accepted) ?> Students</div>
<div><?= count($admissions_pending) ?> Pending Admission</div>
<hr />



<div class="title" style="margin-top:30px;"><h4><a href="/console/<?= $bootcamp['b_id'] ?>/settings"><b><i class="material-icons">settings</i> Settings <i class="fa fa-angle-right" aria-hidden="true"></i></b></a></h4></div>
<div>Instructor<?= ( count($bootcamp['b__admins'])==1 ? '' : 's' ) ?>:
	<?php 
	foreach($bootcamp['b__admins'] as $key=>$instructor){
	    if($key>0){
	        echo ', ';
	    }
	    echo $instructor['u_fname'].' '.$instructor['u_lname'];
	}
	?></div>
<div>Bootcamp Status: <?= status_bible('b',$bootcamp['b_status'],0,'top') ?></div>


    
</div>
