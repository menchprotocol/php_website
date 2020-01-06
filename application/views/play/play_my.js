



$(document).ready(function () {

    //Load top_players:
    load_top_players();

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

function avatar_switch(icon_type){

    //Find active avatar:
    var selected_avatar = $('.avatar-item.active i').attr('class').split(' ');

    //Adjust menu:
    $('.avatar-type-group a').removeClass('active');
    $('.avatar-type-group .btn-'+selected_avatar[0]).addClass('active');


    //Show correct avatars:
    $('.avatar-item').addClass('hidden').removeClass('active');
    $('.avatar-type-'+icon_type).removeClass('hidden');

    //Update Selection:
    $('.avatar-type-'+icon_type+'.avatar-name-'+selected_avatar[1]).addClass('active');
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

    $.post("/play/account_update_radio", {
        parent_en_id: parent_en_id,
        selected_en_id: selected_en_id,
        enable_mulitiselect: enable_mulitiselect,
        was_already_selected: was_already_selected,
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $(notify_el).html('<span style="color:#FF0000;"><i class="fas fa-exclamation-triangle"></i> ' + data.message + '</span>');

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
    $.post("/play/account_update_name", {
        en_name: $('#en_name').val().toUpperCase(),
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

function account_update_email(){

    //Show spinner:
    $('.save_email').html('<span><i class="far fa-yin-yang fa-spin"></i> ' + echo_saving_notify() +  '</span>').hide().fadeIn();

    //Save the rest of the content:
    $.post("/play/account_update_email", {
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


function account_update_password(){

    //Show spinner:
    $('.save_password').html('<span><i class="far fa-yin-yang fa-spin"></i> ' + echo_saving_notify() +  '</span>').hide().fadeIn();

    //Save the rest of the content:
    $.post("/play/account_update_password", {
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
