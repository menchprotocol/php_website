function confirm_skip(tr_id, in_id, tr_id) {
    var in_outcome = $('.primary-title').text();
    var r = confirm("Skip the intention to " + $('.primary-title').text() + "?");
    if (r == true) {
        //Redirect to skip:
        window.location = "/master/skip_tree/" + tr_id + "/" + in_id + "/" + tr_id;
    }
}