


var logged_messenger = false;
var logged_website = false;
var step_count = 0;


$(document).ready(function () {

    //Watch for email address change:
    $('#email_check_next').on('input',function(e){
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

    //Update discover count:
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

            //Update source id IF existed previously:
            $('#sign_e__id').val(data.sign_e__id);

            //Update email:
            $('#input_email').val(data.clean_email_input);
            $('.focus_email').html(data.clean_email_input);
            $('#email_errors').html('');

            //Go to next discovers:
            goto_step(( data.email_existed_previously ? 3 /* To ask for password */ : 4 /* To check their email and create new account */ ));

        } else {
            //Show errors:
            $('#email_errors').html('<b class="css__title discover"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>' + data.message + '</b>').hide().fadeIn();
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
    $('#input_name, #new_password').prop('disabled', true);

    //Check email and validate:
    $.post("/e/e_signin_create", {
        input_email: $('#input_email').val(),
        input_name: $('#input_name').val(),
        new_password: $('#new_password').val(),
        referrer_url: referrer_url,
        sign_i__id: sign_i__id,
    }, function (data) {

        if (data.status) {

            //Release field lock:
            $('#add_acount_next').html(js_e___11035[14424]['m__icon']);
            $('#new_account_errors').html('');

            setTimeout(function () {
                //Redirect to next discovers:
                window.location = data.sign_url;
            }, 377);

        } else {


            //Release field lock:
            account_adding = false;
            $('#add_acount_next').html(go_next_icon);
            $('#new_password, #input_name').prop('disabled', false);

            //Do we know which field to focus on?
            if(data.focus_input_field.length>0) {
                $('#' + data.focus_input_field).focus();
            }

            //Show errors:
            $('#new_account_errors').html('<b class="css__title discover"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>' + data.message + '</b>').hide().fadeIn();
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
            $('#password_check_next').html(js_e___11035[14424]['m__icon']);
            $('#password_errors').html('');

            //Redirect
            window.location = data.sign_url;

        } else {

            //Release field lock:
            password_checking = false;
            $('#password_check_next').html(go_next_icon);
            $('#input_password').prop('disabled', false).focus();

            //Show errors:
            $('#password_errors').html('<b class="css__title discover"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>' + data.message + '</b>').hide().fadeIn();
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
            $('.magic_result').html('<div class="discover css__title"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>' + data.message + '</div>').hide().fadeIn();
        }
    });
}