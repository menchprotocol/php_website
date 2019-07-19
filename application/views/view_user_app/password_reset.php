<?php



//Make sure we have all key variables to show password reset UI:
if (!isset($_GET['en_id']) || intval($_GET['en_id']) < 1 || !isset($_GET['timestamp']) || intval($_GET['timestamp']) < 1 || !isset($_GET['p_hash']) || strlen($_GET['p_hash']) < 10) {

    die('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: Missing Core Variables.</div>');

} elseif (!($_GET['p_hash'] == md5($_GET['en_id'] . $this->config->item('password_salt') . $_GET['timestamp']))) {

    die('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: Invalid hash key.</div>');

} elseif (($_GET['timestamp'] + (24 * 3600)) < time()) {

    die('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: Password reset link has expired. You can request another one <a href="/signin">here</a>.</div>');

}

//Everything is good, show password-reset form:
?>

<script>
    //Show loading:
    function password_process_reset() {
        $('#pass_reset').html('<span><i class="fas fa-spinner fa-spin"></i></span>');
        //Hide the editor & saving results:
        $.post("/entities/password_process_reset", {
            en_id:<?= $_GET['en_id'] ?>,
            timestamp:<?= $_GET['timestamp'] ?>,
            p_hash: "<?= $_GET['p_hash'] ?>",
            new_pass: $('#input_password').val(),
        }, function (data) {
            //Show success:
            $('#pass_reset').html(data);
        });
    }
</script>


<h3>Enter New Password</h3>
<div class="input-group pass_success">
<span class="input-group-addon">
    <i class="fas fa-lock"></i>
</span>
    <div class="form-group is-empty">
        <input type="password" autocomplete="false" id="input_password" placeholder="New Password" class="form-control">
    </div>
</div>

<div id="loginb" class="submit-btn pass_success">
    <a class="btn btn-black btn-round btn-md" style="font-size: 0.9em; font-weight: 500;"
       href="javascript:password_process_reset();">Update Password</a>
</div>
<div id="pass_reset"></div>