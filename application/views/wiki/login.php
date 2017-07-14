<?php
$website = $this->config->item('website');
?>

<h1 class="boldi">Admin Login</h1>
<form class="form-signin" action="/login_process" method="post">
	<input type="hidden" name="login_node_id" value="<?= @$_GET['from'] ?>" />
	<div class="row featurelist boldi">
		<label for="inputEmail" class="sr-only">Email</label>
	    <input type="email" id="inputEmail" name="user_email" class="form-control" placeholder="Email" required autofocus>
	    <label for="inputPassword" class="sr-only">Password</label>
	    <input type="password" style="margin:15px 0 20px 0;" name="user_pass" id="inputPassword" class="form-control" placeholder="Password" required>
	
		<div class="col-xs-6" style="padding-left:0;">
		    <button class="btn btn-lg btn-primary" style="margin:0 0 10px 0;" type="submit">Login</button>
		</div>
		<div class="col-xs-6" style="text-align:right; padding-top:5px; padding-right:2px;">
			<a href="javascript:alert('Email <?= $website['email'] ?> with your email and we will help you reset your password.');">Forgot?</a>
		</div>
	</div>
</form>
