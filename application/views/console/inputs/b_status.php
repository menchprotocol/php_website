<div class="title" style="margin-top:0;"><h4><i class="fa fa-circle" aria-hidden="true"></i> Bootcamp Status</h4></div>
<ul>
	<li>Bootcamps are created in <?= status_bible('b',0) ?> mode to give you time to build them.</li>
	<li>When you're ready to publish you update the status to <?= status_bible('b',1) ?>.</li>
	<li>We will start our review process & work with you to iteratively improve it.</li>
	<li>Once ready & approved we update the status to <?= status_bible('b',3) ?>.</li>
	<li>You may also get your bootcamp published with the status <?= status_bible('b',2) ?>.</li>
</ul>
<?php echo_status_dropdown('b','b_status',$b_status); ?>
<div style="clear:both; margin:0; padding:0;"></div>