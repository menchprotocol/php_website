<?php $page_load_time = time(); ?>
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

</script>

<input type="hidden" id="b_id" value="<?= $admission['b_id'] ?>" />
<input type="hidden" id="r_id" value="<?= $admission['r_id'] ?>" />
<input type="hidden" id="c_id" value="<?= $intent['c_id'] ?>" />
<input type="hidden" id="u_id" value="<?= $admission['u_id'] ?>" />
<input type="hidden" id="s_key" value="<?= md5($intent['c_id'].$page_load_time.'pag3l0aDSla7'.$admission['u_id']) ?>" />

<?php


/* ******************************
 * Breadcrumb
 ****************************** */
echo '<ol class="breadcrumb">';
foreach($breadcrumb_p as $link){
    if($link['link']){
        echo '<li><a href="'.$link['link'].'">'.$link['anchor'].'</a></li>';
    } else {
        echo '<li>'.$link['anchor'].'</li>';
    }
}
echo '</ol>';




/* ****************************************
 * Class Not Started / Ended Notification
 *************************************** */
$class_has_started = ($class['r__class_start_time']<=time());
$class_has_ended = ($class['r__class_end_time']<=time());
if(!$class_has_started){
    //Class has not yet started:
    ?>
    <script>
        $( document ).ready(function() {
            $("#project_start").countdowntimer({
                startDate : "<?= date('Y/m/d H:i:s'); ?>",
                dateAndTime : "<?= date('Y/m/d H:i:s' , $class['r__class_start_time']); ?>",
                size : "lg",
                regexpMatchFormat: "([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})",
                regexpReplaceWith: "<b>$1</b><sup>Days</sup><b>$2</b><sup>H</sup><b>$3</b><sup>M</sup><b>$4</b><sup>S</sup>"
            });
        });
    </script>
    <div class="alert alert-info" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Class starts in <span id="project_start"></span></div>
    <?php
} elseif($class_has_ended){
    //Class has ended
    echo '<div class="alert alert-info" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Class ended '.strtolower(time_diff($class['r__class_end_time'])).' ago</div>';
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
    $uadmission = $this->session->userdata('uadmission');

    //Only load the 3rd Level Step messages that are not yet complete by default, because everything else has already been communicated to the student
    $load_open = ( $level>=3 ); //&& !isset($us_data[$intent['c_id']])

    //Messages:
    echo '<h4 style="margin-top:20px;"><a href="javascript:void(0)" onclick="$(\'.messages_ap\').toggle();"><i class="pointer fa fa-caret-right messages_ap" style="display:'.( $load_open ? 'none' : 'inline-block' ).';" aria-hidden="true"></i><i class="pointer fa fa-caret-down messages_ap" style="display:'.( $load_open ? 'inline-block' : 'none' ).';" aria-hidden="true"></i> <i class="fa fa-commenting" aria-hidden="true"></i> '.$displayed_messages.' Message'.($displayed_messages==1?'':'s').'</a></h4>';
    echo '<div class="tips_content messages_ap" style="display:'.( $load_open ? 'block' : 'none' ).';">';
    foreach($intent['c__messages'] as $i){
        if($i['i_status']==1){
            echo '<div class="tip_bubble">';
            echo echo_i( array_merge( $i , array(
                ( isset($uadmission) && count($uadmission)>0 ? 'noshow' : 'show_new_window' ) => 1, //TO embed the video
                'e_b_id'=>$admission['b_id'],
                'e_recipient_u_id'=>$admission['u_id'],
            )) , $admission['u_fname'] );
            echo '</div>';
        }
    }
    echo '</div>';
}






if($level>=3){


    /* ******************************
     * Step Completion
     ****************************** */
    echo '<h4><i class="fa fa-check-square" aria-hidden="true"></i> Completion</h4>';

    if($class_has_ended){
        //Class if finished, no more submissions allowed!
        echo '<div class="alert alert-info" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> You cannot submit Steps because Class has ended.</div>';
    } else {
        echo '<div id="save_report" class="quill_content">';
        if(isset($us_data[$intent['c_id']])){

            echo_us($us_data[$intent['c_id']]);

        } else {

            if($intent['c_complete_url_required']=='t' && $intent['c_complete_notes_required']=='t'){
                $red_note = 'a URL & completion notes';
                $textarea_note = 'Include a URL & completion notes (and optional instructor feedback) to mark as complete';
            } elseif($intent['c_complete_url_required']=='t'){
                $red_note = 'a URL';
                $textarea_note = 'Include a URL (and optional instructor feedback) to mark as complete';
            } elseif($intent['c_complete_notes_required']=='t'){
                $red_note = 'completion notes';
                $textarea_note = 'Include completion notes (and optional instructor feedback) to mark as complete';
            } else {
                $red_note = null;
                $textarea_note = 'Include optional feedback for your instructor';
            }

            //What instructions do we need to give?
            if($red_note) {
                echo '<div style="color:#FF0000;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Step requires ' . $red_note . '</div>';
            }
            echo '<div>Estimated time to complete: '.echo_time($intent['c_time_estimate'],1).'</div>';
            echo '<div class="mark_done" id="initiate_done"><a href="javascript:start_report();" class="btn btn-black"><i class="fa fa-check-circle initial"></i>Mark as Complete</a></div>';


            //Submission button visible after first button was clicked:
            echo '<div class="mark_done" style="display:none; margin-top:10px;">';
            echo '<textarea id="us_notes" class="form-control maxout" placeholder="'.$textarea_note.'"></textarea>';
            echo '<a href="javascript:mark_done();" class="btn btn-black"><i class="fa fa-check-circle" aria-hidden="true"></i>Submit</a>';
            echo '</div>';


            //Show when this Task is due if not already passed:
            $due_timestamp = time_format($class['r_start_date'],3,7);
            if($due_timestamp>time()){
                ?>
                <script>
                    $( document ).ready(function() {
                        $("#ontime_dueby").countdowntimer({
                            startDate : "<?= date('Y/m/d H:i:s'); ?>",
                            dateAndTime : "<?= date('Y/m/d H:i:s' , $due_timestamp); ?>",
                            size : "lg",
                            regexpMatchFormat: "([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})",
                            regexpReplaceWith: "<b>$1</b><sup>Days</sup><b>$2</b><sup>H</sup><b>$3</b><sup>M</sup><b>$4</b><sup>S</sup>"
                        });
                    });
                </script>
                <div><i class="fa fa-calendar" aria-hidden="true"></i> Due in <span id="ontime_dueby"></span></div>
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
        echo '<h4><i class="fa fa-arrows" aria-hidden="true"></i> Navigation</h4>';
        echo '<div style="font-size:0.8em;">';
        if($previous_on){
            echo '<a href="/my/actionplan/'.$admission['b_id'].'/'.$previous_intent['c_id'].'" class="btn btn-black" style="margin:0 5px;"><i class="fa fa-arrow-left"></i> Previous</a>';
        }
        if($next_on){
            echo '<a href="/my/actionplan/'.$admission['b_id'].'/'.$next_intent['c_id'].'" class="btn btn-black" style="margin:0 5px;">Next <i class="fa fa-arrow-right"></i></a>';
        }
        echo '</div>';
    }

}







/* ******************************
 * Task/Step List
 ****************************** */

if($level<3){

    echo '<h4>';
        if($level==1){
            echo '<i class="fa fa-check-square-o" aria-hidden="true"></i> Tasks';
        } elseif($level==2){
            echo '<i class="fa fa-list-ul" aria-hidden="true"></i> Steps';
        }
        //Show aggregate hours:
        echo ' <span class="sub-title">'.echo_time($intent['c__estimated_hours'],1).'</span>';
    echo '</h4>';

    echo '<div id="list-outbound" class="list-group">';

    //This could be either a list of Tasks or Steps, we'll know using $level
    $previous_item_complete = true; //We start this as its true for the very first Step
    foreach($intent['c__child_intents'] as $this_intent){

        if($this_intent['c_status']<1){
            //Drafting items should be skipped:
            continue;
        }

        if($level==1){

            //Task List
            $child_step_count = 0;
            $this_item_us_status = 1;
            foreach($this_intent['c__child_intents'] as $step){
                if($step['c_status']<1){
                    //Does not count:
                    continue;
                }

                //Count this as a Step:
                $child_step_count++;

                //See the status:
                if(!isset($us_data[$step['c_id']])) {
                    $this_item_us_status = -2; //Incomplete
                    //We know we can't go lowe than this:
                    break;
                } elseif($us_data[$step['c_id']]['us_status']<$this_item_us_status){
                    //Go to the lower denominator
                    $this_item_us_status = $us_data[$step['c_id']]['us_status'];
                }
            }

        } elseif($level==2){

            //Step List
            $this_item_us_status = ( isset($us_data[$this_intent['c_id']]) ? $us_data[$this_intent['c_id']]['us_status'] : -2 );

        }

        //Now determine the lock status of this item...

        //Used in $unlocked_item logic in case instructor modifies Action Plan and Adds items before previously completed items:
        $this_item_complete = ( $this_item_us_status>=1 );

        //See Status:
        $unlocked_item = ($class_has_ended || $previous_item_complete || $this_item_complete);

        //Left content
        if($unlocked_item){

            //Show link to enter this item:
            $ui = '<a href="/my/actionplan/'.$admission['b_id'].'/'.$this_intent['c_id'].'" class="list-group-item">';
            $ui .= '<span class="pull-right"><span class="badge badge-primary" style="margin-top:-5px;"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span>';
            $ui .= status_bible('us',$this_item_us_status,1).' ';

        } else {

            //Step/Task is locked, do not show link:
            $ui = '<li class="list-group-item">';
            $ui .= '<i class="fa fa-lock initial" aria-hidden="true"></i> ';

        }

        //Title on the left:
        if($level==1){
            $ui .= '<span>Task '.$this_intent['cr_outbound_rank'].':</span> ';
        } elseif($level==2){
            $ui .= '<span>Step '.$this_intent['cr_outbound_rank'].':</span> ';
        }

        //Intent title:
        $ui .= $this_intent['c_objective'].' ';


        $ui .= '<span class="sub-stats">';

        //Enable total hours/Task reporting...
        if($level==1 && isset($this_intent['c__estimated_hours'])){
            $ui .= echo_time($this_intent['c__estimated_hours'],1);
        } elseif($level==2 && isset($this_intent['c_time_estimate'])){
            $ui .= echo_time($this_intent['c_time_estimate'],1);
        }

        if($level==1 && $unlocked_item && $child_step_count){
            //Show the number of sub-Steps:
            $ui .= '<span class="title-sub"><i class="fa fa-list-ul" aria-hidden="true"></i>'.$child_step_count.'</span>';
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
