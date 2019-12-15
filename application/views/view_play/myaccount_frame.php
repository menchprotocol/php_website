<?php

//Do they have a local session? (i.e. Browser login):
$session_en = superpower_assigned();

//Are they logged in via the browser?
if (isset($session_en['en_id'])) {

    //User is accessing their account from their browser
    //Fetch page instantly as we know who this is:
    ?>
    <script>
        $.post("/play/myaccount_load/0", {}, function (data) {
            $("#page_content").html(data);
            //Load tooldip:
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    <?php

} else {

    //Use Facebook to see if we can find this user's identity:
    ?>
    <script>

        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {
                return;
            }
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.com/en_US/messenger.Extensions.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'Messenger'));

        //the Messenger Extensions JS SDK is done loading:
        window.extAsyncInit = function () {
            //Get context:
            MessengerExtensions.getContext('<?= config_var(11076) ?>',
                function success(thread_context) {
                    // success
                    //user ID was successfully obtained.
                    var psid = thread_context.psid;
                    var signed_request = thread_context.signed_request;
                    //Fetch Page:
                    $.post("/play/myaccount_load/" + psid + "?sr=" + signed_request, {}, function (data) {
                        //Update UI to confirm with user:
                        $("#page_content").html(data);
                    });
                },
                function error(err) {

                    //Give them instructions on how to access via mench.co:
                    $("#page_content").html('<div class="alert alert-info" role="alert" style="line-height:110%;"><i class="fas fa-exclamation-triangle"></i> To access your Account you need to <a href="https://mench.com/signin?url=<?= urlencode($_SERVER['REQUEST_URI']) ?>" style="font-weight:bold;">Sign In</a></div>');

                }
            );
        };
    </script>
    <?php

}
?>

<div class="container" id="page_content">
    <div style="text-align:center; margin-top: 30px;"><i class="far fa-yin-yang fa-spin"></i></div>
</div>