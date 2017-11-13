<div class="title"><h4><i class="fa fa-circle" aria-hidden="true"></i> Class Status</h4></div>
<ul>
	<li>Only displayed in landing page if status is <?= status_bible('r',1) ?>.</li>
</ul>
<?php echo_status_dropdown('r','r_status',$r_status,( isset($removal_status) ? $removal_status : array() )); ?>