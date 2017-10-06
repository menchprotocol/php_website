<!-- Modal Core -->
<div class="modal fade" id="newCohortModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title">New Cohort</h3>
      </div>
      <div class="modal-body">
      		
      		<div class="alert alert-info" role="alert">It's best to define your <a href="/console/<?= $bootcamp['c_id'] ?>/curriculum">Bootcamp Curriculum</a> before creating a cohort. This gives you a better idea of how much time students should commit and what price you should charge them.</div>
      		
      		<input type="hidden" id="r_c_id" value="<?= $bootcamp['c_id'] ?>" />
      		
      		
        	<div class="title"><h4>Starting Week Of</div>
			<div class="form-group label-floating is-empty">
			    <input type="text" id="r_start_date" style="width:233px;" class="form-control" />
			    <span class="material-input"></span>
			</div>
            
            
			
			<div id="new_cohort_result"></div>
      </div>
      <div class="modal-footer">
        <a href="javascript:r_process_create()" type="button" class="btn btn-primary">Create</a>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
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
