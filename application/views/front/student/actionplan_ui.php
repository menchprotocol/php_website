<?php

//Define some initial variables:
$application_status_salt = $this->config->item('application_status_salt');
$page_load_time = time();

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
	$('#save_report').html('<img src="/img/round_load.gif" class="loader" />').hide().fadeIn();
	
	//Save the rest of the content:
	$.post("/api_v1/us_save", {

        page_load_time:<?= $page_load_time ?>,
		us_notes:us_notes,
        u_id:$('#u_id').val(),
        s_key:$('#s_key').val(),
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
    $('.mark_done').toggle();

    //Reposition to top:
    //$('html,body').animate({ scrollTop: $('#save_report').offset().top }, 150);

    $('#us_notes').focus();
}

function all_enrollments(){

    //Loads the list of all Student enrollments so they can switch their Action Plan

    //Show tem loader:
    $('#all_enrollments').show().html('<div style="font-size: 0.8em;"><img src="/img/round_load.gif" class="loader" /> Loading Bootcamps...</div>');
    $('.ab_btn').hide();

    //Load the frame:
    $.post("/my/all_enrollments", {

        u_id:$('#u_id').val(),
        u_key:$('#u_key').val(),
        current_r_id:$('#r_id').val(),

    }, function(data) {

        //Empty Inputs Fields if success:
        $('#all_enrollments').html(data);

        //SHow inner tooltips:
        $('[data-toggle="tooltip"]').tooltip();

    });

}

</script>

<input type="hidden" id="b_id" value="<?= $enrollment['b_id'] ?>" />
<input type="hidden" id="r_id" value="<?= $enrollment['r_id'] ?>" />
<input type="hidden" id="c_id" value="<?= $intent['c_id'] ?>" />
<input type="hidden" id="u_id" value="<?= $enrollment['u_id'] ?>" />
<input type="hidden" id="u_key" value="<?= md5($enrollment['u_id'].$application_status_salt) ?>" />
<input type="hidden" id="s_key" value="<?= md5($intent['c_id'].$page_load_time.'pag3l0aDSla7'.$enrollment['u_id']) ?>" />

<?php

/* ******************************
 * Breadcrumb
 ****************************** */
echo '<div id="all_enrollments"></div>';
echo '<ol class="breadcrumb">';
foreach($breadcrumb_p as $position=>$link){
    echo '<li>';
    echo ( $link['link'] ? '<a href="'.$link['link'].'">'.$link['anchor'].'</a>' : $link['anchor'] );
    if($position==0){
        //Show the Bootcamp switcher next to the Bootcamp title:
        echo ' <a href="javascript:void(0);" onclick="all_enrollments()" class="ab_btn" title="Switch Between Bootcamps">(switch)</a>';
    }
    echo '</li>';
}
echo '</ol>';




/* ****************************************
 * Class Not Started / Ended Notification
 *************************************** */
$class_has_started = (strtotime($class['r_start_date'])<=time()) || (0); //Always treat as started for now?

$subscription_ends = time()+(24*3600*7); //TODO Fix

$class_has_ended = ($subscription_ends<=time());
if(!$class_has_started){
    //Class has not yet started:
    ?>
    <script>
        $( document ).ready(function() {
            $("#b_start").countdowntimer({
                startDate : "<?= date('Y/m/d H:i:s'); ?>",
                dateAndTime : "<?= date('Y/m/d H:i:s' , strtotime($class['r_start_date'])); ?>",
                size : "lg",
                regexpMatchFormat: "([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})",
                regexpReplaceWith: "<b>$1</b><sup>Days</sup><b>$2</b><sup>H</sup><b>$3</b><sup>M</sup><b>$4</b><sup>S</sup>"
            });
        });
    </script>
    <div class="alert alert-info maxout" role="alert"><i class="fas fa-exclamation-triangle"></i> Class starts in <span id="b_start"></span></div>
    <?php
} elseif($class_has_ended){
    //Class has ended
    echo '<div class="alert alert-info maxout" role="alert"><i class="fas fa-exclamation-triangle"></i> Class ended '.strtolower(echo_diff_time($subscription_ends)).' ago</div>';
}








/* ******************************
 * Intent ONSTART Messages
 ****************************** */
$displayed_messages = 0;
if(count($intent['c__messages'])>0){
    foreach($intent['c__messages'] as $i){
        if($i['i_status']==1){
            $displayed_messages++;
        }
    }
}
if($displayed_messages>0){
    $uenrollment = $this->session->userdata('uenrollment');

    //Only load the 3rd Level Step messages that are not yet complete by default, because everything else has already been communicated to the student
    $load_open = ( $level>=2 ); //&& !isset($us_data[$intent['c_id']])

    //Messages:
    echo '<h4 style="margin-top:20px;" class="maxout"><a href="javascript:void(0)" onclick="$(\'.messages_ap\').toggle();"><i class="pointer fas fa-caret-right messages_ap" style="display:'.( $load_open ? 'none' : 'inline-block' ).';"></i><i class="pointer fas fa-caret-down messages_ap" style="display:'.( $load_open ? 'inline-block' : 'none' ).';"></i> <i class="fas fa-comment-dots"></i> '.$displayed_messages.' Message'.($displayed_messages==1?'':'s').'</a></h4>';
    echo '<div class="tips_content messages_ap" style="display:'.( $load_open ? 'block' : 'none' ).';">';
    foreach($intent['c__messages'] as $i){
        if($i['i_status']==1){
            echo '<div class="tip_bubble">';
            echo echo_i( array_merge( $i , array(
                ( isset($uenrollment) && count($uenrollment)>0 ? 'noshow' : 'show_new_window' ) => 1, //TO embed the video
                'e_b_id'=>$enrollment['b_id'],
                'e_outbound_u_id'=>$enrollment['u_id'],
            )) , $enrollment['u_full_name'] );
            echo '</div>';
        }
    }
    echo '</div>';
}





if($level==2){


    /* ******************************
     * Step Completion
     ****************************** */
    echo '<h4 class="maxout"><i class="fas fa-check-circle"></i> Completion</h4>';

    if($class_has_ended || !$class_has_started){

        //Class if finished, no more submissions allowed!
        echo '<div class="alert alert-info maxout" role="alert"><i class="fas fa-exclamation-triangle"></i> Submissions are not allowed at this time.</div>';

    } else {

        echo '<div id="save_report" class="quill_content">';
        if(isset($us_data[$intent['c_id']])){

            echo_completion_report($us_data[$intent['c_id']]);

        } else {

            if($intent['c_require_url_to_complete'] && $intent['c_require_notes_to_complete']){
                $red_note = 'a URL & completion notes';
                $textarea_note = 'Include a URL & completion notes (and optional coach feedback) to mark as complete';
            } elseif($intent['c_require_url_to_complete']){
                $red_note = 'a URL';
                $textarea_note = 'Include a URL (and optional coach feedback) to mark as complete';
            } elseif($intent['c_require_notes_to_complete']){
                $red_note = 'completion notes';
                $textarea_note = 'Include completion notes (and optional coach feedback) to mark as complete';
            } else {
                $red_note = null;
                $textarea_note = 'Include optional feedback for your coach';
            }

            echo '<div>Estimated time to complete: '.echo_estimated_time($intent['c_time_estimate'],1).'</div>';
            echo '<div class="mark_done" id="initiate_done"><a href="javascript:start_report();" class="btn btn-tight btn-black" style="padding-left:8px; padding-right:8px;"><i class="fas fa-check-circle initial"></i>Mark as Complete</a></div>';


            //Submission button visible after first button was clicked:
            echo '<div class="mark_done" style="display:none; margin-top:10px;">';
            //What instructions do we need to give?
            if($red_note) {
                echo '<div style="color:#FF0000;"><i class="fas fa-exclamation-triangle"></i> Marking as complete requires ' . $red_note . '</div>';
            }
            echo '<textarea id="us_notes" class="form-control maxout" placeholder="'.$textarea_note.'"></textarea>';
            echo '<a href="javascript:mark_done();" class="btn btn-tight btn-black"><i class="fas fa-check-circle"></i>Submit</a>';
            echo '</div>';


            //Show when Bootcamp ends:
            if($subscription_ends>time()){
                ?>
                <script>
                    $( document ).ready(function() {
                        $("#ontime_dueby").countdowntimer({
                            startDate : "<?= date('Y/m/d H:i:s'); ?>",
                            dateAndTime : "<?= date('Y/m/d H:i:s' , $subscription_ends); ?>",
                            size : "lg",
                            regexpMatchFormat: "([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})",
                            regexpReplaceWith: "<b>$1</b><sup>Days</sup><b>$2</b><sup>H</sup><b>$3</b><sup>M</sup><b>$4</b><sup>S</sup>"
                        });
                    });
                </script>
                <div><i class="fas fa-clock"></i> Due in <span id="ontime_dueby"></span></div>
                <?php
            }

        }
        echo '</div>';
    }




    /* ******************************
     * Step Next/Previous Buttons
     ****************************** */
    $previous_on = (isset($previous_intent['c_id']));
    $next_on = ( $next_level>=2 && ($class_has_ended || (isset($next_intent['c_id']) && isset($us_data[$intent['c_id']]))));
    if($previous_on || $next_on){
        echo '<h4 class="maxout"><i class="fas fa-arrows"></i> Navigation</h4>';
        echo '<div style="font-size:0.8em;">';
        if($previous_on){
            echo '<a href="/my/actionplan/'.$enrollment['b_id'].'/'.$previous_intent['c_id'].'" class="btn btn-tight btn-black" style="margin:0;"><i class="fas fa-arrow-left"></i> Previous</a>';
        }
        if($next_on){
            echo '<a href="/my/actionplan/'.$enrollment['b_id'].'/'.$next_intent['c_id'].'" class="btn btn-tight btn-black" style="margin:0 0 0 8px;">Next <i class="fas fa-arrow-right"></i></a>';
        }
        echo '</div>';
    }

}







/* ******************************
 * Task/Step List
 ****************************** */

if($level==1){

    echo '<h4 class="maxout">';
        if($level==1){
            echo '<i class="fas fa-check-square"></i> Tasks';
        } elseif($level==2){
            echo '<i class="fal fa-clipboard-check"></i> Steps';
        }
        //Show aggregate hours:
        echo ' <span class="sub-title">'.echo_estimated_time($intent['c__estimated_hours'],1).'</span>';
    echo '</h4>';

    echo '<div id="list-outbound" class="list-group maxout">';

    //This could be either a list of Tasks or Steps, we'll know using $level
    $previous_item_complete = true; //We start this as its true for the very first Step
    foreach($intent['c__child_intents'] as $this_intent){

        if($this_intent['c_status']<0){
            //Drafting items should be skipped:
            continue;
        }

        if($level==1){

            //Task completion status;
            $this_item_e_status = ( isset($us_data[$this_intent['c_id']]) ? $us_data[$this_intent['c_id']]['e_status'] : -4 );

            //TODO Optimize this based on c_is_any value

        } elseif($level==2){

            //Step List
            $this_item_e_status = ( isset($us_data[$this_intent['c_id']]) ? $us_data[$this_intent['c_id']]['e_status'] : -4 );

        }

        //Now determine the lock status of this item...

        //Used in $unlocked_item logic in case coach modifies Action Plan and Adds items before previously completed items:
        $this_item_complete = ( $this_item_e_status>=-2 );

        //See Status:
        $unlocked_item = $class_has_started && ($class_has_ended || $previous_item_complete || $this_item_complete);

        //Left content
        if($unlocked_item){

            //Show link to enter this item:
            $ui = '<a href="/my/actionplan/'.$enrollment['b_id'].'/'.$this_intent['c_id'].'" class="list-group-item">';
            $ui .= '<span class="pull-right"><span class="badge badge-primary" style="margin-top:-5px;"><i class="fas fa-chevron-right"></i></span></span>';
            $ui .= echo_status('e_status',$this_item_e_status,1).' ';

        } else {

            //Step/Task is locked, do not show link:
            $ui = '<li class="list-group-item">';
            $ui .= '<i class="fas fa-lock initial"></i> ';

        }

        //Title on the left:
        if($level==1){
            $ui .= '<span>Task '.$this_intent['cr_outbound_rank'].':</span> ';
        } elseif($level==2){
            $ui .= '<span>Step '.$this_intent['cr_outbound_rank'].':</span> ';
        }

        //Intent title:
        $ui .= $this_intent['c_outcome'].' ';

        $ui .= '<span class="sub-stats">';

        //Enable total hours/Task reporting...
        if($level==1 && isset($this_intent['c__estimated_hours'])){
            $ui .= echo_estimated_time($this_intent['c__estimated_hours'],1);
        } elseif(isset($this_intent['c_time_estimate'])){
            $ui .= echo_estimated_time($this_intent['c_time_estimate'],1);
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
}
?>
