


function idea_create_initiate(){

    //Load Idea Adder:
    autosize($('#newIdeaTitle'));

    in_load_search("#newIdeaTitle",0, 'a', false /* Search Only */);
    $('#newIdeaTitle').focus();

    //Show UI:
    $('.add-idea-toggle').toggleClass('hidden');

}

function idea_create(){

    //Lockdown:
    $('#newIdeaTitle').prop('disabled', true);
    $('.ideaCreationController').addClass('hidden');
    $('.ideaCreateStatusUpdate').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>');

    //Process this:
    $.post("/idea/idea_create", {
        newIdeaTitle: $('#newIdeaTitle').val(),
    }, function (data) {
        if (data.status) {

            //Redirect:
            $('.ideaCreateStatusUpdate').html(data.message);
            window.location = '/' + data.in_id;

        } else {

            //Unlock:
            $('.ideaCreationController').removeClass('hidden');
            $('.ideaCreateStatusUpdate').html('<span class="read"><i class="fas fa-exclamation-triangle"></i> ' + data.message + '</span>');
            $('#newIdeaTitle').prop('disabled', false).focus();

        }
    });

}