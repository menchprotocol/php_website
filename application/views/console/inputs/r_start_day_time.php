<?php $sprint_units = $this->config->item('sprint_units'); ?>
<?php $start_times = $this->config->item('start_times'); ?>

<script>
$(document).ready(function() {
	update_timeline();

    $( "#r_start_date" ).change(function() {
    	update_timeline();
    });
    $( "#r_start_time_mins" ).change(function() {
    	update_timeline();
    });
});

function update_timeline(){
	//Update timeline IF we have both a date and time:
	if($('#r_start_date').val().length<1 || $('#r_start_time_mins').val().length<1){
		$('#timeline_update').html('<p>Select date & time to see your cohort\'s timeline.</p>');
	}
	//Show spinner:
	$('#timeline_update').html('<img src="/img/round_load.gif" class="loader" />').hide().fadeIn();
	
	//Save the rest of the content:
	$.post("/process/cohort_timeline", {

		//Communication:
		milestone_count:$('#milestone_count').val(),
		b_sprint_unit:$('#b_sprint_unit').val(),
		r_start_date:$('#r_start_date').val(),
		r_start_time_mins:$('#r_start_time_mins').val(),
		
	} , function(data) {
		//Update UI to confirm with user:
		$('#timeline_update').html(data).hide().fadeIn();
    });
}
</script>

<?php if(isset($milestone_count)){ ?>
<input type="hidden" id="milestone_count" value="<?= $milestone_count ?>" />
<?php } ?>
<?php if(isset($b_sprint_unit)){ ?>
<input type="hidden" id="b_sprint_unit" value="<?= $b_sprint_unit ?>" />
<?php } ?>

<div class="title"><h4><i class="fa fa-calendar" aria-hidden="true"></i> Start Day & Time</h4></div>
<ul>
	<li>The day & time when this cohort starts.</li>
	<?php if(isset($b_sprint_unit)){ ?>
	<li>End date calculated based on the number of <?= $sprint_units[$b_sprint_unit]['name'] ?> <b style="display:inline-block;"><i class="fa fa-flag" aria-hidden="true"></i> Milestones</b>.</li>
	<?php } ?>
	<?php if(isset($b_sprint_unit) && $b_sprint_unit=='week'){ ?>
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
<div id="timeline_update"></div>

