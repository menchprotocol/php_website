function confirm_skip(w_id, in_id, tr_id) {
    var c_outcome = $('.primary-title').text();
    var r = confirm("Skip the intention to " + $('.primary-title').text() + "?");
    if (r == true) {
        //Redirect to skip:
        window.location = "/my/skip_tree/" + w_id + "/" + in_id + "/" + tr_id;
    }
}