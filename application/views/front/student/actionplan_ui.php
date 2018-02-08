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
		u_id:$('#u_id').val(),
		b_id:$('#b_id').val(),
		r_id:$('#r_id').val(),
        c_id:$('#c_id').val(),
        next_c_id: <?= ( isset($next_intent['c_id']) ? intval($next_intent['c_id']) : 0 ) ?>,
        next_level: <?= ( isset($next_level) ? intval($next_level) : 0 ) ?>,
        require_notes:<?= ( $intent['c_complete_notes_required']=='t' ? 1 : 0 ) ?>,
        require_url:<?= ( $intent['c_complete_url_required']=='t' ? 1 : 0 ) ?>,

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

<input type="hidden" id="u_id" value="<?= $admission['u_id'] ?>" />
<input type="hidden" id="b_id" value="<?= $admission['b_id'] ?>" />
<input type="hidden" id="r_id" value="<?= $admission['r_id'] ?>" />
<input type="hidden" id="c_id" value="<?= $intent['c_id'] ?>" />

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
if($class['r__class_start_time']>time()){
    //Class has not yet started:
    ?>
    <script>
        $( document ).ready(function() {
            $("#bootcamp_start").countdowntimer({
                startDate : "<?= date('Y/m/d H:i:s'); ?>",
                dateAndTime : "<?= date('Y/m/d H:i:s' , $class['r__class_start_time']); ?>",
                size : "lg",
                regexpMatchFormat: "([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})",
                regexpReplaceWith: "<b>$1</b><sup>Days</sup><b>$2</b><sup>H</sup><b>$3</b><sup>M</sup><b>$4</b><sup>S</sup>"
            });
        });
    </script>
    <div class="alert alert-info" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Class starts in <span id="bootcamp_start"></span></div>
    <?php
} elseif($class['r__class_end_time']<time()){
    //Class has ended
    echo '<div class="alert alert-info" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Class ended '.time_diff($class['r__class_end_time']).'</div>';
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

    //Only load the 3rd Level Task messages that are not yet complete by default, because everything else has already been communicated to the student
    $load_open = ( $level>=2 ); //&& !isset($us_data[$intent['c_id']])

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
     * Task Completion
     ****************************** */
    echo '<h4><i class="fa fa-check-square" aria-hidden="true"></i> Completion</h4>';


    echo '<div id="save_report" class="quill_content">';
    if(isset($us_data[$intent['c_id']])){

        echo_us($us_data[$intent['c_id']]);

    } else {

        if($intent['c_complete_url_required']=='t' && $intent['c_complete_notes_required']=='t'){
            $red_note = 'a URL & Completion Notes';
            $textarea_note = 'Include a URL & completion notes (and optional instructor feedback) to mark as complete';
        } elseif($intent['c_complete_url_required']=='t'){
            $red_note = 'a URL';
            $textarea_note = 'Include a URL (and optional instructor feedback) to mark as complete';
        } elseif($intent['c_complete_notes_required']=='t'){
            $red_note = 'Completion Notes';
            $textarea_note = 'Include completion notes (and optional instructor feedback) to mark as complete';
        } else {
            $red_note = null;
            $textarea_note = 'Include optional feedback for your instructor';
        }

        //What instructions do we need to give?
        if($red_note) {
            echo '<div style="color:#FF0000;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Completing this Task requires ' . $red_note . '</div>';
        }
        echo '<div>Estimated time to complete: '.echo_time($intent['c_time_estimate'],1).'</div>';
        echo '<div class="mark_done" id="initiate_done"><a href="javascript:start_report();" class="btn btn-black"><i class="fa fa-check-circle initial"></i>Mark as Complete</a></div>';


        //Submission button visible after first button was clicked:
        echo '<div class="mark_done" style="display:none; margin-top:10px;">';
            echo '<textarea id="us_notes" class="form-control maxout" placeholder="'.$textarea_note.'"></textarea>';
            echo '<a href="javascript:mark_done();" class="btn btn-black"><i class="fa fa-check-circle" aria-hidden="true"></i>Submit</a>';
        echo '</div>';


        //Show when this Milestone is due if not already passed:
        if($sprint_index==$class['r__current_milestone'] && $class['r__current_milestone']>0 && isset($class['r__milestones_due'][$class['r__current_milestone']])){
            ?>
            <script>
                $( document ).ready(function() {
                    $("#ontime_dueby").countdowntimer({
                        startDate : "<?= date('Y/m/d H:i:s'); ?>",
                        dateAndTime : "<?= date('Y/m/d H:i:s' , $class['r__milestones_due'][$class['r__current_milestone']]); ?>",
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




    /* ******************************
     * Task Next/Previous Buttons
     ****************************** */
    $previous_on = (isset($previous_intent['c_id']));
    $next_on = (isset($next_intent['c_id']) && isset($us_data[$intent['c_id']]) && $next_level>1);
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
 * Milestone/Task List
 ****************************** */

if($level<3){

    echo '<h4>';
        if($level==1){
            echo '<i class="fa fa-flag" aria-hidden="true"></i> Milestones';
        } elseif($level==2){
            echo '<i class="fa fa-list-ul" aria-hidden="true"></i> Tasks';
        }
        //Show aggregate hours:
        echo ' <span class="sub-title">'.echo_time($intent['c__estimated_hours'],1).'</span>';
    echo '</h4>';

    echo '<div id="list-outbound" class="list-group">';

    //This could be either a list of Milestones or Tasks, we'll know using $level
    foreach($intent['c__child_intents'] as $key=>$sub_intent){

        if($sub_intent['c_status']<1){
            continue;
        }

        //Find the next and previous items:
        $previous_item = null;
        $next_item = null;
        $previous_key = $key;
        $next_key = $key;

        while(!$previous_item){
            $previous_key--;
            if(!isset($intent['c__child_intents'][$previous_key])){
                break;
            } elseif($intent['c__child_intents'][$previous_key]['c_status']>=1){
                $previous_item = $intent['c__child_intents'][$previous_key];
                break;
            }
        }
        while(!$next_item){
            $next_key++;
            if(!isset($intent['c__child_intents'][$next_key])){
                break;
            } elseif($intent['c__child_intents'][$next_key]['c_status']>=1){
                $next_item = $intent['c__child_intents'][$next_key];
                break;
            }
        }


        //Determine some variables for this second Milestone onwards:
        $unlocked_action_plan = false; //Everything is locked by default, unless we see that they have done the previous steps

        if($level==1){

            //IF this is the second milestone or more, make sure the previous milestone is done before unlocking this
            $aggregate_status = 1; //We assume it's all done, unless proven otherwise:
            if(!is_null($previous_item) && isset($previous_item['c__child_intents'])){
                foreach($previous_item['c__child_intents'] as $task){
                    if($task['c_complete_is_bonus_task']=='t' || $task['c_status']<1){
                        continue;
                    }
                    if(!isset($us_data[$task['c_id']])){
                        //No submission for this, definitely not done!
                        $aggregate_status = -2; //A special meaning here, which is not found
                        break;
                    } elseif($us_data[$task['c_id']]['us_status']<$aggregate_status){
                        $aggregate_status = $us_data[$task['c_id']]['us_status'];
                    }
                }
            }

            //Determine if this is locked or not
            $unlocked_action_plan = ( $sub_intent['cr_outbound_rank']<=$class['r__current_milestone'] && $aggregate_status>0 );

        } elseif($level==2){

            //TODO Consider Bonus tasks here with some sort of a loop: $previous_item['c_complete_is_bonus_task']=='t'
            $task_is_done = (isset($us_data[$sub_intent['c_id']]) && $us_data[$sub_intent['c_id']]['us_status']>=1);
            $unlocked_action_plan = ( !isset($previous_item['c_id']) || isset($us_data[$previous_item['c_id']]) || $task_is_done );

        }




        //Left content
        if($unlocked_action_plan){

            $ui = '<a href="/my/actionplan/'.$admission['b_id'].'/'.$sub_intent['c_id'].'" class="list-group-item">';
            $ui .= '<span class="pull-right"><span class="badge badge-primary" style="margin-top:-5px;"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span>';

            if($level==1){

                //We need to check if all child tasks are marked as complete:
                $aggregate_status = 1; //We assume it's all done, unless proven otherwise:
                foreach($sub_intent['c__child_intents'] as $task){
                    if($task['c_status']<1 || $task['c_complete_is_bonus_task']=='t'){
                        //Skip Drafting & Bonus Tasks
                        continue;
                    }
                    if(!isset($us_data[$task['c_id']])){
                        //No submission for this, definitely not done!
                        $aggregate_status = -2; //A special meaning here, which is not found
                        break;
                    } elseif($us_data[$task['c_id']]['us_status']<$aggregate_status){
                        $aggregate_status = $us_data[$task['c_id']]['us_status'];
                    }
                }

                if($aggregate_status==-2){
                    $ui .= '<i class="fa fa-square-o initial" aria-hidden="true"></i> ';
                } else {
                    $ui .= status_bible('us',$aggregate_status,1).' ';
                }

            } elseif($level==2){
                //This is a task, it needs to have a direct submission:
                if(isset($us_data[$sub_intent['c_id']])){
                    $ui .= status_bible('us',$us_data[$sub_intent['c_id']]['us_status'],1).' ';
                } else {
                    $ui .= '<i class="fa fa-square-o initial" aria-hidden="true"></i> ';
                }
            }

        } else {

            $ui = '<li class="list-group-item">';
            $ui .= '<i class="fa fa-lock initial" aria-hidden="true"></i> ';

        }

        //Left side starter:
        if($level==1){
            //Show counter:
            $ui .= '<span title="Due '.date("Y-m-d H:i:s PST",$class['r__milestones_due'][$sub_intent['cr_outbound_rank']]).'">'.ucwords($admission['b_sprint_unit']).' '.$sub_intent['cr_outbound_rank'].($sub_intent['c_duration_multiplier']>1 ? '-'.($sub_intent['cr_outbound_rank']+$sub_intent['c_duration_multiplier']-1) :'').':</span> ';
        } elseif($level==2){
            //Show counter:
            $ui .= '<span>Task '.$sub_intent['cr_outbound_rank'].':</span> ';
        }

        //Intent title:
        $ui .= $sub_intent['c_objective'].' ';


        $ui .= '<span class="sub-stats">';

        //Enable total hours/milestone reporting...
        if($level==1 && isset($sub_intent['c__estimated_hours'])){
            $ui .= echo_time($sub_intent['c__estimated_hours'],1);
        } elseif($level==2 && isset($sub_intent['c_time_estimate'])){
            $ui .= echo_time($sub_intent['c_time_estimate'],1);
        }

        if($unlocked_action_plan && $level==1 && isset($sub_intent['c__child_intents']) && count($sub_intent['c__child_intents'])>0){
            //This sprint has Assignments, count the active ones:
            $active_assinments = 0;
            foreach($sub_intent['c__child_intents'] as $task){
                if($task['c_status']>=1){
                    $active_assinments++;
                }
            }
            if($active_assinments>0){
                $ui .= '<span class="title-sub"><i class="fa fa-list-ul" aria-hidden="true"></i>'.$active_assinments.'</span>';
            }
        }

        $ui .= '</span>';


        //The Current focus sign for the focused Task/Milestone:
        if($level==1 && ($class['r__current_milestone']==$sub_intent['cr_outbound_rank'])){
            $ui .= ' <span class="badge badge-current"><i class="fa fa-hand-o-left" aria-hidden="true"></i> CLASS IS HERE</span>';
        } elseif($level==2 && $sub_intent['c_complete_is_bonus_task']=='t'){
            $ui .= ' <span class="badge badge-current"><i class="fa fa-gift" aria-hidden="true"></i> BONUS TASK</span>';
        }

        $ui .= ( $unlocked_action_plan ? '</a>' : '</li>');

        echo $ui;
    }
    echo '</div>';
}
?>