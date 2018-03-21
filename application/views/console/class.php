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

    </div>

    <div class="tab-pane" id="tabactionplan">
        <?php

        ?>
    </div>

</div>

<?php } ?>