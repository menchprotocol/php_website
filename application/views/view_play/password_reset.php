<script>

    var pass_is_resetting = false;
    function sign_reset_password_apply(){

        if(pass_is_resetting){
            return false;
        }

        //Lock fields:
        pass_is_resetting = true;
        $('#reset_pass_next').html('<span><i class="far fa-yin-yang fa-spin"></i></span>');
        $('#input_password').prop('disabled', true).css('background-color','#F7F7F7');

        //Check email and validate:
        $.post("/play/sign_reset_password_apply", {
            ln_id: <?= $validate_link['ln_id'] ?>,
            input_email: '<?= $validate_link['ln_content'] ?>',
            input_password: $('#input_password').val(),
        }, function (data) {

            if (data.status) {

                //Release field lock:
                $('#reset_pass_next').html('<i class="fas fa-check-circle"></i>');
                $('#pass_reset_errors').html('&nbsp;');

                setTimeout(function () {
                    //Redirect to next step:
                    window.location = data.login_url;
                }, 377);

            } else {

                //Release field lock:
                pass_is_resetting = false;
                $('#reset_pass_next').html('UPDATE & SIGN-IN<i class="fas fa-arrow-right"></i>');
                $('#input_password').prop('disabled', false).css('background-color','#FFFFFF');

                //Show errors:
                $('#pass_reset_errors').html('<i class="fas fa-exclamation-triangle"></i> Error: ' + data.message + '</b>').hide().fadeIn();
            }

        });

    }
</script>




<div class="landing-page-intro sign-in-page">

    <div class="sign-logo"><img src="/img/bp_128.png" /></div>

    <h1>Password Reset</h1>

    <div class="login-content" style="margin-top:50px;">

        <div id="step1" class="signup-steps">

            <!-- New Password-->
            <span class="medium-header" style="padding-top: 20px;"><i class="far fa-key"></i> New Password for <span style="text-transform: lowercase !important;"><?= $validate_link['ln_content'] ?></span></span>
            <div class="form-group is-empty"><input type="password" autocomplete="false" id="input_password" class="form-control border"></div>
            <div class="form-group is-empty" style="font-size: 0.9em;">*At-least <?= config_value(11066) ?> characters</div>

            <!-- Apply Buttons -->
            <div id="pass_reset_errors" class="isred"></div>
            <span id="step2buttons">
                <a href="javascript:void(0)" onclick="sign_reset_password_apply()" id="reset_pass_next" class="btn btn-blog pass btn-raised btn-round btn-next">Update & Sign-In <i class="fas fa-arrow-right"></i></a>
            </span>

        </div>
    </div>
</div>

