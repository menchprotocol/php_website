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

function adjust_mentorship_sessions(){
	var val = $('#r_meeting_frequency').val();
	
	if(val=='0'){
		$('#r_meeting_duration').hide();
	} else {
		$('#r_meeting_duration').fadeIn();
	}
	
	if(val=='d1' || val=='w1' || val=='w2' || val=='w3' || val=='w5'){
		$('#frequency_details').html('(Excluding <i class="fa fa-coffee" aria-hidden="true"></i> Break Milestone)');
	} else {
		$('#frequency_details').html('');
	}
}

function update_tuition_calculator(){

	//Save the rest of the content:
	$.post("/process/tuition_calculator", {	
		
		//Object IDs:
		r_id:$('#r_id').val(),
		b_id:$('#b_id').val(),
		
		//Service Factors:
		r_response_time_hours:$('#r_response_time_hours').val(),
		r_meeting_frequency:$('#r_meeting_frequency').val(),
		r_meeting_duration:$('#r_meeting_duration').val(),

		//Duration:
		b_sprint_unit:$('#b_sprint_unit').val(),
		b_effective_milestones:$('#b_effective_milestones').val(),
		c__estimated_hours:$('#c__estimated_hours').val(),
		whatif_selection:( $("#whatif_selection").length==0 ? null : $('#whatif_selection').val() ),
		
	} , function(data) {
		
		//Update UI to confirm with user:
		$('#calculator_body').html(data).hide().fadeIn();

		//Activate Tooltip:
		$('[data-toggle="tooltip"]').tooltip();
		
    });
}

$(document).ready(function() {
    
	//Detect any possible hashes that controll the menu?
	if(window.location.hash) {
		focu_hash(window.location.hash);
    }

	
    //Tuition Calculator:
    update_tuition_calculator();
    $( "#r_response_time_hours, #r_meeting_frequency, #r_meeting_duration" /* #r_student_reach, #r_min_students, #r_max_students */ ).change(function() {
    	update_tuition_calculator();
    });

    //The inner Select change in the pricing calculator:
	$("#calculator_body").on("change", "#whatif_selection", function(){
		update_tuition_calculator();
	});
	
    
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

	
	adjust_mentorship_sessions();
	$('#r_meeting_frequency').change(function() {
		adjust_mentorship_sessions();		
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
		r_meeting_frequency:$('#r_meeting_frequency').val(),
		r_meeting_duration:$('#r_meeting_duration').val(),
		r_live_office_hours_check:$('#r_live_office_hours_val').val(),
		r_office_hour_instructions:$('#r_office_hour_instructions').val(),
		r_closed_dates:$('#r_closed_dates').val(),
		r_start_time_mins:$('#r_start_time_mins').val(),
		
		//Class:
		r_status:$('#r_status').val(),
		r_usd_price:$('#r_usd_price').val(),
		r_student_reach:$('#r_student_reach').val(),
		r_min_students:$('#r_min_students').val(),
		r_max_students:$('#r_max_students').val(),
		r_cancellation_policy:$('input[name=r_cancellation_policy]:checked').val(),
		
		//Item lists:
		r_application_questions: fetch_submit('r_application_questions'),
		r_prerequisites: fetch_submit('r_prerequisites'),
		r_completion_prizes: fetch_submit('r_completion_prizes'),
	};

	console.log(save_data);
	
	//Now merge into timeline dates:
	//for (var key in timeline){
	//	save_data[key] = timeline[key];
	//}
	
	//Save the rest of the content:
	$.post("/process/class_edit", save_data , function(data) {
		//Update UI to confirm with user:
		$('#save_r_results').html(data).hide().fadeIn();
		
		//Disapper in a while:
		setTimeout(function() {
			$('#save_r_results').fadeOut();
	    }, 10000);
    });
}
</script>




<input type="hidden" id="r_id" value="<?= $class['r_id'] ?>" />
<input type="hidden" id="b_id" value="<?= $class['r_b_id'] ?>" />
<input type="hidden" id="b_effective_milestones" value="<?= ( count($bootcamp['c__child_intents']) - $bootcamp['c__break_milestones'] ) ?>" />
<input type="hidden" id="c__estimated_hours" value="<?= $bootcamp['c__estimated_hours'] ?>" />
<input type="hidden" id="b_sprint_unit" value="<?= $bootcamp['b_sprint_unit'] ?>" />



<ul id="topnav" class="nav nav-pills nav-pills-primary">
  <li id="nav_screening" class="active"><a href="#screening" data-toggle="tab" onclick="update_hash('screening')"><i class="fa fa-search" aria-hidden="true"></i> Screening</a></li>
  <li id="nav_support"><a href="#support" data-toggle="tab" onclick="update_hash('support')"><i class="fa fa-life-ring" aria-hidden="true"></i> Support</a></li>
  <li id="nav_pricing"><a href="#pricing" data-toggle="tab" onclick="update_hash('pricing')"><i class="fa fa-usd" aria-hidden="true"></i> Pricing</a></li>
  <li id="nav_settings"><a href="#settings" data-toggle="tab" onclick="update_hash('settings')"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a></li>
</ul>




<div class="tab-content tab-space">


    
        <div class="tab-pane active" id="screening">
        
        
        <div class="title"><h4><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Prerequisites</h4></div>
        <ul>
        	<li>A list of requirements students must meet to join this bootcamp.</li>
        	<li>We ask students to confirm all prerequisites during their application.</li>
        </ul>
        <script>
        $(document).ready(function() {
        	initiate_list('r_prerequisites','+ New Prerequisite','<i class="fa fa-exclamation-triangle"></i>',<?= ( strlen($class['r_prerequisites'])>0 ? $class['r_prerequisites'] : '[]' ) ?>);
        });
        </script>
        <div id="r_prerequisites" class="list-group"></div>
    
    
    
    	<div class="title" style="margin-top:30px;"><h4><i class="fa fa-question-circle" aria-hidden="true"></i> Application Questions</h4></div>
        <ul>
        	<li>Open-ended questions you'd like to ask students during their application.</li>
        	<li>Students are required to answer every question.</li>
        	<li>These questions can help you learn more about each student and assess their desire level and suitability for this bootcamp.</li>
        </ul>
        
        <script>
        $(document).ready(function() {
        	initiate_list('r_application_questions','+ New Question','<i class="fa fa-question-circle"></i>',<?= ( strlen($class['r_application_questions'])>0 ? $class['r_application_questions'] : '[]' ) ?>);
        });
        </script>
        <div id="r_application_questions" class="list-group"></div>
    
    
    	<div style="display:block; margin-top:30px;">
            <div class="title"><h4><i class="fa fa-thermometer-empty" aria-hidden="true"></i> Minimum Students</h4></div>
            <ul>
            	<li>Minimum number of students required to kick-start this class.</li>
            	<li>All applicants will be refunded if the minimum is not met.</li>
            	<li>Set the minimum to 1 or greater.</li>
            </ul>
            <div class="input-group">
            	<input type="number" min="0" step="1" style="width:100px; margin-bottom:-5px;" id="r_min_students" value="<?= (isset($class['r_min_students'])?$class['r_min_students']:null) ?>" class="form-control border" />
            </div>
            <br />
        </div>
        
        
        <div class="title"><h4><i class="fa fa-thermometer-full" aria-hidden="true"></i> Maximum Students</h4></div>
        <ul>
        	<li>Maximum number of students that can apply before class is full.</li>
        	<li>Consider your audience size to leverage this field to create a sense of scarcity.</li>
        	<li>If a class is full, the next published class would become open for admission.</li>
        	<li>You can remove this maximum limitation by setting it to "0".</li>
        </ul>
        <div class="input-group">
          <input type="number" min="0" step="1" style="width:100px; margin-bottom:-5px;" id="r_max_students" value="<?= ( isset($class['r_max_students']) ? $class['r_max_students'] : null ) ?>" class="form-control border" />
        </div>
        
    </div>
    
    
    <div class="tab-pane" id="support">
    
    
		<div class="title"><h4><i class="fa fa-bolt" aria-hidden="true"></i> Inquiry Response Time <span class="badge pricing-badge" data-toggle="tooltip" title="Changing this setting will change the suggested price of the Tuition Calculator. Checkout the Pricing tab for more details." data-placement="bottom"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> AFFECTS PRICING</span></h4></div>
        <ul>
        	<li>Student communication is done on Facebook Messenger using <a href="#" data-toggle="modal" data-target="#MenchBotModal"><i class="fa fa-commenting" aria-hidden="true"></i> <u>MenchBot</u></a>.</li>
        	<li>You are required to respond to all incoming messages from your students.</li>
        	<li>You get to choose how fast you commit to responding to messages.</li>
        	<li>You can delegate support to <?= status_bible('ba',2) ?>.</li>
        </ul>
        <select class="form-control input-mini border" id="r_response_time_hours">
        <option value="">Select Responsiveness</option>
        <?php 
        $r_response_options = $this->config->item('r_response_options');
        foreach($r_response_options as $time){
            echo '<option value="'.$time.'" '.( isset($class['r_response_time_hours']) && $class['r_response_time_hours']==$time ? 'selected="selected"' : '' ).'>Under '.echo_hours($time).'</option>';
        }
        ?>
        </select>



		
		
		<div class="title" style="margin-top:30px;"><h4><i class="fa fa-handshake-o" aria-hidden="true"></i> 1-on-1 Mentorship <span class="badge pricing-badge" data-toggle="tooltip" title="Changing this setting will change the suggested price of the Tuition Calculator. Checkout the Pricing tab for more details." data-placement="bottom"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> AFFECTS PRICING</span></h4></div>
        <ul>
        	<li>If Set, Every student gets 1-on-1 mentorship to maximize chance of success.</li>
        	<li>Recommended for difficult-to-execute bootcamps to help students 1-on-1.</li>
        	<li>Use a Calendar app to manually setup your meetings with each student.</li>
        	<li>Use a video chat app like Skype, Zoom or Hangouts to conduct meetings.</li>
        </ul>
        <table style="width:100%;">
        	<tr>
        		<td style="width:150px;">
        			<select class="form-control input-mini border" id="r_meeting_frequency">
                        <?php
                        if(strlen($class['r_meeting_frequency'])==0){
                            echo '<option value="">Select...</option>';
                        }
                        $r_meeting_frequency = $this->config->item('r_meeting_frequency');
                        foreach($r_meeting_frequency as $val=>$name){
                            echo '<option value="'.$val.'" '.( $class['r_meeting_frequency'].''==$val.'' ? 'selected="selected"' : '' ).'>'.$name.'</option>';
                        }
                        ?>
                    </select>
        		</td>
        		<td style="width:160px;">
        			<select class="form-control input-mini border" id="r_meeting_duration">
                        <?php
                        $r_meeting_duration = $this->config->item('r_meeting_duration');
                        foreach($r_meeting_duration as $time){
                            echo '<option value="'.$time.'" '.( $class['r_meeting_duration']==$time ? 'selected="selected"' : '' ).'>@ '.echo_hours($time).' Each</option>';
                        }
                        ?>
                    </select>
        		</td>
        		<td>
                    <span id="frequency_details"></span>
        		</td>
        	</tr>
        </table>
    	


		<div class="title" style="margin-top:30px;"><h4><i class="fa fa-podcast" aria-hidden="true"></i> Weekly Office Hours <span class="badge pricing-badge" data-toggle="tooltip" title="Changing this setting will change the suggested price of the Tuition Calculator. Checkout the Pricing tab for more details." data-placement="bottom"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> AFFECTS PRICING</span></h4></div>
		<ul>
			<li>Provide virtual group support to students who show-up during office hours.</li>
			<li>Students will receive a broadcast message 30 minute before each timeslot.</li>
			<li>Use a group video chat app like Skype, Zoom or Hangouts to conduct meetings.</li>
		</ul>
		
		
		<input type="hidden" id="r_live_office_hours_val" value="<?= strlen($class['r_live_office_hours'])>0 ? '1' : '0' ?>" />
		<div class="checkbox">
        	<label>
        		<input type="checkbox" id="r_live_office_hours_check" <?= strlen($class['r_live_office_hours'])>0 ? 'checked' : '' ?>>
        		Enable Weekly Office Hours
        	</label>
        </div>
		
		<div class="has_office_hours" style="display:<?= strlen($class['r_live_office_hours'])>0 ? 'block' : 'none' ?>;">
			
			<div class="title"><h4>Office Hours: Contact Method</h4></div>
			<ul>
      			<li>Instructions on how students can contact you or your team.</li>
    			<li>Include Skype ID, Google Hangout link, Zoom video confrence url, etc...</li>
    			<li>Mench sends automatic reminders 30-minutes prior to each office hour.</li>
    		</ul>
            <div class="form-group label-floating is-empty">
                <textarea class="form-control text-edit border" placeholder="Contact using our Skype username: grumomedia" id="r_office_hour_instructions"><?= $class['r_office_hour_instructions'] ?></textarea>
            </div>
            
            
            <div class="title"><h4>Office Hours: Weekly Schedule <span class="badge pricing-badge" data-toggle="tooltip" title="Changing this setting will change the suggested price of the Tuition Calculator. Checkout the Pricing tab for more details." data-placement="bottom"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> AFFECTS PRICING</span></h4></div>
            <ul>
      			<li>Set office hours in PST timezone (Currently <?= time_format(time(),7) ?>).</li>
    			<li>Mench will adjust hours based on each student's timezone.</li>
    			<li>Click once to insert new time-frame and then drag to expand.</li>
    		</ul>
            <iframe id="weekschedule" src="/console/<?= $bootcamp['b_id'] ?>/classes/<?= $class['r_id'] ?>/scheduler" scrolling="no" class="scheduler-iframe"></iframe>
			
			
            <div class="title"><h4>Office Hours: Close Dates</h4></div>
            <ul>
      			<li>Manually define the dates that you would not provide office hours.</li>
    		</ul>
            <div class="form-group label-floating is-empty">
                <textarea class="form-control text-edit border" placeholder="Plain text like: Nov 23, Dec 25, Dec 26 and Jan 1" id="r_closed_dates"><?= $class['r_closed_dates'] ?></textarea>
            </div>
            
		</div>
    </div>
    

    
    
    
    
    <div class="tab-pane" id="pricing">
    
        
        <div style="display:block;">
        	<div class="title"><h4><i class="fa fa-calculator" aria-hidden="true"></i> Tuition Calculator</h4></div>
            
    			<?php 
    			/*
    			echo '<select class="form-control input-mini border" id="r_student_reach">';
    			$r_student_reach = $this->config->item('r_student_reach');
    			foreach($r_student_reach as $val=>$name){
    			    echo '<option value="'.$val.'" '.( $class['r_student_reach']==$val ? 'selected="selected"' : '' ).'>'.$name.'</option>';
    			}
    			echo '</select>';
    			*/
    			?>
    		
            <div id="calculator_body"></div>
        </div>
            
        
        
        <div class="title" style="margin-top:30px;"><h4><i class="fa fa-usd" aria-hidden="true"></i> Tuition Rate</h4></div>
        <ul>
        	<li>A 1-time fee for student to join this bootcamp class.</li>
        	<li>Use above calculator to estimate your customized tuition rate.</li>
        	<li>Enter "0" if free. Good for shorter lead generation bootcamps.</li>
        	<li>Learn more about <a href="https://support.mench.co/hc/en-us/articles/115002473111" target="_blank" style="display:inline-block;">Commission Rates & Payout Installments <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></a>.</li>
        	<li>Contact us to setup co-instructor revenue sharing or student payment plans.</li>
        </ul>
        <div class="input-group">
        	<span class="input-group-addon addon-lean">USD $</span>
        	<input type="number" min="0" step="0.01" style="width:100px; margin-bottom:-5px;" id="r_usd_price" value="<?= isset($class['r_usd_price']) && floatval($class['r_usd_price'])>=0 ? $class['r_usd_price'] : null ?>" class="form-control border" />
        </div>
        <br />
        
      
        
        
        
        
        <div class="title" style="margin-top:30px;"><h4><i class="fa fa-shield" aria-hidden="true"></i> Refund Policy (For Paid Classes)</h4></div>
        <?php 
        $refund_policies = $this->config->item('refund_policies');
        foreach($refund_policies as $type=>$terms){
            echo '<div class="radio">
        	<label>
        		<input type="radio" name="r_cancellation_policy" value="'.$type.'" '.( isset($class['r_cancellation_policy']) && $class['r_cancellation_policy']==$type ? 'checked="true"' : '' ).' />
        		'.ucwords($type).'
        	</label>
        	<ul style="margin-left:15px;">';
            echo '<li>Full Refund: '.( $terms['full']>0 ? '<b>Before '.($terms['full']*100).'%</b> of the class\'s elapsed time' : ( $terms['prorated']>0 ? '<b>Before Start Date</b> of the class' : '<b>None</b> After Admission' ) ).'.</li>';
              echo '<li>Pro-rated Refund: '.( $terms['prorated']>0 ? '<b>Before '.($terms['prorated']*100).'%</b> of the class\'s elapsed time' : '<b>None</b> After Admission' ).'.</li>';
        	echo '</ul></div>';
        }
        ?>
        <p>Students will always receive a full refund if you reject their application during the admission screeing process. Learn more about <a href="https://support.mench.co/hc/en-us/articles/115002095952" target="_blank" style="display:inline-block;">Refund Policies <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></a>.</p>
        
        
        
        <div class="title" style="margin-top:30px;"><h4><i class="fa fa-gift" aria-hidden="true"></i> Optional Completion Prizes</h4></div>
        <ul>
        	<li>Awarded to students who complete all milestones by the last day of this class.</li>
        	<li>Prizes are an additional incentive to increase your bootcamp's completion rate.</li>
        	<li>Completion prizes are published on your landing page's Admission section.</li>
        </ul>
        
        <script>
        $(document).ready(function() {
        	initiate_list('r_completion_prizes','+ New Prize','<i class="fa fa-gift"></i>',<?= ( strlen($class['r_completion_prizes'])>0 ? $class['r_completion_prizes'] : '[]' ) ?>);
        });
        </script>
        <div id="r_completion_prizes" class="list-group"></div>
    </div>
    
    
    <div class="tab-pane" id="settings">
    
        <?php $this->load->view('console/inputs/r_status' , array('r_status'=>$class['r_status']) ); ?>
		<br />
        <?php $this->load->view('console/inputs/r_start_day_time' , array(
            'c__child_intent_count' => count($bootcamp['c__child_intents']),
            'b_sprint_unit' => $bootcamp['b_sprint_unit'],
            'b_id' => $bootcamp['b_id'],
            'b_status' => $bootcamp['b_status'],
            'r_start_date' => $class['r_start_date'],
            'r_start_time_mins' => $class['r_start_time_mins'],
        )); ?>
    	
    </div>
</div>


<br />
<table width="100%"><tr><td class="save-td"><a href="javascript:save_r();" class="btn btn-primary">Save</a></td><td><span id="save_r_results"></span></td></tr></table>
