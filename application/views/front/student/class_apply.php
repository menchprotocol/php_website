<?php
//Expand Prerequisites:
$pre_req_array = prep_prerequisites($admission);
$r_statuses = status_bible('r');
?>
<script>
var current_section = 1; //The index for the wizard

function move_ui(adjustment){

	//Any pre-check with submitted data?
	//Let's check the value of the current posstible ID for input validation checking:
	if(adjustment>0 && typeof $('.wizard-box').eq((current_section-1)).attr( "id" ) !== 'undefined' && $('.wizard-box').eq((current_section-1)).attr( "id" ).length){
		var the_id = $('.wizard-box').eq((current_section-1)).attr( "id" );
		
		if(the_id=='review_prerequisites'){
			//Return false & alert
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
.wizard-box { font-size:0.8em; }
.wizard-box label { font-size:0.8em; }
.wizard-box p, .wizard-box ul { margin-bottom:15px; }
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




<p style="border-bottom:4px solid #000; font-weight:bold; padding-bottom:10px; margin-bottom:20px; display:block;">Join <?= $admission['c_objective'] ?> - Starting <?= time_format($admission['r_start_date'],2) ?></p>







<?php if(count($pre_req_array)>0){ ?>
<div class="wizard-box" id="review_prerequisites">
    <p>Before we welcome you to this Class, let's review the <?= count($pre_req_array) ?> prerequisite<?= show_s(count($pre_req_array)) ?> that will empower you to successfully complete this Bootcamp:</p>
    <ul style="list-style: decimal;">
	<?php
	foreach($pre_req_array as $index=>$prereq){
	    echo '<li>'.$prereq.'</li>';
	}
	?>
    </ul>
    <p>Click "Next" if you meet all prerequisites OR if you believe you can meet them by <b><?= trim(time_format($admission['r_start_date'],2)) ?></b>.</p>
    <p>If not, you can <a href="/"><b>choose another Bootcamp &raquo;</b></a></p>
	<br />
</div>
<?php } ?>






<div class="wizard-box">
    <p>Choose a support level that is right for you:</p>

    <div class="radio">
        <label>
            <input type="radio" name="p_selection" value="1" />
            <i class="fa <?= $r_statuses[0]['s_mini_icon'] ?>" aria-hidden="true"></i> <?= $r_statuses[0]['s_name'] ?>
            <p><?= $r_statuses[0]['s_desc'] ?></p>
        </label>
    </div>



    <?php if($admission['b_p2_max_seats']>0){ ?>
        <div class="radio">
            <label>
                <input type="radio" name="p_selection" value="1" />
                <i class="fa <?= $r_statuses[1]['s_mini_icon'] ?>" aria-hidden="true"></i> <?= $r_statuses[1]['s_name'] ?> <b class="badge"><?= $admission['b_p2_max_seats'] ?> Seats Remaining</b>
                <p><?= $r_statuses[1]['s_desc'] ?></p>
            </label>
        </div>

        <?php if($admission['b_p3_rate']>0){ ?>

            <div class="radio">
                <label>
                    <input type="radio" name="p_selection" value="1" />
                    <i class="fa fa-handshake-o" aria-hidden="true"></i> <?= $r_statuses[1]['s_name'] ?> + 25 Minutes of 1-on-1 Mentorship
                    <p><?= $r_statuses[1]['s_desc'] ?></p>
                </label>
            </div>

        <?php } ?>

    <?php } ?>



    <p>
        <?php if($admission['r_max_students']>0){ ?>
        <li>Classroom Availability: <b><?= $admission['r_max_students'] ?> Seats</b>
            <?php
            if($admission['r__current_admissions']>=$admission['r_max_students']){
                //Class is full:
                echo ' <div style="color:#FF0000;">(FULL, '.($admission['r__current_admissions']-$admission['r_max_students']).' in Waiting List)</div>';
            } elseif(($admission['r__current_admissions']/$admission['r_max_students'])>0.66){
                //Running low on space:
                echo ' <span style="color:#FF0000;">('.($admission['r_max_students']-$admission['r__current_admissions']).' Remaining)</span>';
            }
            ?>
        </li>
        <?php } ?>
    </p>
	<p>Click "Next" to submit your application!</p>

</div>



<div class="wizard-box">
    <p>Confirm Class Details:</p>
    <ul>
        <li>Bootcamp: <b><?= $admission['c_objective'] ?></b></li>
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
        <li>Start Date: <b><?= time_format($admission['r_start_date'],2) ?></b></li>
        <li>Duration: <b>7 Days</b></li>
        <li>End Date: <b><?= time_format($admission['r_start_date'],2,(7*24*3600-60)) ?></b></li>
        <li>Your Commitment: <b><?= echo_hours($admission['c__estimated_hours']) ?> in 7 Days</b> (Average <?= echo_hours($admission['c__estimated_hours']/7) ?> per Day)</li>
    </ul>
</div>



<div class="wizard-box">
	<p style="text-align:center;"><b>Reserving Your Seat...</b></p>
	<div id="application_result"><img src="/img/round_load.gif" class="loader" /></div>
</div>




<a id="btn_prev" href="javascript:move_ui(-1)" class="btn btn-primary" style="padding-left:10px;padding-right:12px; display:none;"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
<span id="btn_next"><a href="javascript:move_ui(1)" class="btn btn-primary">Next <i class="fa fa-chevron-right" aria-hidden="true"></i></a><span class="enter">or press <b>CTRL+ENTER</b></span></span>

<div style="text-align:right; margin:-20px 2px 0;"><b id="step_progress"></b></div>
<div class="progress" style="margin:auto 2px;">
	<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%;"></div>
</div>


