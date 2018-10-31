<?php

//Fetch inbound breadcrumb tree all the way to the top of subscription w_c_id
echo '<div class="list-group" style="margin-top: 10px;">';
foreach($k_ins as $k){
    echo echo_k($k, 1);
}
echo '</div>';


/*
//Next/Previous Buttons
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



//Show title
echo '<h3 class="student-h3">'.$c['c_outcome'].'</h3>';

if(count($k_ins)==0){

    $is_started = true; //Subscriptions are always started!

    //This must be top level subscription, show subscription data:
    echo '<div class="sub_title">';
        echo echo_status('w_status',$w['w_status']);
        echo ' &nbsp;&nbsp;<i class="fas fa-lightbulb-on"></i> '.$c['c__tree_all_count'];
        echo ' &nbsp;&nbsp;<i class="fas fa-clock"></i> '.echo_hour_range($c);
        //echo ' &nbsp;&nbsp;<i class="fas fa-calendar-check"></i> '.echo_time($w['w_timestamp']);
        //TODO Show coach name if w_inbound_u_id>0
        //TODO show subscription pace data such as start/end time, weekly rate & notification type
    echo '</div>';

} elseif(count($k_ins)==1) {


    if($this->Db_model->k_is_parent_done($w['w_id'], $k_ins[0]['cr_outbound_c_id'], $k_ins[0]['cr_inbound_c_id'] )){
        echo '/Parent Done';
    } else {
        echo '/Parent NOT';
    }

    $is_started = ( $k_ins[0]['k_status']>=1 );

    //Show completion progress for the single inbound intent:
    echo '<div class="sub_title">';

    echo echo_status('k_status', $k_ins[0]['k_status']);

    //echo ' &nbsp;&nbsp;<i class="fas fa-lightbulb-on"></i> ' . $c['c__tree_all_count'];
    //echo ' &nbsp;&nbsp;<i class="fas fa-clock"></i> ' . echo_hour_range($c);

    echo ' &nbsp;&nbsp;<i class="fas fa-clock"></i> ' . echo_hours($c['c_time_estimate']);

    if ($k_ins[0]['k_last_updated']) {
        echo ' &nbsp;&nbsp;<i class="fas fa-calendar-check"></i> ' . echo_time($k_ins[0]['k_last_updated']);
    }

    if (strlen($k_ins[0]['k_notes'])>0) {
        echo '<div style="margin:15px 0 0 3px;"><i class="fas fa-edit"></i> ' . echo_link(nl2br(htmlentities($k_ins[0]['k_notes']))) . '</div>';
    }

    echo '</div>';

}



//Show all messages:
$messages = $this->Db_model->i_fetch(array(
    'i_outbound_c_id' => $c['c_id'],
    'i_status' => 1, //On start messages only
));
if(count($messages)>0){
    $hide_messages_onload = ( count($k_ins)==0 || $k_ins[0]['k_status']<=0);
    echo '<div class="tips_content message_content" style="display: '.( $is_started ? 'none' : 'block' ).';">';
    foreach($messages as $i){
        if($i['i_status']==1){
            echo '<div class="tip_bubble">';
            echo echo_i( array_merge( $i , array(
                'e_outbound_u_id' => $w['u_id'],
            )) , $w['u_full_name'] );
            echo '</div>';
        }
    }
    echo '</div>';

    if($is_started){
        //Show button to show messages:
        echo '<a href="javascript:void(0);" onclick="$(\'.message_content\').toggle();" class="message_content btn btn-xs btn-black"><i class="fas fa-comment-dots"></i> See '.count($messages).' Message'.echo__s(count($messages)).'</a>';
    }
}




//Show completion options below messages:
if(count($k_ins)==1) {

    //Echo hidden completion box on page:
    if($c['c_require_url_to_complete'] && $c['c_require_notes_to_complete']){
        $red_note = 'Requires both a URL & completion notes to mark as complete';
        $textarea_note = 'Include a URL & completion notes (and optional feedback) to mark as complete';
    } elseif($c['c_require_url_to_complete']){
        $red_note = 'Requires a URL';
        $textarea_note = 'Include a URL (and optional feedback) to mark as complete';
    } elseif($c['c_require_notes_to_complete']){
        $red_note = 'Requires completion notes';
        $textarea_note = 'Include completion notes (and optional feedback) to mark as complete';
    } else {
        $red_note = null;
        $textarea_note = 'Include optional feedback';
    }

    //Submission button visible after first button was clicked:
    $is_incomplete = ($k_ins[0]['k_status']<=0);
    $show_textarea = ($red_note && $is_incomplete);
    if(!$show_textarea){
        //Show button to make text visible:
        echo '<a href="javascript:void(0);" onclick="$(\'.toggle_text\').toggle();" class="toggle_text btn btn-xs btn-black"><i class="fas fa-edit"></i> '.( $is_incomplete ? 'Add Written Answer' : 'Modify Answer' ).'</a>';
    }

    echo '<form method="POST" action="/my/update_k_save">';

        echo '<input type="hidden" name="k_id"  value="'.$k_ins[0]['k_id'].'" />';
        //echo '<input type="hidden" name="k_key" value="'.md5($k_ins[0]['k_id'].'k_key_SALT555').'" />'; //TODO Wire in for more security?!


        echo '<div class="toggle_text" style="'.( $show_textarea ? '' : 'display:none; ' ).'margin-top:10px;">';
            if($red_note) {
                echo '<div style="color:#2b2b2b; font-size:0.7em; margin:20px 0 0 0 !important; padding:0;"><i class="fas fa-exclamation-triangle"></i> ' . $red_note . '</div>';
            }
            echo '<textarea name="k_notes" class="form-control maxout" placeholder="'.$textarea_note.'" style="padding:5px !important; margin:0 !important;">'.$k_ins[0]['k_notes'].'</textarea>';
        echo '</div>';


        if($k_ins[0]['k_status']<=0){
            echo '<button type="submit" class="btn btn-primary"><i class="fas fa-check-square"></i> Mark as Complete</button>';
        } else {
            echo '<button type="submit" class="btn btn-primary '.( !$show_textarea ? 'toggle_text" style="display:none;' : '' ).'"><i class="fas fa-edit"></i> Update Answer</button>';
        }

    echo '</form>';
}



if(count($k_outs)>0){
    echo '<h5 style="margin-top: 10px;">Complete '.( $c['c_is_any'] ? 'Any' : 'All' ).':</h5>';
    echo '<div class="list-group">';
    foreach($k_outs as $k){
        echo echo_k($k, 0);
    }
    echo '</div>';
}

?>