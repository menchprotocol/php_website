<?php
//Expand Prerequisites:
$pre_req_array = prep_prerequisites($admission);
$status_rs = status_bible('rs');
$classroom_current_students = $this->Db_model->ru_fetch(array(
    'r.r_id'	       => $admission['r_id'],
    'ru.ru_status >='  => 4, //Joined Students
    'ru.ru_p2_price >' => 0, //They are in Classroom or Tutoring
));
$classroom_available = ( $admission['b_p2_max_seats'] - count($classroom_current_students) );
?>
<script>
var current_section = 1; //The index for the wizard
var support_level = 0;
var support_price = 0;

function move_ui(adjustment){

    //Set Defaults:
    $('#btn_next a').html('Next <i class="fa fa-chevron-right" aria-hidden="true"></i>');
    $('#payment_method').html(' ');

	//Any pre-check with submitted data?
	//Let's check the value of the current posstible ID for input validation checking:
	if(adjustment>0 && typeof $('.wizard-box').eq((current_section-1)).attr( "id" ) !== 'undefined' && $('.wizard-box').eq((current_section-1)).attr( "id" ).length){

        var the_id = $('.wizard-box').eq((current_section-1)).attr( "id" );

		if(the_id=='price_selection'){
		    //Which support level did they choose?
            if($('#p_selection_1').is(":checked")){
                support_level = 1;
                support_price = parseFloat( $('#p_selection_1').attr('data-price'));
                $('#confirm_support').html($('#p_name_1').html()); //Update Support Level
            } else if($('#p_selection_2').is(":checked")){
                support_level = 2;
                support_price = parseFloat( $('#p_selection_2').attr('data-price'));
                $('#confirm_support').html($('#p_name_2').html()); //Update Support Level
            } else if($('#p_selection_3').is(":checked")){
                support_level = 3;
                support_price = parseFloat( $('#p_selection_3').attr('data-price'));
                $('#confirm_support').html($('#p_name_3').html()); //Update Support Level
            } else {
                alert('ERROR: Select support level to continue.');
                return false;
            }

            if(support_price>0){
                //Payment is required:
                $('#btn_next a').html('CONFIRM & PAY $'+support_price+' <i class="fa fa-chevron-right" aria-hidden="true"></i>');
                $('#payment_method').html('<span id="white_paypal"><img src="/img/paypal.png" /></span>');
            } else {
                //This is a FREE Class:
                $('#btn_next a').html('CONFIRM & JOIN <i class="fa fa-chevron-right" aria-hidden="true"></i>');
            }
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

		console.log({

            //Core variables:
            ru_id:<?= $ru_id ?>,
            u_id:<?= $u_id ?>,
            u_key:'<?= $u_key ?>',
            support_level:support_level,

        });

		//Send for processing:
		$.post("/api_v1/ru_checkout", {

			//Core variables:
			ru_id:<?= $ru_id ?>,
			u_id:<?= $u_id ?>,
            u_key:'<?= $u_key ?>',
            support_level:support_level,
            support_price:support_price,

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
.form-group textarea{padding:10px; max-width:600px; width:100%; height:120px; margin:5px 0 25px; font-size:18px; }
#application_result {text-align:center; text-align: center; background-color: #FFF; margin:10px 0 40px 0; padding: 30px 5px 0; border-radius: 6px; height:125px; }
</style>




<p style="border-bottom:4px solid #000; font-weight:bold; padding-bottom:10px; margin-bottom:20px; display:block;"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> <?= $admission['c_objective'] ?><span style="font-weight: 500; display: block; padding-top:5px;"><i class="fa fa-calendar" aria-hidden="true"></i> <?= time_format($admission['r_start_date'],2).' - '.time_format($admission['r__class_end_time'],2) ?></span></p>







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
    <br />
    <p>Click "Next" if you meet all prerequisites OR if you believe you can meet them before the Class starts on <b><?= trim(time_format($admission['r_start_date'],2)) ?></b>.</p>
    <p>Or you can <a href="/"><b>choose another Bootcamp &raquo;</b></a></p>
	<br />
</div>
<?php } ?>




<div class="wizard-box" id="price_selection">

    <p>Choose a support level that's right for you:</p>

    <div class="radio pricing_block">
        <label>
            <input type="radio" id="p_selection_1" data-price="<?= echo_price($admission,1, true) ?>" name="p_selection" value="1" />
            <b id="p_name_1"><i class="fa <?= $status_rs[1]['s_mini_icon'] ?>" aria-hidden="true"></i> <?= $status_rs[1]['s_name'] ?> for <?= echo_price($admission,1) ?></b>
            <p><?= $status_rs[1]['s_desc'] ?></p>
        </label>
    </div>

    <?php if($admission['b_p2_max_seats']>0){ ?>

        <div class="radio pricing_block">
            <label>
                <input type="radio" id="p_selection_2" data-price="<?= echo_price($admission,2, true) ?>" name="p_selection" <?= (!$classroom_available ? 'disabled' : '') ?> value="2" />
                <b id="p_name_2"><i class="fa <?= $status_rs[2]['s_mini_icon'] ?>" aria-hidden="true"></i> <?= $status_rs[2]['s_name'] ?> for <?= echo_price($admission,2) ?></b> <b class="badge"><?= ( $classroom_available ? $classroom_available . ' Seat' . show_s($classroom_available).' Remaining' : 'SOLD OUT' ) ?></b>
                <p><?= nl2br($status_rs[2]['s_desc']) ?></p>
            </label>
        </div>

        <?php if($admission['b_p3_rate']>0){ ?>

            <div class="radio pricing_block">
                <label>
                    <input type="radio" id="p_selection_3" data-price="<?= echo_price($admission,3, true) ?>" name="p_selection" value="3" />
                    <b id="p_name_3"><i class="fa <?= $status_rs[3]['s_mini_icon'] ?>" aria-hidden="true"></i> <?= $status_rs[2]['s_name'] ?> + 50 Minutes of <?= $status_rs[3]['s_name'] ?> for <?= echo_price($admission,3) ?></b>
                    <p><?= nl2br($status_rs[3]['s_desc']) ?></p>
                </label>
            </div>

        <?php } ?>

    <?php } ?>

    <br />

</div>



<div class="wizard-box">
    <p>Review and confirm Class details:</p>
    <ul>
        <li>Bootcamp: <b><?= $admission['c_objective'] ?></b></li>
        <li>Designed By: <?='<b>'.$admission['b__admins'][0]['u_fname'].' '.$admission['b__admins'][0]['u_lname'].'</b>' ?></li>
        <li>Class Dates: <b><?= time_format($admission['r_start_date'],2) ?> - <?= time_format($admission['r_start_date'],2,(7*24*3600-60)) ?></b></li>
        <li>Action Plan: <b><?= $admission['c__tasks_count'] ?> Tasks</b></li>
        <li>Your Commitment: <b><?= echo_hours($admission['c__estimated_hours']) ?> in 1 Week</b> (Average <?= echo_hours($admission['c__estimated_hours']/7) ?> per Day)</li>
        <li>Your Support Level: <b id="confirm_support"></b></li>
    </ul>
    <br />
</div>


<div class="wizard-box">
    <p style="text-align:center;"><b>Processing...</b></p>
	<div id="application_result"><img src="/img/round_load.gif" class="loader" /></div>
</div>


<a id="btn_prev" href="javascript:move_ui(-1)" class="btn btn-primary" style="padding-left:10px;padding-right:12px; display:none;"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
<span id="btn_next"><a href="javascript:move_ui(1)" class="btn btn-primary">Next <i class="fa fa-chevron-right" aria-hidden="true"></i></a></span>

<div style="text-align:right; margin:0px 2px 0;"><b id="step_progress"></b></div>
<div class="progress" style="margin:auto 2px;">
	<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%;"></div>
</div>

<div id="payment_method"></div>

<div style="text-align:center; margin-top:20px; font-size:0.8em; font-weight:300;"><a href="/<?= $admission['b_url_key'] ?>">&laquo; Back to Bootcamp Overview</a></div>