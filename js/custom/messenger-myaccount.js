

function radio_update(parent_en_id, selected_en_id, enable_mulitiselect){

    var was_already_selected = ( $('.radio-'+parent_en_id+' .item-'+selected_en_id).hasClass('active') ? 1 : 0 );

    //Save the rest of the content:
    if(!enable_mulitiselect && was_already_selected){
        //Nothing to do here:
        return false;
    } else if(parent_en_id==4454 && selected_en_id==4455){
        //It seems user wants to unsubscribe, confirm before doing so:
        var r = confirm("Are you sure you want to unsubscribe from Mench and stop all communications? I will no longer message you unless you re-subscribe later on.");
        if (r == false) {
            return false;
        }
    }

    //Show spinner on the notification element:
    var notify_el = '.radio-'+parent_en_id+' .item-'+selected_en_id+' .change-results';
    $(notify_el).html('<i class="fas fa-yin-yang fa-spin"></i>');


    if(!enable_mulitiselect){
        //Clear all selections:
        $('.radio-'+parent_en_id+' .list-group-item').removeClass('active');
    }

    //Enable currently selected:
    if(enable_mulitiselect && was_already_selected){
        $('.radio-'+parent_en_id+' .item-'+selected_en_id).removeClass('active');
    } else {
        $('.radio-'+parent_en_id+' .item-'+selected_en_id).addClass('active');
    }

    $.post("/user_app/myaccount_radio_update", {
        en_creator_id: en_creator_id,
        parent_en_id: parent_en_id,
        selected_en_id: selected_en_id,
        enable_mulitiselect: enable_mulitiselect,
        was_already_selected: was_already_selected,
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $(notify_el).html('<span style="color:#FF0000;"><i class="fas fa-exclamation-triangle"></i> ' + data.message + '</span>');

        } else {

            //Show success:
            $(notify_el).html('<i class="fas fa-check-circle"></i></span>');

            //Disappear in a while:
            setTimeout(function () {
                $(notify_el).html('');
            }, 1597);

        }
    });


}

function save_full_name(){

    //Show spinner:
    $('.save_full_name').html('<span><i class="fas fa-yin-yang fa-spin"></i> ' + echo_saving_notify() +  '</span>').hide().fadeIn();

    //Save the rest of the content:
    $.post("/user_app/myaccount_save_full_name", {
        en_id: en_creator_id,
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

function save_phone(){

    //Show spinner:
    $('.save_phone').html('<span><i class="fas fa-yin-yang fa-spin"></i> ' + echo_saving_notify() +  '</span>').hide().fadeIn();

    //Save the rest of the content:
    $.post("/user_app/myaccount_save_phone", {
        en_id: en_creator_id,
        en_phone: $('#en_phone').val(),
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $('.save_phone').html('<span style="color:#FF0000;"><i class="fas fa-exclamation-triangle"></i> ' + data.message + '</span>').hide().fadeIn();

        } else {

            //Show success:
            $('.save_phone').html('<i class="fas fa-check-circle"></i> ' + data.message + '</span>').hide().fadeIn();

            //Disappear in a while:
            setTimeout(function () {
                $('.save_phone').html('');
            }, 1597);

        }
    });

}

function save_email(){

    //Show spinner:
    $('.save_email').html('<span><i class="fas fa-yin-yang fa-spin"></i> ' + echo_saving_notify() +  '</span>').hide().fadeIn();

    //Save the rest of the content:
    $.post("/user_app/myaccount_save_email", {
        en_id: en_creator_id,
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


function myaccount_update_password(){

    //Show spinner:
    $('.save_password').html('<span><i class="fas fa-yin-yang fa-spin"></i> ' + echo_saving_notify() +  '</span>').hide().fadeIn();

    //Save the rest of the content:
    $.post("/user_app/myaccount_update_password", {
        en_id: en_creator_id,
        input_password: $('#input_password').val(),
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
    $('.save_social_profiles').html('<span><i class="fas fa-yin-yang fa-spin"></i> ' + echo_saving_notify() +  '</span>').hide().fadeIn();


    //Fetch all social profile input fields:
    var social_profiles = [];
    $(".social_profile_url").each(function () {
        social_profiles.push([parseInt($(this).attr('parent-en-id')),$(this).val()]);
    });

    //Save the rest of the content:
    $.post("/user_app/myaccount_save_social_profiles", {
        en_id: en_creator_id,
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