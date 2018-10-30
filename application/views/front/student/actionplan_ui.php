<?php

//Define some initial variables:
$application_status_salt = $this->config->item('application_status_salt');
$udata = $this->session->userdata('user');
$page_load_time = time();
$messages = $this->Db_model->i_fetch(array(
    'i_outbound_c_id' => $c['c_id'],
    'i_status' => 1, //On start messages only
));

?>
<script>

function update_k_save(){

	var us_notes = $('#us_notes').val(); //This is needed otherwise we lose the variable!
	
	//Show spinner:
	$('.update_k_save').hide();
	$('#save_report').html('<img src="/img/round_load.gif" class="loader" />').hide().fadeIn();
	
	//Save the rest of the content:
	$.post("/my/us_save", {

        page_load_time:<?= $page_load_time ?>,
		us_notes:us_notes,
        u_id:$('#u_id').val(),
        s_key:$('#s_key').val(),
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

function update_k_start(){
    $('.update_k_save').toggle();
    $('#us_notes').focus();
}


</script>

<input type="hidden" id="c_id" value="<?= $c['c_id'] ?>" />
<input type="hidden" id="u_id" value="<?= $subscriptions[0]['u_id'] ?>" />
<input type="hidden" id="u_key" value="<?= md5($subscriptions[0]['u_id'].$application_status_salt) ?>" />

<?php
//Fetch inbound breadcrumb tree all the way to the top of subscription w_c_id
foreach($k_ins as $k){
    echo echo_k($k, 1);
}

//Show title
echo '<h5>'.$c['c_outcome'].'</h5>';


if(count($k_ins)==0){

    //This must be top level subscription, show subscription data:
    echo '<div class="sub_title">';
        echo echo_status('w_status',$subscriptions[0]['w_status']);
        echo ' &nbsp;&nbsp;<i class="fas fa-lightbulb-on"></i> '.$subscriptions[0]['c__tree_all_count'];
        echo ' &nbsp;&nbsp;<i class="fas fa-clock"></i> '.echo_hour_range($subscriptions[0]);
        echo ' &nbsp;&nbsp;<i class="fas fa-calendar-check"></i> '.echo_time($subscriptions[0]['w_timestamp']);
        //TODO Show coach name if w_inbound_u_id>0
        //TODO show subscription pace data such as start/end time, weekly rate & notification type
    echo '</div>';

} elseif(count($k_ins)==1){

    //Show completion progress for the single inbound intent:
    echo '<div id="save_report" class="sub_title">';

        echo echo_status('k_status',$k_ins[0]['k_status']);

        echo ' &nbsp;&nbsp;<i class="fas fa-lightbulb-on"></i> '.$k_ins[0]['c__tree_all_count'];
        echo ' &nbsp;&nbsp;<i class="fas fa-clock"></i> '.echo_hour_range($k_ins[0]);

        if($k_ins[0]['k_last_updated']){
            echo ' &nbsp;&nbsp;<i class="fas fa-calendar-check"></i> '.echo_time($k_ins[0]['k_last_updated']);
        }

        if(strlen($k_ins[0]['k_notes'])>0){
            echo '<div><i class="fas fa-comment-dots initial"></i> '.echo_link(nl2br(htmlentities($k_ins[0]['k_notes']))).'</div>';
        }

        //Show button in its own row:
        echo '<div class="update_k_save" id="initiate_done"><a href="javascript:update_k_start();" class="btn btn-tight btn-black">'.($k_ins[0]['k_status']<=0 ? '<i class="fas fa-check-square"></i> Mark as Complete' : '<i class="fas fa-edit"></i> Modify Report' ).'</a></div>';


        //Echo hidden completion box on page:
        if($k_ins[0]['c_require_url_to_complete'] && $k_ins[0]['c_require_notes_to_complete']){
            $red_note = 'Requires a URL & completion notes';
            $textarea_note = 'Include a URL & completion notes (and optional coach feedback) to mark as complete';
        } elseif($k_ins[0]['c_require_url_to_complete']){
            $red_note = 'Requires a URL';
            $textarea_note = 'Include a URL (and optional coach feedback) to mark as complete';
        } elseif($k_ins[0]['c_require_notes_to_complete']){
            $red_note = 'Requires completion notes';
            $textarea_note = 'Include completion notes (and optional coach feedback) to mark as complete';
        } else {
            $red_note = null;
            $textarea_note = 'Include optional feedback for your coach';
        }

        //Submission button visible after first button was clicked:
        echo '<div class="update_k_save" style="display:none; margin-top:10px;">';
            if($red_note) {
                echo '<div style="color:#FF0000;"><i class="fas fa-exclamation-triangle"></i> ' . $red_note . '</div>';
            }
            echo '<textarea id="us_notes" class="form-control maxout" placeholder="'.$textarea_note.'"></textarea>';
            echo '<a href="javascript:update_k_save();" class="btn btn-tight btn-black"><i class="fas fa-check-circle"></i>Submit</a>';
        echo '</div>';

    echo '</div>';
}


//Show all messages:
if(count($messages)>0){
    echo '<div class="tips_content">';
    foreach($messages as $i){
        if($i['i_status']==1){
            echo '<div class="tip_bubble">';
            echo echo_i( array_merge( $i , array(
                'e_outbound_u_id' => $subscriptions[0]['u_id'],
            )) , $subscriptions[0]['u_full_name'] );
            echo '</div>';
        }
    }
    echo '</div>';
}



echo '<div class="list-group" style="margin-top: 10px;">';
foreach($k_outs as $k){
    echo echo_k($k, 0);
}
echo '</div>';






/* ******************************
 * Next/Previous Buttons
 ****************************** */
/*
echo '<h4 class="maxout"><i class="fas fa-arrows"></i> Navigation</h4>';
echo '<div style="font-size:0.8em;">';
if(isset($previous_intent['c_id'])){
    echo '<a href="/my/actionplan/'.$previous_intent['c_id'].'" class="btn btn-tight btn-black" style="margin:0;"><i class="fas fa-arrow-left"></i> Previous</a>';
}
if(isset($next_intent['c_id'])){
    echo '<a href="/my/actionplan/'.$next_intent['c_id'].'" class="btn btn-tight btn-black" style="margin:0 0 0 8px;">Next <i class="fas fa-arrow-right"></i></a>';
}
echo '</div>';
*/




/* ******************************
 * Task/Step List
 ****************************** */


//Fetch child intents that are in their subscriptions:
/*

echo '<div id="list-outbound" class="list-group maxout">';

//This could be either a list of Tasks or Steps, we'll know using $level
$previous_item_complete = true; //We start this as its true for the very first Step
foreach($ks[0]['c__child_intents'] as $c){

    if($c['c_status']<0){
        //Drafting items should be skipped:
        continue;
    }

    if($level==1){

        //Task completion status;
        $this_item_e_status = ( isset($us_data[$c['c_id']]) ? $us_data[$c['c_id']]['e_status'] : -4 );

        //TODO Optimize this based on c_is_any value

    } elseif($level==2){

        //Step List
        $this_item_e_status = ( isset($us_data[$c['c_id']]) ? $us_data[$c['c_id']]['e_status'] : -4 );

    }

    //Now determine the lock status of this item...

    //Used in $unlocked_item logic in case coach modifies Action Plan and Adds items before previously completed items:
    $this_item_complete = ( $this_item_e_status>=-2 );

    //See Status:
    $unlocked_item = true; //TODO Can later put limitations if necessary, for now, lets keep it all open

    //Left content
    if($unlocked_item){

        //Show link to enter this item:
        $ui = '<a href="/my/actionplan/'.$subscriptions[0]['b_id'].'/'.$c['c_id'].'" class="list-group-item">';
        $ui .= '<span class="pull-right"><span class="badge badge-primary" style="margin-top:-5px;"><i class="fas fa-chevron-right"></i></span></span>';
        $ui .= echo_status('e_status',$this_item_e_status,1).' ';

    } else {

        //Step/Task is locked, do not show link:
        $ui = '<li class="list-group-item">';
        $ui .= '<i class="fas fa-lock initial"></i> ';

    }

    //Title on the left:
    if($level==1){
        $ui .= '<span>Task '.$c['cr_outbound_rank'].':</span> ';
    } elseif($level==2){
        $ui .= '<span>Step '.$c['cr_outbound_rank'].':</span> ';
    }

    //Intent title:
    $ui .= $c['c_outcome'].' ';

    $ui .= '<span class="sub-stats">';

    //Enable total hours/Task reporting...
    if($level==1 && isset($c['c__estimated_hours'])){
        $ui .= echo_estimated_time($c['c__estimated_hours'],1);
    } elseif(isset($c['c_time_estimate'])){
        $ui .= echo_estimated_time($c['c_time_estimate'],1);
    }

    if($level==1 && $unlocked_item && isset($child_step_count) && $child_step_count){
        //Show the number of sub-Steps:
        //$ui .= '<span class="title-sub"><i class="fas fa-flag"></i>'.$child_step_count.'</span>';
    }

    $ui .= '</span>';

    $ui .= ( $unlocked_item ? '</a>' : '</li>');

    echo $ui;

    //Save this item's completion rate for the next run:
    $previous_item_complete = $this_item_complete;
}
echo '</div>';
*/
?>
