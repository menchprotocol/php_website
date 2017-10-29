<?php
//Fetch the sprint units from config:
$sprint_units = $this->config->item('sprint_units');
$website = $this->config->item('website');
$udata = $this->session->userdata('user');
?>
<script>
function ucwords(str) {
   return (str + '').replace(/^(.)|\s+(.)/g, function ($1) {
      return $1.toUpperCase()
   });
}
function js_mktime(hour,minute,month,day,year) {
    return new Date(year, month-1, day, hour, minutes, 0).getTime() / 1000;
}



$(document).ready(function() {
	//Detect any possible hashes that controll the menu?
	if(window.location.hash) {
        var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
      	//Open specific menu with a 100ms delay to fix TOP NAV bug
    	setTimeout(function() {
    		$('.tab-pane, #topnav > li').removeClass('active');
    		$('#'+hash+', #nav_'+hash).addClass('active');
	    }, 300);
    }
    
	//Load date picker:
	$( function() {
	    $( "#r_start_date" ).datepicker({
	    	minDate : 2,
	    	beforeShowDay: function(date){
	    		  var day = date.getDay(); 
	    		  return [ <?= $bootcamp['b_sprint_unit']=='week' ? 'day==1' : 'day==1 || day==2 || day==3 || day==4 || day==5 || day==6 || day==0' ?> ,""];
	    	},
		});
	});

	//Watch for changing refund policy:
	$('input[name=r_cancellation_policy]').change(function() {
		//$("#r_live_office_hours_val").val(ucwords(this.value));
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
	
	var save_data = {	
		r_id:$('#r_id').val(),
		b_id:$('#b_id').val(),
		r_start_date:$('#r_start_date').val(),
		
		//Communication:
		r_response_time_hours:$('#r_response_time_hours').val(),
		r_weekly_1on1s:$('#r_weekly_1on1s').val(),
		r_live_office_hours_check:$('#r_live_office_hours_val').val(),
		r_office_hour_instructions:$('#r_office_hour_instructions').val(),
		r_closed_dates:$('#r_closed_dates').val(),
		r_start_time_mins:$('#r_start_time_mins').val(),
		r_facebook_group_id:$('#r_facebook_group_id').val(),
		
		//Cohort:
		r_status:$('#r_status').val(),
		r_usd_price:$('#r_usd_price').val(),
		r_min_students:$('#r_min_students').val(),
		r_max_students:$('#r_max_students').val(),
		r_typeform_id:$('#r_typeform_id').val(),
		r_cancellation_policy:$('input[name=r_cancellation_policy]:checked').val(),
		
		//Application:
		r_prerequisites:( r_prerequisites_quill.getLength()>1 ? $('#r_prerequisites .ql-editor').html() : "" ),
		r_application_questions:( r_application_questions_quill.getLength()>1 ? $('#r_application_questions .ql-editor').html() : "" ),
	};
	
	//Now merge into timeline dates:
	//for (var key in timeline){
	//	save_data[key] = timeline[key];
	//}
	
	//Save the rest of the content:
	$.post("/process/cohort_edit", save_data , function(data) {
		//Update UI to confirm with user:
		$('#save_r_results').html(data).hide().fadeIn();
		
		//Disapper in a while:
		setTimeout(function() {
			$('#save_r_results').fadeOut();
	    }, 10000);
    });
}
</script>




<input type="hidden" id="r_id" value="<?= $cohort['r_id'] ?>" />
<input type="hidden" id="b_id" value="<?= $cohort['r_b_id'] ?>" />
<input type="hidden" id="week_count" value="<?= count($bootcamp['c__child_intents']) ?>" />






<ul id="topnav" class="nav nav-pills nav-pills-primary">
  <li id="nav_admission" class="active"><a href="#admission" data-toggle="tab" onclick="update_hash('admission')"><i class="fa fa-ticket" aria-hidden="true"></i> Admission</a></li>
  <li id="nav_support"><a href="#support" data-toggle="tab" onclick="update_hash('support')"><i class="fa fa-life-ring" aria-hidden="true"></i> Support</a></li>
  <li id="nav_pricing"><a href="#pricing" data-toggle="tab" onclick="update_hash('pricing')"><i class="fa fa-usd" aria-hidden="true"></i> Pricing</a></li>
  <li id="nav_settings"><a href="#settings" data-toggle="tab" onclick="update_hash('settings')"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a></li>
</ul>




<div class="tab-content tab-space">

    <div class="tab-pane active" id="admission">
        <?php $this->load->view('console/inputs/r_min_students' , array('r_min_students'=>$cohort['r_min_students']) ); ?>
        <br />
        <?php $this->load->view('console/inputs/r_max_students' , array('r_max_students'=>$cohort['r_max_students']) ); ?>
        <br />
        <?php $this->load->view('console/inputs/r_prerequisites' , array('r_prerequisites'=>$cohort['r_prerequisites']) ); ?>
        <br />
        <?php $this->load->view('console/inputs/r_application_questions' , array('r_application_questions'=>$cohort['r_application_questions']) ); ?>
    </div>
    
    
    <div class="tab-pane" id="support">
    
    	<?php $this->load->view('console/inputs/r_response_time_hours' , array('r_response_time_hours'=>$cohort['r_response_time_hours']) ); ?>

    	<?php $this->load->view('console/inputs/r_facebook_group_id' , array('r_facebook_group_id'=>$cohort['r_facebook_group_id']) ); ?>
		<br />
    	<?php $this->load->view('console/inputs/r_weekly_1on1s' , array(
    	       'r_weekly_1on1s'=>$cohort['r_weekly_1on1s'],
    	       'b_sprint_unit'=>$bootcamp['b_sprint_unit']     
    	)); ?>
    	


		<br />
		<div class="title"><h4><i class="fa fa-podcast" aria-hidden="true"></i> Live Office Hours</h4></div>
		<ul>
			<li>Provide support to students who show-up during pre-set office hours.</li>
			<li>Students will receive a broadcast message 30 minute before each timeslot.</li>
			<li>Use a group video chat app like Skype, Zoom or Hangouts to conduct meetings.</li>
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
			<ul>
      			<li>Instructions on how students can contact you or your team.</li>
    			<li>Include Skype ID, Google Hangout link, Zoom video confrence url, etc...</li>
    			<li>Mench sends automatic reminders 30-minutes prior to each office hour.</li>
    		</ul>
            <div class="form-group label-floating is-empty">
                <textarea class="form-control text-edit border" placeholder="Contact using our Skype username: grumomedia" id="r_office_hour_instructions"><?= $cohort['r_office_hour_instructions'] ?></textarea>
            </div>
            
            
            <div class="title"><h4>Office Hours: Weekly Schedule</h4></div>
            <ul>
      			<li>Set office hours in PST timezone (Currently <?= time_format(time(),7) ?>).</li>
    			<li>Mench will adjust hours based on each student's timezone.</li>
    			<li>Click once to insert new time-frame and then drag to expand.</li>
    		</ul>
            <iframe id="weekschedule" src="/console/<?= $bootcamp['b_id'] ?>/cohorts/<?= $cohort['r_id'] ?>/scheduler" scrolling="no" class="scheduler-iframe"></iframe>
			
			
            <div class="title"><h4>Office Hours: Close Dates</h4></div>
            <ul>
      			<li>Manually define the dates that you would not provide office hours.</li>
    		</ul>
            <div class="form-group label-floating is-empty">
                <textarea class="form-control text-edit border" placeholder="Plain text like: Nov 23, Dec 25, Dec 26 and Jan 1" id="r_closed_dates"><?= $cohort['r_closed_dates'] ?></textarea>
            </div>
            
		</div>
    </div>
    
    
    
    
    <div class="tab-pane" id="pricing">
    
        <?php $this->load->view('console/inputs/r_usd_price' , array('r_usd_price'=>$cohort['r_usd_price']) ); ?>
        <br />
        <br />
        <?php $this->load->view('console/inputs/r_cancellation_policy' , array('r_cancellation_policy'=>$cohort['r_cancellation_policy']) ); ?>
    </div>
    
    
    <div class="tab-pane" id="settings">
    
    
        <?php $this->load->view('console/inputs/r_status' , array('r_status'=>$cohort['r_status']) ); ?>
		<br />
        <?php $this->load->view('console/inputs/r_start_day_time' , array(
            'b_sprint_unit' => $bootcamp['b_sprint_unit'],
            'r_start_date' => $cohort['r_start_date'],
            'r_start_time_mins' => $cohort['r_start_time_mins'],
        )); ?>
        
        <div style="display:<?= ( $udata['u_status']>=3 ? 'block' : 'none' ) ?>;">
        	<div class="title"><h4><i class="fa fa-keyboard-o" aria-hidden="true"></i> Typeform ID</h4></div>
        	<ul>
    			<li>Each cohort has a unique Typeform as its application form.</li>
    			<li>This section is only visible by Mench Super Admins.</li>
    		</ul>
            <div class="form-group label-floating is-empty">
                <input type="text" id="r_typeform_id" style="width:233px;" value="<?= $cohort['r_typeform_id'] ?>" class="form-control border">			
            </div>
            <?php if(strlen($cohort['r_typeform_id'])>0){ ?>
            <div style="margin-bottom:20px;"><a href="<?= typeform_url($cohort['r_typeform_id'],$udata) ?>" target="_blank" class="btn btn-default landing_page_url">View Typeform <i class="fa fa-external-link" style="font-size:1em;" aria-hidden="true"></i></a></div>
            <?php } ?>
    	</div>
    </div>
</div>


<br />
<table width="100%"><tr><td class="save-td"><a href="javascript:save_r();" class="btn btn-primary">Save</a></td><td><span id="save_r_results"></span></td></tr></table>
