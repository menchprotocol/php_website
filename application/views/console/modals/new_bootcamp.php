<!-- Modal Core -->
<div class="modal fade" id="newBootcampModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title">New Bootcamp</h3>
      </div>
      <div class="modal-body">
        	<div class="title"><h4>Primary Objective</h4></div>
			<div class="form-group label-floating is-empty">
			    <input type="text" id="c_primary_objective" placeholder="Get hired as entry-level web developer" class="form-control border" />
			    <span class="material-input"></span>
			</div>
			<div id="new_bootcam_result"></div>
      </div>
      <div class="modal-footer">
        <a href="javascript:bootcamp_create()" type="button" class="btn btn-primary">Create</a>
      </div>
    </div>
  </div>
</div>

<script>

function bootcamp_create(){
	//Show processing:
	$( "#new_bootcam_result" ).html('<img src="/img/round_load.gif" class="loader" />').hide().fadeIn();
	
	//Send for processing:
	$.post("/process/bootcamp_create", {c_primary_objective:$('#c_primary_objective').val()}, function(data) {
		//Append data to view:
		$( "#new_bootcam_result" ).html(data).hide().fadeIn();
	});
}

$(document).ready(function() {
	$('#newBootcampModal').on('shown.bs.modal', function () {
		$('#c_primary_objective').focus();
	});
});

$('#c_primary_objective').bind("enterKey",function(e){
	bootcamp_create();
});
$('#c_primary_objective').keyup(function(e){
    if(e.keyCode == 13)
    {
    	bootcamp_create();
    }
});
</script>