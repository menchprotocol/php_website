<?php
//Fetch the sprint units from config:
$message_max = $this->config->item('message_max');
$website = $this->config->item('website');
$udata = $this->session->userdata('user');


//Fetch the most recent cached action plans:
$cache_action_plans = $this->Db_model->e_fetch(array(
    'e_type_id' => 70,
    'e_r_id' => $class['r_id'],
),1,array('ej'));

?>
<script>


function changeBroadcastCount(){
    var len = $('#r_broadcast').val().length;
    if (len > <?= $message_max ?>) {
    	$('#BroadcastChar').addClass('overload').text(len);
    } else {
        $('#BroadcastChar').removeClass('overload').text(len);
    }
}

$(document).ready(function() {

    if(window.location.hash) {
        focus_hash(window.location.hash);
    }

    //Update counters:
    changeBroadcastCount();

});


function toggle_r_status(r_id){

    //Show spinner:
    $('.save_r_results').html('<img src="/img/round_load.gif" class="loader" />').hide().fadeIn();

    var save_data = {
        r_id:$('#r_id').val(),
        b_id:$('#b_id').val(),
        r_status:$('#r_status').val(),
    };

    //Save the rest of the content:
    $.post("/api_v1/class_edit", save_data , function(data) {
        //Update UI to confirm with user:
        $('.save_r_results').html(data).hide().fadeIn();

        //Disapper in a while:
        setTimeout(function() {
            $('.save_r_results').fadeOut();
        }, 10000);
    });

}

function load_classmates(){

    //Show spinner:
    $('#classmates_html').hide().fadeIn().html('<img src="/img/round_load.gif" style="margin:0 0 80px 0;" class="loader" />');

    //Save the rest of the content:
    $.post("/api_v1/load_classmates", {

        //Object IDs:
        b_id:$('#b_id').val(),
        r_id:$('#r_id').val(),
        is_instructor:1, //To load more columns compared to what students see!

    } , function(data) {

        //Update UI to confirm with user:
        $('#classmates_html').html(data).hide().fadeIn();

        //Activate Tooltip:
        $('[data-toggle="tooltip"]').tooltip();

    });
}

function sync_action_plan(){
    //Show spinner:
    $('#action_plan_status').html('<img src="/img/round_load.gif" class="loader" />').hide().fadeIn();
    var b_id = $('#b_id').val();
    var r_id = $('#r_id').val();

    //Save the rest of the content:
    $.post("/api_v1/sync_action_plan", {
        r_id:r_id,
        b_id:b_id,
    } , function(data) {
        //Update UI to confirm with user:
        $('#action_plan_status').html(data).hide().fadeIn();
        //Assume all good, refresh:
        setTimeout(function() {
            $(location).attr('href', '/console/'+b_id+'/classes/'+r_id);
            window.location.hash = "#actionplan";
            location.reload();
        }, 1000);
    });
}
</script>


<input type="hidden" id="r_id" value="<?= $class['r_id'] ?>" />
<input type="hidden" id="b_id" value="<?= $class['r_b_id'] ?>" />


<?php
if($current_applicants<=0){
    echo '<div class="alert alert-info maxout" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> No students yet.</div>';
} else { ?>




<ul id="topnav" class="nav nav-pills nav-pills-primary">
    <li id="nav_students" class="active"><a href="#students"><i class="fa fa-users" aria-hidden="true"></i> Students</a></li>
    <li id="nav_broadcast"><a href="#broadcast"><i class="fa fa-commenting" aria-hidden="true"></i> Broadcast</a></li>
    <?php if(count($cache_action_plans)>0){ ?>
    <li id="nav_actionplan"><a href="#actionplan"><i class="fa fa-list-ol" aria-hidden="true"></i> Action Plan</a></li>
    <?php } ?>
</ul>



<div class="tab-content tab-space">

    <div class="tab-pane active" id="tabstudents">
        <?php

        itip(2826);

        //Show Leaderboard:
        echo_classmates($class['r_b_id'],$class['r_id'],1);

        ?>
    </div>

    <div class="tab-pane" id="tabbroadcast">

        <?php //itip(2826); ?>

        <div class="form-group label-floating is-empty">
            <textarea class="form-control text-edit border msg msgin" style="min-height:80px; max-width:420px; padding:3px;" onkeyup="changeBroadcastCount()" id="r_broadcast"></textarea>
            <div style="margin:0 0 0 0; font-size:0.8em;"><span id="BroadcastChar">0</span>/<?= $message_max ?></div>
        </div>
        <table width="100%"><tr><td class="save-td"><a href="javascript:send_();" class="btn btn-primary">Send</a></td><td><span class="save_r_results"></span></td></tr></table>

    </div>

    <div class="tab-pane" id="tabactionplan">
        <?php
        //Show helper tip:
        itip(3267);

        //Do we have a copy?
        if(count($cache_action_plans)>0){

            $b = unserialize($cache_action_plans[0]['ej_e_blob']);

            echo '<div class="title"><h4><i class="fa fa-list-ol" aria-hidden="true"></i> Action Plan as of '.time_format($cache_action_plans[0]['e_timestamp'],0).'</h4></div>';

            //Show Action Plan:
            echo '<div id="project-objective" class="list-group maxout">';
            echo echo_cr($b['b_id'],$b,1,0,false);
            echo '</div>';

            //Task Expand/Contract all if more than 2
            if(count($b['c__child_intents'])>0){
                echo '<div id="task_view">';
                echo '<i class="fa fa-plus-square expand_all" aria-hidden="true"></i> &nbsp;';
                echo '<i class="fa fa-minus-square close_all" aria-hidden="true"></i>';
                echo '</div>';
            }

            //Tasks List:
            echo '<div id="list-outbound" class="list-group">';
            foreach($b['c__child_intents'] as $key=>$sub_intent){
                echo echo_cr($b['b_id'],$sub_intent,2,$b['b_id'],0,false);
            }
            echo '</div>';


            //Target Audience:
            echo '<div class="title"><h4><i class="fa fa-address-book" aria-hidden="true"></i> Target Audience <span id="hb_426" class="help_button" intent-id="426"></span> <span id="b_target_audience_status" class="list_status">&nbsp;</span></h4></div>
                <div class="help_body maxout" id="content_426"></div>';
            echo ( strlen($b['b_target_audience'])>0 ? '<ol><li>'.join('</li><li>',json_decode($b['b_target_audience'])).'</li></ol>' : '<div class="alert alert-info maxout" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Not Set</div>' );


            //Prerequisites, which get some system appended ones:
            $b['b_prerequisites'] = prep_prerequisites($b);
            echo '<div class="title" style="margin-top:30px;"><h4><i class="fa fa-check-square-o" aria-hidden="true"></i> Prerequisites <span id="hb_610" class="help_button" intent-id="610"></span> <span id="b_prerequisites_status" class="list_status">&nbsp;</span></h4></div>
                <div class="help_body maxout" id="content_610"></div>';
            echo ( count($b['b_prerequisites'])>0 ? '<ol><li>'.join('</li><li>',$b['b_prerequisites']).'</li></ol>' : '<div class="alert alert-info maxout" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Not Set</div>' );


            //Skills You Will Gain
            echo '<div class="title" style="margin-top:30px;"><h4><i class="fa fa-diamond" aria-hidden="true"></i> Skills You Will Gain <span id="hb_2271" class="help_button" intent-id="2271"></span> <span id="b_transformations_status" class="list_status">&nbsp;</span></h4></div>
                <div class="help_body maxout" id="content_2271"></div>';
            echo ( strlen($b['b_transformations'])>0 ? '<ol><li>'.join('</li><li>',json_decode($b['b_transformations'])).'</li></ol>' : '<div class="alert alert-info maxout" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Not Set</div>' );


            //Completion Awards
            echo '<div class="title" style="margin-top:30px;"><h4><i class="fa fa-trophy" aria-hidden="true"></i> Completion Awards <span id="hb_623" class="help_button" intent-id="623"></span> <span id="b_completion_prizes_status" class="list_status">&nbsp;</span></h4></div>
                <div class="help_body maxout" id="content_623"></div>';
            echo ( strlen($b['b_completion_prizes'])>0 ? '<ol><li>'.join('</li><li>',json_decode($b['b_completion_prizes'])).'</li></ol>' : '<div class="alert alert-info maxout" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Not Set</div>' );


        } else {
            echo '<div class="alert alert-info maxout" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Action Plan not copied yet because this Class has not started. This would happen automatically when this Class starts.</div>';
        }

        if($class['r_status']==2){
            //Show button to update ONLY if class is running
            if($udata['u_status']>=2){
                ?>
                <div class="copy_ap"><a href="javascript:void(0);" onclick="$('.copy_ap').toggle();" class="btn btn-primary">Update Action Plan</a></div>
                <div class="copy_ap" style="display:none;">
                    <p><b><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> WARNING:</b> This Class is currently running, and updating your Action Plan may cause confusion for your students as they might need to complete Steps form previous Tasks they had already marked as complete. Update the Action Plan only if:</p>
                    <ul>
                        <li>You have made changes to Messages only (Not added new Steps or Tasks)</li>
                        <li>You have made changes to Future Tasks that have not been unlocked yet</li>
                    </ul>
                    <p><a href="javascript:void(0);" onclick="sync_action_plan()">I Understand, Continue With Update &raquo;</a></p>
                </div>
                <div id="action_plan_status"></div>
                <?php
            } else {
                echo '<div>Contact us if you like to update a Copy of Your Action Plan.</div>';
            }
        }
        ?>
    </div>

</div>

<?php } ?>