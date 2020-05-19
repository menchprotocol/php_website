

$(document).ready(function () {

    //Show spinner:
    $('#plugin_7264').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>' + js_view_platform_message(12694)).hide().fadeIn();

    //Load report based on input fields:
    $.post("/source/plugin_7264", {
        in_id: parseInt($('#in_id').val()),
        depth_levels: parseInt($('#depth_levels').val()),
    }, function (data) {
        if (!data.status) {
            //Show Errors:
            $('#plugin_7264').html('<span class="read">'+ data.message +'</span>');
        } else {
            //Load Report:
            $('#plugin_7264').html(data.message);
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

});