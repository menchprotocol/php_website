

$(document).ready(function () {

    //Load Idea Search:
    in_load_search("#newIdeaTitle",0, 'a', 'link_my_idea');

    $('#newIdeaTitle').focus(function() {
        $('.in_pad_new_idea' ).removeClass('hidden');
    }).focusout(function() {
        $('.in_pad_new_idea' ).addClass('hidden');
    });

});


function idea_create(){

    //Lockdown:
    $('#newIdeaTitle').prop('disabled', true);
    $('#tempLoader').remove();

    //Set processing status:
    add_to_list('myIdeas', '.itemidealist', '<div id="tempLoader" class="list-group-item itemidea montserrat"><span class="icon-block"><i class="fas fa-yin-yang fa-spin idea"></i></span>Adding... </div>');

    //Process this:
    $.post("/idea/idea_create", {
        newIdeaTitle: $('#newIdeaTitle').val(),
    }, function (data) {
        if (data.status) {

            //Redirect:
            $('#tempLoader').html(data.message);
            window.location = '/' + data.in_id;

        } else {

            //Unlock:
            $('#tempLoader').html('<span class="read"><i class="fas fa-exclamation-triangle"></i> ' + data.message + '</span>');
            $('#newIdeaTitle').prop('disabled', false).focus();

        }
    });

}