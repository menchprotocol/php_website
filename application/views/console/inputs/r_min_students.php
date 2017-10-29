<div class="title"><h4><i class="fa fa-thermometer-empty" aria-hidden="true"></i> Minimum Students</h4></div>
<ul>
	<li>Minimum number of students required to kick-start this cohort.</li>
	<li>All applicants would be refunded if the minimum is not met.</li>
	<li>The value must be "1" or greater.</li>
</ul>
<div class="input-group">
	<input type="number" min="0" step="1" style="width:100px; margin-bottom:-5px;" id="r_min_students" value="<?= (isset($r_min_students)?$r_min_students:null) ?>" class="form-control border" />
</div>