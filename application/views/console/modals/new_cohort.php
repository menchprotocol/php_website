<?php
//Fetch the sprint units from config:
$sprint_units = $this->config->item('sprint_units');
?>
<script>
function r_process_create(){
	//Show processing:
	$( "#new_cohort_result" ).html('<img src="/img/round_load.gif" class="loader" />').hide().fadeIn();
	
	//Send for processing:
	$.post("/process/cohort_create", {
		
		r_b_id:$('#r_b_id').val(),
		r_start_date:$('#r_start_date').val(),
		r_start_time_mins:$('#r_start_time_mins').val(),
		r_status:$('#r_status').val(),
		copy_cohort_id:$('#copy_cohort_id').val(),
		
	}, function(data) {
		//Append data to view:
		$( "#new_cohort_result" ).html(data).hide().fadeIn();
	});
}

$(document).ready(function() {

	//Load date picker:
	$( function() {
	    $( "#r_start_date" ).datepicker({
	    	minDate : 1,
	    	beforeShowDay: function(date){
	    		  var day = date.getDay(); 
	    		  return [ ( <?= $bootcamp['b_sprint_unit']=='week' ? 'day==1' : 'day==1 || day==2 || day==3 || day==4 || day==5 || day==6 || day==0' ?> ) ,""];
	    	},
		});
	});

	//Focus on the datepicker:
	$('#newCohortModal').on('shown.bs.modal', function () {
		//$('#r_start_date').focus();
	});
});


$('#r_start_date').bind("enterKey",function(e){
	r_process_create();
});
$('#r_start_date').keyup(function(e){
    if(e.keyCode == 13)
    {
    	r_process_create();
    }
});
</script>

<style>
#timeline_update{ font-size:0.8em; }
</style>


<!-- NEW COHORT -->
<div class="modal fade" id="newCohortModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title">New Cohort</h3>
      </div>
      <div class="modal-body">
      		      		
      		<input type="hidden" id="r_b_id" value="<?= $bootcamp['b_id'] ?>" />
      		
			<?php $this->load->view('console/inputs/r_start_day_time' , array(
			    'milestone_count' => count($bootcamp['c__child_intents']),
			    'b_sprint_unit' => $bootcamp['b_sprint_unit'],
			    'r_start_time_mins' => '540', //9 AM Default recommendation
            )); ?>
			
			
			<?php $this->load->view('console/inputs/r_status' , array('r_status'=>1,'removal_status'=>array(-1,-2,2,3)) ); ?>
			
			<?php 
            if(count($bootcamp['c__cohorts'])>0){
                //We already have some cohorts, give user the option to copy settings:
                ?>
                <div class="title"><h4><i class="fa fa-clone" aria-hidden="true"></i> Copy Settings</div>
    			<div class="form-group label-floating is-empty">
    			    <select class="form-control input-mini border" id="copy_cohort_id">
    			    	<option value="0">Do Not Copy Settings</option>
                    	<?php 
                    	foreach($bootcamp['c__cohorts'] as $count=>$cohort){
                    	    echo '<option value="'.$cohort['r_id'].'" '.( ($count+1)==count($bootcamp['c__cohorts']) ? 'selected="selected"' : '' ).'>Copy '.time_format($cohort['r_start_date'],1).' Cohort</option>';
                    	}
                    	?>
                    </select>
    			</div>
                <?php
            } else {
                echo '<input type="hidden" id="copy_cohort_id" value="0" />';
            }
            
            ?>
            <div style="margin:0; padding:0; clear:both;">&nbsp;</div>
			<div id="new_cohort_result"></div>
      </div>
      <div class="modal-footer">
        <a href="javascript:r_process_create()" type="button" class="btn btn-primary">Create</a>
      </div>
    </div>
  </div>
</div>




<!-- SCHEDULE COHORTS -->
<div class="modal fade" id="ScheduleCohorts" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title">Cohort Scheduling</h3>
      </div>
      <div class="modal-body">
      		<p>Cohort Scheduling is an advanced feature for bootcamps with a consistent operation. This feature enables you to:</p>
          	<ul>
          		<li>Auto create cohorts based on a pre-selected schedule.</li>
          		<li>Select schedules such as Weekly, Bi-Weekly, Quarterly, etc...</li>
          		<li>Enable students to register in any cohort starting within 3 months.</li>
          	</ul>
          	<p>Contact us via live chat to enable Cohort Scheduling for your account.</p>
      </div>
    </div>
  </div>
</div>

