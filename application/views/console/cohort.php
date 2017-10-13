<script>
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

	//Watchout for changing office hours checkbox:
	$("#r_live_office_hours_check").change(function() {
		if(this.checked){
			//Show related fields:
			$('.has_office_hours').fadeIn();
			$("#r_live_office_hours_val").val('1');
		} else {
			$('.has_office_hours').hide();
			$("#r_live_office_hours_val").val('0');
		}
	});
});


function save_r(){
	//Show spinner:
	$('#save_r_results').html('<img src="/img/round_load.gif" class="loader" />').hide().fadeIn();
	
	//Save Scheduling iFrame content:
	if(parseInt($('#r_live_office_hours_val').val())){
		document.getElementById('weekschedule').contentWindow.save_hours();
	}
	
	//Save the rest of the content:
	$.post("/process/cohort_edit", {
		
		r_id:$('#r_id').val(),
		r_start_date:$('#r_start_date').val(),
		
		//Communication:
		r_response_time_hours:$('#r_response_time_hours').val(),
		r_weekly_1on1s:$('#r_weekly_1on1s').val(),
		r_live_office_hours_check:$('#r_live_office_hours_val').val(),
		r_office_hour_instructions:$('#r_office_hour_instructions').val(),
		r_closed_dates:$('#r_closed_dates').val(),
		
		//Cohort:
		r_status:$('#r_status').val(),
		r_usd_price:$('#r_usd_price').val(),
		r_min_students:$('#r_min_students').val(),
		r_max_students:$('#r_max_students').val(),

		//Application:
		r_application_questions:( r_application_questions_quill.getLength()>1 ? $('#r_application_questions .ql-editor').html() : "" ),
		
	} , function(data) {
		//Update UI to confirm with user:
		$('#save_r_results').html(data).hide().fadeIn();
		
		//Disapper in a while:
		setTimeout(function() {
			$('#save_r_results').fadeOut();
	    }, 10000);
    });
}


function reset_default(){
	<?php $default_cohort_questions = $this->config->item('default_cohort_questions'); ?>
	$('#r_application_questions .ql-editor').html('<ol><li><?= join('</li><li>',$default_cohort_questions) ?></li></ol>');
	alert('Questions reset successful. Remember to save your changes.');
}
</script>




<input type="hidden" id="r_id" value="<?= $cohort['r_id'] ?>" />
<input type="hidden" id="week_count" value="<?= count($bootcamp['c__child_intents']) ?>" />






<ul class="nav nav-pills nav-pills-primary">
  <li class="active"><a href="#pill1" data-toggle="tab"><i class="fa fa-calendar" aria-hidden="true"></i> Timelime</a></li>
  <li><a href="#pill2" data-toggle="tab"><i class="fa fa-life-ring" aria-hidden="true"></i> Support</a></li>
  <li><a href="#pill3" data-toggle="tab"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a></li>
</ul>


<div class="tab-content tab-space">

    <div class="tab-pane active" id="pill1">
    	<div class="title"><h4>Cohort Start Week</h4></div>
  		<p>The bootcamp kick-off week for this cohort is:</p>
        <div class="form-group label-floating is-empty">
            <input type="text" id="r_start_date" value="<?= date("m/d/Y" , strtotime($cohort['r_start_date']) ) ?>" style="width:233px;" class="form-control border" />
            <span class="material-input"></span>
        </div>
        
        <!--
        <hr />
        <div class="row">
        	<div class="col-sm-3">Registration Starts:</div>
            <div class="col-sm-3"><b>NOW</b></div>
            <div class="col-sm-6">As soon as Bootcamp AND Cohort status are live. Once students start registering, you will review applications, conduct interview to validate qualifications and accept students one by one. If rejected, students will receive a full refund.</div>
        </div>
        <hr />
        <div class="row">
        	<div class="col-sm-3">Registration Ends:</div>
            <div class="col-sm-3"><b><span id="r_time_register_end">June 5th 8:00P</span></b></div>
            <div class="col-sm-6">Students who plan to join must have paid in full by this time. This is when the registration for the next cohort starts.</div>
        </div>
        <hr />
        <div class="row">
        	<div class="col-sm-3">Bootcamp Starts:</div>
            <div class="col-sm-3"><b><span id="r_time_cohort_start">June 5th 8:00P</span></b></div>
            <div class="col-sm-6">Interested students must have paid in full by this time. During the bootcamp students work on the weekly sprints as defined in the <a href="/console/<?= $bootcamp['b_id'] ?>/curriculum">curriculum</a>.</div>
        </div>
        <hr />
        <div class="row">
        	<div class="col-sm-3">Free Withdrawal Ends:</div>
            <div class="col-sm-3"><b><span id="r_time_free_end">June 5th 8:00P</span></b></div>
            <div class="col-sm-6">Interested students must have paid in full by this time. During the bootcamp students work on the weekly sprints as defined in the <a href="/console/<?= $bootcamp['b_id'] ?>/curriculum">curriculum</a>.</div>
        </div>
        <hr />
        <div class="row">
        	<div class="col-sm-3">Bootcamp Ends:</div>
            <div class="col-sm-3"><b><span id="r_time_cohort_end">June 5th 8:00P</span></b></div>
            <div class="col-sm-6">This has been calculated based on the <?= count($bootcamp['c__child_intents']) ?> weekly sprints defined in the <a href="/console/<?= $bootcamp['b_id'] ?>/curriculum">curriculum</a>.</div>
        </div>
        -->
    </div>
    
    
    <div class="tab-pane" id="pill2">
    
		<div class="title"><h4>Response Time</h4></div>
        <p>You are required to respond to 100% of student inquiries. Here you can choose how fast you would commit to responding:</p>
        <select class="form-control input-mini border" id="r_response_time_hours">
        	<?php 
        	$r_response_options = $this->config->item('r_response_options');
        	foreach($r_response_options as $time){
        	    echo '<option value="'.$time.'" '.( $cohort['r_response_time_hours']==$time ? 'selected="selected"' : '' ).'>Under '.echo_hours($time).'</option>';
        	}
        	?>
        </select>
        
        
		<div class="title"><h4>1-on-1 Mentorship</h4></div>
        <p>Optionally you can setup weekly 1-on-1 mentorship with each student which is particularly helpful for complex bootcamps where students need more personalized attention to succeed. Mentorship calls are usually conducted via video chat and/or screen-share based on the nature of your bootcamp.</p>
        <select class="form-control input-mini border" id="r_weekly_1on1s" style="width:300px;">
        	<?php 
        	$weekly_1on1s_options = $this->config->item('r_weekly_1on1s_options');
        	foreach($weekly_1on1s_options as $time){
        	    echo '<option value="'.$time.'" '.( $cohort['r_weekly_1on1s']==$time ? 'selected="selected"' : '' ).'>'.echo_hours($time).' per student per week</option>';
        	}
        	?>
        </select>
        
        
        
        
        
        
		
		<div class="title"><h4>Office Hours</h4></div>
		<p>Office hours enable students to contact you (or your team) online for live/on-demand support. A few notes:</p>
		<ul>
			<li>Online office hours are usually live video calls done via Skype, Google Hangouts, etc...</li>
			<li>You will set hours in GMT -8:00 (Pacific Standard Time) and will be adjust for each student based on their time zone.</li>
			<li>How to add a new time frame? Simply click on the scheduler to insert a new time-box and then drag to expand it.</li>
		</ul>
		
		
		<input type="hidden" id="r_live_office_hours_val" value="<?= strlen($cohort['r_live_office_hours'])>0 ? '1' : '0' ?>" />
		<div class="checkbox">
        	<label>
        		<input type="checkbox" id="r_live_office_hours_check" <?= strlen($cohort['r_live_office_hours'])>0 ? 'checked' : '' ?>>
        		Enable Live Office Hours
        	</label>
        </div>
		
		<div class="has_office_hours" style="display:<?= strlen($cohort['r_live_office_hours'])>0 ? 'block' : 'none' ?>;">
		
			<div class="title"><h4>Office Hours: Contact Method</h4></div>
        	<p>Let students know how then can contact you (or your team) during live office hours:</p>
            <div class="form-group label-floating is-empty">
                <textarea class="form-control text-edit border" placeholder="Skype ID, Google Hangout link, Zoom video confrence url, etc..." id="r_office_hour_instructions"><?= $cohort['r_office_hour_instructions'] ?></textarea>
            </div>                    
            
            <div class="title"><h4>Office Hours: Close Dates</h4></div>
        	<p>Define dates that you would not provide office hours during this Cohort. Enter dates in plain text format:</p>
            <div class="form-group label-floating is-empty">
                <textarea class="form-control text-edit border" placeholder="Nov 23, Dec 25, Dec 26 and Jan 1" id="r_closed_dates"><?= $cohort['r_closed_dates'] ?></textarea>
            </div>
            
            
            <div class="title"><h4>Office Hours: Weekly Schedule</h4></div>
            <p>Enable office hours by inserting (and then saving) your weekly schedule below:</p>
            <iframe id="weekschedule" src="/console/<?= $bootcamp['b_id'] ?>/cohorts/<?= $cohort['r_id'] ?>/scheduler" scrolling="no" class="scheduler-iframe"></iframe>
			
		</div>
    </div>
    
    
    
    
    <div class="tab-pane" id="pill3">
    
		<div class="title"><h4>Cohort Status</h4></div>
  		<?= ($cohort['r_status']==0 ? '<p>Newly created cohorts are ON HOLD by default to give you time to edit and then publish live when ready:</p>' : '') ?>
	 	<?php echo_status_dropdown('r','r_status',$cohort['r_status']); ?>
		<br />
		
		
		<div class="title"><h4>Enrollment Price</h4></div>
		<p>Decide how much you like to charge students to join this bootcamp. A few notes:</p>
  		<ul>
  			<li>Your asking price is dependant on the level of personalized support and the number of weekly sprints.</li>
			<li>Typically you want to charge abour $100/week for 30 Minutes of 1-on-1 mentorship.</li>
			<li>Include one question per point</li>
			<li>We ask these questions in the same order listed.</li>
			<li>You can always <b><a href="javascript:reset_default();">reset to default questions</a></b> (and save).</li>
		</ul>
        <div class="input-group">
          <span class="input-group-addon addon-lean">USD $</span>
          <input type="number" min="0" step="0.01" style="width:100px; margin-bottom:-5px;" id="r_usd_price" value="<?= $cohort['r_usd_price'] ?>" class="form-control border" />
        </div>
        
        <br />
        <div class="title"><h4>Minimum Students</h4></div>
        <p>Define the minimum number of students required to be registered to kick-start this cohort. If this number is not met, all existing registrants would be refunded and the cohort would not be started.</p>
        <div class="input-group">
          <input type="number" min="0" step="1" style="width:100px; margin-bottom:-5px;" id="r_min_students" value="<?= $cohort['r_min_students'] ?>" class="form-control border" />
        </div>
        
        
        <br />
        <div class="title"><h4>Maximum Students</h4></div>
        <p>Define the maximum number of students that can enroll before cohort is full. 0 means no maximum.</p>
        <div class="input-group">
          <input type="number" min="0" step="1" style="width:100px; margin-bottom:-5px;" id="r_max_students" value="<?= $cohort['r_max_students'] ?>" class="form-control border" />
        </div>
		
		
		<br />
		<div class="title"><h4>Enrollment Questions</h4></div>
		<p>What open-ended questions would you like to ask students during the enrolling application? A few notes:</p>
  		<ul>
  			<li>These questions can help you determine the level of desire, experience and suitability of the student to your bootcamp.</li>
			<li>We ask students to re-confirm if they meet <b><a href="/console/<?= $bootcamp['b_id'] ?>/curriculum">bootcamp requirements</a></b>, so no need to re-ask here.</li>
			<li>Include one question per point & we'll ask them in the same order.</li>
			<li>You can always <b><a href="javascript:reset_default();">reset to default questions</a></b> (and save).</li>
		</ul>
  		<div id="r_application_questions"><?= $cohort['r_application_questions'] ?></div>
        <script> var r_application_questions_quill = new Quill('#r_application_questions', setting_listo); </script>
		
		
    </div>
</div>
        
<br />
<table width="100%"><tr><td class="save-td"><a href="javascript:save_r();" class="btn btn-primary">Save</a></td><td><span id="save_r_results"></span></td></tr></table>
