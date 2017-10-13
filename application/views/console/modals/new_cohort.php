
<script>
function r_process_create(){
	//Show processing:
	$( "#new_cohort_result" ).html('<img src="/img/loader.gif" /> Processing...').hide().fadeIn();
	
	//Send for processing:
	$.post("/process/cohort_create", {
		
		r_b_id:$('#r_b_id').val(),
		r_start_date:$('#r_start_date').val(),
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
	    	minDate : 2,
	    	beforeShowDay: function(date){
	    		  var day = date.getDay(); 
	    		  return [day == 1,""];
	    	},
		});
	});

	//Focus on the datepicker:
	$('#newCohortModal').on('shown.bs.modal', function () {
		$('#r_start_date').focus();
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




<!-- Modal Core -->
<div class="modal fade" id="newCohortModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title">New Cohort</h3>
      </div>
      <div class="modal-body">
      		
      		<div class="alert alert-info" role="alert">It's best to create your <a href="/console/<?= $bootcamp['b_id'] ?>/curriculum">Bootcamp Curriculum</a> before creating a cohort. This gives you a better idea of how much time students should commit and what price you should charge them.</div>
      		
      		<input type="hidden" id="r_b_id" value="<?= $bootcamp['b_id'] ?>" />
      		
      		
        	<div class="title"><h4>Starting Week Of</div>
			<div class="form-group label-floating is-empty">
			    <input type="text" id="r_start_date" style="width:233px;" class="form-control border" />
			    <span class="material-input"></span>
			</div>
            
            <?php 
            if(count($bootcamp['c__cohorts'])>0){
                //We already have some cohorts, give user the option to copy settings:
                ?>
                <div class="title"><h4>Copy Cohort Settings</div>
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
            
			
			<div id="new_cohort_result"></div>
      </div>
      <div class="modal-footer">
        <a href="javascript:r_process_create()" type="button" class="btn btn-primary">Create</a>
      </div>
    </div>
  </div>
</div>
