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
	$.post("/api_v1/tuition_calculator", {	
		
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

function changeContactMethod(){
    var len = $('#r_office_hour_instructions').val().length;
    if (len > 420) {
    	$('#ContactMethodChar').addClass('overload').text(len);
    } else {
        $('#ContactMethodChar').removeClass('overload').text(len);
    }
}
function changeCloseDates(){
    var len = $('#r_closed_dates').val().length;
    if (len > 420) {
    	$('#CloseDatesChar').addClass('overload').text(len);
    } else {
        $('#CloseDatesChar').removeClass('overload').text(len);
    }
}

var load_calculator = 0;

function initiate_calculator(){
	$('#calculator_body').html('<img src="/img/round_load.gif" class="loader" />').hide().fadeIn();
	load_calculator = 1;
	update_tuition_calculator();
}

$(document).ready(function() {
    
	//Detect any possible hashes that controll the menu?
	if(window.location.hash) {
		focu_hash(window.location.hash);
    }

    //Update counters:
    changeContactMethod();
    changeCloseDates();
	
    //Tuition Calculator:
    $( "#r_response_time_hours, #r_meeting_frequency, #r_meeting_duration" /* #r_student_reach, #r_min_students, #r_max_students */ ).change(function() {
        if(load_calculator){
        	update_tuition_calculator();
        }
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
		r_fb_pixel_id:$('#r_fb_pixel_id').val(),
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
	
	//Now merge into timeline dates:
	//for (var key in timeline){
	//	save_data[key] = timeline[key];
	//}
	
	//Save the rest of the content:
	$.post("/api_v1/class_edit", save_data , function(data) {
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
  <li id="nav_screening" class="active"><a href="#screening" data-toggle="tab" onclick="update_hash('screening')"><i class="fa fa-eye" aria-hidden="true"></i> Screening</a></li>
  <li id="nav_support"><a href="#support" data-toggle="tab" onclick="update_hash('support')"><i class="fa fa-life-ring" aria-hidden="true"></i> Support</a></li>
  <li id="nav_pricing"><a href="#pricing" data-toggle="tab" onclick="update_hash('pricing')"><i class="fa fa-calculator" aria-hidden="true"></i> Pricing</a></li>
  <li id="nav_settings"><a href="#settings" data-toggle="tab" onclick="update_hash('settings')"><i class="fa fa-pencil" aria-hidden="true"></i> Details</a></li>
</ul>




<div class="tab-content tab-space">


        <div class="tab-pane active" id="screening">
        
        
        <div class="title"><h4><i class="fa fa-check-square-o" aria-hidden="true"></i> Prerequisites <span id="hb_610" class="help_button" intent-id="610"></span></h4></div>
        <div class="help_body maxout" id="content_610"></div>
        <script>
        $(document).ready(function() {
        	initiate_list('r_prerequisites','+ New Prerequisite','<i class="fa fa-check-square-o" aria-hidden="true"></i>',<?= ( strlen($class['r_prerequisites'])>0 ? $class['r_prerequisites'] : '[]' ) ?>);
        });
        </script>
        <div id="r_prerequisites" class="list-group"></div>
    
    
    
    	<div class="title" style="margin-top:30px;"><h4><i class="fa fa-question-circle" aria-hidden="true"></i> Application Questions <span id="hb_611" class="help_button" intent-id="611"></span></h4></div>
        <div class="help_body maxout" id="content_611"></div>
        <script>
        $(document).ready(function() {
        	initiate_list('r_application_questions','+ New Application Question','<i class="fa fa-question-circle"></i>',<?= ( strlen($class['r_application_questions'])>0 ? $class['r_application_questions'] : '[]' ) ?>);
        });
        </script>
        <div id="r_application_questions" class="list-group"></div>
    
    
    	<div style="display:block; margin-top:30px;">
            <div class="title"><h4><i class="fa fa-thermometer-empty" aria-hidden="true"></i> Minimum Students <span id="hb_612" class="help_button" intent-id="612"></span></h4></div>
            <div class="help_body maxout" id="content_612"></div>
            <div class="input-group">
            	<input type="number" min="0" step="1" style="width:100px; margin-bottom:-5px;" id="r_min_students" value="<?= (isset($class['r_min_students'])?$class['r_min_students']:null) ?>" class="form-control border" />
            </div>
            <br />
        </div>
        
        
        <div class="title"><h4><i class="fa fa-thermometer-full" aria-hidden="true"></i> Maximum Students <span id="hb_613" class="help_button" intent-id="613"></span></h4></div>
        <div class="help_body maxout" id="content_613"></div>
        <div class="input-group">
          <input type="number" min="0" step="1" style="width:100px; margin-bottom:-5px;" id="r_max_students" value="<?= ( isset($class['r_max_students']) ? $class['r_max_students'] : null ) ?>" class="form-control border" />
        </div>
        
    </div>
    
    
    <div class="tab-pane" id="support">
    
    	<?php itip(630); ?>
		<div class="title"><h4><i class="fa fa-bolt" aria-hidden="true"></i> Response Time <span class="badge pricing-badge" data-toggle="tooltip" title="Changing this setting will change the suggested price of the Tuition Calculator. Checkout the Pricing tab for more details." data-placement="bottom"><i class="fa fa-calculator" aria-hidden="true"></i></span> <span id="hb_614" class="help_button" intent-id="614"></span></h4></div>
        <div class="help_body maxout" id="content_614"></div>
        <select class="form-control input-mini border" id="r_response_time_hours">
        <option value="">Select Responsiveness</option>
        <?php 
        $r_response_options = $this->config->item('r_response_options');
        foreach($r_response_options as $time){
            echo '<option value="'.$time.'" '.( isset($class['r_response_time_hours']) && $class['r_response_time_hours']==$time ? 'selected="selected"' : '' ).'>Under '.echo_hours($time).'</option>';
        }
        ?>
        </select>



		
		
		<div class="title" style="margin-top:30px;"><h4><i class="fa fa-handshake-o" aria-hidden="true"></i> 1-on-1 Mentorship <span class="badge pricing-badge" data-toggle="tooltip" title="Changing this setting will change the suggested price of the Tuition Calculator. Checkout the Pricing tab for more details." data-placement="bottom"><i class="fa fa-calculator" aria-hidden="true"></i></span> <span id="hb_615" class="help_button" intent-id="615"></span></h4></div>
        <div class="help_body maxout" id="content_615"></div>
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
        



		<div class="title" style="margin-top:30px;"><h4><i class="fa fa-podcast" aria-hidden="true"></i> Weekly Office Hours <span class="badge pricing-badge" data-toggle="tooltip" title="Changing this setting will change the suggested price of the Tuition Calculator. Checkout the Pricing tab for more details." data-placement="bottom"><i class="fa fa-calculator" aria-hidden="true"></i></span> <span id="hb_616" class="help_button" intent-id="616"></span></h4></div>
		<div class="help_body maxout" id="content_616"></div>
		
		
		<input type="hidden" id="r_live_office_hours_val" value="<?= strlen($class['r_live_office_hours'])>0 ? '1' : '0' ?>" />
		<div class="checkbox">
        	<label>
        		<input type="checkbox" id="r_live_office_hours_check" <?= strlen($class['r_live_office_hours'])>0 ? 'checked' : '' ?>>
        		Enable Weekly Office Hours
        	</label>
        </div>
		
		<div class="has_office_hours" style="display:<?= strlen($class['r_live_office_hours'])>0 ? 'block' : 'none' ?>;">
			
			<div class="title"><h4><i class="fa fa-commenting" aria-hidden="true"></i> Office Hour Contact Method <span id="hb_617" class="help_button" intent-id="617"></span></h4></div>
			<div class="help_body maxout" id="content_617"></div>
            <div class="form-group label-floating is-empty">
                <textarea class="form-control text-edit border" style="min-height:50px;" onkeyup="changeContactMethod()" placeholder="Contact using our Skype username: grumomedia" id="r_office_hour_instructions"><?= $class['r_office_hour_instructions'] ?></textarea>
                <div style="margin:0 0 0 0; font-size:0.8em;"><span id="ContactMethodChar">0</span>/420</div>
            </div>
            
            <div class="title"><h4><i class="fa fa-calendar" aria-hidden="true"></i> Weekly Schedule PST <span class="badge pricing-badge" data-toggle="tooltip" title="Changing this setting will change the suggested price of the Tuition Calculator. Checkout the Pricing tab for more details." data-placement="bottom"><i class="fa fa-calculator" aria-hidden="true"></i></span> <span id="hb_618" class="help_button" intent-id="618"></span></h4></div>
            <div class="help_body maxout" id="content_618"></div>
            <iframe id="weekschedule" src="/console/<?= $bootcamp['b_id'] ?>/classes/<?= $class['r_id'] ?>/scheduler" scrolling="no" class="scheduler-iframe"></iframe>
		</div>
		
		
		<div class="title" style="margin-top:30px;"><h4><i class="fa fa-commenting" aria-hidden="true"></i> Close Dates <span id="hb_619" class="help_button" intent-id="619"></span></h4></div>
        <div class="help_body maxout" id="content_619"></div>
        <div class="form-group label-floating is-empty">
            <textarea class="form-control text-edit border" style="min-height:50px;" onkeyup="changeCloseDates()" placeholder="We will be closed on Dec 25-26 and Jan 1" id="r_closed_dates"><?= $class['r_closed_dates'] ?></textarea>
            <div style="margin:0 0 10px 0; font-size:0.8em;"><span id="CloseDatesChar">0</span>/420</div>
        </div>
    	
    	
    	
    </div>
    
    
    
    
    <div class="tab-pane" id="pricing">
    
        
        <div style="display:block;">
        	<div class="title"><h4><i class="fa fa-calculator" aria-hidden="true"></i> Tuition Calculator <span id="hb_620" class="help_button" intent-id="620"></span></h4></div>
            <div class="help_body maxout" id="content_620" style="margin-bottom:50px;"></div>
            <div id="calculator_body"><a href="javascript:initiate_calculator()" class="btn btn-primary">Load Calculator</a></div>
        </div>
            
        
        
        <div class="title" style="margin-top:30px;"><h4><i class="fa fa-usd" aria-hidden="true"></i> Tuition Rate <span id="hb_621" class="help_button" intent-id="621"></span></h4></div>
        <div class="help_body maxout" id="content_621"></div>
        <div class="input-group">
        	<span class="input-group-addon addon-lean">USD $</span>
        	<input type="number" min="0" step="0.01" style="width:100px; margin-bottom:-5px;" id="r_usd_price" value="<?= isset($class['r_usd_price']) && floatval($class['r_usd_price'])>=0 ? $class['r_usd_price'] : null ?>" class="form-control border" />
        </div>
        <br />
        
      
        
        
        
        
        <div class="title" style="margin-top:30px;"><h4><i class="fa fa-shield" aria-hidden="true"></i> Refund Policy <span id="hb_622" class="help_button" intent-id="622"></span></h4></div>
        <div class="help_body maxout" id="content_622"></div>
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
        
        
        <div class="title" style="margin-top:30px;"><h4><i class="fa fa-gift" aria-hidden="true"></i> Optional Completion Prizes <span id="hb_623" class="help_button" intent-id="623"></span></h4></div>
        <div class="help_body maxout" id="content_623"></div>
        <script>
        $(document).ready(function() {
        	initiate_list('r_completion_prizes','+ New Prize','<i class="fa fa-gift"></i>',<?= ( strlen($class['r_completion_prizes'])>0 ? $class['r_completion_prizes'] : '[]' ) ?>);
        });
        </script>
        <div id="r_completion_prizes" class="list-group"></div>
    </div>
    
    
    <div class="tab-pane" id="settings">
    
        <?php $this->load->view('console/inputs/r_status' , array('r_status'=>$class['r_status']) ); ?>		
		
		<div style="display:block; margin-top:20px;">
            <div class="title"><h4><i class="fa fa-facebook-official" aria-hidden="true"></i> Facebook Pixel ID <span id="hb_718" class="help_button" intent-id="718"></span></h4></div>
            <div class="help_body maxout" id="content_718"></div>
            <div class="input-group">
            	<input type="number" min="0" step="1" style="width:220px; margin-bottom:-5px;" id="r_fb_pixel_id" placeholder="123456789012345" value="<?= (strlen($class['r_fb_pixel_id'])>1?$class['r_fb_pixel_id']:null) ?>" class="form-control border" />
            </div>
            <br />
        </div>
		
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
