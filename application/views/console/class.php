<?php
//Fetch the sprint units from config:
$sprint_units = $this->config->item('sprint_units');
$message_max = $this->config->item('message_max');
$website = $this->config->item('website');
$udata = $this->session->userdata('user');

//Determine lock down status:
$is_admin = ( $udata['u_status']>=3 );
$disabled = ( !$is_admin && ($current_applicants>0 || $class['r_status']>=2) ? 'disabled' : null );
$soft_disabled = ( !$is_admin && $class['r_status']>=2 ? 'disabled' : null );

//Fetch the most recent cached action plans:
$cache_action_plans = $this->Db_model->e_fetch(array(
    'e_type_id' => 70,
    'e_r_id' => $class['r_id'],
),1,true);

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
}

function hide_refunds(){
    //See what the current price is:
    var price = $('#r_usd_price').val();
    if(price.length && parseInt(price)==0){
        //Seems like a free Bootcamp, hide refunds:
        $('#refund_policies').addClass('hidden');
    } else {
        $('#refund_policies').removeClass('hidden');
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
        c__milestone_units:$('#c__milestone_units').val(),
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
    if (len > <?= $message_max ?>) {
    	$('#ContactMethodChar').addClass('overload').text(len);
    } else {
        $('#ContactMethodChar').removeClass('overload').text(len);
    }
}
function changeCloseDates(){
    var len = $('#r_closed_dates').val().length;
    if (len > <?= $message_max ?>) {
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

//Detect any possible hashes that controll the menu?


$(document).ready(function() {

    //Need to hide the price?
    hide_refunds();
    $( "#r_usd_price" ).change(function() {
        hide_refunds();
    });


    if(window.location.hash) {
        focus_hash(window.location.hash);
    }

    //Update counters:
    changeContactMethod();
    changeCloseDates();
	
    //Tuition Calculator:
    $( "#r_response_time_hours, #r_meeting_frequency, #r_meeting_duration" /* #r_min_students, #r_max_students */ ).change(function() {
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
	    	minDate : 1,
            /*
	    	beforeShowDay: function(date){
	    		  var day = date.getDay(); 
	    		  return [ <?= $bootcamp['b_sprint_unit']=='week' ? 'day==1' : 'day==1 || day==2 || day==3 || day==4 || day==5 || day==6 || day==0' ?> ,""];
	    	},
	    	*/
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
        r_min_students:$('#r_min_students').val(),
        r_max_students:$('#r_max_students').val(),
        r_cancellation_policy:$('input[name=r_cancellation_policy]:checked').val(),
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


function sync_action_plan(){
    //Show spinner:
    $('#action_plan_status').html('<img src="/img/round_load.gif" class="loader" />').hide().fadeIn();
    var b_id = $('#b_id').val();
    var r_id = $('#r_id').val();

    //Save the rest of the content:
    $.post("/api_v1/sync_action_plan", {
        r_id:r_id,
        b_id:b_id,
    } , function(data) {
        //Update UI to confirm with user:
        $('#action_plan_status').html(data).hide().fadeIn();
        //Assume all good, refresh:
        setTimeout(function() {
            $(location).attr('href', '/console/'+b_id+'/classes/'+r_id);
            window.location.hash = "#actionplan";
            location.reload();
        }, 1000);
    });
}
</script>


<input type="hidden" id="r_id" value="<?= $class['r_id'] ?>" />
<input type="hidden" id="b_id" value="<?= $class['r_b_id'] ?>" />
<input type="hidden" id="c__milestone_units" value="<?= $bootcamp['c__milestone_units'] ?>" />
<input type="hidden" id="c__estimated_hours" value="<?= $bootcamp['c__estimated_hours'] ?>" />
<input type="hidden" id="b_sprint_unit" value="<?= $bootcamp['b_sprint_unit'] ?>" />


<ul id="topnav" class="nav nav-pills nav-pills-primary">
    <li id="nav_support" class="active"><a href="#support"><i class="fa fa-life-ring" aria-hidden="true"></i> Support</a></li>
    <li id="nav_pricing"><a href="#pricing"><i class="fa fa-calculator" aria-hidden="true"></i> Pricing</a></li>
    <li id="nav_admission"><a href="#admission"><i class="fa fa-tags" aria-hidden="true"></i> Admission</a></li>
    <?php if(count($cache_action_plans)==1 || $udata['u_status']>=3){ ?>
        <li id="nav_actionplan"><a href="#actionplan"><i class="fa fa-list-ol" aria-hidden="true"></i> Action Plan</a></li>
    <?php } ?>
</ul>


<div class="tab-content tab-space">
    
    <div class="tab-pane active" id="tabsupport">
    
    	<?php itip(630); ?>
		<div class="title"><h4><i class="fa fa-bolt" aria-hidden="true"></i> Response Time <span class="badge pricing-badge" data-toggle="tooltip" title="Changing this setting will change the suggested price of the Tuition Calculator. Checkout the Pricing tab for more details." data-placement="bottom"><i class="fa fa-calculator" aria-hidden="true"></i></span> <span id="hb_614" class="help_button" intent-id="614"></span></h4></div>
        <div class="help_body maxout" id="content_614"></div>
        <select class="form-control input-mini border <?= $disabled ?>" id="r_response_time_hours" <?= $disabled ?>>
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
        			<select class="form-control input-mini border <?= $disabled ?>" id="r_meeting_frequency" <?= $disabled ?>>
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
        			<select class="form-control input-mini border <?= $disabled ?>" id="r_meeting_duration" <?= $disabled ?>>
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
        



		<div class="title" style="margin-top:30px;"><h4><i class="fa fa-podcast" aria-hidden="true"></i> Weekly Group Calls <span class="badge pricing-badge" data-toggle="tooltip" title="Changing this setting will change the suggested price of the Tuition Calculator. Checkout the Pricing tab for more details." data-placement="bottom"><i class="fa fa-calculator" aria-hidden="true"></i></span> <span id="hb_616" class="help_button" intent-id="616"></span></h4></div>
		<div class="help_body maxout" id="content_616"></div>
		
		
		<input type="hidden" id="r_live_office_hours_val" value="<?= strlen($class['r_live_office_hours'])>0 ? '1' : '0' ?>" />
		<div class="checkbox">
        	<label>
        		<input type="checkbox" class="<?= $disabled ?>" <?= $disabled ?> id="r_live_office_hours_check" <?= strlen($class['r_live_office_hours'])>0 ? 'checked' : '' ?>>
        		Offer Weekly Group Calls
        	</label>
        </div>
		
		<div class="has_office_hours" style="display:<?= strlen($class['r_live_office_hours'])>0 ? 'block' : 'none' ?>;">
			
			<div class="title"><h4><i class="fa fa-commenting" aria-hidden="true"></i> Group Call Contact Method <span id="hb_617" class="help_button" intent-id="617"></span></h4></div>
			<div class="help_body maxout" id="content_617"></div>
            <div class="form-group label-floating is-empty">
                <textarea class="form-control text-edit border msg msgin" style="min-height:50px; padding:3px;" onkeyup="changeContactMethod()" placeholder="Contact using our Skype username: grumomedia" id="r_office_hour_instructions"><?= $class['r_office_hour_instructions'] ?></textarea>
                <div style="margin:0 0 0 0; font-size:0.8em;"><span id="ContactMethodChar">0</span>/<?= $message_max ?></div>
            </div>
            
            <div class="title"><h4><i class="fa fa-calendar" aria-hidden="true"></i> Weekly Schedule PST <span class="badge pricing-badge" data-toggle="tooltip" title="Changing this setting will change the suggested price of the Tuition Calculator. Checkout the Pricing tab for more details." data-placement="bottom"><i class="fa fa-calculator" aria-hidden="true"></i></span> <span id="hb_618" class="help_button" intent-id="618"></span></h4></div>
            <div class="help_body maxout" id="content_618"></div>
            <iframe id="weekschedule" src="/console/<?= $bootcamp['b_id'] ?>/classes/<?= $class['r_id'] ?>/scheduler?disabled=<?= $disabled ?>" scrolling="no" class="scheduler-iframe"></iframe>
		</div>
		
		
		<div class="title" style="margin-top:30px;"><h4><i class="fa fa-commenting" aria-hidden="true"></i> Close Dates <span id="hb_619" class="help_button" intent-id="619"></span></h4></div>
        <div class="help_body maxout" id="content_619"></div>
        <div class="form-group label-floating is-empty">
            <textarea class="form-control text-edit border msg msgin" style="min-height:50px; padding:3px;" onkeyup="changeCloseDates()" placeholder="We will be closed on Dec 25-26 and Jan 1" id="r_closed_dates"><?= $class['r_closed_dates'] ?></textarea>
            <div style="margin:0 0 10px 0; font-size:0.8em;"><span id="CloseDatesChar">0</span>/<?= $message_max ?></div>
        </div>


        <br />
        <table width="100%"><tr><td class="save-td"><a href="javascript:save_r();" class="btn btn-primary">Save</a></td><td><span id="save_r_results"></span></td></tr></table>

    </div>
    
    <div class="tab-pane" id="tabpricing">
    
        
        <div style="display:block;">
        	<div class="title"><h4><i class="fa fa-calculator" aria-hidden="true"></i> Tuition Calculator <span id="hb_620" class="help_button" intent-id="620"></span></h4></div>
            <div class="help_body maxout" id="content_620" style="margin-bottom:50px;"></div>
            <div id="calculator_body"><a href="javascript:initiate_calculator()" class="btn btn-primary">Load Calculator</a></div>
        </div>
            
        
        
        <div class="title" style="margin-top:30px;"><h4><i class="fa fa-usd" aria-hidden="true"></i> Tuition Rate <span id="hb_621" class="help_button" intent-id="621"></span></h4></div>
        <div class="help_body maxout" id="content_621"></div>
        <div class="input-group">
        	<span class="input-group-addon addon-lean">USD $</span>
        	<input type="number" min="0" step="0.01" style="width:100px; margin-bottom:-5px;" id="r_usd_price" value="<?= isset($class['r_usd_price']) && floatval($class['r_usd_price'])>=0 ? $class['r_usd_price'] : null ?>" class="form-control border <?= $disabled ?>" <?= $disabled ?> />
        </div>
        <br />
        
        

        <div id="refund_policies">
            <div class="title" style="margin-top:30px;"><h4><i class="fa fa-shield" aria-hidden="true"></i> Refund Policy <span id="hb_622" class="help_button" intent-id="622"></span></h4></div>
            <div class="help_body maxout" id="content_622"></div>
            <?php
            $refund_policies = $this->config->item('refund_policies');
            foreach($refund_policies as $type=>$terms){
                echo '<div class="radio">
                <label>
                    <input type="radio" '.$disabled.' class="'.$disabled.'" name="r_cancellation_policy" value="'.$type.'" '.( isset($class['r_cancellation_policy']) && $class['r_cancellation_policy']==$type ? 'checked="true"' : '' ).' />
                    '.ucwords($type).'
                </label>
                <ul style="margin-left:15px;">';
                echo '<li>Full Refund: '.( $terms['full']>0 ? '<b>Before '.($terms['full']*100).'%</b> of the class\'s elapsed time' : ( $terms['prorated']>0 ? '<b>Before Start Date</b> of the class' : '<b>None</b> After Admission' ) ).'.</li>';
                echo '<li>Pro-rated Refund: '.( $terms['prorated']>0 ? '<b>Before '.($terms['prorated']*100).'%</b> of the class\'s elapsed time' : '<b>None</b> After Admission' ).'.</li>';
                echo '</ul></div>';
            }
            ?>
        </div>



        <?php

        /*
        echo '<div class="title" style="margin-top:30px;"><h4><i class="fa fa-history" aria-hidden="true"></i> Transaction History</h4></div>';
        //Attempt to fetch all payouts:
        $class_transactions = $this->Db_model->t_fetch(array(
            't.t_ru_id' => $enrollment['ru_id'],
        ));
        if(count($class_transactions)<1){
            //Class is not yet started:
            echo '<div class="alert alert-info maxout" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> No transactions yet.</div>';
        } else {
            //List all transactions:

        }
        */

        if(!$disabled){
            //Show button to update:
            ?>
            <br />
            <table width="100%"><tr><td class="save-td"><a href="javascript:save_r();" class="btn btn-primary">Save</a></td><td><span id="save_r_results"></span></td></tr></table>
            <?php
        }
        ?>
    </div>

    <div class="tab-pane" id="tabadmission">

        <?php $this->load->view('console/inputs/r_start_day_time' , array(
            'c__milestone_units' => $bootcamp['c__milestone_units'],
            'b_sprint_unit' => $bootcamp['b_sprint_unit'],
            'b_id' => $bootcamp['b_id'],
            'b_status' => $bootcamp['b_status'],
            'r_start_date' => $class['r_start_date'],
            'r_start_time_mins' => $class['r_start_time_mins'],
            'disabled' => $disabled,
        )); ?>


        <?php
        $this->load->view('console/inputs/r_status' , array(
            'r_status' => $class['r_status'],
            'removal_status' => class_status_change($class['r_status'],$current_applicants),
        ));
        ?>


        <div style="display:block; margin-top:20px;">
            <div class="title"><h4><i class="fa fa-thermometer-empty" aria-hidden="true"></i> Minimum Students <span id="hb_612" class="help_button" intent-id="612"></span></h4></div>
            <div class="help_body maxout" id="content_612"></div>
            <div class="input-group">
                <input type="number" min="0" step="1" style="width:100px; margin-bottom:-5px;" <?= $soft_disabled ?> id="r_min_students" value="<?= (isset($class['r_min_students'])?$class['r_min_students']:null) ?>" class="form-control border <?= $soft_disabled ?>" />
            </div>
            <br />
        </div>


        <div class="title"><h4><i class="fa fa-thermometer-full" aria-hidden="true"></i> Maximum Students <span id="hb_613" class="help_button" intent-id="613"></span></h4></div>
        <div class="help_body maxout" id="content_613"></div>
        <div class="input-group">
            <input type="number" min="0" step="1" style="width:100px; margin-bottom:-5px;" <?= $soft_disabled ?> id="r_max_students" value="<?= ( isset($class['r_max_students']) ? $class['r_max_students'] : null ) ?>" class="form-control border <?= $soft_disabled ?>" />
        </div>



        <div style="display:block; margin-top:30px;">
            <div class="title"><h4><i class="fa fa-facebook-official" aria-hidden="true"></i> Facebook Pixel ID <span id="hb_718" class="help_button" intent-id="718"></span></h4></div>
            <div class="help_body maxout" id="content_718"></div>
            <div class="input-group">
                <input type="number" min="0" step="1" style="width:220px; margin-bottom:-5px;" <?= $soft_disabled ?> id="r_fb_pixel_id" placeholder="123456789012345" value="<?= (strlen($class['r_fb_pixel_id'])>1?$class['r_fb_pixel_id']:null) ?>" class="form-control border <?= $soft_disabled ?>" />
            </div>
            <br />
        </div>

        <br />
        <table width="100%"><tr><td class="save-td"><a href="javascript:save_r();" class="btn btn-primary">Save</a></td><td><span id="save_r_results"></span></td></tr></table>

    </div>

    <div class="tab-pane" id="tabactionplan">
        <?php
        //Show helper tip:
        itip(3267);

        //Do we have a copy?
        if(count($cache_action_plans)==1){

            $bootcamp = unserialize($cache_action_plans[0]['ej_e_blob']);

            echo '<div class="title"><h4><i class="fa fa-list-ol" aria-hidden="true"></i> Action Plan as of '.time_format($cache_action_plans[0]['e_timestamp'],0).'</h4></div>';

            //Show Action Plan:
            echo '<div id="bootcamp-objective" class="list-group maxout">';
            echo echo_cr($bootcamp['b_id'],$bootcamp,'outbound',1,$bootcamp['b_sprint_unit'],0,false);
            echo '</div>';

            //Milestone Expand/Contract all if more than 2
            if(count($bootcamp['c__child_intents'])>0){
                echo '<div id="milestone_view">';
                echo '<i class="fa fa-plus-square expand_all" aria-hidden="true"></i> &nbsp;';
                echo '<i class="fa fa-minus-square close_all" aria-hidden="true"></i>';
                echo '</div>';
            }

            //Milestones List:
            echo '<div id="list-outbound" class="list-group">';
            foreach($bootcamp['c__child_intents'] as $key=>$sub_intent){
                echo echo_cr($bootcamp['b_id'],$sub_intent,'outbound',2,$bootcamp['b_sprint_unit'],$bootcamp['b_id'],0,false);
            }
            echo '</div>';


            //Target Audience:
            echo '<div class="title"><h4><i class="fa fa-address-book" aria-hidden="true"></i> Target Audience <span id="hb_426" class="help_button" intent-id="426"></span> <span id="b_target_audience_status" class="list_status">&nbsp;</span></h4></div>
                <div class="help_body maxout" id="content_426"></div>';
            echo ( strlen($bootcamp['b_target_audience'])>0 ? '<ol><li>'.join('</li><li>',json_decode($bootcamp['b_target_audience'])).'</li></ol>' : '<div class="alert alert-info maxout" role="alert"></div>' );


            //Prerequisites, which get some system appended ones:
            $bootcamp['b_prerequisites'] = prep_prerequisites($bootcamp);
            echo '<div class="title" style="margin-top:30px;"><h4><i class="fa fa-check-square-o" aria-hidden="true"></i> Prerequisites <span id="hb_610" class="help_button" intent-id="610"></span> <span id="b_prerequisites_status" class="list_status">&nbsp;</span></h4></div>
                <div class="help_body maxout" id="content_610"></div>';
            echo ( count($bootcamp['b_prerequisites'])>0 ? '<ol><li>'.join('</li><li>',$bootcamp['b_prerequisites']).'</li></ol>' : '<div class="alert alert-info maxout" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Not Set</div>' );


            //Application Questions
            echo '<div class="title" style="margin-top:30px;"><h4><i class="fa fa-question-circle" aria-hidden="true"></i> Application Questions <span id="hb_611" class="help_button" intent-id="611"></span> <span id="b_application_questions_status" class="list_status">&nbsp;</span></h4></div>
                <div class="help_body maxout" id="content_611"></div>';
            echo ( strlen($bootcamp['b_application_questions'])>0 ? '<ol><li>'.join('</li><li>',json_decode($bootcamp['b_application_questions'])).'</li></ol>' : '<div class="alert alert-info maxout" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Not Set</div>' );


            //Skills You Will Gain
            echo '<div class="title"><h4><i class="fa fa-diamond" aria-hidden="true"></i> Skills You Will Gain <span id="hb_2271" class="help_button" intent-id="2271"></span> <span id="b_transformations_status" class="list_status">&nbsp;</span></h4></div>
                <div class="help_body maxout" id="content_2271"></div>';
            echo ( strlen($bootcamp['b_transformations'])>0 ? '<ol><li>'.join('</li><li>',json_decode($bootcamp['b_transformations'])).'</li></ol>' : '<div class="alert alert-info maxout" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Not Set</div>' );


            //Completion Awards
            echo '<div class="title" style="margin-top:30px;"><h4><i class="fa fa-trophy" aria-hidden="true"></i> Completion Awards <span id="hb_623" class="help_button" intent-id="623"></span> <span id="b_completion_prizes_status" class="list_status">&nbsp;</span></h4></div>
                <div class="help_body maxout" id="content_623"></div>';
            echo ( strlen($bootcamp['b_completion_prizes'])>0 ? '<ol><li>'.join('</li><li>',json_decode($bootcamp['b_completion_prizes'])).'</li></ol>' : '<div class="alert alert-info maxout" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Not Set</div>' );


        } else {
            echo '<div class="alert alert-info maxout" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Action Plan not copied yet because this Class has not started. This would happen automatically when this Class starts.</div>';
        }

        if(($class['r_status']==2 && $udata['u_status']>=2) || ($udata['u_id']==1)){
            //Show button to update ONLY if class is running.
            ?>
            <div class="copy_ap"><a href="javascript:void(0);" onclick="$('.copy_ap').toggle();" class="btn btn-primary">Update Action Plan</a></div>
            <div class="copy_ap" style="display:none;">
                <p><b><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> WARNING:</b> This Class is currently running, and updating your Action Plan may cause confusion for your students as they might need to complete Tasks form previous Milestones they had already marked as complete. Update the Action Plan only if:</p>
                <ul>
                    <li>You have made changes to Messages only (Not added new Tasks or Milestones)</li>
                    <li>You have made changes to Future Milestones that have not been unlocked yet</li>
                </ul>
                <p><a href="javascript:void(0);" onclick="sync_action_plan()">I Understand, Continue With Update &raquo;</a></p>
            </div>
            <div id="action_plan_status"></div>
            <?php
        }
        ?>
    </div>

</div>
