<?php

//Define some initial variables:
$application_status_salt = $this->config->item('application_status_salt');
$udata = $this->session->userdata('user');
$page_load_time = time();
?>
<script>
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
echo '<div class="list-group" style="margin-top: 10px;">';
foreach($k_ins as $k){
    echo echo_k($k, 1);
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



//Show title
echo '<h3 class="student-h3">'.$c['c_outcome'].'</h3>';


if(count($k_ins)==0){

    //This must be top level subscription, show subscription data:
    echo '<div class="sub_title">';
        echo echo_status('w_status',$subscriptions[0]['w_status']);
        echo ' &nbsp;&nbsp;<i class="fas fa-lightbulb-on"></i> '.$subscriptions[0]['c__tree_all_count'];
        echo ' &nbsp;&nbsp;<i class="fas fa-clock"></i> '.echo_hour_range($subscriptions[0]);
        //echo ' &nbsp;&nbsp;<i class="fas fa-calendar-check"></i> '.echo_time($subscriptions[0]['w_timestamp']);
        //TODO Show coach name if w_inbound_u_id>0
        //TODO show subscription pace data such as start/end time, weekly rate & notification type
    echo '</div>';

} elseif(count($k_ins)==1){

    /*
    //TODO Automated completion?
    if($k_ins[0]['k_status']==0 && !$k_ins[0]['c_require_url_to_complete'] && !$k_ins[0]['c_require_notes_to_complete']){
        //It's their first time reading this!
        $this->Db_model->k_update( $k_ins[0]['k_id'] , array(
            'k_status' => ( count($k_outs)==0 ? 2 : 1 ),
        ));
        //TODO implement recursive logic:
    }
    */

    //Show completion progress for the single inbound intent:
    echo '<div class="sub_title">';

        echo echo_status('k_status',$k_ins[0]['k_status']);

        echo ' &nbsp;&nbsp;<i class="fas fa-lightbulb-on"></i> '.$k_ins[0]['c__tree_all_count'];
        echo ' &nbsp;&nbsp;<i class="fas fa-clock"></i> '.echo_hour_range($k_ins[0]);

        if($k_ins[0]['k_last_updated']){
            echo ' &nbsp;&nbsp;<i class="fas fa-calendar-check"></i> '.echo_time($k_ins[0]['k_last_updated']);
        }

        if(strlen($k_ins[0]['k_notes'])>0){
            echo '<div><i class="fas fa-comment-dots initial"></i> '.echo_link(nl2br(htmlentities($k_ins[0]['k_notes']))).'</div>';
        }


    echo '</div>';


    echo '<div>';

        //Show all messages:
        $messages = $this->Db_model->i_fetch(array(
            'i_outbound_c_id' => $c['c_id'],
            'i_status' => 1, //On start messages only
        ));
        if(count($messages)>0){
            $hide_messages_onload = ( count($k_ins)==0 || $k_ins[0]['k_status']<=0);
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



    //Show button in its own row:
        echo '<div class="update_k_save" id="initiate_done"><a href="javascript:update_k_start();" class="btn btn-tight btn-black">'.($k_ins[0]['k_status']<=0 ? '<i class="fas fa-check-square"></i> Mark as Complete' : '<i class="fas fa-edit"></i> Modify' ).'</a></div>';


        //Echo hidden completion box on page:
        if($c['c_require_url_to_complete'] && $c['c_require_notes_to_complete']){
            $red_note = 'Requires a URL & completion notes';
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
        echo '<form method="POST" action="/my/update_k_save">';
            echo '<input type="hidden" name="page_load_time" value="'.$page_load_time.'" />';
            echo '<input type="hidden" name="k_id" value="'.$k_ins[0]['k_id'].'" />';
            echo '<div class="update_k_save" style="display:none; margin-top:10px;">';
                if($red_note) {
                    echo '<div style="color:#FF0000;"><i class="fas fa-exclamation-triangle"></i> ' . $red_note . '</div>';
                }
                echo '<textarea name="us_notes" class="form-control maxout" placeholder="'.$textarea_note.'" style="padding:5px !important;">'.$k_ins[0]['k_notes'].'</textarea>';
                echo '<button type="submit" class="btn btn-tight btn-black"><i class="fas fa-check-circle"></i>Save</button>';
            echo '</div>';

        echo '</form>';

    echo '</div>';
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