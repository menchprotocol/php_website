<script>
var current_section = 1; //The index for the wizard

function move_ui(adjustment){

	//Any pre-check with submitted data?
	//Let's check the value of the current posstible ID for input validation checking:
	if(adjustment>0 && typeof $('.wizard-box').eq((current_section-1)).attr( "id" ) !== 'undefined' && $('.wizard-box').eq((current_section-1)).attr( "id" ).length){
		var the_id = $('.wizard-box').eq((current_section-1)).attr( "id" );
		if(the_id=='wz_objective' && $('#'+the_id+' input').val().length<2){
			alert('Enter Objective to continue');
			$('#'+the_id+' input').focus();
			return false;
		} else if(the_id=='wz_sprint_unit' && typeof $('#'+the_id+' input[name=b_sprint_unit]:checked').val() == 'undefined'){
			alert('Select 1 of the options to continue');
			return false;
		}
	}
    
    
	//Variables:
	var total_steps = $('.wizard-box').length;
	if(adjustment<0 && current_section==1){
		return false;
	} else if(adjustment>0 && current_section==total_steps){
		return false;
	}
	
	//We're all good, lets continue:
	current_section = current_section+adjustment;
	var progress = Math.round((current_section/total_steps*100));

	//UI Adjustment
	$('.wizard-box').hide();
	$('.wizard-box').eq((current_section-1)).fadeIn(function(){
		  $( this ).find( "input" ).focus();
		  $( this ).find( ".ql-editor" ).focus();
	});

	//Previous Button adjustments:
	if(current_section==1){
		$('#btn_prev').hide();
	} else {
		$('#btn_prev').show();
	}
	
	//Update progress:
	$('.progress-bar').attr('aria-valuenow',progress).css('width',progress+'%');
	$('#step_progress').html(progress+'% Done');

	
	//Submit data only if last item:
	if(current_section==total_steps){

		//Hide both buttons:
		$('#btn_next, #btn_prev').hide();
		
		//Send for processing:
		$.post("/process/bootcamp_create", {
			
			c_objective:$('#c_objective').val(),
     		b_sprint_unit:$('input[name=b_sprint_unit]:checked').val(),
     		milestone_list:fetch_submit('milestones_list'),
     		r_start_date:$('#r_start_date').val(),
    		r_start_time_mins:$('#r_start_time_mins').val(),
    		
		}, function(data) {
			//Append data to view:
			$( "#new_bootcam_result" ).html(data).hide().fadeIn();
		});
	}
}

$(window).on('load',function(){
    //$('#newBootcampModal').modal('show');
});

$(document).ready(function() {
	$('#newBootcampModal').on('shown.bs.modal', function () {
		//Update progress bar:
		move_ui(0);
	});

	$('body').keyup(function(e){
        if((event.keyCode == 10 || event.keyCode == 13) && event.ctrlKey)
        {
        	move_ui(1);
        }
    });
});
</script>



<style>
.wizard-box * { line-height:110%; }
.wizard-box { font-size:1.2em; }
.wizard-box label { font-size:0.8em; }
.wizard-box p, .wizard-box ul { margin-bottom:20px; }
.wizard-box ul li { margin-bottom:10px; }
.wizard-box a { text-decoration:underline; }
.wizard-box h4 { margin:0 0 15px 0; padding:0; font-size:1.2em; }
.aligned-list>li>i { width:36px; display:inline-block; text-align:center; }
.large-fa {font-size: 60px; margin-top:15px;}
.xlarge-fa {font-size: 68px; margin-top:15px;}
</style>
<?php 
$udata = $this->session->userdata('user');
?>
<div class="modal fade" id="newBootcampModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title">New Bootcamp Wizard</h3>
      </div>
      <div class="modal-body" style="min-height:300px;">
            
            <div class="wizard-box">
        		<p>Let's start creating a new bootcamp by defining:</p>
        		<ul style="list-style:decimal;" class="aligned-list">
        			<li><i class="fa fa-dot-circle-o" aria-hidden="true"></i> Bootcamp Objective</li>
        			<li><i class="fa fa-hourglass-end" aria-hidden="true"></i> Milestone Submission Frequency</li>
        		</ul>
            </div>
                                    
            <div class="wizard-box" id="wz_objective">
            	<?php $this->load->view('console/inputs/c_objective' , array(
                    'level' => 1,
                )); ?>
            </div>
            
            <div class="wizard-box" id="wz_sprint_unit">
            	<?php $this->load->view('console/inputs/b_sprint_unit' ); ?>
            </div>
            
            <div class="wizard-box">
            	<p style="text-align:center;"><b>Creating New Bootcamp...</b></p>
            	<br />
            	<div id="new_bootcam_result" style="text-align:center; height:200px;"><img src="/img/round_load.gif" class="loader" /></div>
			</div>
			
      </div>
      <div class="modal-footer" style="text-align:left;">
        	<a id="btn_prev" href="javascript:move_ui(-1)" class="btn btn-primary" style="padding-left:10px;padding-right:12px; display:none;"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
            <span id="btn_next"><a href="javascript:move_ui(1)" class="btn btn-primary">Next <i class="fa fa-chevron-right" aria-hidden="true"></i></a><span class="enter">or press <b>CTRL+ENTER</b></span></span>
            
            <div style="text-align:right; margin:-30px 2px 0;"><b id="step_progress"></b></div>
            <div class="progress" style="margin:auto 2px;">
            	<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%;"></div>
            </div>
      </div>
    </div>
  </div>
</div>
