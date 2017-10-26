<?php 
//Fetch some variables:
$sprint_units = $this->config->item('sprint_units');
$application_status_salt = $this->config->item('application_status_salt');
?>
<script>

$( document ).ready(function() {
	if(!parseInt($('#checklist_complete').val())){
		//Show lock icon if sub-tasks are required
		$('#initiate_done .fa').removeClass('fa-check-circle').addClass('fa-lock');
	}
});


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
	$.post("/process/completion_report", {	

		us_notes:us_notes,
		u_id:$('#u_id').val(),
		b_id:$('#b_id').val(),
		r_id:$('#r_id').val(),
		c_id:$('#c_id').val(),
		
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

//Overview:
//echo '<h4><i class="fa fa-binoculars" aria-hidden="true"></i> Overview <span class="sub-title">'.echo_time($intent['c_time_estimate'],1).'</span></h4>';
echo '<div class="quill_content">'.$intent['c_todo_overview'].'</div>';
if($intent['c_time_estimate']>0){
    echo '<div class="quill_content">Estimated completion time is '.echo_time($intent['c_time_estimate'],1).'which equals <b>'.round($intent['c_time_estimate']*60).' Points</b> if completed on-time. <a href="https://support.mench.co/hc/en-us/articles/115002372531"><u>Learn More &raquo;</u></a></div>';
}

//Mark Complete:
//echo '<h4><i class="fa fa-check-circle-o" aria-hidden="true"></i> Mark as Done</h4>';
//echo '<div class="quill_content"><i class="fa fa-calendar" aria-hidden="true"></i> Due by '.time_format($admission['r_start_date'],5,(calculate_duration($admission,count($intent['c__child_intents']))-1)).' 11:59 PM PST</div>';

echo '<div id="save_report" class="quill_content">';
if(isset($us_data[$intent['c_id']])){
    echo_us($us_data[$intent['c_id']]);
} else {
    echo '<div class="mark_done" id="initiate_done"><a href="javascript:start_report();" class="btn btn-black"><i class="fa fa-check-circle initial"></i>Mark as Done</a></div>';
    echo '<div class="mark_done" style="display:none;">';
    echo '<div>Optional comments:</div>';
    echo '<textarea id="us_notes" placeholder="Share comments to get feedback from your instructor..." class="form-control maxout"></textarea>';
    echo '<a href="javascript:mark_done();" class="btn btn-black"><i class="fa fa-check-circle" aria-hidden="true"></i>Submit</a>';
    echo '</div>';
}
echo '</div>';


//Tips:
if($level>1){
    echo '<h4><i class="fa fa-lightbulb-o" aria-hidden="true"></i> Tips</h4>';
    echo '<div class="tips_content">';
    $displayed = 0;
    if(count($i_messages)>0){
        foreach($i_messages as $i){
            //Do logic for ASAP/DRIP-FEED here:
            echo '<div class="tip_bubble">';
            echo '<i class="fa fa-lightbulb-o" aria-hidden="true"></i>';
            echo_i($i,$admission['u_fname']);
            echo '</div>';
        }
    } else {
        echo '<div class="quill_content">None yet.</div>';
    }
    //echo $intent['c_todo_overview'];
    echo '</div>';
}


//Display Action Plan list:
if($level<3){
    echo '<h4><i class="fa fa-list-ul" aria-hidden="true"></i> '.( $level==1 ? $sprint_units[$admission['b_sprint_unit']]['name'].' Action Plan' : 'Tasks' ).' <span class="sub-title">'.echo_time(($intent['c__estimated_hours']-$intent['c_time_estimate']),1).'</span></h4>';
    echo '<div id="list-outbound" class="list-group">';
    if($level==1){
        //Show their successful admission to also train on UI:
        //<a href="/my/applications/?u_key='.md5($matching_users[0]['u_id'].$application_status_salt).'&u_id='.$matching_users[0]['u_id'].'&show_action_plan=1"
        echo '<li class="list-group-item">';
        echo status_bible('us',1,1).' ';
        echo '<span class="inline-level">&nbsp;</span>';
        echo 'Complete Bootcamp Application';
        //echo '<span class="title-sub"><i class="fa fa-list-ul" aria-hidden="true"></i>3</span>';
        echo '</li>';
    }
    $done_count = 0;
    foreach($intent['c__child_intents'] as $sub_intent){
        if(isset($us_data[$sub_intent['c_id']]) && $us_data[$sub_intent['c_id']]['us_status']>=0){
            $done_count++;
        }
        echo echo_c($bootcamp,$sub_intent,($level+1),$us_data);
    }
    $checklist_done = ( $done_count == count($intent['c__child_intents']) );
    echo '</div>';
} else {
    $checklist_done = true;
}
?>
<input type="hidden" id="checklist_complete" value="<?= ( $checklist_done ? 1 : 0 ) ?>" />
