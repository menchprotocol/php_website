<?php

//Do some calculations:
if(count($k_ins)==1) {
    //Echo hidden completion box on page:
    if ($c['c_require_url_to_complete'] && $c['c_require_notes_to_complete']) {
        $red_note = 'Requires both a URL & completion notes to mark as complete';
        $textarea_note = 'Include a URL & completion notes to mark as complete';
    } elseif ($c['c_require_url_to_complete']) {
        $red_note = 'Requires a URL';
        $textarea_note = 'Include a URL to mark as complete';
    } elseif ($c['c_require_notes_to_complete']) {
        $red_note = 'Requires completion notes';
        $textarea_note = 'Include completion notes to mark as complete';
    } else {
        $red_note = null;
        $textarea_note = 'Include optional feedback';
    }

    //Submission button visible after first button was clicked:
    $is_incomplete = ($k_ins[0]['k_status'] <= 0 || ($k_ins[0]['k_status'] == 1 && count($k_outs) == 0));
    $show_textarea = ($red_note && $is_incomplete);
}


//Do we have a next item?
$next_button = null;
if($w['w_status']==1){
    //Active subscription, attempt to find next item, which we should be able to find:
    $ks_next = $this->Db_model->k_next_fetch($w['w_id']);
    if(count($ks_next)>0){
        if($ks_next[0]['c_id']==$c['c_id']){
            //$next_button = '<span style="font-size: 0.7em; padding-left:5px; display:inline-block;"><i class="fas fa-shield-check"></i> This is the next-in-line intent</span>';
            $next_button = null;
        } else {
            $next_button = '<a href="/my/actionplan/'.$ks_next[0]['k_w_id'].'/'.$ks_next[0]['c_id'].'" class="btn '.( count($k_ins)==1 && !$show_textarea && !$is_incomplete ? 'btn-md btn-primary' : 'btn-xs btn-black' ).'" data-toggle="tooltip" data-placement="top" title="Next intent-in-line is to '.$ks_next[0]['c_outcome'].'">Next-in-line <i class="fas fa-angle-right"></i></a>';
        }
    }
}


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
        echo ' &nbsp;&nbsp;<i class="fas fa-calendar-check"></i> '.echo_time($w['w_timestamp']);
        //TODO show subscription pace data such as start/end time, weekly rate & notification type
    echo '</div>';

} elseif(count($k_ins)==1) {

    $is_started = ( $k_ins[0]['k_status']>=1 );

    //Show completion progress for the single inbound intent:
    echo '<div class="sub_title">';

    echo echo_status('k_status', $k_ins[0]['k_status']);
    echo ' &nbsp;&nbsp;<i class="fas fa-clock"></i> ' . echo_hours($c['c_time_estimate']);

    if ($k_ins[0]['k_last_updated']) {
        echo ' &nbsp;&nbsp;<i class="fas fa-calendar-check"></i> ' . echo_time($k_ins[0]['k_last_updated']);
    }

    if (strlen($k_ins[0]['k_notes'])>0) {
        echo '<div style="margin:15px 0 0 3px;"><i class="fas fa-edit"></i> ' . echo_link(nl2br(htmlentities($k_ins[0]['k_notes']))) . '</div>';
    }

    echo '</div>';

}

//Override this for now and always show messages
//TODO Consider updates to this later
//$is_started = false;

//Show all messages:
$messages = $this->Db_model->i_fetch(array(
    'i_outbound_c_id' => $c['c_id'],
    'i_status' => 1, //On start messages only
));
if(count($messages)>0){
    $hide_messages_onload = ( count($k_ins)==0 || $k_ins[0]['k_status']<=0);
    echo '<div class="tips_content message_content left-grey" style="display: '.( $is_started ? 'none' : 'block' ).';">';
    echo '<h5 class="badge badge-hy"><i class="fas fa-comment-dots"></i> '.count($messages).' Message'.echo__s(count($messages)).':</h5>';
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
        echo '<div class="left-grey"><a href="javascript:void(0);" onclick="$(\'.message_content\').toggle();" class="message_content btn btn-xs btn-black"><i class="fas fa-comment-dots"></i> See '.count($messages).' Message'.echo__s(count($messages)).'</a></div>';
    }
}



//Show completion options below messages:
if(count($k_ins)==1){

    if(!$show_textarea){
        //Show button to make text visible:
        echo '<div class="left-grey"><a href="javascript:void(0);" onclick="$(\'.toggle_text\').toggle();" class="toggle_text btn btn-xs btn-black"><i class="fas fa-edit"></i> '.( $is_incomplete ? 'Add Written Answer' : 'Modify Answer' ).'</a></div>';
    }

    echo '<div class="left-grey">';
    echo '<form method="POST" action="/my/update_k_save">';

        echo '<input type="hidden" name="k_id"  value="'.$k_ins[0]['k_id'].'" />';
        //echo '<input type="hidden" name="k_key" value="'.md5($k_ins[0]['k_id'].'k_key_SALT555').'" />'; //TODO Wire in for more security?!

        echo '<div class="toggle_text" style="'.( $show_textarea ? '' : 'display:none; ' ).'">';
            if($red_note) {
                echo '<div style="color:#2b2b2b; font-size:0.7em; margin:0 !important; padding:0;"><i class="fas fa-exclamation-triangle"></i> ' . $red_note . '</div>';
            }
            echo '<textarea name="k_notes" class="form-control maxout" placeholder="'.$textarea_note.'" style="padding:5px !important; margin:0 !important;">'.$k_ins[0]['k_notes'].'</textarea>';
        echo '</div>';


        if($k_ins[0]['k_status']==0 && count($k_outs)>0){
            echo '<button type="submit" '.( $k_ins[0]['c_is_any'] ? '' : ' name="k_next_redirect" value="'.$k_ins[0]['k_rank'].'"' ).' class="btn btn-primary">Got It, Continue <i class="fas fa-angle-right"></i></a>';
        } elseif($is_incomplete){
            echo '<button type="submit" name="k_next_redirect" value="1" class="btn btn-primary"><i class="fas fa-check-square"></i> Mark Complete & Go Next <i class="fas fa-angle-right"></i></button>';
            echo '<div>or <button type="submit" class="btn btn-xs btn-black"><i class="fas fa-check-square"></i> Mark Complete</button></div>';
        } elseif(!$show_textarea) {
            echo '<button type="submit" class="btn btn-primary toggle_text" style="display:none;"><i class="fas fa-edit"></i> Update Answer</button>';
        } else {
            echo '<button type="submit" class="btn btn-primary"><i class="fas fa-edit"></i> Update Answer</button>';
        }

    echo '</form>';
    echo '</div>';
}


if(!isset($k_ins[0]) || !($k_ins[0]['k_status']==0)){
    if(count($k_outs)>0){
        echo '<div class="left-grey">';
        echo '<h5 class="badge badge-hy">'.( $c['c_is_any'] ? '<i class="fas fa-code-merge"></i> Choose One Path to Continue' : '<i class="fas fa-sitemap"></i> Complete All Following' ).':</h5>';
        echo '<div class="list-group">';
        foreach($k_outs as $k){
            echo echo_k($k, 0, ( $c['c_is_any'] && $k['k_status']==0 ? $c['c_id'] : 0 ));
        }
        echo '</div>';
        echo '</div>';
    }
}


//Echo next button if available:
echo $next_button;


?>