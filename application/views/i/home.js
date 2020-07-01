

$(document).ready(function () {

    //Load Idea Search:
    i_load_search("#newIdeaTitle",0, 'a', 'link_my_in');

    $('#newIdeaTitle').focus(function() {
        $('.algolia_pad_search').removeClass('hidden');
    }).focusout(function() {
        $('.algolia_pad_search').addClass('hidden');
    });

    x_remove(13415);

});

var saving_i = false;
function i_create(){

    if(saving_i){
        alert('Idea currently being saved, Be patient...');
        return false;
    } else {
        saving_i = true;
    }

    //Lockdown:
    $('#newIdeaTitle').prop('disabled', true);
    $('#tempLoader').remove();

    //Set processing status:
    add_to_list('myIdeas', '.itemidealist', '<div id="tempLoader" class="list-group-item no-side-padding montserrat"><span class="icon-block"><i class="fas fa-yin-yang fa-spin idea"></i></span>Saving Idea...</div>');

    //Process this:
    $.post("/i/i_create", {
        newIdeaTitle: $('#newIdeaTitle').val(),
    }, function (data) {
        if (data.status) {

            //Redirect:
            $('#tempLoader').html(data.message);
            window.location = '/~' + data.i__id;

        } else {

            //Unlock:
            $('#tempLoader').html('<span class="discover montserrat"><i class="fas fa-exclamation-circle"></i> ' + data.message + '</span>');
            $('#newIdeaTitle').prop('disabled', false).focus();

        }

        saving_i = false;
    });

}