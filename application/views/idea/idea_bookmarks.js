

$(document).ready(function () {

    //Load Tree Search:
    in_load_search("#newTreeTitle",0, 'a', 'link_my_in');

    $('#newTreeTitle').focus(function() {
        $('.in_pad_new_in' ).removeClass('hidden');
    }).focusout(function() {
        $('.in_pad_new_in' ).addClass('hidden');
    });

});


function in_create(){

    //Lockdown:
    $('#newTreeTitle').prop('disabled', true);
    $('#tempLoader').remove();

    //Set processing status:
    add_to_list('myTrees', '.itemtreelist', '<div id="tempLoader" class="list-group-item itemtree montserrat"><span class="icon-block"><i class="fas fa-yin-yang fa-spin tree"></i></span>Adding... </div>');

    //Process this:
    $.post("/tree/in_create", {
        newTreeTitle: $('#newTreeTitle').val(),
    }, function (data) {
        if (data.status) {

            //Redirect:
            $('#tempLoader').html(data.message);
            window.location = '/tree/' + data.in_id;

        } else {

            //Unlock:
            $('#tempLoader').html('<span class="read"><i class="fad fa-exclamation-triangle"></i> ' + data.message + '</span>');
            $('#newTreeTitle').prop('disabled', false).focus();

        }
    });

}