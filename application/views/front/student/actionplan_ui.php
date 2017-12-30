<?php 
//Fetch some variables:
$sprint_units = $this->config->item('sprint_units');
$application_status_salt = $this->config->item('application_status_salt');
$start_times = $this->config->item('start_times');

$class_start_time = strtotime($admission['r_start_date']) + (intval($admission['r_start_time_mins'])*60);

//Do some time calculations for the point system:
$due_date = time_format($admission['r_start_date'],2,(($sprint_index+$sprint_duration_multiplier-1) * ( $admission['b_sprint_unit']=='week' ? 7 : 1 )));
$due_late_date = time_format($admission['r_start_date'],2,(($sprint_index+$sprint_duration_multiplier) * ( $admission['b_sprint_unit']=='week' ? 7 : 1 )));

$ontime_secs_left = (strtotime($due_date) - time())+((24*3600)-1);
$alittle_late_secs = ( $admission['b_sprint_unit']=='week' ? (7*24*3600) : (24*3600) ); //Duplicate logic as $due_late_date (1x Milestone Duration)
$qualify_for_little_late = ( abs($ontime_secs_left) < $alittle_late_secs );
?>
<script>

function mark_done(){

	//Inactive for now! Maybe introduce later...
	/*
	if($('#us_notes').val().length<1){
		alert('Missing report content.');
		return false;
	}
	*/

	var us_notes = $('#us_notes').val(); //This is needed otherwise we lose the variable!
	
	//Show spinner:
	$('.mark_done').hide();
	$('#save_report').html('<img src="/img/round_yellow_load.gif" class="loader" />').hide().fadeIn();

	
	//Save the rest of the content:
	$.post("/api_v1/completion_report", {

		page_loaded:<?= time() ?>,
		us_notes:us_notes,
		us_on_time_score:<?= $ontime_secs_left>0 ? '1.00' : ( $qualify_for_little_late ? '0.50' : '0.00' ) ?>,
		u_id:$('#u_id').val(),
		b_id:$('#b_id').val(),
		r_id:$('#r_id').val(),
        c_id:$('#c_id').val(),
        next_c_id: <?= ( isset($next_intent['c_id']) ? intval($next_intent['c_id']) : 0 ) ?>,
        next_level: <?= ( isset($next_level) ? intval($next_level) : 0 ) ?>,

	} , function(data) {
		//Update UI to confirm with user:
		$('#save_report').html(data).hide().fadeIn();

		//Reposition to top:
		$('html,body').animate({
			scrollTop: $('#save_report').offset().top
		}, 150);
    });
}


function start_report(){
	if(!parseInt($('#checklist_complete').val())){

		$('#initiate_done').html('<span style="color:#FF0000;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> You must first mark all sub-tasks (below) as done before being able to mark this task as done.</span>');
		
	} else {
		$('.mark_done').toggle();

		//Reposition to top:
		$('html,body').animate({
			scrollTop: $('#save_report').offset().top
		}, 150);

		$('#us_notes').focus();
	}
}
</script>

<input type="hidden" id="u_id" value="<?= $admission['u_id'] ?>" />
<input type="hidden" id="b_id" value="<?= $admission['b_id'] ?>" />
<input type="hidden" id="r_id" value="<?= $admission['r_id'] ?>" />
<input type="hidden" id="c_id" value="<?= $intent['c_id'] ?>" />


<?php
//Display Breadcrumb:
echo '<ol class="breadcrumb">';
foreach($breadcrumb_p as $link){
    if($link['link']){
        echo '<li><a href="'.$link['link'].'">'.$link['anchor'].'</a></li>';
    } else {
        echo '<li>'.$link['anchor'].'</li>';
    }
}
echo '</ol>';

if($class_start_time>time()){
    //Class has not yet started:
    ?>
    <script>
        $( document ).ready(function() {
            $("#bootcamp_start").countdowntimer({
                startDate : "<?= date('Y/m/d H:i:s'); ?>",
                dateAndTime : "<?= date('Y/m/d H:i:s' , $class_start_time); ?>",
                size : "lg",
                regexpMatchFormat: "([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})",
                regexpReplaceWith: "<b>$1</b><sup>Days</sup><b>$2</b><sup>H</sup><b>$3</b><sup>M</sup><b>$4</b><sup>S</sup>"
            });
        });
    </script>
    <div class="alert alert-info" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Bootcamp starts in <span id="bootcamp_start"></span></div>
    <?php
}

//Overview:
if($level>1){
    //Messages:
    echo '<h4><i class="fa fa-commenting" aria-hidden="true"></i> Messages</h4>';
    echo '<div class="tips_content">';
    $displayed_messages = 0;
    if(count($i_messages)>0){
        foreach($i_messages as $i){
            if($i['i_status']==1){
                echo '<div class="tip_bubble">';
                echo echo_i($i,$admission['u_fname']);
                echo '</div>';
                $displayed_messages++;
            }
        }
    }
    if($displayed_messages==0){
        echo '<div class="quill_content">None yet.</div>';
    }
    echo '</div>';
}



if($level>2){
    
    echo '<h4><i class="fa fa-check-square" aria-hidden="true"></i> Completion</h4>';
    
    //Do we have a time estimate for this task?
    if($intent['c_time_estimate']>0){
        echo '<div class="quill_content">Estimated completion time is '.echo_time($intent['c_time_estimate'],1).'which equals <b>'.round($intent['c_time_estimate']*60).' Points</b> if completed on-time.</div>';
    }
    
    echo '<div id="save_report" class="quill_content">';
    if(isset($us_data[$intent['c_id']])){

        echo_us($us_data[$intent['c_id']]);

    } else {

        echo '<div class="mark_done" id="initiate_done"><a href="javascript:start_report();" class="btn btn-black"><i class="fa fa-check-circle initial"></i>Mark as Complete</a></div>';
        echo '<div class="mark_done" style="display:none;">';
            if(strlen($intent['c_complete_instructions'])>0){
                echo '<div>Instructions to Mark as Complete: '.$intent['c_complete_instructions'].'</div>';
            }

            if($intent['c_complete_url_required']=='t' || $intent['c_complete_notes_required']=='t') {
                echo '<div style="color:#FF0000;">Requires ' . ($intent['c_complete_url_required'] == 't' ? 'URL' : '') . ($intent['c_complete_notes_required'] == 't' ? ($intent['c_complete_url_required'] == 't' ? ' and ' : '') . 'Notes' : '') . ':</div>';
                echo '<textarea id="us_notes" class="form-control maxout"></textarea>';
            } else {
                echo '<textarea id="us_notes" class="form-control maxout" placeholder="Add Optional Feedback, Notes or URL"></textarea>';
            }

            echo '<a href="javascript:mark_done();" class="btn btn-black"><i class="fa fa-check-circle" aria-hidden="true"></i>Submit</a>';
        echo '</div>';
        
        if($ontime_secs_left>0){
            //Still on time:
            echo '&nbsp;<i class="fa fa-calendar" aria-hidden="true"></i> Due '.$due_date.' '.$start_times[$admission['r_start_time_mins']].' PST in <span id="ontime_dueby"></span>';
        } else {
            echo '<span style="text-decoration: line-through;">&nbsp;<i class="fa fa-calendar" aria-hidden="true"></i> Was due '.$due_date.' '.$start_times[$admission['r_start_time_mins']].' PST</span>';
            if($qualify_for_little_late && $sprint_index>0 && $intent['c_time_estimate']>0){
                echo '<div style="padding-left:22px;"><b>Earn '.floor($intent['c_time_estimate']*30).' late points</b> by '.$due_late_date.' '.$start_times[$admission['r_start_time_mins']].' PST in <span id="late_dueby"></span></div>';
            }
        }
    }
    echo '</div>';
}





//Display Milestone list:
if($level<3){
    echo '<h4>';
        if($level==1){
            echo '<i class="fa fa-flag" aria-hidden="true"></i> '.$sprint_units[$admission['b_sprint_unit']]['name'].' Milestones';
        } elseif($level==2){
            echo '<i class="fa fa-list-ul" aria-hidden="true"></i> '.ucwords($admission['b_sprint_unit']).' '.$sprint_index.( $intent['c_duration_multiplier']>1 ? '-'.($sprint_index+$intent['c_duration_multiplier']-1) : '' ).' Tasks';
        }
        echo ' <span class="sub-title">'.echo_time($intent['c__estimated_hours'],1).'</span>';
    echo '</h4>';

    echo '<div id="list-outbound" class="list-group">';
    
    $sprint_index = 0;
    $done_count = 0;
    foreach($intent['c__child_intents'] as $key=>$sub_intent){
        if($sub_intent['c_status']<1){
            continue;
        }
        $sprint_index += 1; //One step of increment for the start:
        if(isset($us_data[$sub_intent['c_id']]) && $us_data[$sub_intent['c_id']]['us_status']>=0){
            $done_count++;
        }
        echo echo_c($admission,$sub_intent,($level+1),$us_data,$sprint_index,( $key>0 ? $intent['c__child_intents'][($key-1)] : null), ( isset($intent['c__child_intents'][($key+1)]) ? $intent['c__child_intents'][($key+1)] : null));
        //Now increment more for next round:
        $sprint_index += ($sub_intent['c_duration_multiplier']-1);
    }
    $checklist_done = ( $done_count == count($intent['c__child_intents']) );
    echo '</div>';
} else {
    $checklist_done = true;
}
?>
<input type="hidden" id="checklist_complete" value="<?= ( $checklist_done ? 1 : 0 ) ?>" />
