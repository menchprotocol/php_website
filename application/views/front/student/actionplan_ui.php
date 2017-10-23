<?php 
//Fetch some variables:
$application_status_salt = $this->config->item('application_status_salt');
?>
<script>

function mark_done(){

	if($('#us_notes').val().length<1){
		alert('Missing report content.');
		return false;
	}
	
	//Show spinner:
	$('.mark_done').hide();
	$('#save_report').html('<img src="/img/round_yellow_load.gif" class="loader" />').hide().fadeIn();

	
	//Save the rest of the content:
	$.post("/process/completion_report", {	

		u_id:$('#u_id').val(),
		b_id:$('#b_id').val(),
		r_id:$('#r_id').val(),
		c_id:$('#c_id').val(),
		us_notes:$('#us_notes').val(),
		
	} , function(data) {
		//Update UI to confirm with user:
		$('#save_report').html(data).hide().fadeIn();
    });
}


function start_report(){
	$('.mark_done').toggle();
	
	$('html,body').animate({
		scrollTop: $('#completio_report').offset().top
	}, 150);

	$('#us_notes').focus();
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





//Display Action Plan list:
if($level<3){
    echo '<h4><i class="fa fa-list-ul" aria-hidden="true"></i> '.( $level==1 ? 'Action Plan' : 'Tasks' ).' <span class="sub-title">'.echo_time(($intent['c__estimated_hours']-$intent['c_time_estimate']),1).'</span></h4>';
    echo '<div id="list-outbound" class="list-group">';
    if($level==1){
        //Show their successful admission to also train on UI:
        //<a href="/my/applications/?u_key='.md5($matching_users[0]['u_id'].$application_status_salt).'&u_id='.$matching_users[0]['u_id'].'&show_action_plan=1"
        echo '<li class="list-group-item">';
        echo '<i class="fa fa-check-circle initial" aria-hidden="true"></i> ';
        echo '<span class="inline-level">START</span>';
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

//Overview:
echo '<h4><i class="fa fa-binoculars" aria-hidden="true"></i> Overview <span class="sub-title">'.echo_time($intent['c_time_estimate'],1).'</span></h4>';
echo '<div class="quill_content">'.$intent['c_todo_overview'].'</div>';



//Tips:
if($level>1){
    echo '<h4><i class="fa fa-lightbulb-o" aria-hidden="true"></i> Tips</h4>';
    echo '<div class="tips_content">';
    $displayed = 0;
    if(count($i_messages)>0){
        foreach($i_messages as $i){
            //Do logic for ASAP/DRIP-FEED here:
            
        }
    }
    
    if($displayed==0){
        //No tips for now:
        echo '<div class="quill_content">'.( count($i_messages)>0 ? 'None yet.' : 'None yet.' ).'</div>';
    }
    
    //echo $intent['c_todo_overview'];
    echo '</div>';
}



//Mark Complete:
echo '<h4 id="completio_report"><i class="fa fa-check-circle-o" aria-hidden="true"></i> Completion Report</h4>';
if($level==1 && 0){
    echo '<div class="quill_content"><i class="fa fa-calendar" aria-hidden="true"></i> Due by '.time_format($admission['r_start_date'],5,(calculate_duration($admission,count($intent['c__child_intents']))-1)).' 11:59 PM PST</div>';
}
echo '';
echo '<div id="save_report" class="quill_content">';
if(isset($us_data[$intent['c_id']])){
    echo_us($us_data[$intent['c_id']]);
}
echo '</div>';
if(!isset($us_data[$intent['c_id']])){
    if($checklist_done){
        echo '<div class="quill_content mark_done"><a href="javascript:start_report();" class="btn btn-black"><i class="fa fa-pencil" aria-hidden="true"></i>Start</a></div>';
        echo '<div class="quill_content mark_done" style="display:none;">';
        echo '<textarea id="us_notes" placeholder="A summary of what you did and the challenges you faced. You can also add URLs that reference your work..." class="form-control"></textarea>';
        echo '<a href="javascript:mark_done();" class="btn btn-black"><i class="fa fa-check-circle-o" aria-hidden="true"></i>Submit</a>';
        echo '</div>';
    } else {
        echo '<div id="save_report" class="quill_content">PENDING: You must first submit the completion report for all child tasks (listed above).</div>';
    }   
}
?>