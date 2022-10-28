<?php

$sign_i__id = ( isset($_GET['i__id']) && $_GET['i__id'] > 0 ? $_GET['i__id'] : 0 );
$next_url = ( isset($_GET['url']) ? urldecode($_GET['url']) : ($sign_i__id > 0 ? '/' . $sign_i__id : home_url()) );

//Check to see if they are previously logged in?
if(superpower_unlocked()) {

    //Lead member and above, go to console:
    js_redirect($next_url, 13);

} elseif(isset($_COOKIE['auth_cookie'])){

    //Authenticate Cookie:
    $cookie_parts = explode('ABCEFG',$_COOKIE['auth_cookie']);

    $es = $this->E_model->fetch(array(
        'e__id' => $cookie_parts[0],
        'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
    ));

    if(count($es) && $cookie_parts[2]==md5($cookie_parts[0].$cookie_parts[1].$this->config->item('cred_password_salt'))){

        //Assign session & log transaction:
        $this->E_model->activate_session($es[0], false, true);

    } else {

        //Cookie was invalid
        cookie_delete();

    }

    js_redirect($next_url, 13);

} else {


    if($sign_i__id || isset($_GET['url'])){
        //Assign Session variable so we can detect upon social login:
        $session_data = $this->session->all_userdata();
        if($sign_i__id){
            $session_data['login_i__id'] = $_GET['i__id'];
        }
        if(isset($_GET['url'])){
            $session_data['redirect_url'] = urldecode($_GET['url']);
        }
        $this->session->set_userdata($session_data);
    }


    if(0 && !isset($_GET['active'])){

        //Disable for now:
        js_redirect('/-14436', 13);

        echo '<div class="center-info">';
        echo '<div class="text-center platform-large">'.get_domain('m__cover').'</div>';
        echo '<p style="margin-top:13px; text-align: center;">'.view_shuffle_message(12694).'</p>';
        echo '</div>';

    } else {

        $e___4269 = $this->config->item('e___4269');
        $e___11035 = $this->config->item('e___11035'); //NAVIGATION

        $this_attempt = array(
            'x__type' => ( $sign_i__id > 0 ? 7560 : 7561 ),
            'x__left' => $sign_i__id,
        );

        $current_sign_i_attempt = array(); //Will try to find this...
        $current_sign_i_attempts = $this->session->userdata('sign_i_attempts');
        if(is_array($current_sign_i_attempts) && count($current_sign_i_attempts) > 0){
            //See if any of the current sign-in attempts match this:
            foreach($current_sign_i_attempts as $sign_i_attempt){
                $all_match = true;
                foreach(array('x__left') as $sign_i_attempt_field){
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
        if(count($current_sign_i_attempt) == 0){

            //Log transaction:
            $current_sign_i_attempt = $this->X_model->create($this_attempt);

            //Grow the array:
            array_push($current_sign_i_attempts, $current_sign_i_attempt);

            //Add this sign-in attempt to session:
            $this->session->set_userdata(array('sign_i_attempts' => $current_sign_i_attempts));

        }
        ?>

        <script type="text/javascript">

            //Disable social login for Instagram as it has a bug within auth0
            var ua = navigator.userAgent || navigator.vendor || window.opera;
            var isInstagram = (ua.indexOf('Instagram') > -1) ? true : false;
            if (document.documentElement.classList ){
                if (isInstagram) {
                    $('.social-frame').addClass('hidden');
                }
            }


            var go_next_icon = '<?= $e___11035[12211]['m__cover'] ?>';
            var sign_i__id = <?= $sign_i__id ?>;
            var referrer_url = '<?= @$_GET['url'] ?>';
            var logged_messenger = false;
            var logged_website = false;
            var step_count = 0;

            $(document).ready(function () {

                //Watch for email address change:
                $('#input_email').on('input',function(e){
                    if($(this).length){
                        $('#step2buttons').removeClass('hidden');
                    } else {
                        $('#step2buttons').addClass('hidden');
                    }
                });

                goto_step(2);

                $(document).keyup(function (e) {
                    //Watch for action keys:
                    if (e.keyCode == 13) {
                        if(step_count==2){
                            search_email();
                        } else if(step_count==3){
                            e_signin_password();
                        } else if(step_count==4){
                            add_account();
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
                    $('#step'+step_count+' .white-border:first').focus();
                }, 144);

            }


            var email_searching = false;
            function search_email(){

                if(email_searching){
                    return false;
                }

                //Lock fields:
                email_searching = true;
                $('#email_check_next').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>');
                $('#input_email').prop('disabled', true);
                $('#password_errors').html('');
                $('#flash_message').html(''); //Delete previous errors, if any

                //Check email and validate:
                $.post("/e/e_signin_email", {

                    input_email: $('#input_email').val(),
                    sign_i__id: sign_i__id,

                }, function (data) {

                    //Release field lock:
                    email_searching = false;
                    $('#email_check_next').html(go_next_icon);
                    $('#input_email').prop('disabled', false);

                    if (data.status) {

                        //Update email:
                        $('#input_email').val(data.clean_email_input);
                        $('.focus_email').html(data.clean_email_input);
                        $('#email_errors').html('');

                        if(data.email_existed_previously && !data.password_existed_previously){
                            //Did social login before, but now trying to directly login:
                            $('.full_name').addClass('hidden');
                        } else {
                            $('.full_name').removeClass('hidden');
                        }

                        if(data.email_existed_previously && data.password_existed_previously){
                            //Update source id IF existed previously:
                            $('#sign_e__id').val(data.sign_e__id);
                        }

                        //Go to next read:
                        goto_step(( data.email_existed_previously && data.password_existed_previously ? 3 /* To ask for password */ : 4 /* To check their email and create new account */ ));

                    } else {
                        //Show errors:
                        $('#email_errors').html('<b class="css__title zq6255"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>' + data.message + '</b>').hide().fadeIn();
                        $('#input_email').focus();
                    }
                });

            }

            var account_adding = false;
            function add_account(){

                if(account_adding){
                    return false;
                }

                //Lock fields:
                account_adding = true;
                $('#add_acount_next').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>');
                $('#input_name, #password_reset').prop('disabled', true);

                //Check email and validate:
                $.post("/e/e_signin_create", {
                    new_account_passcode: $('#new_account_passcode').val(),
                    input_email: $('#input_email').val(),
                    input_name: $('#input_name').val(),
                    password_reset: $('#password_reset').val(),
                    referrer_url: referrer_url,
                    sign_i__id: sign_i__id,
                }, function (data) {

                    if (data.status) {

                        //Release field lock:
                        $('#add_acount_next').html(js_e___11035[14424]['m__cover']);
                        $('#account_errors').html('');

                        setTimeout(function () {
                            //Redirect to next read:
                            window.location = data.sign_url;
                        }, 377);

                    } else {

                        //Release field lock:
                        account_adding = false;
                        $('#add_acount_next').html(go_next_icon);
                        $('#password_reset, #input_name').prop('disabled', false);

                        //Do we know which field to focus on?
                        if(data.focus_input_field.length>0) {
                            $('#' + data.focus_input_field).focus();
                        }

                        //Show errors:
                        $('#account_errors').html('<b class="css__title zq6255"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>' + data.message + '</b>').hide().fadeIn();

                    }

                });

            }

            var password_checking = false;
            function e_signin_password(){

                if(password_checking){
                    return false;
                }

                //Lock fields:
                password_checking = true;
                $('#password_check_next').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>');
                $('#input_password').prop('disabled', true);

                //Check email and validate:
                $.post("/e/e_signin_password", {
                    sign_e__id: $('#sign_e__id').val(),
                    input_password: $('#input_password').val(),
                    referrer_url: referrer_url,
                    sign_i__id: sign_i__id,
                }, function (data) {

                    if (data.status) {

                        //Release field lock:
                        $('#password_check_next').html(js_e___11035[14424]['m__cover']);
                        $('#password_errors').html('');

                        //Redirect
                        window.location = data.sign_url;

                    } else {

                        //Release field lock:
                        password_checking = false;
                        $('#password_check_next').html(go_next_icon);
                        $('#input_password').prop('disabled', false).focus();

                        //Show errors:
                        $('#password_errors').html('<b class="css__title zq6255"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>' + data.message + '</b>').hide().fadeIn();
                    }

                });

            }

            function e_magic_email(){
                //Update UI:
                goto_step(5); //To check their email and create new account
                $('.magic_result').html('<div><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>Sending Email...</div>');

                //Check email and validate:
                $.post("/e/e_magic_email", {
                    input_email: $('#input_email').val(),
                    sign_i__id: sign_i__id,
                }, function (data) {
                    if (data.status) {
                        //All good, they can close window:
                        $('.magic_result').html('<div><span class="icon-block"><i class="fas fa-eye"></i></span>Check Your Email (Also Spam Folder)</div>').hide().fadeIn();
                    } else {
                        //Show errors:
                        $('.magic_result').html('<div class="zq6255 css__title"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>' + data.message + '</div>').hide().fadeIn();
                    }
                });
            }
        </script>


        <div class="center-info">

            <div class="text-center platform-large"><?= get_domain('m__cover') ?></div>

            <div class="login-content" style="margin-top:41px;">

                <!-- Step 1: Enter Email -->
                <div id="step2" class="signup-steps hidden">

                    <?php


                    //Back only if coming from an idea:
                    $intro_message = $e___4269[7561]['m__message']; //Assume No Idea
                    if ($sign_i__id > 0) {
                        $sign_i = $this->I_model->fetch(array(
                            'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                            'i__id' => $sign_i__id,
                        ));
                        if (count($sign_i)) {
                            $intro_message = str_replace('%s','<br /><a href="/' . $sign_i__id . '"><u>'.$sign_i[0]['i__title'].'</u></a>', $e___4269[7560]['m__message']);
                        }
                    }


                    echo '<p style="margin-top:13px; text-align: center; padding-bottom: 34px;">'.$intro_message.'</p>';


                    //SOCIAL LOGIN:
                    echo '<div class="social-frame">';
                    echo '<div class="full-width-btn center top-margin"><a href="/-14436" class="btn btn-large btn-default">';
                    echo $e___11035[14436]['m__title'].' '.$e___11035[14436]['m__cover'];
                    echo '</a></div>';
                    echo '<div class="mid-text-line"><span>OR</span></div>';
                    echo '</div>';
                    ?>

                    <span class="css__title" style="padding-bottom: 3px; display:block;"><?= '<span class="icon-block">'.$e___4269[3288]['m__cover'].'</span>'.$e___4269[3288]['m__title'] ?></span>
                    <div class="form-group"><input type="email" placeholder="your@email.com" id="input_email" <?= isset($_GET['input_email']) ? ' value="'.$_GET['input_email'].'" ' : '' ?> class="form-control border white-border white-border"></div>
                    <div id="email_errors" class="zq6255 margin-top-down hideIfEmpty"></div>
                    <span id="step2buttons" class="<?= isset($_GET['input_email']) ? '' : ' hidden ' ?>" >
                    <a href="javascript:void(0)" onclick="search_email()" id="email_check_next" class="controller-nav round-btn pull-right" title="<?= $e___11035[12211]['m__title'] ?>"><?= $e___11035[12211]['m__cover'] ?></a>
                <div class="doclear">&nbsp;</div>
                </span>

                    <?php

                    //GUEST LOGIN:
                    if($sign_i__id && 0){
                        echo '<div class="mid-text-line"><span>OR</span></div>';
                        echo '<div class="full-width-btn center top-margin"><a href="/-14938?i__id='.$sign_i__id.'" class="btn btn-large btn-default">';
                        echo $e___11035[14938]['m__title'].' '.$e___11035[14938]['m__cover'];
                        echo '</a></div>';
                    }
                    ?>

                </div>





                <!-- Step 3: Enter password (IF FOUND) -->
                <div id="step3" class="signup-steps hidden">

                    <!-- To be updated to >0 IF email was found -->
                    <input type="hidden" id="sign_e__id" value="0" />

                    <span class="css__title" style="padding-bottom: 3px; display:block;"><?= '<span class="icon-block">'.$e___4269[3286]['m__cover'].'</span>'.$e___4269[3286]['m__title'] ?></span>
                    <div class="form-group"><input type="password" id="input_password" class="form-control border white-border"></div>
                    <div id="password_errors" class="zq6255 margin-top-down hideIfEmpty"></div>

                    <div class="doclear">&nbsp;</div>

                    <div id="step3buttons">
                        <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom" onclick="goto_step(2)" class="controller-nav round-btn pull-left" title="<?= $e___11035[12991]['m__title'] ?>"><?= $e___11035[12991]['m__cover'] ?></a>
                        <a href="javascript:void(0)" onclick="e_signin_password()" id="password_check_next" class="controller-nav round-btn pull-right" title="<?= $e___11035[12211]['m__title'] ?>"><?= $e___11035[12211]['m__cover'] ?></a>
                    </div>

                    <div class="doclear">&nbsp;</div>
                    <div style="padding-top:13px;">No password? Try <a href="javascript:void(0)" onclick="e_magic_email()" style="text-decoration:none;font-weight: bold;"><?= '<u>'.$e___11035[11068]['m__title'].'</u> '.$e___11035[11068]['m__cover'] ?></a></div>

                </div>





                <!-- Step 4: Create New Account (IF NOT FOUND) -->
                <div id="step4" class="signup-steps hidden">

                    <!-- pre-set Email -->
                    <div class="margin-top-down">
                        <div class="css__title"><span class="icon-block"><?= $e___4269[14026]['m__cover'] ?></span><?= $e___4269[14026]['m__title'] ?></div>
                        <div style="padding:8px 0;"><span class="icon-block">&nbsp;</span><span class="focus_email"></span></div>
                    </div>


                    <!-- Email Verification Pass Code -->
                    <div class="margin-top-down">
                        <span class="css__title" style="padding-bottom: 3px; display:block;"><?= '<span class="icon-block">'.$e___4269[28782]['m__cover'].'</span>'.$e___4269[28782]['m__title'] ?></span>
                        <p><?= $e___4269[28782]['m__message'] ?></p>
                        <div class="form-group"><input type="number" step="1" id="new_account_passcode" maxlength="4" class="form-control border css__title white-border"></div>
                    </div>


                    <!-- Full Name -->
                    <div class="margin-top-down full_name">
                        <span class="css__title" style="padding-bottom: 3px; display:block;"><?= '<span class="icon-block">'.$e___4269[13025]['m__cover'].'</span>'.$e___4269[13025]['m__title'] ?></span>
                        <div class="form-group"><input type="text" placeholder="<?= $e___4269[13025]['m__message'] ?>" id="input_name" maxlength="<?= view_memory(6404,6197) ?>" class="form-control border css__title white-border"></div>
                    </div>


                    <!-- New Password -->
                    <div class="margin-top-down">
                        <span class="css__title" style="padding-bottom: 3px; display:block;"><span class="icon-block"><?= $e___4269[14027]['m__cover'] ?></span><?= $e___4269[14027]['m__title'] ?></span>
                        <div class="form-group"><input type="password" id="password_reset" class="form-control border white-border"></div>
                    </div>


                    <!-- Signup Buttons -->
                    <div id="account_errors" class="zq6255 margin-top-down hideIfEmpty"></div>
                    <span>
                    <a href="javascript:void(0)" onclick="goto_step(2)" class="controller-nav round-btn pull-left" title="<?= $e___11035[12991]['m__title'] ?>"><?= $e___11035[12991]['m__cover'] ?></a>
                    <a href="javascript:void(0)" onclick="add_account()" id="add_acount_next" class="controller-nav round-btn pull-right" title="<?= $e___11035[12211]['m__title'] ?>"><?= $e___11035[12211]['m__cover'] ?></a>
                    <div class="doclear">&nbsp;</div>
                </span>

                </div>


                <!-- Step 5: Check your email -->
                <div id="step5" class="signup-steps hidden">
                    <div style="padding-bottom: 10px;"><span class="icon-block"><i class="fas fa-envelope-open"></i></span><span class="focus_email"></span></div>
                    <span class="medium-header magic_result"></span>
                </div>


            </div>
        </div>

        <?php

    }
}