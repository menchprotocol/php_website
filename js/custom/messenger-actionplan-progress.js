

function actionplan_skip_steps(en_id, in_id) {
    //Make a AJAX Call to see how many steps would be skipped if we were to continue:
    $.post("/user_app/actionplan_skip_preview/"+ en_id+"/"+in_id, {}, function (data) {

        var r = confirm(data.skip_step_preview);

        if (r == true) {
            //If confirmed, will skip those steps:
            window.location = "/user_app/actionplan_skip_apply/"+ en_id+"/"+in_id;
        }
    });
}