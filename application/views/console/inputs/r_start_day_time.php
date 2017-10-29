<?php $sprint_units = $this->config->item('sprint_units'); ?>
<?php $start_times = $this->config->item('start_times'); ?>
<div class="title"><h4><i class="fa fa-calendar" aria-hidden="true"></i> Start Day & Time</h4></div>
<ul>
	<li>The day & time when this cohort starts.</li>
	<li>End date calculated based on the number of <?= $sprint_units[$b_sprint_unit]['name'] ?> Action Plans.</li>
	<?php if($b_sprint_unit=='week'){ ?>
	<li><?= $sprint_units[$b_sprint_unit]['name'] ?> bootcamps always start on Mondays and end on Sundays.</li>
	<?php } ?>
</ul>
<div class="form-group label-floating is-empty">
    <input type="text" id="r_start_date" value="<?= ( isset($r_start_date) ? date("m/d/Y",strtotime($r_start_date)) : '' )  ?>" style="width:120px;display:inline-block;" class="form-control border" />
    <span class="material-input"></span>
</div>
<div class="form-group label-floating is-empty" style="margin-left:130px; margin-top:-50px;">
    <select class="form-control input-mini border" id="r_start_time_mins">
    	<?php
    	foreach($start_times as $minutes=>$fancy_time){
    	    echo '<option value="'.$minutes.'" '.( isset($r_start_time_mins) && $r_start_time_mins==$minutes ? 'selected="selected"' : '' ).'>'.$fancy_time.' PST</option>';
    	}
    	?>
    </select>
</div>