


$(document).ready(function () {

    $('#openEn3288').on('show.bs.collapse', function () {
        //call a service here
        var original_val = $('#e_email').val();
        setTimeout(function() { $('#e_email').focus().val('').val(original_val); }, 144);
    });

    $('#openEn3286').on('show.bs.collapse', function () {
        //call a service here
        var original_val = $('#input_password').val();
        setTimeout(function() { $('#input_password').focus().val('').val(original_val); }, 144);
    });

});




function account_toggle_all(is_enabled){
    //Turn all superpowers on/off:
    $(".btn-superpower").each(function () {
        if ((is_enabled && !$(this).hasClass('active')) || (!is_enabled && $(this).hasClass('active'))) {
            e_toggle_superpower(parseInt($(this).attr('en-id')));
        }
    });
}



function e_toggle_superpower(superpower_id){

    superpower_id = parseInt(superpower_id);

    var superpower_icon = $('.superpower-frame-'+superpower_id).html();
    $('.superpower-frame-'+superpower_id).html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>');

    //Save session variable to save the state of advance setting:
    $.post("/e/e_toggle_superpower/"+superpower_id, {}, function (data) {

        //Change top menu icon:
        $('.superpower-frame-'+superpower_id).html(superpower_icon);

        if(!data.status){

            alert(data.message);

        } else {

            //Toggle UI elements:
            $('.superpower-'+superpower_id).toggleClass('hidden');

            //Change top menu icon:
            $('.superpower-frame-'+superpower_id).toggleClass('active');

            //TOGGLE:
            var index = js_session_superpowers_activated.indexOf(superpower_id);
            if (index > -1) {
                //Delete it:
                js_session_superpowers_activated.splice(index, 1);
            } else {
                //Not there, add it:
                js_session_superpowers_activated.push(superpower_id);
            }
        }
    });

}




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
    e_avatar(type_css, null);

}

function e_avatar(type_css, icon_css){

    //Detect current icon type:
    if(!icon_css){
        icon_css = $('.avatar-item.active').attr('icon-css');
    } else {
        //Set Proper Focus:
        $('.avatar-item').removeClass('active');
        $('.avatar-item.avatar-name-'+icon_css).addClass('active');
    }

    $('.e_ui_icon_'+js_pl_id).html('<i class="far fa-yin-yang fa-spin"></i>');

    //Update via call:
    $.post("/e/e_avatar", {
        type_css: type_css,
        icon_css: icon_css,
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            alert(data.message);

        } else {

            //Delete message:
            $('.e_ui_icon_'+js_pl_id).html(data.new_avatar);

        }
    });

}


var current_focus = 0;

function remove_ui_class(item, index) {
    var the_class = 'custom_ui_'+current_focus+'_'+item;
    console.log('REMOVED: '+the_class);
    $('body').removeClass(the_class);
}

function e_radio(parent_e__id, selected_e__id, enable_mulitiselect){

    var was_previously_selected = ( $('.radio-'+parent_e__id+' .item-'+selected_e__id).hasClass('active') ? 1 : 0 );

    //Save the rest of the content:
    if(!enable_mulitiselect && was_previously_selected){
        //Nothing to do here:
        return false;
    }

    //Updating Font?
    if(js_n___13890.includes(parent_e__id)){
        current_focus = parent_e__id;
        $('body').removeClass('custom_ui_'+parent_e__id+'_');
        window['js_n___'+parent_e__id].forEach(remove_ui_class); //Removes all Classes
        $('body').addClass('custom_ui_'+parent_e__id+'_'+selected_e__id);
    }

    //Show spinner on the notification element:
    var notify_el = '.radio-'+parent_e__id+' .item-'+selected_e__id+' .change-results';
    $(notify_el).html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>');


    if(!enable_mulitiselect){
        //Clear all selections:
        $('.radio-'+parent_e__id+' .list-group-item').removeClass('active');
    }

    //Enable currently selected:
    if(enable_mulitiselect && was_previously_selected){
        $('.radio-'+parent_e__id+' .item-'+selected_e__id).removeClass('active');
    } else {
        $('.radio-'+parent_e__id+' .item-'+selected_e__id).addClass('active');
    }

    $.post("/e/e_radio", {
        parent_e__id: parent_e__id,
        selected_e__id: selected_e__id,
        enable_mulitiselect: enable_mulitiselect,
        was_previously_selected: was_previously_selected,
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $(notify_el).html('<b class="discover montserrat"><i class="fas fa-exclamation-circle"></i> ' + data.message + '</b>');

        } else {

            //Delete message:
            $(notify_el).html('');

        }
    });


}


function e_email(){

    //Show spinner:
    $('.save_email').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>' + js_view_shuffle_message(12695)).hide().fadeIn();

    //Save the rest of the content:
    $.post("/e/e_email", {
        e_email: $('#e_email').val(),
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $('.save_email').html('<b class="discover montserrat"><i class="fas fa-exclamation-circle"></i> ' + data.message + '</b>').hide().fadeIn();

        } else {

            //Show success:
            $('.save_email').html(js_e___11035[14424]['m__icon'] + ' ' + data.message + '</span>').hide().fadeIn();

            //Disappear in a while:
            setTimeout(function () {
                $('.save_email').html('');
            }, 1597);

        }
    });

}



function e_name(){

    //Show spinner:
    $('.save_name').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>' + js_view_shuffle_message(12695)).hide().fadeIn();

    $.post("/x/x_set_text", {
        s__id: js_pl_id,
        cache_e__id: 6197,
        field_value: $('#e_name').val().trim()
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $('.save_name').html('<b class="discover montserrat"><i class="fas fa-exclamation-circle"></i> ' + data.message + '</b>').hide().fadeIn();

        } else {

            //Show success:
            $('.save_name').html(js_e___11035[14424]['m__icon'] + ' ' + js_e___11035[14422]['m__title']).hide().fadeIn();

            //Disappear in a while:
            setTimeout(function () {
                $('.save_name').html('');
            }, 1597);

        }

    });

}


function e_password(){

    //Show spinner:
    $('.save_password').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>' + js_view_shuffle_message(12695)).hide().fadeIn();

    //Save the rest of the content:
    $.post("/e/e_password", {
        input_password: $('#input_password').val(),
    }, function (data) {

        if (!data.status) {

            //Ooops there was an error!
            $('.save_password').html('<b class="discover montserrat"><i class="fas fa-exclamation-circle"></i> ' + data.message + '</b>').hide().fadeIn();

        } else {

            //Show success:
            $('.save_password').html(js_e___11035[14424]['m__icon'] + ' ' + data.message + '</span>').hide().fadeIn();

            //Disappear in a while:
            setTimeout(function () {
                $('.save_password').html('');
            }, 1597);

        }
    });

}

