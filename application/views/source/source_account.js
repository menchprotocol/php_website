

$(document).ready(function () {

    //Do we need to auto open?
    if(open_en_id > 0){
        setTimeout(function() { $('#openEn'+open_en_id).collapse('show'); }, 987);
    }

    //Setup auto focus:
    $('#openEn6197').on('show.bs.collapse', function () {
        //call a service here
        var original_val = $('#en_name').val();
        setTimeout(function() { $('#en_name').focus().val('').val(original_val); }, 144);
    });

    $('#openEn3288').on('show.bs.collapse', function () {
        //call a service here
        var original_val = $('#en_email').val();
        setTimeout(function() { $('#en_email').focus().val('').val(original_val); }, 144);
    });

    $('#openEn3286').on('show.bs.collapse', function () {
        //call a service here
        var original_val = $('#input_password').val();
        setTimeout(function() { $('#input_password').focus().val('').val(original_val); }, 144);
    });

});


function account_update_avatar_type(type_css){

    //Find active avatar:
    var selected_avatar = $('.avatar-item.active i').attr('class').split(' ');

    //Adjust menu:
    $('.avatar-type-group .btn').removeClass('active');
    $('.avatar-type-group .btn-'+type_css).addClass('active');


    //Show correct avatars:
    $('.avatar-item').addClass('hidden').removeClass('active');
    $('.avatar-type-'+type_css).removeClass('hidden');

    //Update Selection:
    $('.avatar-type-'+type_css+'.avatar-name-'+selected_avatar[1]).addClass('active');

    //Update Icon:
    account_update_avatar_icon(type_css, null);

}

function account_update_avatar_icon(type_css, icon_css){

    //Detect current icon type:
    if(!icon_css){
        icon_css = $('.avatar-item.active').attr('icon-css');
    } else {
        //Set Proper Focus:
        $('.avatar-item').removeClass('active');
        $('.avatar-item.avatar-name-'+icon_css).addClass('active');
    }


    //Update via call:
    $.post("/source/account_update_avatar_icon", {
        type_css: type_css,
        icon_css: icon_css,
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            alert('Alert: ' + data.message);

        } else {

            //Remove message:
            $('.icon_en_'+js_pl_id).html(data.new_avatar);

        }
    });

}


function account_update_radio(parent_en_id, selected_en_id, enable_mulitiselect){

    var was_already_selected = ( $('.radio-'+parent_en_id+' .item-'+selected_en_id).hasClass('active') ? 1 : 0 );

    //Save the rest of the content:
    if(!enable_mulitiselect && was_already_selected){
        //Nothing to do here:
        return false;
    }

    //Show spinner on the notification element:
    var notify_el = '.radio-'+parent_en_id+' .item-'+selected_en_id+' .change-results';
    $(notify_el).html('<i class="far fa-yin-yang fa-spin"></i>');


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

    $.post("/source/account_update_radio", {
        parent_en_id: parent_en_id,
        selected_en_id: selected_en_id,
        enable_mulitiselect: enable_mulitiselect,
        was_already_selected: was_already_selected,
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $(notify_el).html('<span style="color:#FF0000;"><i class="fad fa-exclamation-triangle"></i> ' + data.message + '</span>');

        } else {

            //Remove message:
            $(notify_el).html('');

        }
    });


}

function account_update_name(){

    //Show spinner:
    $('.save_full_name').html('<span><i class="far fa-yin-yang fa-spin"></i> ' + echo_saving_notify() +  '</span>').hide().fadeIn();

    //Save the rest of the content:
    var en_name_new = $('#en_name').val().toUpperCase();
    $.post("/source/account_update_name", {
        en_name: en_name_new,
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $('.save_full_name').html('<span style="color:#FF0000;"><i class="fad fa-exclamation-triangle"></i> ' + data.message + '</span>').hide().fadeIn();

        } else {

            //Show success:
            $('.save_full_name').html('<i class="fas fa-check-circle"></i> ' + data.message + '</span>').hide().fadeIn();

            //Update name on page:
            $('.en_name_first_'+js_pl_id).text(data.first__name);
            $('.en_name_full_'+js_pl_id).text(en_name_new);

            //Disappear in a while:
            setTimeout(function () {

                $('.save_full_name').html('');

            }, 1597);

        }
    });

}

function account_update_email(){

    //Show spinner:
    $('.save_email').html('<span><i class="far fa-yin-yang fa-spin"></i> ' + echo_saving_notify() +  '</span>').hide().fadeIn();

    //Save the rest of the content:
    $.post("/source/account_update_email", {
        en_email: $('#en_email').val(),
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $('.save_email').html('<span style="color:#FF0000;"><i class="fad fa-exclamation-triangle"></i> ' + data.message + '</span>').hide().fadeIn();

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


function account_update_password(){

    //Show spinner:
    $('.save_password').html('<span><i class="far fa-yin-yang fa-spin"></i> ' + echo_saving_notify() +  '</span>').hide().fadeIn();

    //Save the rest of the content:
    $.post("/source/account_update_password", {
        input_password: $('#input_password').val(),
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $('.save_password').html('<span style="color:#FF0000;"><i class="fad fa-exclamation-triangle"></i> ' + data.message + '</span>').hide().fadeIn();

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
