

function save_full_name(){

    //Show spinner:
    $('.save_full_name').html('<span><i class="fas fa-spinner fa-spin"></i> Saving...</span>').hide().fadeIn();

    //Save the rest of the content:
    $.post("/messenger/myaccount_save_full_name", {
        en_id: parseInt($('#en_id').val()),
        en_name: $('#en_name').val(),
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $('.save_full_name').html('<span style="color:#FF0000;"><i class="fas fa-exclamation-triangle"></i> ' + data.message + '</span>').hide().fadeIn();

        } else {

            //Show success:
            $('.save_full_name').html('<i class="fas fa-check-circle"></i> ' + data.message + '</span>').hide().fadeIn();

            //Disappear in a while:
            setTimeout(function () {
                $('.save_full_name').html('');
            }, 1597);

        }
    });

}



function save_email(){

    //Show spinner:
    $('.save_email').html('<span><i class="fas fa-spinner fa-spin"></i> Saving...</span>').hide().fadeIn();

    //Save the rest of the content:
    $.post("/messenger/myaccount_save_email", {
        en_id: parseInt($('#en_id').val()),
        en_email: $('#en_email').val(),
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $('.save_email').html('<span style="color:#FF0000;"><i class="fas fa-exclamation-triangle"></i> ' + data.message + '</span>').hide().fadeIn();

        } else {

            //Show success:
            $('.save_email').html('<i class="fas fa-check-circle"></i> ' + data.message + '</span>').hide().fadeIn();

            //Disappear in a while:
            setTimeout(function () {
                $('.save_email').html('');
            }, 1597);

        }
    });

}


function save_password(){

    //Show spinner:
    $('.save_password').html('<span><i class="fas fa-spinner fa-spin"></i> Saving...</span>').hide().fadeIn();

    //Save the rest of the content:
    $.post("/messenger/myaccount_save_password", {
        en_id: parseInt($('#en_id').val()),
        en_password: $('#en_password').val(),
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $('.save_password').html('<span style="color:#FF0000;"><i class="fas fa-exclamation-triangle"></i> ' + data.message + '</span>').hide().fadeIn();

        } else {

            //Show success:
            $('.save_password').html('<i class="fas fa-check-circle"></i> ' + data.message + '</span>').hide().fadeIn();

            //Disappear in a while:
            setTimeout(function () {
                $('.save_password').html('');
            }, 1597);

        }
    });

}



function save_social_profiles(){

    //Show spinner:
    $('.save_social_profiles').html('<span><i class="fas fa-spinner fa-spin"></i> Saving...</span>').hide().fadeIn();


    //Fetch all social profile input fields:
    var social_profiles = [];
    $(".social_profile_url").each(function () {
        social_profiles.push([parseInt($(this).attr('parent-en-id')),$(this).val()]);
    });

    //Save the rest of the content:
    $.post("/messenger/myaccount_save_social_profiles", {
        en_id: parseInt($('#en_id').val()),
        social_profiles: social_profiles,
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $('.save_social_profiles').html('<span style="color:#FF0000;"><i class="fas fa-exclamation-triangle"></i> ' + data.message + '</span>').hide().fadeIn();

        } else {

            //Show success:
            $('.save_social_profiles').html('<i class="fas fa-check-circle"></i> ' + data.message + '</span>').hide().fadeIn();

            //Disappear in a while:
            setTimeout(function () {
                $('.save_social_profiles').html('');
            }, 4181);

        }
    });

}
