<?php //print_r($admission); ?>
<script>
var current_section = 1; //The index for the wizard

function move_ui(adjustment){

	//Any pre-check with submitted data?
	//Let's check the value of the current posstible ID for input validation checking:
	if(adjustment>0 && typeof $('.wizard-box').eq((current_section-1)).attr( "id" ) !== 'undefined' && $('.wizard-box').eq((current_section-1)).attr( "id" ).length){
		var the_id = $('.wizard-box').eq((current_section-1)).attr( "id" );
		
		if(the_id=='overview_agree' && !document.getElementById('bootcamp_overview_agreement').checked){
			alert('You must agree to continue...');
			$('#'+the_id+' input').focus();
			return false;
		} else if(the_id=='refund_agreement' && !document.getElementById('bootcamp_refund_agreement').checked){
			alert('You must agree to continue...');
			$('#'+the_id+' input').focus();
			return false;
		}		

		<?php
		if(strlen($admission['r_application_questions'])>0){
    	    foreach(json_decode($admission['r_application_questions']) as $index=>$question){
    	        //Now show the JS check for these fields:
    	        ?>
    	        if(the_id=='check_question_<?= ($index+1) ?>' && $('#question_<?= ($index+1) ?>').val().length<1 ){
    				alert('Answer is required...');
    				$('#question_<?= ($index+1) ?>').focus();
    				return false;
    			}
    	        <?php
    	    }
    	 }
    	 ?>
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
		  $( this ).find( "input, .ql-editor, textarea" ).focus();
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
		$.post("/api_v1/submit_application", {

			//Core variables:
			ru_id:<?= $ru_id ?>,
			u_id:<?= $u_id ?>,
			u_key:'<?= $u_key ?>',
			
			//Get some PHP help to generate answers array for saving:
			answers: {
				'prerequisites' : {
					<?php
	        		if(strlen($admission['r_prerequisites'])>0){
	        		    foreach(json_decode($admission['r_prerequisites']) as $index=>$prereq){
	            	        //Now show the JS check for these fields:
	            	        ?>
	            	        '<?= ($index+1) ?>' : {
	        		        	'item' : '<?= str_replace('\'','',$prereq) ?>',
	        			        'answer' : ( document.getElementById('pre_requisite_<?= ($index+1) ?>').checked ? 'Yes' : 'No' ),
	        			    },
	            	        <?php
	            	    }
	            	 }
	            	 ?>
			    },
			    'questions' : {
	    		    <?php
	        		if(strlen($admission['r_application_questions'])>0){
	            	    foreach(json_decode($admission['r_application_questions']) as $index=>$question){
	            	        //Now show the JS check for these fields:
	            	        ?>
	            	        '<?= ($index+1) ?>' : {
	        		        	'item' : '<?= str_replace('\'','',$question) ?>',
	        			        'answer' : $('#question_<?= ($index+1) ?>').val(),
	        			    },
	            	        <?php
	            	    }
	            	 }
	            	 ?>
			     },
			},
    		
		}, function(data) {
			//Append data to view:
			$( "#application_result" ).html(data);
		});
	}
}

$(document).ready(function() {
	//Load first one:
	move_ui(0);
	//Watch for Ctrl+Enter
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
.wizard-box { font-size:0.9em; }
.wizard-box label { font-size:0.8em; }
.wizard-box p, .wizard-box ul { margin-bottom:20px; }
.wizard-box ul li { margin-bottom:10px; }
.wizard-box a { text-decoration:underline; }
.wizard-box h4 { margin:0 0 15px 0; padding:0; font-size:1.2em; }
.aligned-list>li>i { width:36px; display:inline-block; text-align:center; }
.large-fa {font-size: 60px; margin-top:15px;}
.xlarge-fa {font-size: 68px; margin-top:15px;}
.progress{background-color:#FFF !important;}
.progress-bar{background-color:#000 !important;}
.enter{width:170px;}
.btn-primary{background-color: #000 !important;color:#FFF !important;}
.checkbox-material>.check{margin-top:-6px; margin-left:14px;}
.checkbox{ margin:30px 0; }
.form-group{text-align:left;}
.form-group textarea{padding:10px; max-width:600px; width:100%; height:120px; margin:5px 0 25px; font-size:18px; border:1px solid #000; }
#application_result {text-align:center; text-align: center; background-color: #FFF; margin:10px 0 40px 0; padding: 30px 5px 0; border-radius: 6px; height:125px; }
</style>




<p style="border-bottom:4px solid #000; font-weight:bold; padding-bottom:10px; margin-bottom:20px; display:block;">Apply to <?= $admission['c_objective'] ?> Starting <?= time_format($admission['r_start_date'],4) ?></p>


<div class="wizard-box">
	<p>Hi <?= $admission['u_fname'] ?>,</p>
	<p>Welcome to the bootcamp application.</p>
	<p>We just sent an email to <b><?= $admission['u_email'] ?></b> with a link to this application so you can easily access it at anytime.</p>
	<p>We're so excited to have you here! We're about to ask you a few questions to find out if you're a good fit for this bootcamp.</p>
	<p>This application should take about 5 minutes to complete.</p>
</div>

<?php 
$start_times = $this->config->item('start_times');
?>

<div class="wizard-box" id="overview_agree">
	<p>Confirm that you commit to participating and doing the required work for this bootcamp:</p>
	<ul>
		<li>Bootcamp Outcome: <b><?= $admission['c_objective'] ?></b></li>
    	<li>Instructor<?= ( count($admission['b__admins'])==1 ? '' : 's' ) ?>: 
        	<?php 
        	foreach($admission['b__admins'] as $key=>$instructor){
        	    if($key>0){
        	        echo ', ';
        	    }
        	    echo '<b>'.$instructor['u_fname'].' '.$instructor['u_lname'].'</b>';
        	}
        	?>
    	</li>
    	<li>Start Time: <b><?= time_format($admission['r_start_date'],2).' '.$start_times[$admission['r_start_time_mins']] ?> PST</b></li>
    	<li>Duration: <b><?= count($admission['c__child_intents']) ?> <?= ucwords($admission['b_sprint_unit']).( count($admission['c__child_intents'])==1 ? '' : 's') ?></b></li>
    	<li>End Time: <b><?= time_format($admission['r_start_date'],2,(calculate_duration($admission))).' '.$start_times[$admission['r_start_time_mins']] ?> PST</b></li>
    	<li>Your Commitment: <b><?= echo_hours(round($admission['c__estimated_hours']/count($admission['c__child_intents']))) ?>/<?= ucwords($admission['b_sprint_unit']) ?></b></li>
    	<?php if(strlen($admission['r_meeting_frequency'])>0 && !($admission['r_meeting_frequency']=="0")){ ?>
    	<li>Mentorship: <b><?= echo_mentorship($admission['r_meeting_frequency'],$admission['r_meeting_duration']) ?></b></li>
    	<?php } ?>
	</ul>
	<div class="form-group label-floating is-empty">
    	<div class="checkbox">
        	<label>
        		<input type="checkbox" id="bootcamp_overview_agreement" /> <b style="font-size:1.3em;">Yes I Agree</b>
        	</label>
        </div>
    </div>
</div>


<?php if(strlen($admission['r_prerequisites'])>0){ ?>
<div class="wizard-box" id="confirm_pre_requisites">
	<p>Below it's the list with all the prerequisites needed to apply for this bootcamp.</p>
	<p>Select all the ones you currently meet:</p>
	<?php
	foreach(json_decode($admission['r_prerequisites']) as $index=>$prereq){
	    ?>
	    <div class="form-group label-floating is-empty">
        	<div class="checkbox" style="margin:0; padding:0;">
            	<label>
            		<input type="checkbox" id="pre_requisite_<?= ($index+1) ?>" /> <b style="font-size:1.2em;"><?= $prereq ?></b>
            	</label>
            </div>
        </div>
	    <?php
	}
	?>
	<br />
</div>
<?php } ?>


<?php if(strlen($admission['r_application_questions'])>0){ ?>

    <div class="wizard-box">
    	<p>Thanks! That was pretty easy right?</p>
    	<p>The next <?= count(json_decode($admission['r_application_questions'])) ?> questions would help the instructor to learn more about yourself and your current skills.</p>
    </div>
    
    <?php
    foreach(json_decode($admission['r_application_questions']) as $index=>$question){
        ?>
        <div class="wizard-box" id="check_question_<?= ($index+1) ?>">
        	<p><?= $question ?></p>
        	<div class="form-group">
                <textarea id="question_<?= ($index+1) ?>" placeholder="Your Answer"></textarea>
            </div>
        </div>
        <?php
    }
    ?>

<?php } ?>


<?php if($admission['r_usd_price']>0){ ?>
<div class="wizard-box" id="refund_agreement">
	<p>This bootcamp offers a <b><?= ucwords($admission['r_cancellation_policy']); ?></b> refund policy:</p>
	<?php 
	$full_days = calculate_refund(calculate_duration($admission),'full',$admission['r_cancellation_policy']);
	$prorated_days = calculate_refund(calculate_duration($admission),'prorated',$admission['r_cancellation_policy']);
	//Display cancellation terms:
	echo '<ul>';
	echo '<li>You will always receive a full refund if your admission application was not approved.</li>';
	echo '<li>Full Refund: <b>'.( $full_days>0 ? 'Before '.time_format($admission['r_start_date'],1,($full_days-1)).' '.$start_times[$admission['r_start_time_mins']].' PST' : 'None After Admission' ).'</b></li>';
	echo '<li>Pro-Rated Refund: <b>'.( $prorated_days>0 ? 'Before '.time_format($admission['r_start_date'],1,($prorated_days-1)).' '.$start_times[$admission['r_start_time_mins']].' PST' : 'None After Admission' ).'</b></li>';
	echo '</ul>';
	?>
		
	<div class="form-group label-floating is-empty">
    	<div class="checkbox">
        	<label>
        		<input type="checkbox" id="bootcamp_refund_agreement" /> <b style="font-size:1.3em;">I Understand My Refund Rights</b>
        	</label>
        </div>
    </div>
</div>
<?php } ?>



<?php if(strlen($admission['u_fb_id'])<4){ ?>
<div class="wizard-box">
	<?php $mench_bots = $this->config->item('mench_bots'); ?>
	<p><img src="/img/bp_48.png" alt="MenchBot" /> <b style="font-size:1.2em;">MenchBot Activation</b></p>
	<p><?= nl2br($mench_bots['381488558920384']['settings']['greeting'][0]['text']) ?></p>
	<p style="margin:40px 0; font-weight:bold;"><a href="<?= messenger_activation_url('381488558920384',$admission['u_id']) ?>" target="_blank">Activate MenchBot</a> <i class="fa fa-external-link-square" style="font-size: 0.8em;" aria-hidden="true"></i></p>
</div>
<?php } ?>




<div class="wizard-box">
	<p>That's all!</p>
	<p>Click "Next" to submit your application!</p>
    <?php if($admission['r_usd_price']>0){ ?>
	<p>The final remaining step is to pay <b><?= echo_price($admission['r_usd_price']); ?></b> via PayPal to reserve your seat.</p>
    <?php } ?>
</div>


<div class="wizard-box">
	<p style="text-align:center;"><b>Submitting Your Application...</b></p>
	<div id="application_result"><img src="/img/round_load.gif" class="loader" /></div>
</div>




<a id="btn_prev" href="javascript:move_ui(-1)" class="btn btn-primary" style="padding-left:10px;padding-right:12px; display:none;"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
<span id="btn_next"><a href="javascript:move_ui(1)" class="btn btn-primary">Next <i class="fa fa-chevron-right" aria-hidden="true"></i></a><span class="enter">or press <b>CTRL+ENTER</b></span></span>

<div style="text-align:right; margin:-20px 2px 0;"><b id="step_progress"></b></div>
<div class="progress" style="margin:auto 2px;">
	<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%;"></div>
</div>


