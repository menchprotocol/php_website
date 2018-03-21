<script>

function toggle_support(r_id){

    //Show spinner:
    var r_status = parseInt($('#support_toggle_'+r_id).attr('current-status'));
    if(r_status==0){
        var r_new_status = 1;
    } else if(r_status==1){
        var r_new_status = 0;
    } else {
        return false;
    }

    //Show Loader:
    $('#support_toggle_'+r_id).html('<img src="/img/round_load.gif" style="width:16px !important; height:16px !important;" class="loader" />');

    //Save the rest of the content:
    $.post("/api_v1/class_update_status", {
        r_id:r_id,
        r_new_status:r_new_status,
    } , function(data) {

        //Restore Loader:
        $('#support_toggle_'+r_id).html('<i class="fa fa-life-ring" aria-hidden="true"></i>');

        if(data.status){
            //Update UI to confirm with user:
            $('#support_toggle_'+r_id).attr('current-status',r_new_status);
            if(r_new_status){
                $('#support_toggle_'+r_id).removeClass('grey');
            } else {
                $('#support_toggle_'+r_id).addClass('grey');
            }
        } else {
            //Show Error:
            alert('ERROR: ' + data.message);
        }

    });
}

$(document).ready(function() {
    if(window.location.hash) {
        focus_hash(window.location.hash);
    }
});

</script>


<div class="help_body maxout below_h" id="content_2274"></div>


<input type="hidden" id="focus_r_id" value="0" />

<table class="table">
    <tr>
        <td class="class_nav">

            <ul id="topnav" class="nav nav-pills nav-pills-primary" style="margin-bottom:12px;">
                <li id="nav_active" class="active"><a href="#active"><i class="fa fa-play-circle initial"></i> Active</a></li>
                <li id="nav_complete"><a href="#complete"><i class="fa fa-check-circle initial"></i> Complete</a></li>
            </ul>

            <div class="tab-content tab-space">

                <div class="tab-pane active" id="tabactive">
                    <?php

                    $class_settings = $this->config->item('class_settings');

                    $active_classes = $this->Db_model->r_fetch(array(
                        'r.r_b_id'	        => $b['b_id'],
                        'r.r_status >='	    => 0, //No Support
                        'r.r_status <='	    => 2, //Running
                    ), $b, 'ASC');

                    if(count($active_classes)>0){

                        echo '<div class="list-group maxout">';
                        foreach($active_classes as $key=>$class){
                            echo_r($b['b_id'],$class,($key>=$class_settings['instructor_show_default']?'active_extra hidden':''));
                        }
                        if(count($active_classes)>$class_settings['instructor_show_default']){
                            echo '<a href="javascript:void(0);" onclick="toggle_hidden_class(\'active_extra\')" class="list-group-item active_extra" style="text-decoration:none;"><i class="fa fa-plus-square-o" style="margin: 0 6px 0 4px; font-size: 19px;" aria-hidden="true"></i> See all '.count($active_classes).'</a>';
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
                            echo_r($b['b_id'],$class,($key>=$class_settings['instructor_show_default']?'past_extra hidden':''));
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
        <td style="padding-top:0;">
            <div id="load_leaderboard"></div>
        </td>
    </tr>
</table>

