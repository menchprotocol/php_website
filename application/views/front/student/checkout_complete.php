<?php
//Expand Prerequisites:
$admission['b_p2_weeks'] = 1;
$admission['b_p3_weeks'] = 1;
$b = ( $admission['b_is_parent'] ? b_aggregate($admission) : $admission );
$pre_req_array = prep_prerequisites($b);
$status_rs = status_bible('rs');

?>
<script>
var current_section = 1; //The index for the wizard
var support_level = 0;
var support_price = 0;

function move_ui(adjustment){

    //Set Defaults:
    $('#btn_next a').html('Next <i class="fa fa-chevron-right" aria-hidden="true"></i>');
    $('#payment_method').html('');

	//Any pre-check with submitted data?
	//Let's check the value of the current posstible ID for input validation checking:
	if(adjustment>0 && typeof $('.wizard-box').eq((current_section-1)).attr( "id" ) !== 'undefined' && $('.wizard-box').eq((current_section-1)).attr( "id" ).length){

        var the_id = $('.wizard-box').eq((current_section-1)).attr( "id" );

		if(the_id=='price_selection'){

            //Which support level did they choose?
            if($('#p_selection_1').is(":checked")){
                support_level = 1;
                support_price = parseFloat( $('#p_selection_1').attr('data-price'));
            } else if($('#p_selection_2').is(":checked")){
                support_level = 2;
                support_price = parseFloat( $('#p_selection_2').attr('data-price'));
            } else if($('#p_selection_3').is(":checked")){
                support_level = 3;
                support_price = parseFloat( $('#p_selection_3').attr('data-price'));
            } else {
                alert('ERROR: Select <?= $this->lang->line('obj_rs_name') ?> to continue');
                return false;
            }


            //Load possible dates based on their support package:
            $( "#select_dates" ).html('<img src="/img/round_load.gif" class="loader" /> Checking Available Dates...');

            $.post("/api_v1/ru_date_selector", {
                ru_id:<?= $ru_id ?>,
                u_id:<?= $u_id ?>,
                u_key:'<?= $u_key ?>',
                support_level:support_level,
            }, function(data) {
                //Append data to view:
                $( "#select_dates" ).html('<select id="start_dates">'+data+'</select>');
            });


        } else if(the_id=='date_selection'){

		    //Did they select a Date?
            if(!$('#start_dates').val().length){
                alert('ERROR: Select a start date to continue');
                return false;
            }

		    //Update details:
            $('#confirm_support').html($('#p_name_'+support_level).html()); //Update Support Level
            $('#confirm_price').html($('#p_price_'+support_level).html());
            $('#class_dates').html($('#start_dates').find(":selected").text());

            if(support_price>0){

                //Payment is required:
                $('#btn_next a').html('CONFIRM & PAY &nbsp;[$'+support_price+'] &nbsp;&nbsp;<i class="fa fa-chevron-right" aria-hidden="true"></i>');
                $('#payment_method').html('<span id="white_paypal"><img src="/img/paypal.png" /></span>');
                $('#outcome_guarantee').html(' (<a href="https://support.mench.com/hc/en-us/articles/115002080031" target="_blank">Tuition Reimbursement Guarantee <i class="fa fa-external-link-square" aria-hidden="true"></i></a>)');

            } else {
                //This is a FREE Class:
                $('#btn_next a').html('CONFIRM & JOIN <i class="fa fa-chevron-right" aria-hidden="true"></i>');
                $('#outcome_guarantee').html('');
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

		//Send for processing:
		$.post("/api_v1/ru_checkout_complete", {

			//Core variables:
			ru_id:<?= $ru_id ?>,
			u_id:<?= $u_id ?>,
            u_key:'<?= $u_key ?>',
            support_level:support_level,
            support_price:support_price,
            r_ids:$('#start_dates').val(),

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
.progress-bar{background-color:#3C4858 !important;}
.enter{width:170px;}
.btn-primary{background-color: #3C4858 !important;color:#FFF !important;}
.checkbox-material>.check{margin-top:-6px; margin-left:14px;}
.checkbox{ margin:30px 0; }
.form-group{text-align:left;}
.form-group textarea{padding:10px; max-width:600px; width:100%; height:120px; margin:5px 0 25px; font-size:18px; }
#application_result {text-align:center; text-align: center; background-color: #FFF; margin:10px 0 40px 0; padding: 30px 5px 0; border-radius: 6px; height:125px; }
</style>




<p style="border-bottom:4px solid #3C4858; font-weight:bold; padding-bottom:10px; margin-bottom:20px; display:block;"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> <?= $b['c_objective'] ?><span style="font-weight: 500; display: block; padding-top:5px; font-size:0.8em;"><i class="fa fa-calendar" aria-hidden="true"></i> <?= format_hours($b['c__estimated_hours']).' in '.$b['b__week_count'].' Week'.show_s($b['b__week_count']) ?> [<?= format_hours($b['c__estimated_hours']/($b['b__week_count']*7)) ?> per Day]</span></p>



<?php if(count($pre_req_array)>0){ ?>
<div class="wizard-box" id="review_prerequisites">
    <p>Welcome <?= $b['u_fname'] ?> 👋​</p>
    <p>Before we welcome you to this Bootcamp, let's review the <?= count($pre_req_array) ?> prerequisite<?= show_s(count($pre_req_array)) ?> that will empower you to [<?= $b['c_objective'] ?>]:</p>
    <ul style="list-style: decimal;">
	<?php
	foreach($pre_req_array as $index=>$prereq){
	    echo '<li>'.$prereq.'</li>';
	}
	?>
    </ul>
    <br />
    <p>Click "Next" if you meet all prerequisites OR if you believe you can meet them before you start this Bootcamp.</p>
    <p>If not, you can <a href="/"><b>choose another Bootcamp &raquo;</b></a></p>
	<br />
</div>
<?php } ?>





<div class="wizard-box" id="price_selection">

    <p>Choose a <?= $this->lang->line('obj_rs_name') ?> that's right for you:</p>

    <div class="radio pricing_block">
        <label>
            <input type="radio" id="p_selection_1" data-price="<?= echo_price($b,1, true) ?>" name="p_selection" value="1" />
            <b id="p_name_1"><i class="fa <?= $status_rs[1]['s_mini_icon'] ?>" style="margin:0 5px;" aria-hidden="true"></i> <?= $status_rs[1]['s_name'] ?></b> &nbsp;[<b id="p_price_1"><?= echo_price($b,1) ?></b>]
            <p style="margin-left:30px;"><?= nl2br($status_rs[1]['s_desc']) ?></p>
        </label>
    </div>


    <?php if($b['b_p2_max_seats']>0){ ?>

        <div class="radio pricing_block">
            <label>
                <input type="radio" id="p_selection_2" data-price="<?= echo_price($b,2, true) ?>" name="p_selection" value="2" />
                <b id="p_name_2"><i class="fa <?= $status_rs[2]['s_mini_icon'] ?>" aria-hidden="true" style="margin:0 1px;"></i> <?= $b['b_p2_weeks'] .' Week'.show_s($b['b_p2_weeks']).' of '.$status_rs[2]['s_name'] ?></b> &nbsp;[<b id="p_price_2"><?= echo_price($b,2) ?></b>]<?php /* <b class="badge badge-grey"><?= ( $instructor_support_off ? 'NOT AVAILABLE' : ( $classroom_available ? $classroom_available . ' Seat' . show_s($classroom_available).' Remaining' : 'SOLD OUT' ) ) ?></b> */ ?>
                <p style="margin-left:30px;"><?= nl2br($status_rs[2]['s_desc']) ?></p>
            </label>
        </div>

        <?php if($b['b_p3_rate']>0){ ?>

            <div class="radio pricing_block">
                <label>
                    <input type="radio" id="p_selection_3" data-price="<?= echo_price($b,3, true) ?>" name="p_selection" value="3" />
                    <b id="p_name_3"><i class="fa <?= $status_rs[3]['s_mini_icon'] ?>" aria-hidden="true"></i> <?= $b['b_p3_weeks'] .' Week'.show_s($b['b_p3_weeks']).' of '.$status_rs[3]['s_name'] ?></b> &nbsp;[<b id="p_price_3"><?= echo_price($b,3) ?></b>]
                    <p style="margin-left:30px;"><?= nl2br($status_rs[3]['s_desc']) ?></p>
                </label>
            </div>

        <?php } ?>

    <?php } ?>

    <br />

</div>






<div class="wizard-box" id="date_selection">

    <p>Choose your start date for this <?= $b['b__week_count'] ?> week Bootcamp:</p>
    <div id="select_dates"></div>
    <br /><br /><br />

</div>



<div class="wizard-box">
    <p>Review and confirm admission details:</p>
    <ul>
        <li>Target Outcome: <b><?= $b['c_objective'] ?></b></li>
        <li>Duration: <b><?= $b['b__week_count'].' Week'.show_s($b['b__week_count']) ?></b></li>
        <li>Dates: <b id="class_dates"></b></li>
        <?php /* <li>Content By: <?= '<b>'.$b['b__admins'][0]['u_fname'].' '.$b['b__admins'][0]['u_lname'].'</b>' ?></li> */ ?>
        <li>Commitment: <b><?= format_hours($b['c__estimated_hours']).' in '.$b['b__week_count'].' week'.show_s($b['b__week_count']) ?></b> (<?= format_hours($b['c__estimated_hours']/($b['b__week_count']*7)) ?> per Day)</li>
        <li><?= $this->lang->line('obj_rs_name') ?>: <b id="confirm_support"></b></li>
        <li>Tuition: <b id="confirm_price"></b><span id="outcome_guarantee"></span></li>
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

<div style="text-align:center; margin-top:20px; font-size:0.8em; font-weight:300;"><a href="/<?= $b['b_url_key'] ?>">&laquo; Back to Bootcamp Overview</a></div>