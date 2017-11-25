<?php
//Fetch the sprint units from config:
$sprint_units = $this->config->item('sprint_units');
?>
<script>
function r_process_create(){
	//Show processing:
	$( "#new_r_result" ).html('<img src="/img/round_load.gif" class="loader" />').hide().fadeIn();
	
	//Send for processing:
	$.post("/api_v1/r_create", {
		
		r_b_id:$('#r_b_id').val(),
		r_start_date:$('#r_start_date').val(),
		r_start_time_mins:$('#r_start_time_mins').val(),
		r_status:$('#r_status').val(),
		copy_r_id:$('#copy_r_id').val(),
		
	}, function(data) {
		//Append data to view:
		$( "#new_r_result" ).html(data).hide().fadeIn();
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
	$('#newClassModal').on('shown.bs.modal', function () {
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


<!-- NEW class -->
<div class="modal fade" id="newClassModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title">New Class</h3>
      </div>
      <div class="modal-body">
      		      		
      		<input type="hidden" id="r_b_id" value="<?= $bootcamp['b_id'] ?>" />
      		
			<?php $this->load->view('console/inputs/r_start_day_time' , array(
			    'c__child_intent_count' => count($bootcamp['c__child_intents']),
			    'b_sprint_unit' => $bootcamp['b_sprint_unit'],
			    'b_id' => $bootcamp['b_id'],
			    'b_status' => $bootcamp['b_status'],
			    'r_start_time_mins' => '540', //9 AM Default recommendation
            )); ?>
			
			
			<?php $this->load->view('console/inputs/r_status' , array('r_status'=>1,'removal_status'=>array(-1,-2,2,3)) ); ?>
			
			<?php 
            if(count($bootcamp['c__classes'])>0){
                //We already have some classes, give user the option to copy settings:
                ?>
                <div class="title"><h4><i class="fa fa-clone" aria-hidden="true"></i> Copy Settings</div>
    			<div class="form-group label-floating is-empty">
    			    <select class="form-control input-mini border" id="copy_r_id">
    			    	<option value="0">Do Not Copy Settings</option>
                    	<?php 
                    	foreach($bootcamp['c__classes'] as $count=>$class){
                    	    echo '<option value="'.$class['r_id'].'" '.( ($count+1)==count($bootcamp['c__classes']) ? 'selected="selected"' : '' ).'>Copy '.time_format($class['r_start_date'],1).' Class</option>';
                    	}
                    	?>
                    </select>
    			</div>
                <?php
            } else {
                echo '<input type="hidden" id="copy_r_id" value="0" />';
            }
            
            ?>
            <div style="margin:0; padding:0; clear:both;">&nbsp;</div>
			<div id="new_r_result"></div>
      </div>
      <div class="modal-footer">
        <a href="javascript:r_process_create()" type="button" class="btn btn-primary">Create</a>
      </div>
    </div>
  </div>
</div>




<!-- SCHEDULE CLASSES -->
<div class="modal fade" id="ScheduleClasses" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title">Class Scheduling</h3>
      </div>
      <div class="modal-body">
      		<p>Class Scheduling is an advanced feature for bootcamps with a consistent operation. This feature enables you to:</p>
          	<ul>
          		<li>Auto create classes based on a pre-selected schedule.</li>
          		<li>Ideal for classes that run Weekly, Bi-Weekly, Monthly or Quarterly.</li>
          		<li>Enable students to choose from a list of your upcoming classes.</li>
          	</ul>
          	<p>Contact us via live chat to enable Class Scheduling for your account.</p>
      </div>
    </div>
  </div>
</div>

