<?php

$sign_i = array();

if(isset($_GET['posthashtag']) && strlen($_GET['posthashtag'])){
    $sign_i = $this->Posts->read(array(
        'LOWER(posthashtag)' => strtolower($_GET['posthashtag']),
    ));
}
$next_url = ( isset($_GET['url']) ? urldecode($_GET['url']) : ( count($sign_i) ? login . view_memory(42903, 33286) . $sign_i[0]['posthashtag'] : home_url()) );
$users___14870 = $this->config->item('users___14870'); //Website Partner

//Check to see if they are previously logged in?
if(user_session()) {

    //Lead member and above, go to console:
    js_php_redirect($next_url, 13);

} elseif(isset($_COOKIE['auth_cookie'])){

    verify_cookie();

    js_php_redirect($next_url, 13);

} elseif(isset($_GET['userlogin']) && $_GET['userlogin']!='SuccessfulWhale' && isset($_GET['hash']) && isset($_GET['time']) && view_hash($_GET['time'].$_GET['userlogin'])==$_GET['hash']){

    $es = $this->Users->read(array(
        'LOWER(userhandle)' => strtolower($_GET['userlogin']),
    ));

    if(count($es)){
        //Assign session & log Chain:
        $this->Users->activate($es[0], false, true);
    }

    js_php_redirect($next_url, 13);

} else {


    if(count($sign_i) || isset($_GET['url'])){
        //Assign Session variable so we can detect upon social login:
        $session_data = $this->session->all_userdata();
        if(count($sign_i)){
            $session_data['login_posthashtag'] = $sign_i[0]['posthashtag'];
        }
        if(isset($_GET['url'])){
            $session_data['redirect_url'] = urldecode($_GET['url']);
        }
        $this->session->set_userdata($session_data);
    }


    $users___4269 = $this->config->item('users___4269');
    $users___11035 = $this->config->item('users___11035'); //Encyclopedia



    $current_sign_post_attempt = array(); //Will try to find this
    $current_sign_post_attempts = $this->session->userdata('sign_post_attempts');
    if(is_array($current_sign_post_attempts) && count($current_sign_post_attempts) > 0){
        //See if any of the current sign-in attempts match this:
        foreach($current_sign_post_attempts as $sign_post_attempt){
            $all_match = true;
            if(count($sign_i) && $sign_i[0]['postid'] != intval($sign_post_attempt['chainpostinput'])){
                $all_match = false;
                break;
            }
            if($all_match){
                //We found a match!
                $current_sign_post_attempt = $sign_post_attempt;
                break;
            }
        }
    } else {
        $current_sign_post_attempts = array();
    }


    //See what to do based on current matches:
    if(count($current_sign_post_attempt)==0){

        //Grow the array:
        array_push($current_sign_post_attempts, $current_sign_post_attempt);

        //Add this sign-in attempt to session:
        $this->session->set_userdata(array('sign_post_attempts' => $current_sign_post_attempts));

    }
    ?>

    <script>

        function load_away(){
            $('.login-content').html('<div class="center"><span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span></div>');
        }

        //Disable login for Instagram Frame:
        $(document).ready(function () {

            //Watch for 4 digit code:
            $("#input_code").on("input", function() {
                if($(this).val().length==4){
                    user_authenticate();
                }
            });

            var ua = navigator.userAgent || navigator.vendor || window.opera;
            var isInstagram = (ua.indexOf('Instagram') > -1) ? true : false;
            if (document.documentElement.classList ){
                if (isInstagram) {
                    $('.login-content').html('Instagram Frame detected! Visit us from A Web Browser like Google Chrome or Safari to Continue');
                }
            }
        });


        var next_icon = '<?= $users___11035[26104]['m__cover'] ?>';
        var sign_postid = <?= ( count($sign_i) ? $sign_i[0]['postid'] : 0 ) ?>;
        var referrer_url = '<?= @$_GET['url'] ?>';
        var logged_messenger = false;
        var logged_website = false;
        var step_count = 0;

        $(document).ready(function () {

            goto_step(2);

            $(document).keyup(function (e) {
                //Watch for action keys:
                if (e.keyCode==13) {
                    if(step_count==2){
                        user_verify();
                    } else if(step_count==3){
                        user_authenticate();
                    }
                }
            });
        });

        function goto_step(this_count){

            //Update read count:
            step_count = this_count;

            $('.signup-steps').addClass('hidden');
            $('#step'+step_count).removeClass('hidden');

            setTimeout(function () {
                $('#step'+step_count+' .input_border:first').focus();
            }, 144);

        }


        var verifying_contact = false;
        function user_verify(){

            if(verifying_contact){
                return false;
            }

            //Lock fields:
            verifying_contact = true;
            var account_email_phone = $('#account_email_phone').val();
            $('#email_check_next').html('<span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>');
            $('#account_email_phone').prop('disabled', true);
            $('#sign_code_errors').html('');
            $('#flash_message').html(''); //Delete previous errors, if any

            //Check email and validate:
            $.post("/controller/user_verify", {

                account_email_phone: account_email_phone,
                sign_postid: sign_postid,
                js_request_uri: js_request_uri, //Always append to AJAX Calls

            }, function (data) {

                //Release field lock:
                verifying_contact = false;
                $('#email_check_next').html(next_icon);
                $('#account_email_phone').prop('disabled', false);

                if (data.status) {

                    //Update email:
                    $('#account_email_phone_errors').html('');
                    $('#account_id').val(data.account_id);
                    $('#account_preview').html(data.account_preview);
                    $('#account_email_phone').val(data.clean_contact);
                    $('.code_sent_to').html(data.clean_contact);

                    if(!data.account_id){

                        //Allow to create new account with email/phone
                        $('.new_account').removeClass('hidden');

                        if(data.valid_email){
                            $('.new_email').addClass('hidden');
                        } else {
                            $('.new_email').removeClass('hidden');
                        }

                    } else {
                        $('.new_account').addClass('hidden');
                    }

                    //Go to final step:
                    goto_step(3);
                    $("#input_code").val('').focus();

                } else {

                    //Show errors:
                    $('#account_email_phone_errors').html('<b class="main__title"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>' + data.message + '</b>').hide().fadeIn();
                    $('#account_email_phone').focus();

                }

            });

        }


        var code_checking = false;
        function user_authenticate(){

            if(code_checking){
                return false;
            }

            //Lock fields:
            code_checking = true;
            $('#code_check_next').html('<span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>');
            $('#input_code').prop('disabled', true);

            //Check email/phone and validate:
            $.post("/controller/user_authenticate", {
                account_id: $('#account_id').val(), //Might be zero if new account
                account_email_phone: $('#account_email_phone').val(),
                new_account_email: $('#new_account_email').val(),
                input_code: $('#input_code').val(),
                referrer_url: referrer_url,
                sign_postid: sign_postid,
                js_request_uri: js_request_uri, //Always append to AJAX Calls
            }, function (data) {
                if (data.status) {

                    js_redirect(data.sign_url);

                } else {

                    //Release field lock:
                    code_checking = false;
                    $('#code_check_next').html(next_icon);
                    $('#input_code').prop('disabled', false).focus();
                    $('#sign_code_errors').html('<b class="main__title"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>' + data.message + '</b>').hide().fadeIn();

                }
            });

        }

    </script>


    <div class="center-info">

        <div class="login-content" style="margin-top:21px;">

            <div id="step1" class="signup-steps">
                <div class="doclear">&nbsp;</div>
            </div>

            <!-- Step 1: Enter Email -->
            <div id="step2" class="signup-steps hidden">

                <span class="main__title" style="padding-bottom: 3px; display:block;"><?= '<span class="icon-block">'.$users___4269[32079]['m__cover'].'</span>'.$users___4269[32079]['m__name'] ?></span>

                <div class="form-group"><input type="text" autocapitalize="none" placeholder="<?= $users___4269[32079]['m__message'] ?>" id="account_email_phone" <?= isset($_GET['account_email_phone']) ? ' value="'.$_GET['account_email_phone'].'" ' : '' ?> class="form-control border input_border"></div>

                <div id="account_email_phone_errors" class="margin-top-down hideIfEmpty"></div>


                <span>
                    <a href="javascript:void(0)" onclick="user_verify()" id="email_check_next" class="controller-nav round-btn pull-right" title="<?= $users___11035[26104]['m__name'] ?>"><?= $users___11035[26104]['m__cover'] ?></a>
                </span>


                <div class="doclear">&nbsp;</div>


                <?php
                //ANONYMOUS LOGIN:
                if(intval(view_memory(6404,14938)) && count($sign_i)){
                    echo '<div class="social-frame">';
                    echo '<div class="mid-text-line"><span>OR</span></div>';
                    echo '<div class="full-width-btn center top-margin"><a href="'.view_app_chain(14938).view_memory(42903,33286) . $sign_i[0]['posthashtag'] . '" onclick="load_away()" class="btn btn-large btn-default">';
                    echo $users___11035[14938]['m__name'].' '.$users___11035[14938]['m__cover'];
                    echo ( strlen($users___11035[14938]['m__message']) ? ': '.$users___11035[14938]['m__message'] : '' );
                    echo '</a></div>';
                    echo '</div>';
                }
                ?>

                <div class="doclear">&nbsp;</div>

            </div>


            <!-- Step 3: Enter Sign in Code (and Maybe signup if not found) -->
            <div id="step3" class="signup-steps hidden">

                <!-- To be updated to >0 IF account was found -->
                <input type="hidden" id="account_id" value="0" />
                <div id="account_preview"></div>

                <!-- New Account (If not found) -->
                <div class="margin-top-down new_account hidden">

                    <div class="main__title"><span class="icon-block"><?= $users___4269[14026]['m__cover'] ?></span><?= $users___4269[14026]['m__name'] ?></div>

                    <!-- Enter Email -->
                    <div class="new_email hidden" style="padding:34px 0 3px; display:block;">
                        <div class="main__title"><span class="icon-block"><?= $users___4269[3288]['m__cover'] ?></span><?= $users___4269[3288]['m__name'] ?></div>
                        <div class="form-group"><input type="email" placeholder="" id="new_account_email" class="form-control border main__title input_border" /></div>
                    </div>
                    <div class="doclear">&nbsp;</div>
                </div>


                <!-- Sign in Code -->
                <div style="padding:8px 0;">Enter the <?= $users___4269[32078]['m__name'] ?> sent to <span class="code_sent_to"></span> (Also check spam folder):</div>
                <div class="form-group"><input maxlength="4" autocomplete="off" type="number"step="1" id="input_code" class="form-control border input_border" /></div>
                <div id="sign_code_errors" class="margin-top-down hideIfEmpty"></div>
                <div class="doclear">&nbsp;</div>


                <div id="step3buttons">
                    <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom" onclick="goto_step(2)" class="controller-nav round-btn pull-left" title="<?= $users___11035[12991]['m__name'] ?>"><?= $users___11035[12991]['m__cover'] ?></a>
                    <a href="javascript:void(0)" onclick="user_authenticate()" id="code_check_next" class="controller-nav round-btn pull-right" title="<?= $users___11035[26104]['m__name'] ?>"><?= $users___11035[26104]['m__cover'] ?></a>
                </div>

                <div class="doclear">&nbsp;</div>

            </div>

        </div>



    </div>

    <?php
}