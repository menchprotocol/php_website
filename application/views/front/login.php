<h1>Login</h1>
<br />

<div class="login-content">
	
	<?php 
	//Check to make sure it's Chrome:
    $website = $this->config->item('website');
	    ?>
        <script>
            //Show loading:
            function request_password_reset(){
                //Show loading:
                $('#pass_reset').html('<span><img src="/img/round_load.gif" style="width:16px; height:16px; margin-top:-2px;" class="loader" /></span>');
                //Hide the editor & saving results:
                $.post("/api_v1/request_password_reset", {
                    email:$('#u_email').val(),
                }, function(data) {
                    //Show success:
                    $('#pass_reset').html(data);
                });
            }
        </script>

	    <form method="post" action="/api_v1/login">
	    <input type="hidden" name="url" value="<?= @$_GET['url'] ?>" />
		<div class="input-group pass_success" style="margin-bottom: 5px;">
			<span class="input-group-addon">
				<i class="material-icons">email</i>
			</span>
			<div class="form-group is-empty"><input type="email" id="u_email" name="u_email" required="required" class="form-control" placeholder="Email"><span class="material-input"></span></div>
		</div>
	
		<div class="input-group pass pass_success">
			<span class="input-group-addon">
				<i class="material-icons">lock_outline</i>
			</span>
			<div class="form-group is-empty"><input type="password" name="u_password" required="required" placeholder="Password" class="form-control"><span class="material-input"></span></div>
		</div>

	    <div id="loginb" class="submit-btn pass_success">
            <input type="submit" class="btn btn-primary pass btn-raised btn-round" value="Login">
            <a class="btn btn-primary pass btn-raised btn-round" style="display: none;" href="javascript:request_password_reset();">Request Password Reset</a>
            <span class="pass" style="width:294px; display:inline-block; font-size:0.9em; text-align: right;"><a href="javascript:$('.pass').toggle();">Forgot Password</a></span>
            <span class="pass" style="font-size:0.9em; display: none;">or <a href="javascript:$('.pass').toggle();">Cancel</a></span>
        </div>
	    </form>

        <div id="pass_reset"></div>

	<br />
	
	<div class="extra-info">
		<p>Want to Run a Bootcamp? <a href="<?= typeform_url('nh4s2u') ?>">Signup As Instructor <i class="fa fa-sign-in"></i></a></p>
	</div>
</div>