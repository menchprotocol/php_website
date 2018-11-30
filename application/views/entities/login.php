<h1>Login</h1>
<br/>

<div class="login-content">
    <script>
        //Show loading:
        function u_password_reset_initiate() {
            //Show loading:
            $('#pass_reset').html('<span><img src="/img/round_load.gif" style="width:16px; height:16px; margin-top:-2px;" class="loader" /></span>');
            //Hide the editor & saving results:
            $.post("/entities/u_password_reset_initiate", {
                email: $('#u_email').val(),
            }, function (data) {
                //Show success:
                $('#pass_reset').html(data);
            });
        }
    </script>

    <form method="post" action="/entities/login">
        <input type="hidden" name="url" value="<?= @$_GET['url'] ?>"/>
        <div class="input-group pass_success" style="margin-bottom: 5px;">
			<span class="input-group-addon">
				<i class="material-icons">email</i>
			</span>
            <div class="form-group is-empty"><input type="email" id="u_email" name="u_email" required="required"
                                                    class="form-control" placeholder="Email"><span
                        class="material-input"></span></div>
        </div>

        <div class="input-group pass pass_success">
			<span class="input-group-addon">
				<i class="material-icons">lock_outline</i>
			</span>
            <div class="form-group is-empty"><input type="password" name="u_password" required="required"
                                                    placeholder="Password" class="form-control"><span
                        class="material-input"></span></div>
        </div>

        <div id="loginb" class="submit-btn pass_success">
            <input type="submit" class="btn btn-primary pass btn-raised btn-round" value="Login">
            <a class="btn btn-primary pass btn-raised btn-round" style="display: none;"
               href="javascript:u_password_reset_initiate();">Request Password Reset</a>
            <span class="pass" style="width:294px; display:inline-block; font-size:0.9em; text-align: right;"><a
                        href="javascript:void(0)" onclick="$('.pass').toggle()">Forgot Password</a></span>
            <span class="pass" style="font-size:0.9em; display: none;">or <a href="javascript:void(0)"
                                                                             onclick="$('.pass').toggle()">Cancel</a></span>
        </div>
    </form>

    <div id="pass_reset"></div>
    <br/>
</div>