


var logged_messenger = false;
var logged_website = false;


$(document).ready(function () {
    goto_step(( referrer_in_id > 0 ? 1 : 2 ));
});

function goto_step(step_count){
    $('.signup-steps').addClass('hidden');
    $('#step'+step_count).removeClass('hidden');
}

function confirm_signin_on_messenger(){

    var r = confirm("You will now be redirected to Mench on Facebook Messenger");
    if (r == true) {
        //Go to target intent:
        $('#messenger_signin').html('Redirecting...');

        signin_on_messenger();
    }
}

function signin_on_messenger(){

    if(!logged_messenger){
        js_ln_create(channel_choice_messenger);
        logged_messenger = true;
    }

    //Redirect to Messenger with a bit of delay to log the link above:
    setTimeout(function () {
        window.location = 'https://m.me/askmench' + ( referrer_in_id > 0 ? '?ref=' + ( referrer_en_id > 0 ? 'REFERUSER_'+referrer_en_id+'_' : '' ) + referrer_in_id : '' );
    }, 250);

}

function choose_channel(){


    if(parseInt($('input:radio[name=platform_channels]:checked').val()) == 6196 /* Mench on Messenger */ ){

        //Remove button:
        $('#step1button').html('<div style="font-size: 1.2em; padding-top:10px;"><i class="fas fa-spinner fa-spin"></i> Taking you to Messenger...</div>');

        //Log link:
        signin_on_messenger();

    } else {

        //Log link:
        if(!logged_website){
            js_ln_create(channel_choice_website);
            logged_website = true;
        }

        goto_step(2);

    }
}

var email_is_searching = false;
function search_email(){

    if(email_is_searching){
        return false;
    }

    //Lock fields:
    email_is_searching = true;
    $('#email_check_next').html('<i class="fas fa-spinner fa-spin"></i>');
    $('#input_email').prop('disabled', true).css('background-color','#EFEFEF');
    $('#password_errors').html('&nbsp;');

    //Check email and validate:
    $.post("/user_app/singin_check_email", {

        input_email: $('#input_email').val(),
        referrer_url: referrer_url,
        referrer_in_id: referrer_in_id,
        referrer_en_id: referrer_en_id,
        password_reset: 0,

    }, function (data) {

        //Release field lock:
        email_is_searching = false;
        $('#email_check_next').html('Next <i class="fas fa-arrow-right"></i>');
        $('#input_email').prop('disabled', false).css('background-color','#FFFFFF');

        if (data.status) {

            //Update entity id IF existed already:
            $('#login_en_id').val(data.login_en_id);

            //Update email:
            $('#input_email').val(data.clean_input_email);
            $('.focus_email').html(data.clean_input_email);
            $('#email_errors').html('&nbsp;');

            //Go to next step:
            goto_step(( data.email_existed_already ? 3 /* To ask for password */ : 4 /* To check their email and create new account */ ));

        } else {
            //Show errors:
            $('#email_errors').html('<i class="fas fa-exclamation-triangle"></i> Error: ' + data.message + '</b>').hide().fadeIn();
        }
    });

}

var password_is_checking = false;
function check_password(){

    if(password_is_checking){
        return false;
    }

    //Lock fields:
    password_is_checking = true;
    $('#password_check_next').html('<i class="fas fa-spinner fa-spin"></i>');
    $('#input_password').prop('disabled', true).css('background-color','#EFEFEF');

    //Check email and validate:
    $.post("/user_app/singin_check_password", {
        login_en_id: $('#login_en_id').val(),
        input_password: $('#input_password').val(),
    }, function (data) {

        if (data.status) {

            //Release field lock:
            $('#password_check_next').html('<i class="fas fa-check-circle"></i>');
            $('#password_errors').html('&nbsp;');

            //Redirect
            window.location = data.login_url;

        } else {

            //Release field lock:
            password_is_checking = false;
            $('#password_check_next').html('Sign In <i class="fas fa-arrow-right"></i>');
            $('#input_password').prop('disabled', false).css('background-color','#FFFFFF');

            //Show errors:
            $('#password_errors').html('<i class="fas fa-exclamation-triangle"></i> Error: ' + data.message + '</b>').hide().fadeIn();
        }

    });

}

function email_forgot_password(){
    var r = confirm("I will email you a link to reset your password.");
    if (r == true) {
        //Check email and validate:
        $.post("/user_app/singin_check_email", {
            input_email: $('#input_email').val(),
            referrer_url: referrer_url,
            referrer_in_id: referrer_in_id,
            referrer_en_id: referrer_en_id,
            password_reset: 1,
        }, function (data) {
            if (data.status) {
                goto_step(4 /* To check their email and create new account */);
            } else {
                //Show errors:
                $('#email_errors').html('<i class="fas fa-exclamation-triangle"></i> Error: ' + data.message + '</b>').hide().fadeIn();
            }
        });
    }
}