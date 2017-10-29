<script>
function reset_default(){
	<?php $default_cohort_questions = $this->config->item('default_cohort_questions'); ?>
	$('#r_application_questions .ql-editor').html('<ol><li><?= join('</li><li>',$default_cohort_questions) ?></li></ol>');
	alert('Questions reset successful. Remember to save your changes.');
}
</script>
<div class="title"><h4><i class="fa fa-question-circle" aria-hidden="true"></i> Application Questions</h4></div>
<ul>
	<li>Open-ended questions you'd like to ask students during their application.</li>
	<li>Useful to assess student's desire level and suitability for this bootcamp.</li>
	<li>Include one question per point & we'll ask them in the same order.</li>
    <!-- <li>You can always <b><a href="javascript:reset_default();">reset to default questions</a></b> (and save).</li> -->
</ul>
<div id="r_application_questions"><?= ( isset($r_application_questions) ? $r_application_questions : null ) ?></div>
<script> var r_application_questions_quill = new Quill('#r_application_questions', setting_listo); </script>