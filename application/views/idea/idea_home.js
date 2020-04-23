

$(document).ready(function () {

    //Load Idea Search:
    in_load_search("#newIdeaTitle",0, 'a', 'link_my_in');

    $('#newIdeaTitle').focus(function() {
        $('.algolia_pad_search').removeClass('hidden');
    }).focusout(function() {
        $('.algolia_pad_search').addClass('hidden');
    });

});


var saving_idea = false;
function in_create(){

    if(saving_idea){
        alert('Idea already being saved, Be patient...');
        return false;
    } else {
        saving_idea = true;
    }

    //Lockdown:
    $('#newIdeaTitle').prop('disabled', true);
    $('#tempLoader').remove();

    //Set processing status:
    add_to_list('myIdeas', '.itemidealist', '<div id="tempLoader" class="list-group-item montserrat"><span class="icon-block"><i class="fas fa-yin-yang fa-spin idea"></i></span>Saving Idea...</div>');

    //Process this:
    $.post("/idea/in_create", {
        newIdeaTitle: $('#newIdeaTitle').val(),
    }, function (data) {
        if (data.status) {

            //Redirect:
            $('#tempLoader').html(data.message);
            window.location = '/idea/' + data.in_id;

        } else {

            //Unlock:
            $('#tempLoader').html('<span class="discover"><i class="fas fa-exclamation-circle"></i> ' + data.message + '</span>');
            $('#newIdeaTitle').prop('disabled', false).focus();

        }

        saving_idea = false;
    });

}