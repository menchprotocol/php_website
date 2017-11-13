<script>
$(document).ready(function() {
	//Watchout for the copy URL to clipboard:
    $( ".copy_button" ).click(function() {
    	copyToClipboard(document.getElementById("copy_button"));
    	$( ".copy_button" ).hide().fadeIn().css('color','#fedd16');
    });
});
</script>

<?php $sprint_units = $this->config->item('sprint_units'); ?>
<div class="dashboard">    
    
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
      <div class="col-sm-3"><a href="/console/<?= $bootcamp['b_id'] ?>/actionplan"><b><i class="fa fa-list-ol" aria-hidden="true"></i> Action Plan <i class="fa fa-angle-right" aria-hidden="true"></i></b></a></div>
      <div class="col-sm-9">
        <div>Bootcamp Objective: <?= $bootcamp['c_objective'] ?></div>
      	<div><?= count($bootcamp['c__child_intents']) ?> <?= $sprint_units[$bootcamp['b_sprint_unit']]['name'].' Milestone'.( count($bootcamp['c__child_intents'])==1 ? '' : 's' ) ?></div>
      	<div><?= $bootcamp['c__task_count'] ?> Task<?= ($bootcamp['c__task_count']==1?'':'s') ?> = <?= round($bootcamp['c__estimated_hours'],1) ?> Hours<?= ( count($bootcamp['c__child_intents'])>0 ? ' = '.round($bootcamp['c__estimated_hours']/count($bootcamp['c__child_intents'])).' Hours/'.ucwords($bootcamp['b_sprint_unit']) : '' ) ?></div>
      	<div><?= $bootcamp['c__insight_count'] ?> Insight<?= ($bootcamp['c__insight_count']==1?'':'s') ?></div>
      	<?php /* if(count($bootcamp['c__child_intents'])>0){ ?>
      	<div><a href="https://support.mench.co/hc/en-us/articles/115002372531" target="_blank"><u><?= number_format($bootcamp['c__estimated_hours']*60) ?> Points <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></u></a></div>
      	<div></div>
      	<?php } */ ?>      	
      </div>
    </div>
    <hr />
    
    <div class="row">
      <div class="col-sm-3"><a href="/console/<?= $bootcamp['b_id'] ?>/classes"><b><i class="fa fa-calendar" aria-hidden="true"></i> Classes <i class="fa fa-angle-right" aria-hidden="true"></i></b></a></div>
      <div class="col-sm-9">
      	<div><?= count($bootcamp['c__classes']) ?> Class<?= (count($bootcamp['c__classes'])==1?'':'es') ?></div>
      	<?php 
      	//Fetch class:
      	if(count($bootcamp['c__classes'])>0){
      	    $focus_class = filter_class($bootcamp['c__classes'],null);
      	    echo '<div>Next Starting Date: '.time_format($focus_class['r_start_date'],2).'</div>';
      	}
        ?>
      </div>
    </div>
    <hr />
    
    <div class="row">
      <div class="col-sm-3"><a href="/console/<?= $bootcamp['b_id'] ?>/students"><b><i class="fa fa-users" aria-hidden="true"></i> Students <i class="fa fa-angle-right" aria-hidden="true"></i></b></a></div>
      <div class="col-sm-9">
      	<div>0 Students</div>
      	<div>0 Pending Admission</div>
      	<div>0 With Pending Messages on <a href="#" data-toggle="modal" data-target="#MenchBotModal"><i class="fa fa-commenting" aria-hidden="true"></i> MenchBot</a></div>
      	<div>0 Late on Milestone Submissions</div>
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
      	<div>Instructor<?= ( count($bootcamp['b__admins'])==1 ? '' : 's' ) ?>: 
        	<?php 
        	foreach($bootcamp['b__admins'] as $key=>$instructor){
        	    if($key>0){
        	        echo ', ';
        	    }
        	    echo $instructor['u_fname'].' '.$instructor['u_lname'];
        	}
        	?></div>
        <?php $website = $this->config->item('website'); ?>
        <?php $url = $website['url'].'bootcamps/'.$bootcamp['b_url_key']; ?>
      	<div>Landing Page URL: <a href="<?= $url ?>" id="landing_page_url" target="_blank"><u><?= $url ?></u></a> <i class="fa fa-clone copy_button" aria-hidden="true" data-toggle="tooltip" title="Copy Landing Page URL into Clipboard"></i></div>
      	<div id="copy_button"><?= $url ?></div>
      	<div>Bootcamp Status: <?= status_bible('b',$bootcamp['b_status'],0,'top') ?></div>
      </div>
    </div>
    
</div>
