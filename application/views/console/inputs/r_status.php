<div class="title"><h4><i class="fa fa-circle" aria-hidden="true"></i> Cohort Status</h4></div>
<ul>
	<li>Only displayed in landing page if status is <?= status_bible('r',1) ?>.</li>
	<li>If a cohort is full, the next published cohort would become open for admission.</li>
</ul>
<?php echo_status_dropdown('r','r_status',$r_status,( isset($removal_status) ? $removal_status : array() )); ?>