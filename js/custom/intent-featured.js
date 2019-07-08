

function add_to_actionplan(in_id){
    $('#added_to_actionplan').html('<span><i class="fas fa-spinner fa-spin"></i></span> Adding...');
    $.post("/user_app/actionplan_intention_add", {in_id: in_id}, function (data) {
        $('#added_to_actionplan').html(data.message);
    });
}

function confirm_child_go(in_id) {
    $('.alink-' + in_id).attr('href', 'javascript:void(0);');
    var in_outcome_parent = $('#title-parent').text();
    var in_outcome_child = $('#title-' + in_id).text();
    var r = confirm("Press OK to ONLY " + in_outcome_child + "\nPress CANCEL to " + in_outcome_parent);
    if (r == true) {
        //Go to target intent:
        window.location = "/" + in_id;
    }
}