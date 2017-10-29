<div class="dashboard">

	<div class="row">
      <div class="col-sm-3"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> <b>Primary Goal</b></div>
      <div class="col-sm-9"><?= $bootcamp['c_objective'] ?></div>
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
      <div class="col-sm-3"><a href="/console/<?= $bootcamp['b_id'] ?>/actionplan"><b><i class="material-icons">format_list_numbered</i> Action Plan <i class="fa fa-angle-right" aria-hidden="true"></i></b></a></div>
      <div class="col-sm-9">
      	<div><?= count($bootcamp['c__child_intents']) ?> <?= ucwords($bootcamp['b_sprint_unit']).( count($bootcamp['c__child_intents'])==1 ? '' : 's' ) ?></div>
      	<div><?= $bootcamp['c__task_count'] ?> Task<?= ($bootcamp['c__task_count']==1?'':'s') ?></div>
      	<div><?= $bootcamp['c__tip_count'] ?> Tip<?= ($bootcamp['c__tip_count']==1?'':'s') ?></div>
      	
      	<?php if(count($bootcamp['c__child_intents'])>0){ ?>
      	<div><?= round($bootcamp['c__estimated_hours'],1) ?> Hours = <?= number_format($bootcamp['c__estimated_hours']*60) ?> Points</div>
      	<div><?= round($bootcamp['c__estimated_hours']/count($bootcamp['c__child_intents'])) ?> Hours/<?= ucwords($bootcamp['b_sprint_unit']) ?></div>
      	<?php } ?>
      	
      </div>
    </div>
    <hr />
    
    <div class="row">
      <div class="col-sm-3"><a href="/console/<?= $bootcamp['b_id'] ?>/cohorts"><b><i class="fa fa-calendar" aria-hidden="true"></i> Cohorts <i class="fa fa-angle-right" aria-hidden="true"></i></b></a></div>
      <div class="col-sm-9">
      	<div><?= count($bootcamp['c__cohorts']) ?> Total</div>
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
      	<div>0 Total</div>
      	<div>0 Pending Admission</div>
      	<div>0 Asked For Help</div>
      	<div>0 Late on Action Plan</div>
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
      	<div><?= count($bootcamp['b__admins']) ?> Team Member<?= (count($bootcamp['b__admins'])>1 ? 's' : '') ?></div>
      	<?= status_bible('b',$bootcamp['b_status']) ?>
      </div>
    </div>
    
</div>
