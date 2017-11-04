<?php $sprint_units = $this->config->item('sprint_units'); ?>
<div class="dashboard">

	<div class="row">
      <div class="col-sm-3"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> <b>Primary Goal</b></div>
      <div class="col-sm-9">
      	<p style="margin-bottom:0; font-size:1.4em; font-weight:bold;"><?= $bootcamp['c_objective'] ?></p>
      	<div><a href="/bootcamps/<?= $bootcamp['b_url_key'] ?>" target="_blank" style=""><u>Open Landing Page</u> <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></a></div>
      </div>
    </div>
    <hr />
    
    
    <?php $launch_status = calculate_bootcamp_status($bootcamp); ?>
    <div class="row">
      <div class="col-sm-3"><b><?= $launch_status['stage'] ?></b></div>
      <div class="col-sm-9">
      		<div><b><?= $launch_status['progress'] ?>% Ready</b></div>
      		<div class="progress">
            	<div class="progress-bar" role="progressbar" aria-valuenow="<?= $launch_status['progress'] ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $launch_status['progress'] ?>%;">
            	<span class="sr-only"><?= $launch_status['progress'] ?>% Complete</span>
            	</div>
            </div>
      		<ul style="margin:-10px 0 0 -15px; list-style:decimal;">
      			<?php 
      			foreach($launch_status['call_to_action'] as $action){
      			    echo '<li>'.$action.'</li>';
      			}
      			?>
      		</ul>
      </div>
    </div>
    <hr />
    
    
    <div class="row">
      <div class="col-sm-3"><a href="/console/<?= $bootcamp['b_id'] ?>/milestones"><b><i class="fa fa-flag" aria-hidden="true"></i> Milestones <i class="fa fa-angle-right" aria-hidden="true"></i></b></a></div>
      <div class="col-sm-9">
      	<div><?= count($bootcamp['c__child_intents']) ?> <?= $sprint_units[$bootcamp['b_sprint_unit']]['name'].' Milestone'.( count($bootcamp['c__child_intents'])==1 ? '' : 's' ) ?></div>
      	<div><?= $bootcamp['c__task_count'] ?> Total Task<?= ($bootcamp['c__task_count']==1?'':'s') ?></div>
      	<div><?= $bootcamp['c__tip_count'] ?> Total Tip<?= ($bootcamp['c__tip_count']==1?'':'s') ?></div>
      	
      	<?php if(count($bootcamp['c__child_intents'])>0){ ?>
      	<div><?= round($bootcamp['c__estimated_hours'],1) ?> Total Hours = <a href="https://support.mench.co/hc/en-us/articles/115002372531" target="_blank"><u><?= number_format($bootcamp['c__estimated_hours']*60) ?> Points <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></u></a></div>
      	<div><?= round($bootcamp['c__estimated_hours']/count($bootcamp['c__child_intents'])) ?> Hours/<?= ucwords($bootcamp['b_sprint_unit']) ?> Average</div>
      	<?php } ?>
      	
      </div>
    </div>
    <hr />
    
    <div class="row">
      <div class="col-sm-3"><a href="/console/<?= $bootcamp['b_id'] ?>/cohorts"><b><i class="fa fa-calendar" aria-hidden="true"></i> Cohorts <i class="fa fa-angle-right" aria-hidden="true"></i></b></a></div>
      <div class="col-sm-9">
      	<div><?= count($bootcamp['c__cohorts']) ?> Total Cohorts</div>
      	<?php 
      	//Fetch cohort:
      	if(count($bootcamp['c__cohorts'])>0){
      	    $next_cohort = filter_next_cohort($bootcamp['c__cohorts']);
      	    echo '<div>'.time_format($next_cohort['r_start_date'],1).' is next</div>';
      	}
        ?>
      </div>
    </div>
    <hr />
    
    <div class="row">
      <div class="col-sm-3"><a href="/console/<?= $bootcamp['b_id'] ?>/students"><b><i class="fa fa-users" aria-hidden="true"></i> Students <i class="fa fa-angle-right" aria-hidden="true"></i></b></a></div>
      <div class="col-sm-9">
      	<div>0 Total Students</div>
      	<div>0 Pending Admission</div>
      	<div>0 With Pending Messages on <a href="#" data-toggle="modal" data-target="#MenchBotModal"><i class="fa fa-commenting" aria-hidden="true"></i> MenchBot</a></div>
      	<div>0 Late on Milestones</div>
	  </div>
    </div>
    <hr />
    
    <?php /*
    <div class="row">
      <div class="col-sm-3"><a href="/console/<?= $bootcamp['b_id'] ?>/stream"><b><i class="material-icons">forum</i> Activity Stream <i class="fa fa-angle-right" aria-hidden="true"></i></b></a></div>
      <div class="col-sm-9">
      	<div>0 Total</div>
      	<div>0 New</div>
      </div>
    </div>
    <hr />
    */?>
    
    <div class="row">
      <div class="col-sm-3"><a href="/console/<?= $bootcamp['b_id'] ?>/settings"><b><i class="material-icons">settings</i> Settings <i class="fa fa-angle-right" aria-hidden="true"></i></b></a></div>
      <div class="col-sm-9">
      	<div>Lead Instructor: <?= $bootcamp['b__admins'][0]['u_fname'].' '.$bootcamp['b__admins'][0]['u_lname'] ?></div>
      	<div><?= (count($bootcamp['b__admins'])-1) ?> Co-Instructor<?= ((count($bootcamp['b__admins'])-1)==1 ? '' : 's') ?></div>
      	<?= status_bible('b',$bootcamp['b_status']) ?>
      </div>
    </div>
    
</div>
