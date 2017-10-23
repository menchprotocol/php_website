<h1><?= $this->lang->line('login') ?></h1>
<br />

<div class="login-content">
	<form method="post" action="/process/login">
		<input type="hidden" name="url" value="<?= @$_GET['url'] ?>" />
		<div class="input-group">
			<span class="input-group-addon">
				<i class="material-icons">email</i>
			</span>
			<div class="form-group is-empty"><input type="email" name="u_email" required="required" class="form-control" placeholder="Email"><span class="material-input"></span></div>
		</div>
	
		<div class="input-group">
			<span class="input-group-addon">
				<i class="material-icons">lock_outline</i>
			</span>
			<div class="form-group is-empty"><input type="password" name="u_password" required="required" placeholder="Password" class="form-control"><span class="material-input"></span></div>
		</div>
		
		<div class="submit-btn">
			<input type="submit" class="btn btn-primary btn-raised btn-round" value="Login">
		</div>
	</form>
	<br />
	
	<div class="extra-info">
		<p><?= $this->lang->line('no_account') ?> <a href="<?= typeform_url('nh4s2u') ?>"><?= $this->lang->line('signup') ?> <i class="fa fa-sign-in"></i></a></p>
		<p><?= $this->lang->line('forgot_pass') ?> <a href="/contact"><?= $this->lang->line('contact_us') ?> <i class="fa fa-comment" aria-hidden="true"></i></a></p>
	</div>
</div>