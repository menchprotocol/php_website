<?php
$sprint_units = $this->config->item('sprint_units');
?>
<div class="dashboard">
    <div class="row">
      <div class="col-sm-3"><a href="/console/<?= $bootcamp['b_id'] ?>/actionplan"><b><i class="material-icons">format_list_numbered</i> Action Plan <i class="fa fa-angle-right" aria-hidden="true"></i></b></a></div>
      <div class="col-sm-9">
      	<div><?= count($bootcamp['c__child_intents']) ?> <?= ucwords($bootcamp['b_sprint_unit']).( count($bootcamp['c__child_intents'])==1 ? '' : 's' ) ?></div>
      	<div><?= $bootcamp['c__task_count'] ?> Tasks</div>
      	<?= ( count($bootcamp['c__child_intents'])>0 ? '<div>'.round($bootcamp['c__estimated_hours'],1).' Hours</div><div>'.round($bootcamp['c__estimated_hours']/count($bootcamp['c__child_intents'])).' Hours/'.ucwords($bootcamp['b_sprint_unit']).'</div>' : '' ) ?>
      </div>
    </div>
    <hr />
    <div class="row">
      <div class="col-sm-3"><a href="/console/<?= $bootcamp['b_id'] ?>/cohorts"><b><i class="fa fa-calendar" aria-hidden="true"></i> Cohorts <i class="fa fa-angle-right" aria-hidden="true"></i></b></a></div>
      <div class="col-sm-9">
      	<div><?= count($bootcamp['c__cohorts']) ?> Total</div>
      	<?= ( count($bootcamp['c__cohorts'])>0 ? '<div>'.time_format($bootcamp['c__cohorts'][0]['r_start_date'],1).' is next</div>' : '' )  ?>
      </div>
    </div>
    <hr />
    <div class="row">
      <div class="col-sm-3"><a href="/console/<?= $bootcamp['b_id'] ?>/students"><b><i class="fa fa-users" aria-hidden="true"></i> Students <i class="fa fa-angle-right" aria-hidden="true"></i></b></a></div>
      <div class="col-sm-9">
      	<div>0 Total</div>
      	<div>0 Pending Admission</div>
      	<div>0 Asked For Help</div>
      	<div>0 Late on Assignments</div>
	  </div>
    </div>
    <hr />
    <div class="row">
      <div class="col-sm-3"><a href="/console/<?= $bootcamp['b_id'] ?>/stream"><b><i class="material-icons">forum</i> Activity <i class="fa fa-angle-right" aria-hidden="true"></i></b></a></div>
      <div class="col-sm-9">
      	<div>0 Total</div>
      	<div>0 New</div>
      </div>
    </div>
    <hr />
    <div class="row">
      <div class="col-sm-3"><a href="/console/<?= $bootcamp['b_id'] ?>/settings"><b><i class="material-icons">settings</i> Settings <i class="fa fa-angle-right" aria-hidden="true"></i></b></a></div>
      <div class="col-sm-9">
      	<div><?= count($bootcamp['b__admins']) ?> Admin<?= (count($bootcamp['b__admins'])>1 ? 's' : '') ?></div>
      	<?= status_bible('b',$bootcamp['b_status']) ?>
      </div>
    </div>
    
    
    
</div>
