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
		$('#timeline_update').html('<p>Select date & time to see your class\'s timeline.</p>');
	}
	//Show spinner:
	$('#timeline_update').html('<img src="/img/round_load.gif" class="loader" />').hide().fadeIn();
	
	//Save the rest of the content:
	$.post("/api_v1/class_timeline", {

		//Communication:
		c__child_intent_count:$('#c__child_intent_count').val(),
		b_status:$('#b_status').val(),
		b_id:$('#b_id').val(),
		b_sprint_unit:$('#b_sprint_unit').val(),
		r_start_date:$('#r_start_date').val(),
		r_start_time_mins:$('#r_start_time_mins').val(),
		
	} , function(data) {
		//Update UI to confirm with user:
		$('#timeline_update').html(data).hide().fadeIn();

		//Activate Tooltip:
		$('[data-toggle="tooltip"]').tooltip();
    });
}
</script>

<?php if(isset($c__child_intent_count)){ ?>
<input type="hidden" id="c__child_intent_count" value="<?= $c__child_intent_count ?>" />
<?php } ?>
<?php if(isset($b_sprint_unit)){ ?>
<input type="hidden" id="b_sprint_unit" value="<?= $b_sprint_unit ?>" />
<?php } ?>
<?php if(isset($b_id)){ ?>
<input type="hidden" id="b_id" value="<?= $b_id ?>" />
<?php } ?>
<?php if(isset($b_status)){ ?>
<input type="hidden" id="b_status" value="<?= $b_status ?>" />
<?php } ?>

<div class="title"><h4><i class="fa fa-calendar" aria-hidden="true"></i> Start Day & Time <span id="hb_625" class="help_button" intent-id="625"></span></h4></div>
<div class="help_body maxout" id="content_625"></div>

<div class="form-group label-floating is-empty">
    <input type="text" id="r_start_date" value="<?= ( isset($r_start_date) ? date("m/d/Y",strtotime($r_start_date)) : '' )  ?>" style="width:120px;display:inline-block;" class="form-control border" />
    <span class="material-input"></span>
</div>
<div class="form-group label-floating is-empty" style="margin-left:130px; margin-top:-36px;">
    <select class="form-control input-mini border" id="r_start_time_mins">
    	<?php
    	foreach($start_times as $minutes=>$fancy_time){
    	    echo '<option value="'.$minutes.'" '.( isset($r_start_time_mins) && $r_start_time_mins==$minutes ? 'selected="selected"' : '' ).'>'.$fancy_time.' PST</option>';
    	}
    	?>
    </select>
</div>
<div id="timeline_update"></div>

