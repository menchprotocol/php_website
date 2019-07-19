

<h1>Create Account</h1>

<p>Your account is now created. We will email  your further instructions once we have verified your account.</p>

<div class="company-signup hidden">
    <form autocomplete="off" id="AddCompanyForm">
        <b class="mini-header">Your Full Name</b>
        <span class="white-wrapper"><input type="text" id="user_full_name" class="form-control" autocomplete="off" data-lpignore="true"></span>

        <b class="mini-header">Your Work Email</b>
        <span class="white-wrapper"><input type="email" id="user_email" class="form-control" autocomplete="off" data-lpignore="true"></span>

        <b class="mini-header">Your Company Name</b>
        <span class="white-wrapper"><input type="text" id="company_name" class="form-control" autocomplete="off" data-lpignore="true"></span>

        <b class="mini-header">Your Password</b>
        <span class="white-wrapper"><input type="password" id="your_password" class="form-control" autocomplete="off" autocomplete="new-password" data-lpignore="true"></span>

        <b class="mini-header">Repeat Password</b>
        <span class="white-wrapper"><input type="password" id="repeat_password" class="form-control" autocomplete="off" autocomplete="new-password" data-lpignore="true"></span>

        <table style="width: 100%;"><tr>
                <td style="width:100px;"><a class="btn btn-primary tag-manager-get-started" href="javascript:void(0);" onclick="add_company_account()" style="display: inline-block; font-size: 1em;">Get Started&nbsp;&nbsp;&nbsp;<i class="fas fa-angle-double-right"></i></a></td>
                <td style="padding-left: 10px;"><div id="company-add-results"></div></td>
            </tr></table>

    </form>
</div>

<script>

    function add_company_account(){

        $("#company-add-results").html('<div><i class="fas fa-spinner fa-spin"></i></div>');
        $("#AddCompanyForm :input").prop("disabled", true).css('background-color', '#F9F9F9');

        //Update message:
        $.post("/entities/add_company", {
            user_full_name: $('#user_full_name').val(),
            user_email: $('#user_email').val(),
            company_name: $('#company_name').val(),
            your_password: $('#your_password').val(),
            repeat_password: $('#repeat_password').val(),
        }, function (data) {
            if (!data.status) {
                //Error:
                $("#AddCompanyForm :input").prop("disabled", false).css('background-color', '#FFFFFF');
                $('#' + data.error_field).focus();
                $("#company-add-results").html('<b style="color:#FF0000 !important; line-height: 110% !important;">Error: ' + data.message + '</b>');
                return false;
            } else {
                //Success:
                $("#company-add-results").html(data.message);
                //Disapper in a while:
                setTimeout(function () {
                    //Redirect to intent with more info on next step:
                    window.location = data.success_url;
                }, 333);
            }
        });
    }

</script>