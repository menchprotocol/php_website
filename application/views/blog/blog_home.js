

$(document).ready(function () {

    //Load Blog Search:
    in_load_search("#newBlogTitle",0, 'a', 'link_my_blog');

    $('#newBlogTitle').focus(function() {
        $('.in_pad_new_blog' ).removeClass('hidden');
    }).focusout(function() {
        $('.in_pad_new_blog' ).addClass('hidden');
    });

});


function blog_create(){

    //Lockdown:
    $('#newBlogTitle').prop('disabled', true);
    $('#tempLoader').remove();

    //Set processing status:
    add_to_list('myBlogs', '.itembloglist', '<div id="tempLoader" class="list-group-item itemblog montserrat"><span class="icon-block"><i class="fas fa-yin-yang fa-spin blog"></i></span>Adding... </div>');

    //Process this:
    $.post("/blog/blog_create", {
        newBlogTitle: $('#newBlogTitle').val(),
    }, function (data) {
        if (data.status) {

            //Redirect:
            $('#tempLoader').html(data.message);
            window.location = '/' + data.in_id;

        } else {

            //Unlock:
            $('#tempLoader').html('<span class="read"><i class="fad fa-exclamation-triangle"></i> ' + data.message + '</span>');
            $('#newBlogTitle').prop('disabled', false).focus();

        }
    });

}