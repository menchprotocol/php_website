

$(document).ready(function () {

    autosize($('#newBlogTitle'));

    $('#newBlogTitle').keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            blog_create();
            e.preventDefault();
        }
    });

});


function blog_create(){

    //Lockdown:
    $('#newBlogTitle').prop('disabled', true);
    $('.blogCreationController').addClass('hidden');
    $('.blogCreateStatusUpdate').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>');

    //Process this:
    $.post("/blog/blog_create", {
        newBlogTitle: $('#newBlogTitle').val(),
    }, function (data) {
        if (data.status) {

            //Redirect:
            $('.blogCreateStatusUpdate').html(data.message);
            window.location = '/' + data.in_id;

        } else {

            //Unlock:
            $('.blogCreationController').removeClass('hidden');
            $('.blogCreateStatusUpdate').html('<span class="read"><i class="fas fa-exclamation-triangle"></i> ' + data.message + '</span>');
            $('#newBlogTitle').prop('disabled', false).focus();

        }
    });

}