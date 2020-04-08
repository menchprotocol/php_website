

$(document).ready(function () {

    //Load Note Search:
    in_load_search("#newNoteTitle",0, 'a', 'link_my_in');

    $('#newNoteTitle').focus(function() {
        $('.in_pad_new_in' ).removeClass('hidden');
    }).focusout(function() {
        $('.in_pad_new_in' ).addClass('hidden');
    });

});


function in_create(){

    //Lockdown:
    $('#newNoteTitle').prop('disabled', true);
    $('#tempLoader').remove();

    //Set processing status:
    add_to_list('myNotes', '.itemnotelist', '<div id="tempLoader" class="list-group-item itemnote montserrat"><span class="icon-block"><i class="fas fa-yin-yang fa-spin note"></i></span>Adding... </div>');

    //Process this:
    $.post("/note/in_create", {
        newNoteTitle: $('#newNoteTitle').val(),
    }, function (data) {
        if (data.status) {

            //Redirect:
            $('#tempLoader').html(data.message);
            window.location = '/note/' + data.in_id;

        } else {

            //Unlock:
            $('#tempLoader').html('<span class="read"><i class="fad fa-exclamation-triangle"></i> ' + data.message + '</span>');
            $('#newNoteTitle').prop('disabled', false).focus();

        }
    });

}