<?php
//Expand Prerequisites:
$b = ( $enrollment['c_level'] ? b_aggregate($enrollment) : $enrollment );
$pre_req_array = prep_prerequisites($b);
?>
<script>
var current_section = 1; //The index for the wizard
var ru_support_package = 0; //Not selected yet
var ru_inbound_u_id = 0; //Coach ID
var ru_network_level = 0; //Not selected

function set_package(package_id){
    //Set price based on chosen package:
    ru_support_package = package_id;

    //Move forward:
    move_ui(1);
}

function choose_coach(u_id){
    //Set coach ID:
    ru_inbound_u_id = u_id;

    //Move forward:
    move_ui(1);
}

function move_ui(adjustment){

    //Set Defaults:
    $('#btn_next a').show().html('Next <i class="fas fa-chevron-right"></i>');


	//Any pre-check with submitted data?
    var current_div_id = $('.wizard-box').eq((current_section+adjustment-1)).attr( "id" );

	//Let's check the value of the current posstible ID for input validation checking:
	if(typeof $('.wizard-box').eq((current_section-1)).attr( "id" ) !== 'undefined' && $('.wizard-box').eq((current_section-1)).attr( "id" ).length){

        var toload_div_id = $('.wizard-box').eq((current_section-1)).attr( "id" );

        if(current_div_id=='price_selection') {

            //Hide Next button as the buttons are on the pricing boxes:
            $('#btn_next a').hide();


        } else if(adjustment>0 && toload_div_id=='networking_selection'){

            ru_network_level = parseInt($('input[name=ru_network_level]:checked').val());
            if(!(ru_network_level>=1)){
                alert('ERROR: Select an option to continue');
                return false;
            } else {
                //Update confirmation page:
                $('#networking_level').html($('#networking_'+ru_network_level).html());
            }

        } else if(adjustment>0 && toload_div_id=='price_selection'){

            //Which support level did they choose?
            if(!(ru_support_package==1 || ru_support_package==2 || ru_support_package==3)){
                alert('ERROR: Select an option to continue');
                return false;
            }

            //Load possible dates based on their Support Framework:
            $( "#select_dates" ).html('<img src="/img/round_load.gif" class="loader" /> Loading Available Dates...');

            $.post("/api_v1/ru_date_selector", {
                ru_id:<?= $ru_id ?>,
                u_id:<?= $u_id ?>,
                u_key:'<?= $u_key ?>',
                ru_support_package:ru_support_package,
            }, function(data) {
                //Append data to view:
                $( "#select_dates" ).html('<select id="start_dates">'+data+'</select>');
            });

        } else if(adjustment>0 && toload_div_id=='date_selection'){

		    //Did they select a Date?
            if(!$('#start_dates').val().length){
                alert('ERROR: Select a start date to continue');
                return false;
            }

		    //Update details:
            $('#class_dates').html($('#start_dates').find(":selected").text());

            if(ru_support_package==1){
                //Reset coaching:
                ru_inbound_u_id = 0;
            }
        }
	}


    //Do we need to skip coach selection?
    if(ru_support_package<2 && adjustment>0 && toload_div_id=='date_selection') {
        adjustment++;
    } else if(ru_support_package<2 && adjustment<0 && current_div_id=='coach_selection') {
        adjustment--;
    } else if ((adjustment>0 && toload_div_id=='date_selection') || (adjustment<0 && current_div_id=='coach_selection')){
	    //Do not show next button on coaching:
        $('#btn_next').hide();
    } else {
        $('#btn_next').show();
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
        $.post("/my/checkout_submit", {

            //Core variables:
            ru_id:<?= $ru_id ?>,
            u_id:<?= $u_id ?>,
            u_key:'<?= $u_key ?>',
            ru_support_package:ru_support_package,
            ru_inbound_u_id:ru_inbound_u_id,
            ru_network_level:ru_network_level,
            r_id:$('#start_dates').val(),

        }, function(data) {
            //Append data to view:
            $( "#application_result" ).html(data);
        });

    } else if((current_section+1)==total_steps){

        //This is the confirmation view before they submit:

        if(ru_inbound_u_id>0){
            $('#btn_next a').html('Book Free Call <i class="fas fa-chevron-right"></i>');
        } else {
            $('#btn_next a').html('CONFIRM & ENROLL <i class="fas fa-chevron-right"></i>');
        }

        //Update support package:
        $('#confirm_support').html( $('#p_name_'+ru_support_package).html() + ( ru_inbound_u_id>0 ? ' with ' + $('#coach_'+ru_inbound_u_id+' h3').text() : '' ));

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
.wizard-box ul li { margin-bottom:10px; }
.wizard-box a { text-decoration:underline; }
.wizard-box>p { font-size:1.6em !important; margin-bottom: 20px; }
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



<?= echo_b_header($b); ?>


<?php if(count($pre_req_array)>0){ ?>
<div class="wizard-box" id="review_prerequisites">
    <p>Welcome <?= one_two_explode('',' ', $b['u_full_name']) ?> 👋​</p>
    <p>Here are the <?= count($pre_req_array) ?> prerequisite<?= echo__s(count($pre_req_array)) ?> that will empower you to <?= strtolower($b['c_outcome']) ?>:</p>
    <ul style="list-style: decimal; margin-top: 30px;">
	<?php
	foreach($pre_req_array as $index=>$prereq){
	    echo '<li>'.$prereq.'</li>';
	}
	?>
    </ul>
	<br />
</div>
<?php } ?>





<div class="wizard-box" id="price_selection">
    <?= ( $b['b_offers_diy'] && $b['b_offers_coaching'] ? '<p>Choose a support package that\'s right for you:</p>' : '' ) ?>
    <div class="row">
        <?php echo_package($b,1,0); ?>
        <?php echo_package($b,0,0); ?>
    </div>
    <br />
</div>


<div class="wizard-box" id="date_selection">
    <p>Choose the best <?= $b['b__week_count'] ?>-week timeframe for you to join and <?= strtolower($b['c_outcome']) ?>:</p>
    <div id="select_dates"></div>
    <br /><br /><br />
</div>




<div class="wizard-box" id="coach_selection">
    <p>Choose your coach by checking each person's biography, LinkedIn and other social profiles:</p>
    <div class="row">
        <?php
        if($b['b_offers_coaching']){
            $count = 0;
            foreach($b['b__coaches'] as $coach){

                if(!$coach['u_booking_x_id']){
                    //Coach does not have their Booking ID, which is required to be listed:
                    continue;
                }

                if($count>0 && fmod($count,2)==0){
                    //A new row:
                    echo '</div><div class="row">';
                }
                echo_coach($coach, $b,0);
                $count++;
            }
        }
        ?>
    </div>
    <br /><br />
</div>



<div class="wizard-box" id="networking_selection">
    <p>Choose your desired level of interaction and collaboration with fellow classmates:</p>
    <div class="row">
        <?php
        $ru_network_levels = echo_status('ru_network_level');
        unset($ru_network_levels[0]); //Remove not-selected as we don't need it
        if(!$b['b_offers_job']){
            unset($ru_network_levels[2]); //Interview practice only available if offers a job
        }

        foreach($ru_network_levels as $key=>$ru_network_level){
            echo '<div class="radio">
                        <label class="radio-box">
                            <input type="radio" name="ru_network_level" value="'.$key.'" />
                            <h3><i class="'.$ru_network_level['s_icon'].'"></i> <b id="networking_'.$key.'">'.$ru_network_level['s_name'].'</b> [Free]</h3>
                            <p>'.$ru_network_level['s_desc'].'</p>
                        </label>
                    </div>';
        }
        ?>
    </div>
    <br /><br />
</div>






<div class="wizard-box" id="confirm_details">
    <p>Review and confirm your enrollment details:</p>

    <div class="review-item">Target Outcome<b><?= $b['c_outcome'] ?></b></div>
    <div class="review-item">Your Commitment: <b><?= $b['b__week_count'].' Week'.echo__s($b['b__week_count']).' @ '.echo_hours(($b['c__estimated_hours']/$b['b__week_count']),false).'/Week' ?></b></div>
    <div class="review-item">Class Dates: <b id="class_dates"></b></div>
    <div class="review-item">Classmate Interactions: <b id="networking_level"></b></div>
    <div class="review-item">Support Package: <b id="confirm_support"></b></div>
    <br />
</div>


<div class="wizard-box">
    <p style="text-align:center;"><b>Processing...</b></p>
	<div id="application_result"><img src="/img/round_load.gif" class="loader" /></div>
</div>


<a id="btn_prev" href="javascript:move_ui(-1)" class="btn btn-primary" style="padding-left:10px;padding-right:12px; display:none;"><i class="fas fa-chevron-left"></i></a>
<span id="btn_next"><a href="javascript:move_ui(1)" class="btn btn-primary">Next <i class="fas fa-chevron-right"></i></a></span>

<div style="text-align:right; margin:0px 2px 0;"><b id="step_progress"></b></div>
<div class="progress" style="margin:auto 2px;">
	<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%;"></div>
</div>

<div style="text-align:center; margin-top:20px; font-size:0.8em; font-weight:300;"><a href="/<?= $b['b_url_key'] ?>">&laquo; Back to Bootcamp Overview</a></div>