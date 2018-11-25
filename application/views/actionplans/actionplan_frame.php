<?php

//Do they have a local session? (i.e. Browser login):
$udata = $this->session->userdata('user');
$fb_settings = $this->config->item('fb_settings');

if((isset($udata['u__ws']) && count($udata['u__ws'])) || (isset($udata['u__parents']) && count($udata['u__parents'])>0 && array_key_exists(1308, $udata['u__parents']))){

    //User is accessing the Action Plan from their browser

    //Include header:
    $this->load->view('shared/messenger_nav' , array(
        'current' => 'actionplan',
    ));

    //Fetch page instantly as we know who this is:
    ?>
    <script>
        $.post("/my/display_actionplan/0/<?= ( isset($w_id) ? intval($w_id) : $udata['u__ws'][0]['w_id'] ) ?>/<?= ( isset($c_id) ? intval($c_id) : $udata['u__ws'][0]['w_c_id'] ) ?>", {}, function(data) {
            $( "#page_content").html(data);

            //Load tooldip:
            $('[data-toggle="tooltip"]').tooltip();
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
            MessengerExtensions.getContext('<?= $fb_settings['app_id'] ?>',
                function success(thread_context){
                    // success
                    //user ID was successfully obtained.
                    var psid = thread_context.psid;
                    var signed_request = thread_context.signed_request;
                    //Fetch Page:
                    $.post("/my/display_actionplan/"+psid+"/<?= ( isset($w_id) ? intval($w_id) : 0) ?>/<?= ( isset($c_id) ? intval($c_id) : 0) ?>?sr="+signed_request, {}, function(data) {
                        //Update UI to confirm with user:
                        $( "#page_content").html(data);
                    });
                },
                function error(err){

                    //Give them instructions on how to access via mench.co:
                    $("#page_content").html('<div class="alert alert-info" role="alert" style="line-height:110%;"><i class="fas fa-exclamation-triangle"></i> To access your Action Plan you need to <a href="https://mench.com/login?url=<?= urlencode($_SERVER['REQUEST_URI']) ?>" style="font-weight:bold;">Login</a>. Use [Forgot Password] if you never logged in before.</div>');

                }
            );
        };
    </script>
    <?php

}
?>

<div id="page_content" class="maxout"><div style="text-align:center; margin-top: 30px;"><img src="/img/round_load.gif" class="loader" /></div></div>