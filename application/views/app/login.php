<?php

$sign_i = array();

if(isset($_GET['i__hashtag']) && strlen($_GET['i__hashtag'])){
    $sign_i = $this->Ideas->read(array(
        'LOWER(i__hashtag)' => strtolower($_GET['i__hashtag']),
    ));
}
$next_url = ( isset($_GET['url']) ? urldecode($_GET['url']) : ( count($sign_i) ? view_memory(42903,33286) . $sign_i[0]['i__hashtag'] : home_url()) );
$e___14870 = $this->config->item('e___14870'); //Website Partner

//Check to see if they are previously logged in?
if(superpower_unlocked()) {

    //Lead member and above, go to console:
    js_php_redirect($next_url, 13);

} elseif(isset($_COOKIE['auth_cookie'])){

    verify_cookie();

    js_php_redirect($next_url, 13);

} elseif(isset($_GET['e__handle']) && $_GET['e__handle']!='SuccessfulWhale' && isset($_GET['e__hash']) && isset($_GET['e__time']) && view__hash($_GET['e__time'].$_GET['e__handle'])==$_GET['e__hash']){

    $es = $this->Sources->read(array(
        'LOWER(e__handle)' => strtolower($_GET['e__handle']),
    ));

    if(count($es)){
        //Assign session & log transaction:
        $this->Sources->activate_session($es[0], false, true);
    }

    js_php_redirect($next_url, 13);

} else {


    if(count($sign_i) || isset($_GET['url'])){
        //Assign Session variable so we can detect upon social login:
        $session_data = $this->session->all_userdata();
        if(count($sign_i)){
            $session_data['login_i__hashtag'] = $sign_i[0]['i__hashtag'];
        }
        if(isset($_GET['url'])){
            $session_data['redirect_url'] = urldecode($_GET['url']);
        }
        $this->session->set_userdata($session_data);
    }


    $e___4269 = $this->config->item('e___4269');
    $e___11035 = $this->config->item('e___11035'); //Encyclopedia

    $this_attempt = array(
        'x__type' => ( count($sign_i) ? 7560 : 7561 ),
        'x__previous' => ( count($sign_i) ? $sign_i[0]['i__id'] : 0 ),
    );

    $current_sign_i_attempt = array(); //Will try to find this
    $current_sign_i_attempts = $this->session->userdata('sign_i_attempts');
    if(is_array($current_sign_i_attempts) && count($current_sign_i_attempts) > 0){
        //See if any of the current sign-in attempts match this:
        foreach($current_sign_i_attempts as $sign_i_attempt){
            $all_match = true;
            foreach(array('x__previous') as $sign_i_attempt_field){
                if(intval($this_attempt[$sign_i_attempt_field]) != intval($sign_i_attempt[$sign_i_attempt_field])){
                    $all_match = false;
                    break;
                }
            }
            if($all_match){
                //We found a match!
                $current_sign_i_attempt = $sign_i_attempt;
                break;
            }
        }
    } else {
        $current_sign_i_attempts = array();
    }


    //See what to do based on current matches:
    if(count($current_sign_i_attempt)==0){

        //Log transaction:
        $current_sign_i_attempt = $this->Ledger->write($this_attempt);

        //Grow the array:
        array_push($current_sign_i_attempts, $current_sign_i_attempt);

        //Add this sign-in attempt to session:
        $this->session->set_userdata(array('sign_i_attempts' => $current_sign_i_attempts));

    }
    ?>

    <script>

        function load_away(){
            $('.login-content').html('<div class="center"><span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span></div>');
        }

        //Disable social login for Instagram as it has a bug within auth0
        $(document).ready(function () {

            //Watch for 4 digit code:
            $("#input_code").on("input", function() {
                if($(this).val().length==4){
                    e_contact_auth();
                }
            });

            var ua = navigator.userAgent || navigator.vendor || window.opera;
            var isInstagram = (ua.indexOf('Instagram') > -1) ? true : false;
            if (document.documentElement.classList ){
                if (isInstagram) {
                    $('.social-frame').addClass('hidden');
                }
            }
        });


        var next_icon = '<?= $e___11035[26104]['m__cover'] ?>';
        var sign_i__id = <?= ( count($sign_i) ? $sign_i[0]['i__id'] : 0 ) ?>;
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
                        e_verify_contact();
                    } else if(step_count==3){
                        e_contact_auth();
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
        function e_verify_contact(){

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
            $.post("/apps/e_verify_contact", {

                account_email_phone: account_email_phone,
                sign_i__id: sign_i__id,
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
        function e_contact_auth(){

            if(code_checking){
                return false;
            }

            //Lock fields:
            code_checking = true;
            $('#code_check_next').html('<span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>');
            $('#input_code').prop('disabled', true);

            //Check email/phone and validate:
            $.post("/apps/e_contact_auth", {
                account_id: $('#account_id').val(), //Might be zero if new account
                account_email_phone: $('#account_email_phone').val(),
                new_account_email: $('#new_account_email').val(),
                new_username: $('#new_username').val(),
                input_code: $('#input_code').val(),
                referrer_url: referrer_url,
                sign_i__id: sign_i__id,
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

        <div class="text-center platform-large"><?= get_domain('m__cover') ?></div>
        <div class="text-center platform-text main__title"><?= get_domain('m__title') ?></div>
        <div class="login-content" style="margin-top:21px;">

            <div id="step1" class="signup-steps">
                <div class="doclear">&nbsp;</div>
            </div>

            <!-- Step 1: Enter Email -->
            <div id="step2" class="signup-steps hidden">

                <span class="main__title" style="padding-bottom: 3px; display:block;"><?= '<span class="icon-block">'.$e___4269[32079]['m__cover'].'</span>'.$e___4269[32079]['m__title'] ?></span>

                <div class="form-group"><input type="text" autocapitalize="none" placeholder="<?= $e___4269[32079]['m__message'] ?>" id="account_email_phone" <?= isset($_GET['account_email_phone']) ? ' value="'.$_GET['account_email_phone'].'" ' : '' ?> class="form-control border input_border"></div>

                <div id="account_email_phone_errors" class="margin-top-down hideIfEmpty"></div>


                <span>
                    <a href="javascript:void(0)" onclick="e_verify_contact()" id="email_check_next" class="controller-nav round-btn pull-right" title="<?= $e___11035[26104]['m__title'] ?>"><?= $e___11035[26104]['m__cover'] ?></a>
                </span>


                <div class="doclear">&nbsp;</div>


                <?php

                //SOCIAL LOGIN:
                if(strlen(website_setting(14881)) && strlen(website_setting(14882))){
                    echo '<div class="social-frame">';
                    echo '<div class="mid-text-line"><span>OR</span></div>';
                    echo '<div class="full-width-btn center top-margin"><a href="'.view_app_link(14436).'" onclick="load_away()" class="btn btn-large btn-default">';
                    echo $e___11035[14436]['m__title'].' '.$e___11035[14436]['m__cover'];
                    echo '</a></div>';
                    echo '</div>';
                }



                //ANONYMOUS LOGIN:
                if(intval(view_memory(6404,14938)) && count($sign_i)){
                    echo '<div class="social-frame">';
                    echo '<div class="mid-text-line"><span>OR</span></div>';
                    echo '<div class="full-width-btn center top-margin"><a href="'.view_app_link(14938).view_memory(42903,33286) . $sign_i[0]['i__hashtag'] . '" onclick="load_away()" class="btn btn-large btn-default">';
                    echo $e___11035[14938]['m__title'].' '.$e___11035[14938]['m__cover'];
                    echo ( strlen($e___11035[14938]['m__message']) ? ': '.$e___11035[14938]['m__message'] : '' );
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

                    <div class="main__title"><span class="icon-block"><?= $e___4269[14026]['m__cover'] ?></span><?= $e___4269[14026]['m__title'] ?></div>

                    <!-- Account Name -->
                    <div style="padding:21px 0 3px; display:block;">
                        <div class="main__title"><span class="icon-block"><?= $e___4269[40893]['m__cover'] ?></span><?= $e___4269[40893]['m__title'] ?></div>
                        <div class="form-group"><input type="text" placeholder="" id="new_username" class="form-control border main__title input_border" /></div>
                    </div>

                    <!-- Enter Email -->
                    <div class="new_email hidden" style="padding:34px 0 3px; display:block;">
                        <div class="main__title"><span class="icon-block"><?= $e___4269[3288]['m__cover'] ?></span><?= $e___4269[3288]['m__title'] ?></div>
                        <div class="form-group"><input type="email" placeholder="" id="new_account_email" class="form-control border main__title input_border" /></div>
                    </div>
                    <div class="doclear">&nbsp;</div>
                </div>


                <!-- Sign in Code -->
                <div style="padding:8px 0;">Enter the <?= $e___4269[32078]['m__title'] ?> sent to <span class="code_sent_to"></span>:</div>
                <div class="form-group"><input maxlength="4" autocomplete="off" type="number"step="1" id="input_code" class="form-control border input_border" /></div>
                <div id="sign_code_errors" class="margin-top-down hideIfEmpty"></div>
                <div class="doclear">&nbsp;</div>


                <div id="step3buttons">
                    <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom" onclick="goto_step(2)" class="controller-nav round-btn pull-left" title="<?= $e___11035[12991]['m__title'] ?>"><?= $e___11035[12991]['m__cover'] ?></a>
                    <a href="javascript:void(0)" onclick="e_contact_auth()" id="code_check_next" class="controller-nav round-btn pull-right" title="<?= $e___11035[26104]['m__title'] ?>"><?= $e___11035[26104]['m__cover'] ?></a>
                </div>

                <div class="doclear">&nbsp;</div>

            </div>

        </div>



    </div>

    <?php
}