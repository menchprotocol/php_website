<div class="title"><h4><i class="fa fa-gift" aria-hidden="true"></i> Completion Prizes</h4></div>
<ul>
	<li>Awarded to students who complete all milestones by the last day of the cohort.</li>
	<li>Prizes are an additional incentive to increase your bootcamp's completion rates.</li>
	<li>Completion prizes are published on your landing page's Admission section.</li>
	<li>Empty this field to blank if you wish not to award any prizes.</li>
</ul>
<div id="r_completion_prizes"><?= ( isset($r_completion_prizes) ? $r_completion_prizes : null ) ?></div>
<script> var r_completion_prizes_quill = new Quill('#r_completion_prizes', setting_listo); </script>