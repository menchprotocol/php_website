<?php
//Fetch the sprint units from config:
$sprint_units = $this->config->item('sprint_units');
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

	//Watch for changing cancellation policy:
	$('input[name=r_cancellation_policy]').change(function() {
		//$("#r_live_office_hours_val").val(ucwords(this.value));
		//update_timeline();
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




function update_timeline(){

	//Set base variables:
	var week_count = parseInt($('#week_count').val());
	var r_start_date = $('#r_start_date').val().split("/");
	var cancellation_policy = $('input[name=r_cancellation_policy]:checked').val();
	
	//Define cancellation policy variables:
	if(cancellation_policy=='flexible'){
		var complete_refund_days = Math.ceil(week_count * 7 * 0.10);
		var prorated_refund_days = Math.ceil(week_count * 7 * 0.60);
	} else if(cancellation_policy=='moderate'){
		var complete_refund_days = -1; //A day before the start date midnight
		var prorated_refund_days = Math.ceil(week_count * 7 * 0.30);
	} else if(cancellation_policy=='strict'){
		var complete_refund_days = null;
		var prorated_refund_days = null;
	} else {
		//Unknown?!
		alert('Unrecognized cancellation policy ['+cancellation_policy+']. Cannot generate timeline.');
		return false;
	}


	//Calculate times:
	var r_cache_registration_end_time = new Date(parseInt(r_start_date[2]), parseInt(r_start_date[0]), parseInt(r_start_date[1]), 23, 59, 0, 0);
	r_cache_registration_end_time.setDate(r_cache_registration_end_time.getDate() - 1);

	console.log(r_cache_registration_end_time);
	return false;
	
    var timeline = {
			r_cache_registration_end_time : add_days(a,-1),
			r_cache_cohort_first_day : null,
			r_cache_full_refund_time : ( complete_refund_days===null ? null : add_days(a,complete_refund_days) ),
			r_cache_pro_rated_refund_time : ( prorated_refund_days===null ? null : add_days(a,prorated_refund_days) ),
			r_cache_cohort_last_day : null,
	};

	//Some examples:
	var a = new Date(UNIX_timestamp * 1000);
    var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    var year = a.getFullYear();
    var month = months[a.getMonth()];
    var date = a.getDate();
    var hour = a.getHours();
    var min = a.getMinutes();
    var sec = a.getSeconds();
    var formatted_time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec ;
	
	//Update UI:
	for (var key in timeline){
		$('#'+key).val(timeline[key]);
	}
	
	//Return raw dates:
	return timeline;
}


function save_r(){
	//Show spinner:
	$('#save_r_results').html('<img src="/img/round_load.gif" class="loader" />').hide().fadeIn();
	
	//Save Scheduling iFrame content:
	if(parseInt($('#r_live_office_hours_val').val())){
		document.getElementById('weekschedule').contentWindow.save_hours();
	}
	
	var save_data = {	
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
		r_cancellation_policy:$('input[name=r_cancellation_policy]:checked').val(),
		
		//Application:
		r_prerequisites:( r_prerequisites_quill.getLength()>1 ? $('#r_prerequisites .ql-editor').html() : "" ),
		r_application_questions:( r_application_questions_quill.getLength()>1 ? $('#r_application_questions .ql-editor').html() : "" ),
	};
	
	//Now merge into timeline dates:
	//var timeline = update_timeline();
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


function reset_default(){
	<?php $default_cohort_questions = $this->config->item('default_cohort_questions'); ?>
	$('#r_application_questions .ql-editor').html('<ol><li><?= join('</li><li>',$default_cohort_questions) ?></li></ol>');
	alert('Questions reset successful. Remember to save your changes.');
}
</script>




<input type="hidden" id="r_id" value="<?= $cohort['r_id'] ?>" />
<input type="hidden" id="week_count" value="<?= count($bootcamp['c__child_intents']) ?>" />






<ul class="nav nav-pills nav-pills-primary">
  <li class="active"><a href="#pill1" data-toggle="tab"><i class="fa fa-ticket" aria-hidden="true"></i> Admission</a></li>
  <li><a href="#pill2" data-toggle="tab"><i class="fa fa-life-ring" aria-hidden="true"></i> 1-on-1 Support</a></li>
  <li><a href="#pill3" data-toggle="tab"><i class="fa fa-usd" aria-hidden="true"></i> Pricing</a></li>
  <li><a href="#pill4" data-toggle="tab"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a></li>
</ul>




<div class="tab-content tab-space">

    <div class="tab-pane active" id="pill1">
        
    	<div class="title"><h4>Minimum Students</h4></div>
        <ul>
			<li>Minimum number of students required to kick-start the cohort.</li>
  			<li>All applicants would be refunded if the minimum is not met.</li>
  			<li>The value must be "1" or greater.</li>
		</ul>
        <div class="input-group">
          <input type="number" min="0" step="1" style="width:100px; margin-bottom:-5px;" id="r_min_students" value="<?= $cohort['r_min_students'] ?>" class="form-control border" />
        </div>
        
        
        <br />
        <div class="title"><h4>Maximum Students</h4></div>
        <ul>
			<li>Maximum number of students that can apply before cohort is full.</li>
  			<li>The next cohort (if any) would be displayed if this cohort gets maxed-out.</li>
  			<li>Remove the maximum limitation by setting it to "0".</li>
		</ul>
        <div class="input-group">
          <input type="number" min="0" step="1" style="width:100px; margin-bottom:-5px;" id="r_max_students" value="<?= $cohort['r_max_students'] ?>" class="form-control border" />
        </div>
		
		
		<br />
		<div class="title"><h4>Prerequisites</h4></div>
		<ul>
			<li>An optional list of requirements students must meet to join this cohort.</li>
  			<li>We ask students to confirm all prerequisites during their application.</li>
		</ul>
    	<div id="r_prerequisites"><?= $cohort['r_prerequisites'] ?></div>
        <script> var r_prerequisites_quill = new Quill('#r_prerequisites', setting_listo); </script>
        
        
		
		<br />
		<div class="title"><h4>Application Questions</h4></div>
  		<ul>
  			<li>Open-ended questions you'd like to ask students during their application.</li>
  			<li>Useful to assess student's desire level and suitability for this bootcamp.</li>
			<li>Include one question per point & we'll ask them in the same order.</li>
			<!-- <li>You can always <b><a href="javascript:reset_default();">reset to default questions</a></b> (and save).</li> -->
		</ul>
  		<div id="r_application_questions"><?= $cohort['r_application_questions'] ?></div>
        <script> var r_application_questions_quill = new Quill('#r_application_questions', setting_listo); </script>
        
    </div>
    
    
    <div class="tab-pane" id="pill2">
    
		<div class="title"><h4>Response Time</h4></div>
		<ul>
			<li>On Mench you are required to respond to all student inquiries.</li>
			<li>You get to choose how fast you commit to responding.</li>
		</ul>
        <select class="form-control input-mini border" id="r_response_time_hours">
        	<?php 
        	$r_response_options = $this->config->item('r_response_options');
        	foreach($r_response_options as $time){
        	    echo '<option value="'.$time.'" '.( $cohort['r_response_time_hours']==$time ? 'selected="selected"' : '' ).'>Under '.echo_hours($time).'</option>';
        	}
        	?>
        </select>
        
        
		<div class="title"><h4>1-on-1 Mentorship</h4></div>
		<ul>
			<li>Provide personalized 1-on-1 support to each student.</li>
			<li>Usually conducted over live video (Skype, Zoom, Hangouts, etc...)</li>
			<li>May also include screen sharing and assignment review.</li>
		</ul>
        <select class="form-control input-mini border" id="r_weekly_1on1s" style="width:300px;">
        	<?php 
        	$weekly_1on1s_options = $this->config->item('r_weekly_1on1s_options');
        	foreach($weekly_1on1s_options as $time){
        	    echo '<option value="'.$time.'" '.( $cohort['r_weekly_1on1s']==$time ? 'selected="selected"' : '' ).'>'.echo_hours($time).' per student per '.$bootcamp['b_sprint_unit'].'</option>';
        	}
        	?>
        </select>
        
        
        
        
        
        
		
		<div class="title"><h4>Live Office Hours</h4></div>
		<ul>
			<li>Use to offer live support to your students who choose to show-up.</li>
			<li>Usually conducted over live video (Skype, Zoom, Hangouts, etc...)</li>
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
    			<li>We send automatic reminders 30-minutes prior to each office hour.</li>
    		</ul>
            <div class="form-group label-floating is-empty">
                <textarea class="form-control text-edit border" placeholder="Contact using our Skype username: grumomedia" id="r_office_hour_instructions"><?= $cohort['r_office_hour_instructions'] ?></textarea>
            </div>
            
            
            <div class="title"><h4>Office Hours: Weekly Schedule</h4></div>
            <ul>
      			<li>Set office hours in PST -8:00 (Los Angeles) timezone.</li>
    			<li>We will adjust hours based on each student's timezone.</li>
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
    
    
    
    
    <div class="tab-pane" id="pill3">
    	
    	<div class="title"><h4>Admission Price</h4></div>
  		<ul>
  			<li>Set based on bootcamp length and level of 1-on-1 support.</li>
			<li>We recommend charging $100-$200 per support hour.</li>
			<li>Enter "0" for free cohorts. Our commission is 15% for paid cohorts.</li>
		</ul>
        <div class="input-group">
          <span class="input-group-addon addon-lean">USD $</span>
          <input type="number" min="0" step="0.01" style="width:100px; margin-bottom:-5px;" id="r_usd_price" value="<?= $cohort['r_usd_price'] ?>" class="form-control border" />
        </div>
        
        <br />
        <div class="title"><h4>Cancellation Policy (for Paid Cohorts)</h4></div>
		<?php 
		$cancellation_policies = $this->config->item('cancellation_policies');
		foreach($cancellation_policies as $key=>$policies){
		    echo '<div class="radio">
        	<label>
        		<input type="radio" name="r_cancellation_policy" value="'.$key.'" '.( $cohort['r_cancellation_policy']==$key ? 'checked="true"' : '' ).' />
        		'.ucwords($key).'
        	</label>
        	<ul style="margin-left:15px;">';
		    foreach($policies as $policy){
		        echo '<li>'.$policy.'</li>';
		    }
        	echo '</ul></div>';
		}
		?>
        <p>Learn more about our <a href="https://support.mench.co/hc/en-us/articles/115002095952" target="_blank">Cancellation Policies <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></a></p>
        

    </div>
    
    
    <div class="tab-pane" id="pill4">
    
    
    	<div class="title"><h4>Cohort Start Day</h4></div>
  		<ul>
			<li>The bootcamp's start day for this cohort of students.</li>
  			<li>End date automatically calculated based on the number of <a href="/console/<?= $bootcamp['b_id'] ?>/actionplan"><b><?= $sprint_units[$bootcamp['b_sprint_unit']]['name'] ?> Goals</b></a>.</li>
  			<?php if($bootcamp['b_sprint_unit']=='week'){ ?>
  			<li><?= $sprint_units[$bootcamp['b_sprint_unit']]['name'] ?> bootcamps always start on Mondays and end on Sundays.</li>
  			<?php } ?>
		</ul>
        <div class="form-group label-floating is-empty">
            <input type="text" id="r_start_date" value="<?= date("m/d/Y" , strtotime($cohort['r_start_date']) ) ?>" style="width:233px;" class="form-control border" />
            <span class="material-input"></span>
        </div>
        <?php /*
        <ul style="list-style:none; margin-left:-20px;">
  			<li><span style="width:165px; display:inline-block;">Admission Starts</span><span class="dynamic" id="r_cache_registration_start_time"></span></li>
			<li><span style="width:165px; display:inline-block;">Admission Ends</span><span class="dynamic" id="r_cache_registration_end_time"></span></li>
			<li><span style="width:165px; display:inline-block;">Bootcamp Starts</span><span class="dynamic" id="r_cache_cohort_first_day"></span></li>
			<li><span style="width:165px; display:inline-block;">Bootcamp Ends</span><span class="dynamic" id="r_cache_cohort_last_day"></span></li>
		</ul>
        */?>
        <br />
    
    
		<div class="title"><h4>Cohort Status</h4></div>
		<ul>
			<li>Only displayed in landing page if status is <?= status_bible('r',1) ?>.</li>
			<li>If a cohort is full, the next published cohort would become open for admission.</li>
		</ul>
	 	<?php echo_status_dropdown('r','r_status',$cohort['r_status']); ?>
		<br />
		
    </div>
</div>
        
<br />
<table width="100%"><tr><td class="save-td"><a href="javascript:save_r();" class="btn btn-primary">Save</a></td><td><span id="save_r_results"></span></td></tr></table>
