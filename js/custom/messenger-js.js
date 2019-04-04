function confirm_skip(tr_id) {
    //Make a AJAX Call to see how many steps would be skipped if we were to continue:
    $.post("/messenger/actionplan_skip_step/"+ tr_id+"/0", { tr_id: tr_id }, function (data) {
        var r = confirm("Are you sure you want to skip "+data.step_count+" steps to " + $('.primary-title').text() + "?");
        if (r == true) {
            //Redirect to skip:
            window.location = "/messenger/actionplan_skip_step/" + tr_id+"/1";
        }
    });
}


function save_full_name(){
    alert('howdy');
}