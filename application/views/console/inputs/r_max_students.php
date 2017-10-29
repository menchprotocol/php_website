<div class="title"><h4><i class="fa fa-thermometer-full" aria-hidden="true"></i> Maximum Students</h4></div>
<ul>
	<li>Maximum number of students that can apply before cohort is full.</li>
	<li>Consider your audience size to leverage this field to create a sense of scarcity.</li>
	<li>Once cohort is full we will automatically display your next published cohort.</li>
	<li>Remove the maximum limitation by setting it to "0".</li>
</ul>
<div class="input-group">
  <input type="number" min="0" step="1" style="width:100px; margin-bottom:-5px;" id="r_max_students" value="<?= ( isset($r_max_students) ? $r_max_students : null ) ?>" class="form-control border" />
</div>