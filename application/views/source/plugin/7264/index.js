

$(document).ready(function () {

    //Show spinner:
    $('#ajax_7264').html('<span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>' + js_echo_platform_message(12694)).hide().fadeIn();

    //Load report based on input fields:
    $.post("/plugin/ajax_7264", {
        in_id: parseInt($('#in_id').val()),
        depth_levels: parseInt($('#depth_levels').val()),
    }, function (data) {
        if (!data.status) {
            //Show Errors:
            $('#ajax_7264').html('<span style="color:#FF0000;">Alert: '+ data.message +'</span>');
        } else {
            //Load Report:
            $('#ajax_7264').html(data.message);
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

});