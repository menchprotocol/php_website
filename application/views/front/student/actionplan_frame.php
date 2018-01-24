

<?php if(is_dev() && 0){ ?>
    <script>
    $(document).ready(function() {
        //Load shervin for Development Server:
        var psid = '1443101719058431'; //Shervin
        //var psid = '1614565628581807'; //Sam
        $.post("/my/display_actionplan/"+psid+"/<?= intval($b_id) ?>/<?= intval($c_id) ?>", {}, function(data) {
            //Update UI to confirm with user:
            $( "#page_content").html(data).append('<p style="font-size:0.6em; color:#999;">In local development mode</p>');
        });
    });
    </script>
<?php } else {

    //Do they have a local session? (i.e. Browser login):
    $uadmission = $this->session->userdata('uadmission');
    if(isset($uadmission) && count($uadmission)>0){

        //Include header:
        $this->load->view('front/shared/student_nav' , array(
                'current' => 'actionplan',
        ));

        //Fetch page instantly as we know who this is:
        ?>
        <script>
        $.post("/my/display_actionplan/<?= $uadmission['u_fb_id'] ?>/<?= (isset($b_id) ? intval($b_id) : $uadmission['b_id']) ?>/<?= ( isset($c_id) ? intval($c_id) : $uadmission['b_c_id']) ?>", {}, function(data) {
            $( "#page_content").html(data);
        });
        </script>
        <?php

    } else {

        //Use Facebook to see if we can find this user's identity:
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
                    $.post("/my/display_actionplan/"+psid+"/<?= (isset($b_id) ? intval($b_id) : 0) ?>/<?= ( isset($c_id) ? intval($c_id) : 0) ?>?sr="+signed_request, {}, function(data) {
                        //Update UI to confirm with user:
                        $( "#page_content").html(data);
                    });
                },
                function error(err){

                    //Ooops, there was sone sort of an error! Let's see if the student
                    MessengerExtensions.getSupportedFeatures(function success(result) {
                        if(result.supported_features.indexOf("context")<0) {
                            $("#page_content").html('<div class="alert alert-alert" role="alert">Visit www.messenger.com using your PC to access this page using messenger for web.</div>');
                        } else {
                            $("#page_content").html('<div class="alert alert-danger" role="alert">Error: Authentication failed</div>');
                        }
                    }, function error(err) {
                        $("#page_content").html('<div class="alert alert-danger" role="alert">Error: Failed to authenticate</div>');
                    });

                }
            );
        };
        </script>
        <?php
    }
}
?>

<div id="page_content"><div style="text-align:center;"><img src="/img/round_yellow_load.gif" class="loader" /></div></div>