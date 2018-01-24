<?php
$uadmission = $this->session->userdata('uadmission');
if(is_dev() && 0){

    ?>
    <script>
    $(document).ready(function() {
        //Load shervin for Development:
        var psid = '1443101719058431'; //Shervin
        //var psid = '1614565628581807'; //Sam
        $.post( "/api_v1/load_leaderboard", { psid:psid }, function(data) {
            //Update UI to confirm with user:
            $( "#page_content").html(data).append('<p style="font-size:0.6em; color:#999;">In local development mode</p>');
        });
    });
    </script>
    <?php

} elseif(isset($uadmission) && count($uadmission)>0) {

    //Include header:
    $this->load->view('front/shared/student_nav' , array(
        'current' => 'leaderboard',
    ));

    //Fetch page instantly as we know who this is:
    ?>
    <script>
        $.post("/api_v1/load_leaderboard", { psid:<?= $uadmission['u_fb_id'] ?> }, function(data) {
            $( "#page_content").html(data);
        });
    </script>
    <?php

} else {

    ?>
    <script>
    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.com/en_US/messenger.Extensions.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'Messenger'));

    //the Messenger Extensions JS SDK is done loading:
    window.extAsyncInit = function() {

        //Get context:
        MessengerExtensions.getContext('1782431902047009',
            function success(thread_context){
                // success
                //User ID was successfully obtained.
                var psid = thread_context.psid;
                var signed_request = thread_context.signed_request;
                //Fetch Page:
                $.post( "/api_v1/load_leaderboard", { psid:psid }, function(data) {
                    //Update UI to confirm with user:
                    $( "#page_content").html(data);
                });
            },
            function error(err){

                //Give them instructions on how to access via mench.co:
                $("#page_content").html('<div class="alert alert-info" role="alert" style="line-height:110%;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> This version of Facebook Messenger does not support loading websites.<br /><br />Solution: login to <a href="https://mench.co/login" target="_blank"><u>mench.co</u></a> to access your Bootcamp content. Use <b><u>Forgot Password</u></b> to generate a new password if you do not have one.</div>');

            }
        );
    };
    </script>
    <?php

}
?>

<div id="page_content"><div style="text-align:center;"><img src="/img/round_yellow_load.gif" class="loader" /></div></div>