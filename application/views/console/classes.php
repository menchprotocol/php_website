<?php
$message_max = $this->config->item('message_max');
$class_settings = $this->config->item('class_settings');
?><script>

$(document).ready(function() {
    if(window.location.hash) {
        focus_hash(window.location.hash);
    }
});

function load_class(r_id){

    //Show Loader:
    $('#class_content').html('<img src="/img/round_load.gif" style="margin-top:50px;" class="loader" />');

    //Save the rest of the content:
    $.post("/my/display_classmates", { r_id:r_id } , function(data) {

        //Update UI to confirm with user:
        $('#class_content').html(data).hide().fadeIn();

        //Activate Tooltip:
        $('[data-toggle="tooltip"]').tooltip();

    });
}

function toggle_support(r_id){

    //Show spinner:
    var r_status = parseInt($('#support_toggle_'+r_id).attr('current-status'));
    if(r_status==1){
        var rs_new_status = 2;
    } else if(r_status==2){
        var rs_new_status = 1;
    } else {
        alert('ERROR: Unknown status');
        return false;
    }

    //Show Loader:
    $('#support_toggle_'+r_id).html('<img src="/img/round_load.gif" style="width:18px !important; height:16px !important;" class="loader" />');

    //Save the rest of the content:
    $.post("/api_v1/r_update_status", {

        r_id:r_id,
        rs_new_status:rs_new_status,

    } , function(data) {

        if(data.status){
            //Restore Loader:
            $('#support_toggle_'+r_id).html(data.message);

            //Update UI to confirm with user:
            $('#support_toggle_'+r_id).attr('current-status',data.rs_new_status);

            if(data.rs_new_status==2){
                $('#support_toggle_'+r_id).removeClass('grey');
            } else if(data.rs_new_status==1) {
                $('#support_toggle_'+r_id).addClass('grey');
            }

        } else {
            //Restore Loader:
            $('#support_toggle_'+r_id).html('<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>');

            //Show Error:
            alert('ERROR: ' + data.message);
        }

    });
}


function changeBroadcastCount(){
    var len = $('#r_broadcast').val().length;
    if (len > <?= $message_max ?>) {
        $('#BroadcastChar').addClass('overload').text(len);
    } else {
        $('#BroadcastChar').removeClass('overload').text(len);
    }
}

function r_sync_c(b_id,r_id){

    //Show spinner:
    $('#action_plan_status').html('<img src="/img/round_load.gif" class="loader" />').hide().fadeIn();

    //Save the rest of the content:
    $.post("/api_v1/r_sync_c", {
        r_id:r_id,
        b_id:b_id,
    } , function(data) {
        //Update UI to confirm with user:
        $('#action_plan_status').html(data).hide().fadeIn();

        //Assume all good, refresh:
        setTimeout(function() {
            load_class(r_id);
        }, 1000);
    });
}

</script>


<div class="help_body maxout below_h" id="content_2274"></div>

<?php
if(!($b['b__admins'][0]['u_id']==$udata['u_id'])){
    echo '<div class="alert alert-info maxout" role="alert" style="margin:-15px 0 15px 10px;"><i class="fa fa-lock" aria-hidden="true"></i> Support settings locked because you are not the lead instructor of this Bootcamp.</div>';
}
?>

<table class="table" style="margin-top:-10px;">
    <tr>
        <td class="class_nav" style="vertical-align:top;">

            <ul id="topnav" class="nav nav-pills nav-pills-primary" style="margin-bottom:12px;">
                <li id="nav_active" class="active"><a href="#active"><i class="fa fa-play-circle initial"></i> Active</a></li>
                <li id="nav_complete"><a href="#complete"><i class="fa fa-check-circle initial"></i> Complete</a></li>
            </ul>

            <div class="tab-content tab-space">

                <div class="tab-pane active" id="tabactive">
                    <?php
                    $active_classes = $this->Db_model->r_fetch(array(
                        'r.r_b_id'	        => $b['b_id'],
                        'r.r_status >='	    => 1, //Open Admission or higher
                    ), $b, 'ASC');

                    if(count($active_classes)>0){

                        echo '<div class="list-group maxout">';
                        foreach($active_classes as $key=>$class){
                            echo_r($b,$class,($key>=$class_settings['instructor_show_default']?'active_extra hidden':''));
                        }
                        if(count($active_classes)>$class_settings['instructor_show_default']){
                            echo '<a href="javascript:void(0);" onclick="toggle_hidden_class(\'active_extra\')" data-toggle="tooltip" data-placement="top" title="Classes are automatically created for the next '.$class_settings['create_weeks_ahead'].' Weeks" class="list-group-item active_extra" style="text-decoration:none;"><i class="fa fa-plus-square-o" style="margin: 0 6px 0 4px; font-size: 19px;" aria-hidden="true"></i> See All Classes</a>';
                        }
                        echo '</div>';

                    } else {
                        //Show none
                        echo '<div class="alert alert-info"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> None</div>';
                    }
                    ?>
                </div>
                <div class="tab-pane" id="tabcomplete">
                    <?php
                    $complete_classes = $this->Db_model->r_fetch(array(
                        'r.r_b_id'	        => $b['b_id'],
                        'r.r_status'	    => 3, //Completed
                    ), $b, 'DESC');

                    if(count($complete_classes)>0){
                        echo '<div class="list-group maxout">';
                        foreach($complete_classes as $key=>$class){
                            echo_r($b,$class,($key>=$class_settings['instructor_show_default']?'past_extra hidden':''));
                        }
                        if(count($complete_classes)>$class_settings['instructor_show_default']){
                            echo '<a href="javascript:void(0);" onclick="toggle_hidden_class(\'past_extra\')" class="list-group-item past_extra" style="text-decoration:none;"><i class="fa fa-plus-square-o" style="margin: 0 6px 0 4px; font-size: 19px;" aria-hidden="true"></i> See all '.count($complete_classes).'</a>';
                        }
                        echo '</div>';
                    } else {
                        echo '<div class="alert alert-info"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> None</div>';
                    }
                    ?>
                </div>
            </div>

        </td>
        <td style="padding-top:7px; vertical-align:top;">
            <div id="class_content"></div>
        </td>
    </tr>
</table>

