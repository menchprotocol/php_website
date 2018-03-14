<?php
//Fetch the sprint units from config:
$message_max = $this->config->item('message_max');
$website = $this->config->item('website');
$udata = $this->session->userdata('user');

//Determine lock down status based on User & Class situation:
$admin_can_edit = ( $udata['u_status']>=3 && !($class['r_status']==3) );
$disabled = ( !$admin_can_edit && ($current_applicants>0 || $class['r_status']>=2) ? 'disabled' : null );
$soft_disabled = ( !$admin_can_edit && $class['r_status']>=2 ? 'disabled' : null );

//Fetch the most recent cached action plans:
$cache_action_plans = $this->Db_model->e_fetch(array(
    'e_type_id' => 70,
    'e_r_id' => $class['r_id'],
),1,array('ej'));

?>
<script>

function ucwords(str) {
   return (str + '').replace(/^(.)|\s+(.)/g, function ($1) {
      return $1.toUpperCase()
   });
}
function js_mktime(hour,minute,month,day,year) {
    return new Date(year, month-1, day, hour, minutes, 0).getTime() / 1000;
}



function changeContactMethod(){
    var len = $('#r_office_hour_instructions').val().length;
    if (len > <?= $message_max ?>) {
    	$('#ContactMethodChar').addClass('overload').text(len);
    } else {
        $('#ContactMethodChar').removeClass('overload').text(len);
    }
}

$(document).ready(function() {

    //Class Toggle:
    $("#class_focus").on("change", function(){
        load_classmates($(this).val());
    });

    if(window.location.hash) {
        focus_hash(window.location.hash);
    }

    //Update counters:
    changeContactMethod();

});


function save_r(){
    //Show spinner:
    $('.save_r_results').html('<img src="/img/round_load.gif" class="loader" />').hide().fadeIn();

    //Save Scheduling iFrame content:
    if(parseInt($('#r_live_office_hours_val').val())){
        document.getElementById('weekschedule').contentWindow.save_hours();
    }

    var save_data = {
        r_id:$('#r_id').val(),
        b_id:$('#b_id').val(),
        r_start_date:$('#r_start_date').val(),

        //Communication:
        r_live_office_hours_check:$('#r_live_office_hours_val').val(),
        r_office_hour_instructions:$('#r_office_hour_instructions').val(),
        r_start_time_mins:$('#r_start_time_mins').val(),

        //Class:
        r_status:$('#r_status').val(),
        r_min_students:$('#r_min_students').val(),
        r_max_students:$('#r_max_students').val(),
    };

    //Now merge into timeline dates:
    //for (var key in timeline){
    //	save_data[key] = timeline[key];
    //}

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
<input type="hidden" id="c__milestone_units" value="<?= $b['c__milestone_units'] ?>" />
<input type="hidden" id="c__estimated_hours" value="<?= $b['c__estimated_hours'] ?>" />


<ul id="topnav" class="nav nav-pills nav-pills-primary">
    <li id="nav_students" <?= ($current_applicants>0 ? 'class="active"' : '') ?>><a href="#students"><i class="fa fa-users" aria-hidden="true"></i> Students</a></li>
    <li id="nav_settings" <?= ($current_applicants>0 ? '' : 'class="active"') ?>><a href="#settings"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a></li>
    <?php if(count($cache_action_plans)>0 || $udata['u_status']>=3){ ?>
        <li id="nav_actionplan"><a href="#actionplan"><i class="fa fa-list-ol" aria-hidden="true"></i> Action Plan</a></li>
    <?php } ?>
</ul>


<div class="tab-content tab-space">


    <div class="tab-pane <?= ($current_applicants>0 ? 'active' : '') ?>" id="tabstudents">
        <?php

        itip(2826);

        if($current_applicants>0){

            //Show Leaderboard:
            echo_classmates($class['r_b_id'],$class['r_id'],1);

            //TODO Show Broadcast Feature...

        } else {
            echo '<div class="alert alert-info maxout" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> No students yet.</div>';
        }

        ?>
    </div>


    <div class="tab-pane <?= ($current_applicants>0 ? '' : 'active') ?>" id="tabsettings">


        <div class="title" style="margin-top:30px;"><h4><i class="fa fa-circle" aria-hidden="true"></i> Class Status <span id="hb_624" class="help_button" intent-id="624"></span></h4></div>
        <div class="help_body maxout" id="content_624"></div>
        <?= echo_status_dropdown('r','r_status', $class['r_status'], class_status_change($class['r_status'],$current_applicants)); ?>



        <div style="display:block; margin-top:20px;">
            <div class="title"><h4><i class="fa fa-thermometer-empty" aria-hidden="true"></i> Minimum Students <span id="hb_612" class="help_button" intent-id="612"></span></h4></div>
            <div class="help_body maxout" id="content_612"></div>
            <div class="input-group">
                <input type="number" min="0" step="1" style="width:100px; margin-bottom:-5px;" <?= $soft_disabled ?> id="r_min_students" value="<?= (isset($class['r_min_students'])?$class['r_min_students']:null) ?>" class="form-control border <?= $soft_disabled ?>" />
            </div>
            <br />
        </div>


        <div class="title"><h4><i class="fa fa-thermometer-full" aria-hidden="true"></i> Maximum Students <span id="hb_613" class="help_button" intent-id="613"></span></h4></div>
        <div class="help_body maxout" id="content_613"></div>
        <div class="input-group">
            <input type="number" min="0" step="1" style="width:100px; margin-bottom:-5px;" <?= $soft_disabled ?> id="r_max_students" value="<?= ( isset($class['r_max_students']) ? $class['r_max_students'] : null ) ?>" class="form-control border <?= $soft_disabled ?>" />
        </div>


        <div class="title" style="margin-top:30px;"><h4><i class="fa fa-podcast" aria-hidden="true"></i> Group Calls PST Schedule <span id="hb_618" class="help_button" intent-id="618"></span></h4></div>
        <div class="help_body maxout" id="content_618"></div>
        <iframe id="weekschedule" src="/console/<?= $b['b_id'] ?>/classes/<?= $class['r_id'] ?>/scheduler?r_start_date=<?= $class['r_start_date'] ?>&disabled=<?= $disabled ?>" scrolling="no" class="scheduler-iframe"></iframe>



        <div class="title"><h4><i class="fa fa-commenting" aria-hidden="true"></i> Group Call Instructions <span id="hb_617" class="help_button" intent-id="617"></span></h4></div>
        <div class="help_body maxout" id="content_617"></div>
        <div class="form-group label-floating is-empty">
            <textarea class="form-control text-edit border msg msgin" style="min-height:50px; padding:3px;" onkeyup="changeContactMethod()" placeholder="Contact using our Skype username: grumomedia" id="r_office_hour_instructions"><?= $class['r_office_hour_instructions'] ?></textarea>
            <div style="margin:0 0 0 0; font-size:0.8em;"><span id="ContactMethodChar">0</span>/<?= $message_max ?></div>
        </div>



        <br />
        <table width="100%"><tr><td class="save-td"><a href="javascript:save_r();" class="btn btn-primary">Save</a></td><td><span class="save_r_results"></span></td></tr></table>

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
